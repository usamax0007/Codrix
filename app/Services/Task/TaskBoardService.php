<?php

namespace App\Services\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use App\Models\User;
use App\Support\AppPermission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskBoardService
{
    /**
     * @return array<string, Collection<int, Task>>
     */
    public function boardFor(User $user): array
    {
        $query = Task::query()
            ->with(['assignee:id,name,email', 'creator:id,name'])
            ->withCount(['comments', 'attachments'])
            ->orderBy('sort_order')
            ->orderBy('id');

        if (! $user->can(AppPermission::TASKS_ASSIGN)) {
            $query->where('assignee_id', $user->id);
        }

        $tasks = $query->get()->groupBy(fn (Task $task): string => $task->status->value);

        return collect(TaskStatus::cases())
            ->mapWithKeys(fn (TaskStatus $status): array => [
                $status->value => $tasks->get($status->value, collect()),
            ])
            ->all();
    }

    public function loadDetails(Task $task): Task
    {
        return $task->load([
            'assignee:id,name,email',
            'creator:id,name,email',
            'attachments.uploader:id,name',
            'comments.user:id,name',
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
     * @param  array{summary: string, description?: ?string, status?: string, priority?: string, assignee_id?: int, due_date?: ?string}  $data
     * @param  array<int, UploadedFile>  $files
     */
    public function create(User $actor, array $data, array $files = []): Task
    {
        $assigneeId = $actor->can(AppPermission::TASKS_ASSIGN)
            ? (int) $data['assignee_id']
            : $actor->id;

        $status = TaskStatus::from($data['status'] ?? TaskStatus::Todo->value);

        $sortOrder = (int) Task::query()
            ->where('status', $status->value)
            ->max('sort_order') + 1;

        return DB::transaction(function () use ($actor, $data, $files, $assigneeId, $status, $sortOrder): Task {
            $task = Task::query()->create([
                'summary' => $data['summary'],
                'description' => $data['description'] ?? null,
                'status' => $status,
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

    public function addComment(Task $task, User $actor, string $body): TaskComment
    {
        return TaskComment::query()->create([
            'task_id' => $task->id,
            'user_id' => $actor->id,
            'body' => $body,
        ]);
    }

    /**
     * @param  list<int>  $orderedIds
     */
    public function move(Task $task, TaskStatus $status, array $orderedIds): void
    {
        DB::transaction(function () use ($task, $status, $orderedIds): void {
            $task->update([
                'status' => $status,
            ]);

            foreach ($orderedIds as $index => $id) {
                Task::query()
                    ->whereKey($id)
                    ->update([
                        'status' => $status->value,
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
