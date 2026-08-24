<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'abdullah@gmail.com'],
            [
                'name' => 'Abdullah',
                'password' => Hash::make('88888888'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('user');

        $this->command->info('Regular user created successfully.');
        $this->command->info('Email: abdullah@gmail.com');
        $this->command->info('Password: 88888888');
    }
}
