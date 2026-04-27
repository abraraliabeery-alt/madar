<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Neighborhood;
use App\Models\City;
use App\Models\Street;

class StreetSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'riyadh' => [
                'النرجس' => ['طريق الملك سلمان', 'طريق أبي بكر الصديق', 'شارع أنس بن مالك'],
                'الياسمين' => ['طريق الملك عبدالعزيز', 'طريق الثمامة', 'شارع القادسية'],
                'السليمانية' => ['شارع الأمير محمد بن عبدالعزيز', 'شارع التحلية', 'شارع العليا'],
                'العليا' => ['شارع العليا العام', 'طريق الملك فهد', 'شارع موسى بن نصير'],
            ],
            'jeddah' => [
                'الكورنيش' => ['طريق الكورنيش', 'شارع الأمير فيصل بن فهد', 'شارع صاري'],
                'الحمراء' => ['شارع فلسطين', 'شارع الأندلس', 'شارع الحمراء'],
                'النسيم' => ['شارع الأمير ماجد', 'شارع عبدالله سليمان', 'شارع النسيم'],
                'الروضة' => ['شارع الأمير سعود الفيصل', 'شارع الروضة', 'طريق المدينة'],
            ],
        ];

        foreach ($data as $citySlug => $neighMap) {
            $city = City::where('slug', $citySlug)->first();
            if (!$city) continue;
            foreach ($neighMap as $neighborhoodName => $streets) {
                $neigh = Neighborhood::where('city_id', $city->id)->where('name', $neighborhoodName)->first();
                if (!$neigh) continue;
                foreach ($streets as $streetName) {
                    Street::firstOrCreate([
                        'neighborhood_id' => $neigh->id,
                        'name' => $streetName,
                    ], [
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
