<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Services\PdfSettingsService;

class PdfSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(PdfSettingsService::class);
        $defaults = $service->defaults();

        Setting::setValue('pdf.product_profile.settings', json_encode($defaults, JSON_UNESCAPED_UNICODE));
    }
}
