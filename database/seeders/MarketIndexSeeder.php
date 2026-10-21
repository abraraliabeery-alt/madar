<?php

namespace Database\Seeders;

use App\Models\MarketIndex;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketIndexSeeder extends Seeder
{
    /**
     * مؤشرات سوق تقريبية 2026 — تُستبدل لاحقاً ببيانات REGA المفتوحة
     */
    public function run(): void
    {
        $data = [
            // [اسم المدينة بالعربي, متوسط إيجار سنوي للشقة, متوسط إيجار فيلا, متوسط متر بيع]
            ['الرياض',   30000, 90000, 4500],
            ['جدة',      26000, 75000, 4200],
            ['مكة',      24000, 70000, 5000],
            ['المدينة',  20000, 60000, 3800],
            ['الدمام',   22000, 65000, 3200],
            ['الخبر',    25000, 80000, 4000],
        ];

        $period = '2026-Q1';

        foreach ($data as [$cityName, $rentApt, $rentVilla, $saleSqm]) {
            $cityId = DB::table('city_translations')->where('name', $cityName)->value('city_id');
            if (!$cityId) {
                continue;
            }

            MarketIndex::updateOrCreate(
                ['city_id' => $cityId, 'property_type' => 'apartment', 'period' => $period],
                ['avg_rent_yearly' => $rentApt, 'avg_sale_sqm' => $saleSqm, 'source' => 'manual']
            );
            MarketIndex::updateOrCreate(
                ['city_id' => $cityId, 'property_type' => 'villa', 'period' => $period],
                ['avg_rent_yearly' => $rentVilla, 'avg_sale_sqm' => $saleSqm, 'source' => 'manual']
            );
            MarketIndex::updateOrCreate(
                ['city_id' => $cityId, 'property_type' => 'all', 'period' => $period],
                ['avg_rent_yearly' => (int) round(($rentApt + $rentVilla) / 2), 'avg_sale_sqm' => $saleSqm, 'source' => 'manual']
            );
        }
    }
}
