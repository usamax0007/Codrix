<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'user@xcoderix.com'],
            [
                'name' => 'Staff User',
                'password' => 'password',
                'role' => UserRole::User,
                'email_verified_at' => now(),
            ]
        );
    }
}
