<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'To Do', 'slug' => 'todo', 'color' => '#94A3B8', 'sort_order' => 0, 'is_completed' => false],
            ['name' => 'In Progress', 'slug' => 'in_progress', 'color' => '#0066FF', 'sort_order' => 1, 'is_completed' => false],
            ['name' => 'Testing', 'slug' => 'testing', 'color' => '#FBBF24', 'sort_order' => 2, 'is_completed' => false],
            ['name' => 'Done', 'slug' => 'done', 'color' => '#00E5C0', 'sort_order' => 3, 'is_completed' => true],
        ];

        foreach ($defaults as $status) {
            TaskStatus::query()->updateOrCreate(
                ['slug' => $status['slug']],
                [
                    'name' => $status['name'],
                    'color' => $status['color'],
                    'sort_order' => $status['sort_order'],
                    'is_enabled' => true,
                    'is_completed' => $status['is_completed'],
                ]
            );
        }
    }
}
