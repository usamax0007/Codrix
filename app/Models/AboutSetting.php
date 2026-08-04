<?php

namespace App\Models;

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
            'hero_badge' => 'About Us',
            'hero_title' => 'About XCodrix',
            'hero_subtitle' => 'A premium software development agency with 12+ years of experience building products that matter.',
            'section_badge' => 'About XCodrix',
            'section_title' => "Who We <span class='xc-gradient-text'>Are</span>",
            'section_subtitle' => 'A premium software development agency helping businesses build scalable digital products.',
            'intro_heading' => 'We Build Digital Products That Drive Growth',
            'intro_paragraph_1' => 'XCodrix is a software development agency that partners with startups and enterprises to design, build, and scale custom software. We specialize in AI-powered applications, SaaS platforms, Laravel backends, Vue.js frontends, mobile apps, and Twilio communication systems.',
            'intro_paragraph_2' => 'With 12+ years of experience and 150+ projects delivered, our team combines deep technical expertise with a transparent, client-first approach. We don\'t just write code — we solve business problems.',
            'who_we_help_title' => 'Who We Help',
            'who_we_help_content' => 'XCodrix partners with SaaS startups launching their first product, mid-size companies scaling existing platforms, and enterprises modernizing legacy systems. We serve clients in healthcare, FinTech, e-commerce, education, logistics, and telecommunications.',
            'what_we_do_title' => 'What We Do',
            'what_we_do_content' => 'We provide end-to-end software development — from discovery and UI/UX design to development, testing, deployment, and ongoing support. Our core expertise includes AI development, SaaS platforms, Laravel backends, Vue.js and Nuxt.js frontends, mobile apps, Twilio communication systems, CRM development, API design, and cloud DevOps.',
            'mission_title' => 'Our Mission',
            'mission_content' => 'To help businesses transform ideas into scalable, high-quality software that drives measurable growth. We believe in transparent communication, realistic timelines, and building long-term partnerships with every client.',
            'stat_1_value' => '150+',
            'stat_1_label' => 'Projects Delivered',
            'stat_2_value' => '85+',
            'stat_2_label' => 'Happy Clients',
            'stat_3_value' => '12+',
            'stat_3_label' => 'Years Experience',
            'stat_4_value' => '98%',
            'stat_4_label' => 'Client Retention',
            'meta_title' => 'About XCodrix — Expert Software Development Team',
            'meta_description' => 'Learn about XCodrix, a premium software development agency with 12+ years of experience building SaaS, AI, Laravel, and mobile solutions for startups and enterprises.',
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
