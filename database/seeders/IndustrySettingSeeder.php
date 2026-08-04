<?php

namespace Database\Seeders;

use App\Models\IndustrySetting;
use Illuminate\Database\Seeder;

class IndustrySettingSeeder extends Seeder
{
    public function run(): void
    {
        IndustrySetting::query()->updateOrCreate(
            ['id' => 1],
            IndustrySetting::defaults()
        );
    }
}
