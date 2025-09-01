<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\CityTranslation;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => 'الرياض',
                'name_en' => 'Riyadh',
                'slug' => 'riyadh',
                'description' => 'عاصمة المملكة العربية السعودية وأكبر مدنها',
                'description_en' => 'Capital of Saudi Arabia and its largest city',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'جدة',
                'name_en' => 'Jeddah',
                'slug' => 'jeddah',
                'description' => 'العاصمة التجارية للمملكة وميناء الحجاز',
                'description_en' => 'Commercial capital of the Kingdom and port of Hejaz',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'الدمام',
                'name_en' => 'Dammam',
                'slug' => 'dammam',
                'description' => 'عاصمة المنطقة الشرقية ومركز النفط',
                'description_en' => 'Capital of the Eastern Province and oil center',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'مكة المكرمة',
                'name_en' => 'Makkah',
                'slug' => 'makkah',
                'description' => 'أقدس مدن الإسلام وموطن الكعبة المشرفة',
                'description_en' => 'Holiest city of Islam and home of the Kaaba',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'المدينة المنورة',
                'name_en' => 'Madinah',
                'slug' => 'madinah',
                'description' => 'مدينة الرسول صلى الله عليه وسلم',
                'description_en' => 'City of the Prophet Muhammad (PBUH)',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'الخبر',
                'name_en' => 'Khobar',
                'slug' => 'khobar',
                'description' => 'مدينة ساحلية في المنطقة الشرقية',
                'description_en' => 'Coastal city in the Eastern Province',
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'الظهران',
                'name_en' => 'Dhahran',
                'slug' => 'dhahran',
                'description' => 'مركز شركة أرامكو السعودية',
                'description_en' => 'Headquarters of Saudi Aramco',
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'تبوك',
                'name_en' => 'Tabuk',
                'slug' => 'tabuk',
                'description' => 'عاصمة منطقة تبوك',
                'description_en' => 'Capital of Tabuk region',
                'is_featured' => false,
                'sort_order' => 8,
            ],
            [
                'name' => 'أبها',
                'name_en' => 'Abha',
                'slug' => 'abha',
                'description' => 'عاصمة منطقة عسير',
                'description_en' => 'Capital of Asir region',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'حائل',
                'name_en' => 'Hail',
                'slug' => 'hail',
                'description' => 'عاصمة منطقة حائل',
                'description_en' => 'Capital of Hail region',
                'is_featured' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($cities as $cityData) {
            // Create or update the city
            $city = City::updateOrCreate(
                ['slug' => $cityData['slug']],
                [
                    'name' => $cityData['name'],
                    'slug' => $cityData['slug'],
                    'description' => $cityData['description'],
                    'is_featured' => $cityData['is_featured'],
                    'sort_order' => $cityData['sort_order'],
                    'is_active' => true,
                ]
            );

            // Create or update Arabic translation
            CityTranslation::updateOrCreate(
                [
                    'city_id' => $city->id,
                    'locale' => 'ar'
                ],
                [
                    'name' => $cityData['name'],
                    'description' => $cityData['description'],
                ]
            );

            // Create or update English translation
            CityTranslation::updateOrCreate(
                [
                    'city_id' => $city->id,
                    'locale' => 'en'
                ],
                [
                    'name' => $cityData['name_en'],
                    'description' => $cityData['description_en'],
                ]
            );
        }
    }
}
