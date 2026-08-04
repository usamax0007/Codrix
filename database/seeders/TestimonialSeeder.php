<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = config('xcodrix.testimonials', []);

        foreach (array_values($items) as $index => $item) {
            Testimonial::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'role' => $item['role'] ?? null,
                    'image' => $item['image'] ?? null,
                    'text' => $item['text'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
