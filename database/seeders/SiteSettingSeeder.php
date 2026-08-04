<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'XCodrix',
                'logo' => null,
                'short_description' => 'XCodrix is a premium software development agency building AI-powered SaaS platforms, Laravel backends, Vue.js frontends, mobile apps, and Twilio communication systems.',
                'email' => 'hello@xcodrix.com',
                'phone' => null,
                'address' => null,
                'linkedin' => 'https://linkedin.com/company/xcodrix',
                'twitter' => 'https://twitter.com/xcodrix',
                'github' => 'https://github.com/xcodrix',
            ]
        );
    }
}
