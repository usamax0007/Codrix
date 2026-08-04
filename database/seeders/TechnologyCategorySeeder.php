<?php

namespace Database\Seeders;

use App\Models\TechnologyCategory;
use Illuminate\Database\Seeder;

class TechnologyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = config('xcodrix.technologies', []);

        $index = 0;
        foreach ($categories as $name => $items) {
            $index++;

            TechnologyCategory::query()->updateOrCreate(
                ['name' => $name],
                [
                    'items' => array_values($items),
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
