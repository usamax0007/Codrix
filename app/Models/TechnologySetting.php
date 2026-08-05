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
    'bottom_title',
    'bottom_content',
    'bottom_content_2',
    'meta_title',
    'meta_description',
])]
class TechnologySetting extends Model
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
            'bottom_title' => null,
            'bottom_content' => null,
            'bottom_content_2' => null,
            'meta_title' => null,
            'meta_description' => null,
        ];
    }
}
