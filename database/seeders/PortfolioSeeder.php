<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = config('xcodrix.portfolio', []);

        foreach (array_values($items) as $index => $item) {
            Portfolio::query()->updateOrCreate(
                ['title' => $item['title']],
                [
                    'category' => $item['category'] ?? null,
                    'image' => $item['image'] ?? null,
                    'description' => $item['description'] ?? null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
