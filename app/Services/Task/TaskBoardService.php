<?php

namespace App\Services\Task;

use App\Enums\TaskPriority;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use App\Models\TaskStatus;
use App\Models\User;
use App\Services\Project\ProjectService;
use App\Support\AppPermission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class TaskBoardService
{
    public const COLUMN_PAGE_SIZE = 10;

    public function __construct(private readonly TaskStatusService $statuses) {}

    /**
     * @return array{
     *     statuses: Collection<int, TaskStatus>,
     *     columns: array<int, Collection<int, Task>>,
     *     totals: array<int, int>,
     *     page_size: int
     * }
     */
    public function boardFor(User $user): array
    {
        $statuses = $this->statuses->enabledOrdered();
        $pageSize = self::COLUMN_PAGE_SIZE;

        $columns = [];
        $totals = [];

        foreach ($statuses as $status) {
            $query = $this->visibleTasksQuery($user)
                ->where('task_status_id', $status->id);

            $totals[$status->id] = (clone $query)->count();
            $columns[$status->id] = (clone $query)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit($pageSize)
                ->get();
        }

        return [
            'statuses' => $statuses,
            'columns' => $columns,
            'totals' => $totals,
            'page_size' => $pageSize,
        ];
    }

    /**
     * @return array{
     *     tasks: Collection<int, Task>,
     *     per_page: int,
     *     total: int,
     *     has_more: bool
     * }
     */
    public function columnPage(User $user, TaskStatus $status, ?int $afterId = null): array
    {
        $perPage = self::COLUMN_PAGE_SIZE;

        $base = $this->visibleTasksQuery($user)
            ->where('task_status_id', $status->id);

        $total = (clone $base)->count();

        $query = clone $base;

        if ($afterId) {
            $after = Task::query()
                ->whereKey($afterId)
                ->where('task_status_id', $status->id)
                ->first();

            if ($after) {
                $query->where(function (Builder $builder) use ($after): void {
                    $builder
                        ->where('sort_order', '>', $after->sort_order)
                        ->orWhere(function (Builder $inner) use ($after): void {
                            $inner
                                ->where('sort_order', $after->sort_order)
                                ->where('id', '>', $after->id);
                        });
                });
            }
        }

        $tasks = $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit($perPage + 1)
            ->get();

        $hasMore = $tasks->count() > $perPage;
        if ($hasMore) {
            $tasks = $tasks->take($perPage);
        }

        return [
            'tasks' => $tasks,
            'per_page' => $perPage,
            'total' => $total,
            'has_more' => $hasMore,
        ];
    }

    private function visibleTasksQuery(User $user): Builder
    {
        $query = Task::query()
            ->with(['assignees:id,name,email', 'creator:id,name', 'status', 'project:id,name'])
            ->withCount([
                'comments as comments_count',
                'attachments',
                'subtasks',
                'subtasks as completed_subtasks_count' => fn ($query) => $query->where('is_completed', true),
            ]);

        if (! $user->can(AppPermission::TASKS_ASSIGN)) {
            $query->whereHas('assignees', fn (Builder $assignees) => $assignees->where('users.id', $user->id));
        }

        return $query;
    }

    public function loadDetails(Task $task): Task
    {
        return $task->load([
            'project:id,name,slug',
            'status',
            'assignees:id,name,email',
            'creator:id,name,email',
            'attachments.uploader:id,name',
            'subtasks.creator:id,name',
            'comments' => fn ($query) => $query
                ->with(['user:id,name', 'replies' => fn ($replies) => $replies->with('user:id,name')->oldest()])
                ->withCount('replies')
                ->latest(),
        ])->loadCount([
            'comments as comments_count',
            'subtasks',
            'subtasks as completed_subtasks_count' => fn ($query) => $query->where('is_completed', true),
        ]);
    }

    public function addComment(Task $task, User $actor, string $body, ?int $parentId = null): TaskComment
    {
        if ($parentId !== null) {
            $parent = TaskComment::query()
                ->where('task_id', $task->id)
                ->whereKey($parentId)
                ->firstOrFail();

            // Keep replies one level deep under the root comment.
            $parentId = $parent->parent_id ?: $parent->id;
        }

        return TaskComment::query()->create([
            'task_id' => $task->id,
            'user_id' => $actor->id,
            'parent_id' => $parentId,
            'body' => $body,
        ]);
    }

    /**
     * @return Collection<int, User>
     */
    public function assignableUsers(): Collection
    {
        return User::query()
            ->where('role', UserRole::User)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    /**
     * @param  array{summary: string, description?: ?string, project_id: int, task_status_id?: int, priority?: string, assignee_ids?: list<int>, due_date?: ?string}  $data
     * @param  array<int, UploadedFile>  $files
     */
    public function create(User $actor, array $data, array $files = []): Task
    {
        $assigneeIds = $actor->can(AppPermission::TASKS_ASSIGN)
            ? array_values(array_unique(array_map('intval', $data['assignee_ids'] ?? [])))
            : [$actor->id];

        if ($assigneeIds === []) {
            $assigneeIds = [$actor->id];
        }

        $status = isset($data['task_status_id'])
            ? TaskStatus::query()->enabled()->whereKey($data['task_status_id'])->firstOrFail()
            : $this->statuses->defaultStatus();

        $projectId = (int) $data['project_id'];

        if (! app(ProjectService::class)->userCanCreateTaskIn($actor, $projectId)) {
            throw ValidationException::withMessages([
                'project_id' => 'You cannot create tasks in this project.',
            ]);
        }

        $sortOrder = (int) Task::query()
            ->where('task_status_id', $status->id)
            ->max('sort_order') + 1;

        return DB::transaction(function () use ($actor, $data, $files, $assigneeIds, $status, $sortOrder, $projectId): Task {
            $task = Task::query()->create([
                'summary' => $data['summary'],
                'description' => $data['description'] ?? null,
                'project_id' => $projectId,
                'task_status_id' => $status->id,
                'priority' => TaskPriority::from($data['priority'] ?? TaskPriority::Medium->value),
                'created_by' => $actor->id,
                'sort_order' => $sortOrder,
                'due_date' => $data['due_date'] ?? null,
            ]);

            $task->assignees()->sync($assigneeIds);
            $this->storeAttachments($task, $actor, $files);

            return $task->load('assignees:id,name,email');
        });
    }

    /**
     * @param  list<int>  $assigneeIds
     */
    public function syncAssignees(Task $task, array $assigneeIds): Task
    {
        $assigneeIds = array_values(array_unique(array_map('intval', $assigneeIds)));

        if ($assigneeIds === []) {
            throw ValidationException::withMessages([
                'assignee_ids' => 'Select at least one assignee.',
            ]);
        }

        $task->assignees()->sync($assigneeIds);

        return $task->load('assignees:id,name,email');
    }

    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function storeAttachments(Task $task, User $actor, array $files): void
    {
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('task-attachments/'.$task->id, 'public');

            TaskAttachment::query()->create([
                'task_id' => $task->id,
                'uploaded_by' => $actor->id,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'disk' => 'public',
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize() ?: 0,
            ]);
        }
    }

    /**
     * Move/reorder a task using the currently loaded column order.
     * Merges with any not-yet-loaded tasks so pagination does not wipe sort order.
     *
     * @param  list<int>  $orderedIds
     */
    public function move(Task $task, TaskStatus $status, array $orderedIds): void
    {
        if (! $status->is_enabled) {
            abort(422, 'Cannot move tasks into a disabled status.');
        }

        if (! in_array($task->id, $orderedIds, true)) {
            abort(422, 'Moved task must be included in ordered_ids.');
        }

        DB::transaction(function () use ($task, $status, $orderedIds): void {
            $oldStatusId = (int) $task->task_status_id;
            $targetStatusId = (int) $status->id;

            $existingIds = Task::query()
                ->where('task_status_id', $targetStatusId)
                ->whereKeyNot($task->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->pluck('id')
                ->map(fn ($id): int => (int) $id)
                ->all();

            $position = array_search($task->id, $orderedIds, true);
            $visibleOthers = array_values(array_filter(
                $orderedIds,
                fn (int $id): bool => $id !== $task->id
            ));

            if ($position === 0) {
                $insertAt = 0;
                if ($visibleOthers !== []) {
                    $firstVisible = $visibleOthers[0];
                    $idx = array_search($firstVisible, $existingIds, true);
                    $insertAt = $idx === false ? 0 : $idx;
                }
            } else {
                $previousId = $orderedIds[$position - 1];
                $idx = array_search($previousId, $existingIds, true);
                $insertAt = $idx === false ? count($existingIds) : $idx + 1;
            }

            array_splice($existingIds, $insertAt, 0, [$task->id]);

            foreach ($existingIds as $index => $id) {
                Task::query()
                    ->whereKey($id)
                    ->update([
                        'task_status_id' => $targetStatusId,
                        'sort_order' => $index,
                    ]);
            }

            if ($oldStatusId !== $targetStatusId) {
                $oldIds = Task::query()
                    ->where('task_status_id', $oldStatusId)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->pluck('id');

                foreach ($oldIds as $index => $id) {
                    Task::query()->whereKey($id)->update(['sort_order' => $index]);
                }
            }
        });
    }

    public function delete(Task $task): void
    {
        DB::transaction(function () use ($task): void {
            foreach ($task->attachments as $attachment) {
                Storage::disk($attachment->disk)->delete($attachment->path);
                $attachment->delete();
            }

            $task->delete();
        });
    }
}
