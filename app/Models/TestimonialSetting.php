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
class TestimonialSetting extends Model
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
            'hero_badge' => 'Testimonials',
            'hero_title' => "Client <span class='xc-gradient-text'>Testimonials</span>",
            'hero_subtitle' => 'Hear from the founders and CTOs who trusted XCodrix with their most important projects.',
            'section_badge' => 'Testimonials',
            'section_title' => "What Our <span class='xc-gradient-text'>Clients Say</span>",
            'section_subtitle' => 'Trusted by startups and enterprises worldwide.',
            'meta_title' => config('xcodrix.pages.testimonials.title'),
            'meta_description' => config('xcodrix.pages.testimonials.description'),
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
