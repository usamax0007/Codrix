<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class TaskStatusService
{
    /**
     * @return Collection<int, TaskStatus>
     */
    public function allOrdered(): Collection
    {
        return TaskStatus::query()->ordered()->withCount('tasks')->get();
    }

    /**
     * @return Collection<int, TaskStatus>
     */
    public function enabledOrdered(): Collection
    {
        return TaskStatus::query()->enabled()->ordered()->get();
    }

    public function defaultStatus(): TaskStatus
    {
        $status = TaskStatus::query()->enabled()->ordered()->first();

        if (! $status) {
            throw new RuntimeException('No enabled task statuses are configured.');
        }

        return $status;
    }

    /**
     * @param  array{name: string, color: string, is_enabled?: bool, is_completed?: bool, sort_order?: int}  $data
     */
    public function create(array $data): TaskStatus
    {
        return TaskStatus::query()->create([
            'name' => $data['name'],
            'slug' => TaskStatus::uniqueSlugFromName($data['name']),
            'color' => strtoupper($data['color']),
            'is_enabled' => $data['is_enabled'] ?? true,
            'is_completed' => $data['is_completed'] ?? false,
            'sort_order' => $data['sort_order'] ?? ((int) TaskStatus::query()->max('sort_order') + 1),
        ]);
    }

    /**
     * @param  array{name?: string, color?: string, is_enabled?: bool, is_completed?: bool}  $data
     */
    public function update(TaskStatus $status, array $data): TaskStatus
    {
        if (array_key_exists('is_enabled', $data) && $data['is_enabled'] === false) {
            $this->assertCanDisable($status);
        }

        $status->fill([
            'name' => $data['name'] ?? $status->name,
            'color' => isset($data['color']) ? strtoupper($data['color']) : $status->color,
            'is_enabled' => $data['is_enabled'] ?? $status->is_enabled,
            'is_completed' => $data['is_completed'] ?? $status->is_completed,
        ]);
        $status->save();

        return $status->refresh();
    }

    public function toggle(TaskStatus $status): TaskStatus
    {
        if ($status->is_enabled) {
            $this->assertCanDisable($status);
            $status->update(['is_enabled' => false]);
        } else {
            $status->update(['is_enabled' => true]);
        }

        return $status->refresh();
    }

    /**
     * @param  list<int>  $orderedIds
     */
    public function reorder(array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds): void {
            foreach ($orderedIds as $index => $id) {
                TaskStatus::query()->whereKey($id)->update(['sort_order' => $index]);
            }
        });
    }

    public function delete(TaskStatus $status): void
    {
        if ($status->tasks()->exists()) {
            throw ValidationException::withMessages([
                'status' => 'This status is used by tasks. Move or reassign those tasks first, or disable the status instead.',
            ]);
        }

        $enabledCount = TaskStatus::query()->enabled()->count();

        if ($status->is_enabled && $enabledCount <= 1) {
            throw ValidationException::withMessages([
                'status' => 'You cannot delete the last enabled status.',
            ]);
        }

        if (TaskStatus::query()->count() <= 1) {
            throw ValidationException::withMessages([
                'status' => 'You must keep at least one status.',
            ]);
        }

        $status->delete();
    }

    private function assertCanDisable(TaskStatus $status): void
    {
        $enabledCount = TaskStatus::query()->enabled()->count();

        if ($status->is_enabled && $enabledCount <= 1) {
            throw ValidationException::withMessages([
                'status' => 'You cannot disable the last enabled status.',
            ]);
        }
    }
}
