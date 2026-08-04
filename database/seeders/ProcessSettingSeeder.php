<?php

namespace Database\Seeders;

use App\Models\ProcessSetting;
use Illuminate\Database\Seeder;

class ProcessSettingSeeder extends Seeder
{
    public function run(): void
    {
        ProcessSetting::query()->updateOrCreate(
            ['id' => 1],
            ProcessSetting::defaults()
        );
    }
}
