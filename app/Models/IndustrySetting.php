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
    'bottom_title',
    'bottom_content',
    'meta_title',
    'meta_description',
])]
class IndustrySetting extends Model
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
            'hero_badge' => 'Industries',
            'hero_title' => "Industries We <span class='xc-gradient-text'>Serve</span>",
            'hero_subtitle' => 'Deep domain expertise across SaaS, healthcare, FinTech, e-commerce, and more.',
            'section_badge' => 'Industries',
            'section_title' => "Industries We <span class='xc-gradient-text'>Serve</span>",
            'section_subtitle' => 'Deep domain expertise across multiple sectors.',
            'bottom_title' => 'Which Industries Does XCodrix Serve?',
            'bottom_content' => 'XCodrix works with companies across eight major industries. We understand the compliance requirements, user expectations, and technical challenges unique to each sector — whether that\'s HIPAA for healthcare, PCI for FinTech, or real-time communication for telecommunications.',
            'meta_title' => config('xcodrix.pages.industries.title'),
            'meta_description' => config('xcodrix.pages.industries.description'),
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
