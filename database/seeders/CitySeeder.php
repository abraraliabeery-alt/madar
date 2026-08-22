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
            ['name' => 'الرياض', 'name_en' => 'Riyadh', 'slug' => 'riyadh', 'is_featured' => true],
            ['name' => 'جدة', 'name_en' => 'Jeddah', 'slug' => 'jeddah', 'is_featured' => true],
            ['name' => 'مكة المكرمة', 'name_en' => 'Makkah', 'slug' => 'makkah', 'is_featured' => true],
            ['name' => 'المدينة المنورة', 'name_en' => 'Madinah', 'slug' => 'madinah', 'is_featured' => true],
            ['name' => 'الدمام', 'name_en' => 'Dammam', 'slug' => 'dammam', 'is_featured' => true],
            ['name' => 'الخبر', 'name_en' => 'Khobar', 'slug' => 'khobar', 'is_featured' => true],
            ['name' => 'الظهران', 'name_en' => 'Dhahran', 'slug' => 'dhahran', 'is_featured' => true],
            ['name' => 'الأحساء', 'name_en' => 'Al-Ahsa', 'slug' => 'al-ahsa', 'is_featured' => true],
            ['name' => 'القطيف', 'name_en' => 'Qatif', 'slug' => 'qatif', 'is_featured' => false],
            ['name' => 'الجبيل', 'name_en' => 'Jubail', 'slug' => 'jubail', 'is_featured' => false],
            ['name' => 'ينبع', 'name_en' => 'Yanbu', 'slug' => 'yanbu', 'is_featured' => false],
            ['name' => 'رابغ', 'name_en' => 'Rabigh', 'slug' => 'rabigh', 'is_featured' => false],
            ['name' => 'الطائف', 'name_en' => 'Taif', 'slug' => 'taif', 'is_featured' => true],
            ['name' => 'بريدة', 'name_en' => 'Buraidah', 'slug' => 'buraidah', 'is_featured' => true],
            ['name' => 'عنيزة', 'name_en' => 'Unaizah', 'slug' => 'unaizah', 'is_featured' => false],
            ['name' => 'أبها', 'name_en' => 'Abha', 'slug' => 'abha', 'is_featured' => true],
            ['name' => 'خميس مشيط', 'name_en' => 'Khamis Mushait', 'slug' => 'khamis-mushait', 'is_featured' => false],
            ['name' => 'جازان', 'name_en' => 'Jazan', 'slug' => 'jazan', 'is_featured' => true],
            ['name' => 'نجران', 'name_en' => 'Najran', 'slug' => 'najran', 'is_featured' => true],
            ['name' => 'الباحة', 'name_en' => 'Al-Bahah', 'slug' => 'al-bahah', 'is_featured' => true],
            ['name' => 'تبوك', 'name_en' => 'Tabuk', 'slug' => 'tabuk', 'is_featured' => true],
            ['name' => 'حائل', 'name_en' => 'Hail', 'slug' => 'hail', 'is_featured' => true],
            ['name' => 'سكاكا', 'name_en' => 'Sakaka', 'slug' => 'sakaka', 'is_featured' => true],
            ['name' => 'عرعر', 'name_en' => 'Arar', 'slug' => 'arar', 'is_featured' => true],
            ['name' => 'حفر الباطن', 'name_en' => 'Hafr Al-Batin', 'slug' => 'hafr-al-batin', 'is_featured' => false],
            ['name' => 'الخفجي', 'name_en' => 'Khafji', 'slug' => 'khafji', 'is_featured' => false],
            ['name' => 'الخرج', 'name_en' => 'Al-Kharj', 'slug' => 'al-kharj', 'is_featured' => false],
            ['name' => 'وادي الدواسر', 'name_en' => 'Wadi ad-Dawasir', 'slug' => 'wadi-ad-dawasir', 'is_featured' => false],
            ['name' => 'القريات', 'name_en' => 'Al Qurayyat', 'slug' => 'al-qurayyat', 'is_featured' => false],
            ['name' => 'العلا', 'name_en' => 'Al-Ula', 'slug' => 'al-ula', 'is_featured' => false],
            ['name' => 'أملج', 'name_en' => 'Umluj', 'slug' => 'umluj', 'is_featured' => false],
            ['name' => 'الوجه', 'name_en' => 'Al Wajh', 'slug' => 'al-wajh', 'is_featured' => false],
            ['name' => 'ضباء', 'name_en' => 'Duba', 'slug' => 'duba', 'is_featured' => false],
            ['name' => 'تيماء', 'name_en' => 'Tayma', 'slug' => 'tayma', 'is_featured' => false],
            ['name' => 'دومة الجندل', 'name_en' => 'Dumat Al-Jandal', 'slug' => 'dumat-al-jandal', 'is_featured' => false],
            ['name' => 'بيشة', 'name_en' => 'Bisha', 'slug' => 'bisha', 'is_featured' => false],
            ['name' => 'محايل', 'name_en' => 'Muhayl', 'slug' => 'muhayl', 'is_featured' => false],
            ['name' => 'تنومة', 'name_en' => 'Tanomah', 'slug' => 'tanomah', 'is_featured' => false],
            ['name' => 'النماص', 'name_en' => 'Namas', 'slug' => 'namas', 'is_featured' => false],
            ['name' => 'سراة عبيدة', 'name_en' => 'Sarat Abidah', 'slug' => 'sarat-abidah', 'is_featured' => false],
            ['name' => 'رجال المع', 'name_en' => 'Rijal Almaa', 'slug' => 'rijal-almaa', 'is_featured' => false],
        ];

        foreach ($cities as $index => $cityData) {
            $description = 'مدينة ' . $cityData['name'] . ' في المملكة العربية السعودية';
            $description_en = $cityData['name_en'] . ' city in Saudi Arabia';
            $image = 'https://image.pollinations.ai/prompt/' . rawurlencode('Famous landmark in ' . $cityData['name_en'] . ', Saudi Arabia') . '?seed=' . $cityData['slug'] . '&width=500&height=300&nologo=true';

            // Create or update the city
            $city = City::updateOrCreate(
                ['slug' => $cityData['slug']],
                [
                    'name' => $cityData['name'],
                    'slug' => $cityData['slug'],
                    'description' => $description,
                    'image' => $image,
                    'is_featured' => $cityData['is_featured'] ?? false,
                    'sort_order' => $index + 1,
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
                    'description' => $description,
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
                    'description' => $description_en,
                ]
            );
        }
    }
}
