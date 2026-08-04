<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = config('xcodrix.faq', []);

        foreach (array_values($faqs) as $index => $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['q']],
                [
                    'answer' => $faq['a'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
