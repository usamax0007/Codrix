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
        $data = [
            'hero_badge' => null,
            'hero_title' => null,
            'hero_subtitle' => null,
            'section_badge' => null,
            'section_title' => null,
            'section_subtitle' => null,
            'footer_title' => null,
            'footer_content_1' => null,
            'footer_content_2' => null,
            'meta_title' => null,
            'meta_description' => null,
        ];

        for ($n = 1; $n <= 6; $n++) {
            $data["step_{$n}_number"] = null;
            $data["step_{$n}_title"] = null;
            $data["step_{$n}_description"] = null;
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
