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
    'footer_title',
    'footer_content',
    'meta_title',
    'meta_description',
])]
class ServiceSetting extends Model
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
            'hero_badge' => 'Services',
            'hero_title' => "Our <span class='xc-gradient-text'>Services</span>",
            'hero_subtitle' => 'Comprehensive software development services — from AI and SaaS to Laravel, Vue.js, Twilio, and mobile apps.',
            'section_badge' => 'Our Services',
            'section_title' => "What We <span class='xc-gradient-text'>Build</span>",
            'section_subtitle' => 'End-to-end software development services for modern businesses.',
            'footer_title' => 'Why Choose XCodrix for Your Project?',
            'footer_content' => 'Every XCodrix service is delivered by senior engineers with deep domain expertise. We don\'t outsource, we don\'t cut corners, and we don\'t disappear after launch. From the first consultation to post-launch support, you work directly with the team building your product.',
            'meta_title' => 'Our Services — AI, SaaS, Laravel, Vue.js & More | XCodrix',
            'meta_description' => 'Explore XCodrix services: AI development, SaaS platforms, Laravel backends, Vue.js frontends, Twilio voice systems, CRM, APIs, cloud DevOps, and mobile apps.',
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
