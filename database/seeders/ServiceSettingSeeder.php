<?php

namespace Database\Seeders;

use App\Models\ServiceSetting;
use Illuminate\Database\Seeder;

class ServiceSettingSeeder extends Seeder
{
    public function run(): void
    {
        ServiceSetting::query()->updateOrCreate(
            ['id' => 1],
            ServiceSetting::defaults()
        );
    }
}
