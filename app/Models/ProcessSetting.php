<?php

namespace App\Models;

use App\Models\Concerns\HasSingletonSettings;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'hero_badge',
    'hero_title',
    'hero_subtitle',
    'section_badge',
    'section_title',
    'section_subtitle',
    'footer_title',
    'footer_content_1',
    'footer_content_2',
    'meta_title',
    'meta_description',
    'step_1_number',
    'step_1_title',
    'step_1_description',
    'step_2_number',
    'step_2_title',
    'step_2_description',
    'step_3_number',
    'step_3_title',
    'step_3_description',
    'step_4_number',
    'step_4_title',
    'step_4_description',
    'step_5_number',
    'step_5_title',
    'step_5_description',
    'step_6_number',
    'step_6_title',
    'step_6_description',
])]
class ProcessSetting extends Model
{
    use HasSingletonSettings;

    public static function defaults(): array
    {
        $steps = config('xcodrix.process', []);

        $data = [
            'hero_badge' => 'Development Process',
            'hero_title' => "Our Development <span class='xc-gradient-text'>Process</span>",
            'hero_subtitle' => 'A proven 6-step process that takes your idea from concept to production with full transparency.',
            'section_badge' => 'Our Process',
            'section_title' => "How We <span class='xc-gradient-text'>Build</span> Software",
            'section_subtitle' => 'A proven 6-step process from idea to launch and beyond.',
            'footer_title' => 'How Does Our Development Process Work?',
            'footer_content_1' => 'XCodrix follows an agile methodology tailored for software agencies. After an initial discovery call, we deliver a detailed proposal within 48 hours. Once approved, we move through design, development sprints, QA, and launch — with weekly demos so you always see real progress.',
            'footer_content_2' => 'Our process is designed for clarity: you know what\'s being built, when it ships, and how much it costs at every stage. No surprises, no scope creep without discussion.',
            'meta_title' => config('xcodrix.pages.process.title'),
            'meta_description' => config('xcodrix.pages.process.description'),
        ];

        foreach (array_values($steps) as $index => $step) {
            $n = $index + 1;
            $data["step_{$n}_number"] = $step['step'] ?? sprintf('%02d', $n);
            $data["step_{$n}_title"] = $step['title'] ?? null;
            $data["step_{$n}_description"] = $step['description'] ?? null;
        }

        return $data;
    }

    public function steps(): array
    {
        $steps = [];

        for ($i = 1; $i <= 6; $i++) {
            $number = $this->{"step_{$i}_number"};
            $title = $this->{"step_{$i}_title"};
            $description = $this->{"step_{$i}_description"};

            if (blank($number) && blank($title) && blank($description)) {
                continue;
            }

            $steps[] = [
                'step' => $number,
                'title' => $title,
                'description' => $description,
            ];
        }

        return $steps;
    }
}
