<?php

namespace App\Services\Task;

use App\Enums\TaskPriority;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use App\Models\TaskStatus;
use App\Models\User;
use App\Support\AppPermission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskBoardService
{
    public function __construct(private readonly TaskStatusService $statuses) {}

    /**
     * @return array{statuses: Collection<int, TaskStatus>, columns: array<int, Collection<int, Task>>}
     */
    public function boardFor(User $user): array
    {
        $statuses = $this->statuses->enabledOrdered();

        $query = Task::query()
            ->with(['assignee:id,name,email', 'creator:id,name', 'status', 'project:id,name'])
            ->withCount(['allComments as comments_count', 'attachments'])
            ->orderBy('sort_order')
            ->orderBy('id');

        if (! $user->can(AppPermission::TASKS_ASSIGN)) {
            $query->where('assignee_id', $user->id);
        }

        $tasks = $query->get()->groupBy(fn (Task $task): int => (int) $task->task_status_id);

        $columns = $statuses
            ->mapWithKeys(fn (TaskStatus $status): array => [
                $status->id => $tasks->get($status->id, collect()),
            ])
            ->all();

        return [
            'statuses' => $statuses,
            'columns' => $columns,
        ];
    }

    public function loadDetails(Task $task): Task
    {
        return $task->load([
            'project:id,name,slug',
            'status',
            'assignee:id,name,email',
            'creator:id,name,email',
            'attachments.uploader:id,name',
            'comments' => fn ($query) => $query
                ->with(['user:id,name', 'replies' => fn ($replies) => $replies->with('user:id,name')->oldest()])
                ->withCount('replies')
                ->latest(),
        ])->loadCount('allComments as comments_count');
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
     * @param  array{summary: string, description?: ?string, project_id: int, task_status_id?: int, priority?: string, assignee_id?: int, due_date?: ?string}  $data
     * @param  array<int, UploadedFile>  $files
     */
    public function create(User $actor, array $data, array $files = []): Task
    {
        $assigneeId = $actor->can(AppPermission::TASKS_ASSIGN)
            ? (int) $data['assignee_id']
            : $actor->id;

        $status = isset($data['task_status_id'])
            ? TaskStatus::query()->enabled()->whereKey($data['task_status_id'])->firstOrFail()
            : $this->statuses->defaultStatus();

        $projectId = (int) $data['project_id'];

        $sortOrder = (int) Task::query()
            ->where('task_status_id', $status->id)
            ->max('sort_order') + 1;

        return DB::transaction(function () use ($actor, $data, $files, $assigneeId, $status, $sortOrder, $projectId): Task {
            $task = Task::query()->create([
                'summary' => $data['summary'],
                'description' => $data['description'] ?? null,
                'project_id' => $projectId,
                'task_status_id' => $status->id,
                'priority' => TaskPriority::from($data['priority'] ?? TaskPriority::Medium->value),
                'assignee_id' => $assigneeId,
                'created_by' => $actor->id,
                'sort_order' => $sortOrder,
                'due_date' => $data['due_date'] ?? null,
            ]);

            $this->storeAttachments($task, $actor, $files);

            return $task;
        });
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
     * @param  list<int>  $orderedIds
     */
    public function move(Task $task, TaskStatus $status, array $orderedIds): void
    {
        if (! $status->is_enabled) {
            abort(422, 'Cannot move tasks into a disabled status.');
        }

        DB::transaction(function () use ($task, $status, $orderedIds): void {
            $task->update([
                'task_status_id' => $status->id,
            ]);

            foreach ($orderedIds as $index => $id) {
                Task::query()
                    ->whereKey($id)
                    ->update([
                        'task_status_id' => $status->id,
                        'sort_order' => $index,
                    ]);
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
