<?php

namespace Database\Seeders;

use App\Models\TechnologySetting;
use Illuminate\Database\Seeder;

class TechnologySettingSeeder extends Seeder
{
    public function run(): void
    {
        TechnologySetting::query()->updateOrCreate(
            ['id' => 1],
            TechnologySetting::defaults()
        );
    }
}
