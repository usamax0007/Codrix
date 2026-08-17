<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();

        $tasks = [
            [
                'project_id' => $projects->first()->id ?? null,
                'summary' => 'Test',
                'description' => 'Test',
                'assignee_id' => null,
                'priority' => 'high',
                'status' => 'to_do',
                'due_date' => '2024-09-15',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
