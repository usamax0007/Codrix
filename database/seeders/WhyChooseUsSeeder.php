<?php

namespace Database\Seeders;

use App\Models\WhyChooseUs;
use Illuminate\Database\Seeder;

class WhyChooseUsSeeder extends Seeder
{
    public function run(): void
    {
        $items = config('xcodrix.why_choose_us', []);

        foreach (array_values($items) as $index => $item) {
            WhyChooseUs::query()->updateOrCreate(
                ['title' => $item['title']],
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
