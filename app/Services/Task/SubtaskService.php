<?php

namespace App\Services\Task;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubtaskService
{
    /**
     * @param  array{title: string, description?: ?string}  $data
     */
    public function create(Task $task, User $actor, array $data): Subtask
    {
        $sortOrder = (int) $task->subtasks()->max('sort_order') + 1;

        return Subtask::query()->create([
            'task_id' => $task->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_completed' => false,
            'sort_order' => $sortOrder,
            'created_by' => $actor->id,
        ]);
    }

    /**
     * @param  array{title?: string, description?: ?string}  $data
     */
    public function update(Subtask $subtask, array $data): Subtask
    {
        $subtask->fill([
            'title' => $data['title'] ?? $subtask->title,
            'description' => array_key_exists('description', $data)
                ? $data['description']
                : $subtask->description,
        ]);
        $subtask->save();

        return $subtask->refresh();
    }

    public function toggle(Subtask $subtask): Subtask
    {
        $subtask->update([
            'is_completed' => ! $subtask->is_completed,
        ]);

        return $subtask->refresh();
    }

    /**
     * @param  list<int>  $orderedIds
     */
    public function reorder(Task $task, array $orderedIds): void
    {
        DB::transaction(function () use ($task, $orderedIds): void {
            foreach ($orderedIds as $index => $id) {
                Subtask::query()
                    ->where('task_id', $task->id)
                    ->whereKey($id)
                    ->update(['sort_order' => $index]);
            }
        });
    }

    public function delete(Subtask $subtask): void
    {
        $subtask->delete();
    }
}
