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
            ['email' => 'user@xcodrix.com'],
            [
                'name' => 'Abdullah',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('user');

        $this->command->info('Regular user created successfully.');
        $this->command->info('Email: user@xcodrix.com');
        $this->command->info('Password: password');
    }
}
