<?php

namespace Database\Seeders;

use App\Models\AboutSetting;
use Illuminate\Database\Seeder;

class AboutSettingSeeder extends Seeder
{
    public function run(): void
    {
        AboutSetting::query()->updateOrCreate(
            ['id' => 1],
            AboutSetting::defaults()
        );
    }
}
