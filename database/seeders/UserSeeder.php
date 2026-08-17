<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'abdullah@gmail.com'],
            [
                'name' => 'Abdullah',
                'password' => bcrypt('88888888'),
                'email_verified_at' => now(),
            ]
        );
    }
}
