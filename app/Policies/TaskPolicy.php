<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Support\AppPermission;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(AppPermission::TASKS_ACCESS);
    }

    public function view(User $user, Task $task): bool
    {
        if (! $user->can(AppPermission::TASKS_ACCESS)) {
            return false;
        }

        if ($user->can(AppPermission::TASKS_ASSIGN)) {
            return true;
        }

        return $task->assignee_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can(AppPermission::TASKS_ACCESS);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->can(AppPermission::TASKS_ASSIGN)) {
            return true;
        }

        return $task->created_by === $user->id && $task->assignee_id === $user->id;
    }

    public function assign(User $user): bool
    {
        return $user->can(AppPermission::TASKS_ASSIGN);
    }
}
