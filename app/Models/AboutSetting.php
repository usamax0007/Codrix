<?php

namespace App\Models;

use App\Models\Concerns\HasSingletonSettings;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'hero_badge',
    'hero_title',
    'hero_subtitle',
    'section_badge',
    'section_title',
    'section_subtitle',
    'intro_heading',
    'intro_paragraph_1',
    'intro_paragraph_2',
    'image_1',
    'image_2',
    'who_we_help_title',
    'who_we_help_content',
    'what_we_do_title',
    'what_we_do_content',
    'mission_title',
    'mission_content',
    'stat_1_value',
    'stat_1_label',
    'stat_2_value',
    'stat_2_label',
    'stat_3_value',
    'stat_3_label',
    'stat_4_value',
    'stat_4_label',
    'meta_title',
    'meta_description',
])]
class AboutSetting extends Model
{
    use HasSingletonSettings;

    public static function defaults(): array
    {
        return [
            'hero_badge' => null,
            'hero_title' => null,
            'hero_subtitle' => null,
            'section_badge' => null,
            'section_title' => null,
            'section_subtitle' => null,
            'intro_heading' => null,
            'intro_paragraph_1' => null,
            'intro_paragraph_2' => null,
            'who_we_help_title' => null,
            'who_we_help_content' => null,
            'what_we_do_title' => null,
            'what_we_do_content' => null,
            'mission_title' => null,
            'mission_content' => null,
            'stat_1_value' => null,
            'stat_1_label' => null,
            'stat_2_value' => null,
            'stat_2_label' => null,
            'stat_3_value' => null,
            'stat_3_label' => null,
            'stat_4_value' => null,
            'stat_4_label' => null,
            'meta_title' => null,
            'meta_description' => null,
        ];
    }

    public function image1Url(): string
    {
        return $this->imageUrl($this->image_1, 'images/about-1.webp');
    }

    public function image2Url(): string
    {
        return $this->imageUrl($this->image_2, 'images/about-2.webp');
    }

    public function stats(): array
    {
        return array_values(array_filter([
            ['value' => $this->stat_1_value, 'label' => $this->stat_1_label],
            ['value' => $this->stat_2_value, 'label' => $this->stat_2_label],
            ['value' => $this->stat_3_value, 'label' => $this->stat_3_label],
            ['value' => $this->stat_4_value, 'label' => $this->stat_4_label],
        ], fn (array $stat) => filled($stat['value']) || filled($stat['label'])));
    }

    protected function imageUrl(?string $path, string $fallback): string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset($fallback);
    }
}
