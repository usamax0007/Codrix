<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            StaffUserSeeder::class,
            AttendanceSettingSeeder::class,
            BlogPostSeeder::class,
            SiteSettingSeeder::class,
            AboutSettingSeeder::class,
            ServiceSeeder::class,
            ServiceSettingSeeder::class,
            ProcessSettingSeeder::class,
            FaqSeeder::class,
            FaqSettingSeeder::class,
            PortfolioSeeder::class,
            PortfolioSettingSeeder::class,
            WhyChooseUsSeeder::class,
            WhyChooseUsSettingSeeder::class,
            IndustrySeeder::class,
            IndustrySettingSeeder::class,
            TechnologyCategorySeeder::class,
            TechnologySettingSeeder::class,
            TestimonialSeeder::class,
            TestimonialSettingSeeder::class,
        ]);
    }
}
