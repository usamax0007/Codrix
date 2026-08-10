<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Support\AppPermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect([
            AppPermission::ATTENDANCE_ACCESS,
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
        ])->map(
            fn (string $name) => Permission::query()->firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ])
        )->keyBy('name');

        foreach (UserRole::cases() as $role) {
            Role::query()->firstOrCreate([
                'name' => $role->value,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::findByName(UserRole::SuperAdmin->value);
        $admin = Role::findByName(UserRole::Admin->value);
        $user = Role::findByName(UserRole::User->value);

        $superAdmin->syncPermissions(Permission::query()->pluck('name'));

        $admin->syncPermissions([
            $permissions[AppPermission::TASKS_ACCESS],
            $permissions[AppPermission::TASKS_ASSIGN],
        ]);

        $user->syncPermissions([
            $permissions[AppPermission::ATTENDANCE_ACCESS],
            $permissions[AppPermission::TASKS_ACCESS],
        ]);
    }
}
