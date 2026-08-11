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

        return $task->isAssignedTo($user);
    }

    public function create(User $user): bool
    {
        return $user->can(AppPermission::TASKS_CREATE);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can(AppPermission::TASKS_DELETE);
    }

    public function assign(User $user): bool
    {
        return $user->can(AppPermission::TASKS_ASSIGN);
    }
}
