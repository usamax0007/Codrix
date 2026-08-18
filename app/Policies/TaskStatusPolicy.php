<?php

namespace App\Policies;

use App\Models\TaskStatus;
use App\Models\User;
use App\Support\AppPermission;

class TaskStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(AppPermission::TASKS_MANAGE_STATUSES);
    }

    public function create(User $user): bool
    {
        return $user->can(AppPermission::TASKS_MANAGE_STATUSES);
    }

    public function update(User $user, TaskStatus $taskStatus): bool
    {
        return $user->can(AppPermission::TASKS_MANAGE_STATUSES);
    }

    public function delete(User $user, TaskStatus $taskStatus): bool
    {
        return $user->can(AppPermission::TASKS_MANAGE_STATUSES);
    }
}
