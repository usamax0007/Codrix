<?php

namespace Database\Seeders;

use App\Models\PortfolioSetting;
use Illuminate\Database\Seeder;

class PortfolioSettingSeeder extends Seeder
{
    public function run(): void
    {
        PortfolioSetting::query()->updateOrCreate(
            ['id' => 1],
            PortfolioSetting::defaults()
        );
    }
}
