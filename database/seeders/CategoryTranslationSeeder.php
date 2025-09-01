<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;

class CategoryTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            'شقق' => [
                'name' => 'Apartments',
                'description' => 'Residential apartments for rent or sale',
            ],
            'فيلات' => [
                'name' => 'Villas',
                'description' => 'Luxury villas for rent or sale',
            ],
            'مكاتب' => [
                'name' => 'Offices',
                'description' => 'Commercial offices for business use',
            ],
            'محلات' => [
                'name' => 'Shops',
                'description' => 'Retail shops for sale',
            ],
            'مستودعات' => [
                'name' => 'Warehouses',
                'description' => 'Storage warehouses',
            ],
            'أراضي' => [
                'name' => 'Land',
                'description' => 'Residential land for construction',
            ],
        ];

        foreach ($translations as $arabicName => $translation) {
            $category = Category::where('name', $arabicName)->first();

            if ($category) {
                CategoryTranslation::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'locale' => 'en',
                    ],
                    [
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                    ]
                );
            }
        }
    }
}
