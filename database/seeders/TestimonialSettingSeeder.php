<?php

namespace Database\Seeders;

use App\Models\TestimonialSetting;
use Illuminate\Database\Seeder;

class TestimonialSettingSeeder extends Seeder
{
    public function run(): void
    {
        TestimonialSetting::query()->updateOrCreate(
            ['id' => 1],
            TestimonialSetting::defaults()
        );
    }
}
