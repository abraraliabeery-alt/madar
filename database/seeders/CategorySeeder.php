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
                'icon' => 'fas fa-building',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'شقق',
                        'description' => 'شقق سكنية للإيجار أو البيع',
                    ],
                    'en' => [
                        'name' => 'Apartments',
                        'description' => 'Residential apartments for rent or sale',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-home',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
                'translations' => [
                    'ar' => [
                        'name' => 'فيلات',
                        'description' => 'فيلات فاخرة للإيجار أو البيع',
                    ],
                    'en' => [
                        'name' => 'Villas',
                        'description' => 'Luxury villas for rent or sale',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-briefcase',
                'is_active' => true,
                'is_featured' => false,
                'order' => 3,
                'translations' => [
                    'ar' => [
                        'name' => 'مكاتب',
                        'description' => 'مكاتب للاستخدام التجاري',
                    ],
                    'en' => [
                        'name' => 'Offices',
                        'description' => 'Commercial offices for business use',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-store',
                'is_active' => true,
                'is_featured' => false,
                'order' => 4,
                'translations' => [
                    'ar' => [
                        'name' => 'محلات',
                        'description' => 'محلات للبيع بالتجزئة',
                    ],
                    'en' => [
                        'name' => 'Shops',
                        'description' => 'Retail shops for sale',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-warehouse',
                'is_active' => true,
                'is_featured' => false,
                'order' => 5,
                'translations' => [
                    'ar' => [
                        'name' => 'مستودعات',
                        'description' => 'مستودعات للتخزين',
                    ],
                    'en' => [
                        'name' => 'Warehouses',
                        'description' => 'Storage warehouses',
                    ],
                ],
            ],
            [
                'icon' => 'fas fa-map',
                'is_active' => true,
                'is_featured' => false,
                'order' => 6,
                'translations' => [
                    'ar' => [
                        'name' => 'أراضي',
                        'description' => 'أراضي للبناء',
                    ],
                    'en' => [
                        'name' => 'Land',
                        'description' => 'Residential land for construction',
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
