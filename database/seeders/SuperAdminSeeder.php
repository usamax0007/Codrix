<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@codrix.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->assignRole('super-admin');

        $this->command->info('Super Admin user created successfully.');
        $this->command->info('Email: superadmin@coderix.com');
        $this->command->info('Password: password');
    }
}
