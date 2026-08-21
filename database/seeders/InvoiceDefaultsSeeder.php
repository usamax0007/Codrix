<?php

namespace Database\Seeders;

use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;

class InvoiceDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = InvoiceSetting::query()->first();

        if ($settings) {
            $settings->fill(InvoiceSetting::defaults())->save();

            return;
        }

        InvoiceSetting::query()->create(InvoiceSetting::defaults());
    }
}
