<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'To Do', 'color' => '#EF4444', 'order' => 1],
            ['name' => 'In Progress', 'color' => '#F59E0B', 'order' => 2],
            ['name' => 'Testing', 'color' => '#3B82F6', 'order' => 3],
            ['name' => 'Done', 'color' => '#10B981', 'order' => 4],
        ];

        foreach ($statuses as $status) {
            TaskStatus::updateOrCreate(['name' => $status['name']], $status);
        }
    }
}