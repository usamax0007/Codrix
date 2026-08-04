<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = config('xcodrix.services', []);

        foreach (array_values($services) as $index => $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                [
                    'title' => $service['title'],
                    'description' => $service['summary'],
                    'summary' => $service['summary'],
                    'icon' => $service['icon'] ?? null,
                    'is_popular' => (bool) ($service['popular'] ?? false),
                    'what' => $service['what'] ?? null,
                    'benefits' => $service['benefits'] ?? [],
                    'technologies' => $service['technologies'] ?? [],
                    'why' => $service['why'] ?? null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
