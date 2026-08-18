<?php

namespace App\Services\User;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserManagementService
{
    public function paginateForPortal(int $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->where('role', '!=', UserRole::SuperAdmin->value)
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * @param  array{name: string, email: string, password: string, role: string}  $data
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => UserRole::from($data['role']),
            ]);

            $user->syncSpatieRole();

            return $user;
        });
    }
}
