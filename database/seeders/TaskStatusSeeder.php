<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TaskStatus::create([
            'name' => 'to_do',
            'color' => '#9CA3AF'
        ]);

        \App\Models\TaskStatus::create([
            'name' => 'in_progress',
            'color' => '#60A5FA'
        ]);

        \App\Models\TaskStatus::create([
            'name' => 'testing',
            'color' => '#FBBF24'
        ]);

        \App\Models\TaskStatus::create([
            'name' => 'done',
            'color' => '#34D399'
        ]);
    }
}
