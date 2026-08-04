<?php

namespace Database\Seeders;

use App\Models\FaqSetting;
use Illuminate\Database\Seeder;

class FaqSettingSeeder extends Seeder
{
    public function run(): void
    {
        FaqSetting::query()->updateOrCreate(
            ['id' => 1],
            FaqSetting::defaults()
        );
    }
}
