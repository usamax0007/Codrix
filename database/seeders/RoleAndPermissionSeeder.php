<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'create-task']);
        Permission::create(['name' => 'edit-task']);
        Permission::create(['name' => 'delete-task']);
        Permission::create(['name' => 'reorder-status']);

        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name' => 'Manager']);
        $userRole = Role::create(['name' => 'User']);

        $adminRole->givePermissionTo(Permission::all());
        $managerRole->givePermissionTo(['create-task', 'edit-task', 'reorder-status']);
        $userRole->givePermissionTo(['create-task', 'edit-task']);
    }
}