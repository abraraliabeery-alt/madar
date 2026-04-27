<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'icon' => 'fas fa-helmet-safety',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'تشييد وبناء',
                        'description' => 'أعمال الهيكل الإنشائي وبناء المباني',
                    ],
                    'en' => [
                        'name' => 'Construction',
                        'description' => 'Structural and building construction works',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-paint-roller',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
                'translations' => [
                    'ar' => [
                        'name' => 'تشطيبات',
                        'description' => 'دهانات، أرضيات، أسقف، أبواب ونوافذ',
                    ],
                    'en' => [
                        'name' => 'Finishing',
                        'description' => 'Paint, flooring, ceilings, doors & windows',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-bolt',
                'is_active' => true,
                'is_featured' => false,
                'order' => 3,
                'translations' => [
                    'ar' => [
                        'name' => 'أعمال كهرباء',
                        'description' => 'تمديدات، لوحات، إنارة، أنظمة ضعيفة',
                    ],
                    'en' => [
                        'name' => 'Electrical',
                        'description' => 'Wiring, panels, lighting, low-current systems',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-fan',
                'is_active' => true,
                'is_featured' => false,
                'order' => 4,
                'translations' => [
                    'ar' => [
                        'name' => 'ميكانيكا (HVAC)',
                        'description' => 'تكييف وتهوية ومجاري هواء وتشغيل',
                    ],
                    'en' => [
                        'name' => 'Mechanical (HVAC)',
                        'description' => 'Air-conditioning, ventilation, ducts & commissioning',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-road',
                'is_active' => true,
                'is_featured' => false,
                'order' => 5,
                'translations' => [
                    'ar' => [
                        'name' => 'بنية تحتية',
                        'description' => 'طرق، أرصفة، إنارة، شبكات',
                    ],
                    'en' => [
                        'name' => 'Infrastructure',
                        'description' => 'Roads, sidewalks, lighting, utilities networks',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-truck-fast',
                'is_active' => true,
                'is_featured' => false,
                'order' => 6,
                'translations' => [
                    'ar' => [
                        'name' => 'توريد وتركيب',
                        'description' => 'توريد مواد/معدات مع التركيب والاختبارات',
                    ],
                    'en' => [
                        'name' => 'Supply & Installation',
                        'description' => 'Supply materials/equipment with installation and testing',
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $translations = $categoryData['translations'];
            unset($categoryData['translations']);
            
            // Create or update the category
            $category = Category::updateOrCreate(
                ['icon' => $categoryData['icon']],
                $categoryData
            );
            
            // Create translations
            foreach ($translations as $locale => $translationData) {
                CategoryTranslation::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'locale' => $locale,
                    ],
                    $translationData
                );
            }
        }
    }
}
