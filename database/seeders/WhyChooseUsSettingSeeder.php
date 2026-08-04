<?php

namespace Database\Seeders;

use App\Models\WhyChooseUsSetting;
use Illuminate\Database\Seeder;

class WhyChooseUsSettingSeeder extends Seeder
{
    public function run(): void
    {
        WhyChooseUsSetting::query()->updateOrCreate(
            ['id' => 1],
            WhyChooseUsSetting::defaults()
        );
    }
}
