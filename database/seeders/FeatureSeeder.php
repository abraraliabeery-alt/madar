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
                'icon' => 'fas fa-user-check',
                'description' => 'تأهيل فني موثق للمقاول',
                'is_active' => true,
                'order' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'تأهيل فني',
                        'description' => 'تأهيل فني موثق للمقاول',
                    ],
                    'en' => [
                        'name' => 'Technical Qualification',
                        'description' => 'Verified technical qualification',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'description' => 'ضمان على الأعمال حسب العقد',
                'is_active' => true,
                'order' => 2,
                'translations' => [
                    'ar' => [
                        'name' => 'ضمان',
                        'description' => 'ضمان على الأعمال حسب العقد',
                    ],
                    'en' => [
                        'name' => 'Warranty',
                        'description' => 'Work warranty per contract',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-file-contract',
                'description' => 'عقد واضح وبنود قابلة للتتبع',
                'is_active' => true,
                'order' => 3,
                'translations' => [
                    'ar' => [
                        'name' => 'عقد وتوثيق',
                        'description' => 'عقد واضح وبنود قابلة للتتبع',
                    ],
                    'en' => [
                        'name' => 'Contract & Documentation',
                        'description' => 'Clear contract with traceable terms',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-hard-hat',
                'description' => 'التزام بإجراءات السلامة بالموقع',
                'is_active' => true,
                'order' => 4,
                'translations' => [
                    'ar' => [
                        'name' => 'سلامة الموقع',
                        'description' => 'التزام بإجراءات السلامة بالموقع',
                    ],
                    'en' => [
                        'name' => 'Site Safety',
                        'description' => 'Compliance with site safety procedures',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-id-card',
                'description' => 'تصنيف/اعتماد مقاول (إن وجد)',
                'is_active' => true,
                'order' => 5,
                'translations' => [
                    'ar' => [
                        'name' => 'اعتماد وتصنيف',
                        'description' => 'تصنيف/اعتماد مقاول (إن وجد)',
                    ],
                    'en' => [
                        'name' => 'Accreditation',
                        'description' => 'Contractor classification/accreditation (if available)',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-clipboard-list',
                'description' => 'تقديم عرض فني ومالي منظم',
                'is_active' => true,
                'order' => 6,
                'translations' => [
                    'ar' => [
                        'name' => 'عرض منظم',
                        'description' => 'تقديم عرض فني ومالي منظم',
                    ],
                    'en' => [
                        'name' => 'Structured Bid',
                        'description' => 'Structured technical & financial bid',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-truck',
                'description' => 'جاهزية توريد المواد والمعدات',
                'is_active' => true,
                'order' => 7,
                'translations' => [
                    'ar' => [
                        'name' => 'جاهزية التوريد',
                        'description' => 'جاهزية توريد المواد والمعدات',
                    ],
                    'en' => [
                        'name' => 'Supply Readiness',
                        'description' => 'Materials & equipment supply readiness',
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
