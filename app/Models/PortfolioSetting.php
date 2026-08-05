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
    'meta_title',
    'meta_description',
])]
class PortfolioSetting extends Model
{
    use HasSingletonSettings;

    public static function defaults(): array
    {
        return [
            'hero_badge' => 'Portfolio',
            'hero_title' => "Case Studies & <span class='xc-gradient-text'>Projects</span>",
            'hero_subtitle' => 'Real solutions built for SaaS startups, healthcare, FinTech, and enterprise clients.',
            'section_badge' => 'Portfolio',
            'section_title' => "Case Studies & <span class='xc-gradient-text'>Projects</span>",
            'section_subtitle' => 'Real solutions built for real businesses.',
            'meta_title' => config('xcodrix.pages.portfolio.title'),
            'meta_description' => config('xcodrix.pages.portfolio.description'),
        ];
    }
}
