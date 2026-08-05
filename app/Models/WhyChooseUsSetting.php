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
            'hero_badge' => 'Why Choose Us',
            'hero_title' => "Why Choose <span class='xc-gradient-text'>XCodrix</span>",
            'hero_subtitle' => '85+ companies trust XCodrix to build their most important software products.',
            'section_badge' => 'Why Choose Us',
            'section_title' => "Why Companies Trust <span class='xc-gradient-text'>XCodrix</span>",
            'section_subtitle' => 'We combine technical excellence with a partnership mindset.',
            'partner_image' => null,
            'partner_title' => 'A Partner, Not Just a Vendor',
            'partner_content' => 'XCodrix takes ownership of your project\'s success. We proactively suggest improvements, flag risks early, and align our work with your business goals — not just your feature list.',
            'partner_points' => [
                'Direct access to senior developers',
                'Weekly progress demos and transparent reporting',
                'Fixed-price proposals with no hidden costs',
                'Post-launch support and maintenance plans',
            ],
            'meta_title' => config('xcodrix.pages.why-choose-us.title'),
            'meta_description' => config('xcodrix.pages.why-choose-us.description'),
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
