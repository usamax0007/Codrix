<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        $permissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',

            'view-admins',
            'create-admins',
            'edit-admins',
            'delete-admins',
            'manage-admins',

            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'assign-task',
            'manage-task-statuses',

            'view-task-status',
            'create-task-status',
            'edit-task-status',
            'delete-task-status',

            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'manage-projects',

            'create-subtask',
            'edit-subtask',
            'delete-subtask',

            'view-attendance',
            'manage-attendance',

            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'manage-permissions',
            'assign-roles-to-users',

            'manage-admin-access',
            'manage-user-access',

            'view-reports',
            'generate-reports',

            'view-settings',
            'manage-settings',

            'view-profile',
            'edit-profile',
            'view-orders',
            'manage-orders',

            'attendance access',
            'tasks access',
            'projects access',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }


        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $superAdminPermissions = [
            // User management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',
            
            // Admin management
            'view-admins',
            'create-admins',
            'edit-admins',
            'delete-admins',
            'manage-admins',
            
            // Task management
            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'assign-task',
            'manage-task-statuses',
            
            // Task status management
            'view-task-status',
            'create-task-status',
            'edit-task-status',
            'delete-task-status',
            
            // Project management
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'manage-projects',
            
            // Subtask management
            'create-subtask',
            'edit-subtask',
            'delete-subtask',
            
            // Attendance
            'view-attendance',
            'manage-attendance',
            
            // Roles & Permissions
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'manage-permissions',
            'assign-roles-to-users',
            
            // Access management
            'manage-admin-access',
            'manage-user-access',
            
            // Reports
            'view-reports',
            'generate-reports',
            
            // Settings
            'view-settings',
            'manage-settings',
            
            // Profile & Orders
            'view-profile',
            'edit-profile',
            'view-orders',
            'manage-orders',
            
            // Access permissions
            'attendance access',
            'tasks access',
            'projects access',
        ];
        $superAdmin->syncPermissions($superAdminPermissions);

        $adminPermissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',
            'view-reports',
            'generate-reports',
            'manage-user-access',
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'view-task-status',
            'create-task-status',
            'edit-task-status',
            'delete-task-status',
            'manage-task-statuses',
        ];
        $admin->syncPermissions($adminPermissions);

        $userPermissions = [
            'view-profile',
            'edit-profile',
            'view-projects',
            'view-tasks',
            'view-task-status',
        ];
        $user->syncPermissions($userPermissions);

        $this->command->info('Roles and permissions seeded successfully.');
    }
}