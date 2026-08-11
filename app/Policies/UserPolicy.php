<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Support\AppPermission;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(AppPermission::USERS_MANAGE);
    }

    public function create(User $user): bool
    {
        return $user->can(AppPermission::USERS_MANAGE);
    }

    public function update(User $actor, User $user): bool
    {
        if (! $actor->can(AppPermission::USERS_MANAGE)) {
            return false;
        }

        return $user->role !== UserRole::SuperAdmin;
    }

    public function delete(User $actor, User $user): bool
    {
        if (! $actor->can(AppPermission::USERS_MANAGE)) {
            return false;
        }

        if ($actor->is($user)) {
            return false;
        }

        return $user->role !== UserRole::SuperAdmin;
    }
}
