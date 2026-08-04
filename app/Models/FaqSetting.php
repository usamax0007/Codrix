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
class FaqSetting extends Model
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
            'hero_badge' => 'FAQ',
            'hero_title' => "Frequently Asked <span class='xc-gradient-text'>Questions</span>",
            'hero_subtitle' => 'Everything you need to know about working with XCodrix.',
            'section_badge' => 'FAQ',
            'section_title' => "Frequently Asked <span class='xc-gradient-text'>Questions</span>",
            'section_subtitle' => 'Common questions about XCodrix services, process, and pricing.',
            'meta_title' => config('xcodrix.pages.faq.title'),
            'meta_description' => config('xcodrix.pages.faq.description'),
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
