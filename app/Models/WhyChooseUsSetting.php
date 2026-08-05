<?php

namespace App\Models;

use App\Models\Concerns\HasSingletonSettings;
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
    'partner_image',
    'partner_title',
    'partner_content',
    'partner_points',
    'meta_title',
    'meta_description',
])]
class WhyChooseUsSetting extends Model
{
    use HasSingletonSettings;

    protected function casts(): array
    {
        return [
            'partner_points' => 'array',
        ];
    }

    public static function defaults(): array
    {
        return [
            'hero_badge' => null,
            'hero_title' => null,
            'hero_subtitle' => null,
            'section_badge' => null,
            'section_title' => null,
            'section_subtitle' => null,
            'partner_image' => null,
            'partner_title' => null,
            'partner_content' => null,
            'partner_points' => [],
            'meta_title' => null,
            'meta_description' => null,
        ];
    }

    public function partnerImageUrl(): string
    {
        if ($this->partner_image && Storage::disk('public')->exists($this->partner_image)) {
            return Storage::disk('public')->url($this->partner_image);
        }

        if ($this->partner_image && file_exists(public_path('images/'.$this->partner_image))) {
            return asset('images/'.$this->partner_image);
        }

        return asset('images/why-choose-us.webp');
    }

    /**
     * @return list<string>
     */
    public function partnerPointsList(): array
    {
        $points = $this->partner_points ?? [];

        return array_values(array_filter($points, fn ($point) => filled($point)));
    }
}
