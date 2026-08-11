<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Migrate legacy seeded emails when present (avoid unique conflicts).
        User::query()
            ->where('email', 'admin@xcoderix.com')
            ->where('role', UserRole::SuperAdmin)
            ->update(['email' => 'superadmin@xcoderix.com']);

        User::query()
            ->where('email', 'manager@xcoderix.com')
            ->update(['email' => 'admin@xcoderix.com']);

        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@xcoderix.com'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncSpatieRole();

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@xcoderix.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncSpatieRole();
    }
}
