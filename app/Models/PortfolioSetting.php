<?php

namespace App\Models;

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
    protected static ?self $cached = null;

    public static function current(): self
    {
        if (static::$cached instanceof self) {
            return static::$cached;
        }

        static::$cached = static::query()->first() ?? new static(static::defaults());

        return static::$cached;
    }

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

    public static function clearCache(): void
    {
        static::$cached = null;
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
