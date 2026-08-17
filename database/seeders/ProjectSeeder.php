<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'name' => 'Test Project',
                'description' => 'Test project for testing task management functionality.',
                'due_date' => '2024-12-31',
                'end_date' => '2024-12-31',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
