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
    'bottom_content_2',
    'meta_title',
    'meta_description',
])]
class TechnologySetting extends Model
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
            'hero_badge' => 'Technologies',
            'hero_title' => "Technologies We <span class='xc-gradient-text'>Use</span>",
            'hero_subtitle' => 'Modern, battle-tested technologies chosen for performance, security, and scalability.',
            'section_badge' => 'Technologies',
            'section_title' => "Tech Stack We <span class='xc-gradient-text'>Master</span>",
            'section_subtitle' => 'Modern, battle-tested technologies chosen for performance and scalability.',
            'bottom_title' => 'What Technologies Does XCodrix Specialize In?',
            'bottom_content' => 'XCodrix specializes in the Laravel and PHP ecosystem for backends, Vue.js and Nuxt.js for frontends, React Native and Flutter for mobile, and Twilio for communication systems. We deploy on AWS and GCP with Docker, and integrate AI via OpenAI and Claude APIs.',
            'bottom_content_2' => 'We choose technologies based on your project requirements — not trends. Every stack decision is documented and justified during the discovery phase.',
            'meta_title' => config('xcodrix.pages.technologies.title'),
            'meta_description' => config('xcodrix.pages.technologies.description'),
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
