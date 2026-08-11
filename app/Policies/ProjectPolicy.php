<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Support\AppPermission;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(AppPermission::PROJECTS_ACCESS);
    }

    public function view(User $user, Project $project): bool
    {
        if (! $user->can(AppPermission::PROJECTS_ACCESS)) {
            return false;
        }

        if ($user->can(AppPermission::PROJECTS_MANAGE) || $user->can(AppPermission::TASKS_ASSIGN)) {
            return true;
        }

        return $project->tasks()->where('assignee_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->can(AppPermission::PROJECTS_MANAGE);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can(AppPermission::PROJECTS_MANAGE);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can(AppPermission::PROJECTS_MANAGE);
    }
}
