<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Neighborhood;

class NeighborhoodSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'riyadh' => [
                'النرجس',
                'الياسمين',
                'السليمانية',
                'العليا',
            ],
            'jeddah' => [
                'الكورنيش',
                'الحمراء',
                'النسيم',
                'الروضة',
            ],
        ];

        foreach ($map as $slug => $neighborhoods) {
            $city = City::where('slug', $slug)->first();
            if (!$city) continue;

            foreach ($neighborhoods as $name) {
                Neighborhood::firstOrCreate([
                    'city_id' => $city->id,
                    'name' => $name,
                ], [
                    'is_active' => true,
                ]);
            }
        }
    }
}
