<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use App\Models\FeatureTranslation;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'icon' => 'fas fa-snowflake',
                'description' => 'مكيف هواء مركزي أو منفصل',
                'is_active' => true,
                'order' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'مكيف هواء',
                        'description' => 'مكيف هواء مركزي أو منفصل',
                    ],
                    'en' => [
                        'name' => 'Air Conditioning',
                        'description' => 'Central or split air conditioning',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-utensils',
                'description' => 'مطبخ كامل التجهيز',
                'is_active' => true,
                'order' => 2,
                'translations' => [
                    'ar' => [
                        'name' => 'مطبخ مجهز',
                        'description' => 'مطبخ كامل التجهيز',
                    ],
                    'en' => [
                        'name' => 'Equipped Kitchen',
                        'description' => 'Fully equipped kitchen',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-elevator',
                'description' => 'مصعد للعمارة',
                'is_active' => true,
                'order' => 3,
                'translations' => [
                    'ar' => [
                        'name' => 'مصعد',
                        'description' => 'مصعد للعمارة',
                    ],
                    'en' => [
                        'name' => 'Elevator',
                        'description' => 'Building elevator',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-car',
                'description' => 'موقف سيارات خاص',
                'is_active' => true,
                'order' => 4,
                'translations' => [
                    'ar' => [
                        'name' => 'موقف سيارات',
                        'description' => 'موقف سيارات خاص',
                    ],
                    'en' => [
                        'name' => 'Parking',
                        'description' => 'Private parking space',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-tree',
                'description' => 'حديقة خاصة',
                'is_active' => true,
                'order' => 5,
                'translations' => [
                    'ar' => [
                        'name' => 'حديقة',
                        'description' => 'حديقة خاصة',
                    ],
                    'en' => [
                        'name' => 'Garden',
                        'description' => 'Private garden',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-swimming-pool',
                'description' => 'مسبح خاص',
                'is_active' => true,
                'order' => 6,
                'translations' => [
                    'ar' => [
                        'name' => 'مسبح',
                        'description' => 'مسبح خاص',
                    ],
                    'en' => [
                        'name' => 'Swimming Pool',
                        'description' => 'Private swimming pool',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-dumbbell',
                'description' => 'صالة رياضية مجهزة',
                'is_active' => true,
                'order' => 7,
                'translations' => [
                    'ar' => [
                        'name' => 'صالة رياضية',
                        'description' => 'صالة رياضية مجهزة',
                    ],
                    'en' => [
                        'name' => 'Gym',
                        'description' => 'Equipped gym',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'description' => 'حراسة أمنية 24/7',
                'is_active' => true,
                'order' => 8,
                'translations' => [
                    'ar' => [
                        'name' => 'خدمات أمنية',
                        'description' => 'حراسة أمنية 24/7',
                    ],
                    'en' => [
                        'name' => 'Security',
                        'description' => '24/7 security service',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-wifi',
                'description' => 'اتصال إنترنت مجاني',
                'is_active' => true,
                'order' => 9,
                'translations' => [
                    'ar' => [
                        'name' => 'إنترنت مجاني',
                        'description' => 'اتصال إنترنت مجاني',
                    ],
                    'en' => [
                        'name' => 'Free Internet',
                        'description' => 'Free internet connection',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-tshirt',
                'description' => 'غسالة ملابس في الوحدة',
                'is_active' => true,
                'order' => 10,
                'translations' => [
                    'ar' => [
                        'name' => 'غسالة ملابس',
                        'description' => 'غسالة ملابس في الوحدة',
                    ],
                    'en' => [
                        'name' => 'Washing Machine',
                        'description' => 'Washing machine in unit',
                    ],
                ],
            ],
        ];

        foreach ($features as $featureData) {
            $translations = $featureData['translations'];
            unset($featureData['translations']);
            
            $feature = Feature::updateOrCreate(
                ['icon' => $featureData['icon']],
                $featureData
            );

            // Create translations
            foreach ($translations as $locale => $translationData) {
                FeatureTranslation::updateOrCreate(
                    [
                        'feature_id' => $feature->id,
                        'locale' => $locale,
                    ],
                    $translationData
                );
            }
        }
    }
}
