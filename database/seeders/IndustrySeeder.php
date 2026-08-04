<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $items = config('xcodrix.industries', []);

        foreach (array_values($items) as $index => $item) {
            Industry::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'description' => $item['description'] ?? null,
                    'icon' => $item['icon'] ?? null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
