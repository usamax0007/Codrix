<?php

namespace Database\Seeders;

use App\Models\AttendanceSetting;
use Illuminate\Database\Seeder;

class AttendanceSettingSeeder extends Seeder
{
    public function run(): void
    {
        AttendanceSetting::query()->updateOrCreate(
            ['id' => 1],
            AttendanceSetting::defaults()
        );
    }
}
