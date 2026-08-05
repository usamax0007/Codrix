<?php

namespace App\Models;

use App\Models\Concerns\HasSingletonSettings;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'site_name',
    'logo',
    'short_description',
    'email',
    'phone',
    'address',
    'linkedin',
    'twitter',
    'github',
])]
class SiteSetting extends Model
{
    use HasSingletonSettings;

    public static function defaults(): array
    {
        return [
            'site_name' => config('xcodrix.name', 'XCodrix'),
            'email' => config('xcodrix.email'),
            'short_description' => 'XCodrix is a premium software development agency building AI-powered SaaS platforms, Laravel backends, Vue.js frontends, mobile apps, and Twilio communication systems.',
            'linkedin' => config('xcodrix.social.linkedin'),
            'twitter' => config('xcodrix.social.twitter'),
            'github' => config('xcodrix.social.github'),
        ];
    }

    public function logoUrl(): string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::disk('public')->url($this->logo);
        }

        return asset('images/xcodrix-logo.png');
    }

    public function socialLinks(): array
    {
        return array_filter([
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
            'github' => $this->github,
        ]);
    }
}
