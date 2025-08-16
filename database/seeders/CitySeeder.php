<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

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
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'جدة',
                'name_en' => 'Jeddah',
                'slug' => 'jeddah',
                'description' => 'العاصمة التجارية للمملكة وميناء الحجاز',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'الدمام',
                'name_en' => 'Dammam',
                'slug' => 'dammam',
                'description' => 'عاصمة المنطقة الشرقية ومركز النفط',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'مكة المكرمة',
                'name_en' => 'Makkah',
                'slug' => 'makkah',
                'description' => 'أقدس مدن الإسلام وموطن الكعبة المشرفة',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'المدينة المنورة',
                'name_en' => 'Madinah',
                'slug' => 'madinah',
                'description' => 'مدينة الرسول صلى الله عليه وسلم',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'الخبر',
                'name_en' => 'Khobar',
                'slug' => 'khobar',
                'description' => 'مدينة ساحلية في المنطقة الشرقية',
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'الظهران',
                'name_en' => 'Dhahran',
                'slug' => 'dhahran',
                'description' => 'مركز شركة أرامكو السعودية',
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'تبوك',
                'name_en' => 'Tabuk',
                'slug' => 'tabuk',
                'description' => 'عاصمة منطقة تبوك',
                'is_featured' => false,
                'sort_order' => 8,
            ],
            [
                'name' => 'أبها',
                'name_en' => 'Abha',
                'slug' => 'abha',
                'description' => 'عاصمة منطقة عسير',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'حائل',
                'name_en' => 'Hail',
                'slug' => 'hail',
                'description' => 'عاصمة منطقة حائل',
                'is_featured' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['slug' => $city['slug']],
                $city
            );
        }
    }
}
