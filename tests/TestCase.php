<?php

namespace Tests;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<int|User>|int|User  $assignees
     */
    protected function createTask(array $attributes, array|int|User $assignees = []): Task
    {
        $task = Task::query()->create($attributes);

        $ids = collect(is_array($assignees) ? $assignees : [$assignees])
            ->map(fn ($assignee): int => $assignee instanceof User ? $assignee->id : (int) $assignee)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($ids !== []) {
            $task->assignees()->sync($ids);
        }

        return $task->load('assignees');
    }
}
