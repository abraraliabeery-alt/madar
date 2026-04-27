<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\Category;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        // Contracting / projects attributes
        $attributesToCreate = [
            [
                'type' => 'text',
                'required' => true,
                'Symbol' => '',
                'name' => 'النشاط',
                'symbol' => ''
            ],
            [
                'type' => 'number',
                'required' => false,
                'Symbol' => 'يوم',
                'name' => 'مدة التنفيذ',
                'symbol' => 'يوم'
            ],
            [
                'type' => 'number',
                'required' => false,
                'Symbol' => 'شهر',
                'name' => 'الضمان',
                'symbol' => 'شهر'
            ],
            [
                'type' => 'number',
                'required' => false,
                'Symbol' => 'ر.س',
                'name' => 'الميزانية',
                'symbol' => 'ر.س'
            ],
            [
                'type' => 'text',
                'required' => false,
                'Symbol' => '',
                'name' => 'نطاق العمل',
                'symbol' => ''
            ],
        ];

        // Assign attributes to categories based on their names or IDs
        foreach ($categories as $category) {
            // Create attributes for this category
            foreach ($attributesToCreate as $attributeData) {
                $name = $attributeData['name'];
                $symbol = $attributeData['symbol'];
                unset($attributeData['name'], $attributeData['symbol']);

                // Add category_id to the attribute data
                $attributeData['category_id'] = $category->id;

                $attribute = Attribute::create($attributeData);

                // Create translations for both Arabic and English
                $translations = [
                    'ar' => [
                        'name' => $name,
                        'symbol' => $symbol,
                    ],
                    'en' => [
                        'name' => $this->getEnglishName($name),
                        'symbol' => $this->getEnglishSymbol($symbol),
                    ],
                ];

                foreach ($translations as $locale => $translationData) {
                    $attribute->translations()->create([
                        'locale' => $locale,
                        'name' => $translationData['name'],
                        'symbol' => $translationData['symbol'],
                    ]);
                }
            }
        }
    }

    /**
     * Get English name for Arabic attribute names
     */
    private function getEnglishName($arabicName)
    {
        $translationMap = [
            'النشاط' => 'Activity',
            'مدة التنفيذ' => 'Duration',
            'الضمان' => 'Warranty',
            'الميزانية' => 'Budget',
            'نطاق العمل' => 'Scope of Work',
            'موقع المشروع' => 'Project Location',
            'نوع المشروع' => 'Project Type',
            'مواد التنفيذ' => 'Materials',
        ];

        return $translationMap[$arabicName] ?? $arabicName;
    }

    /**
     * Get English symbol for Arabic symbols
     */
    private function getEnglishSymbol($arabicSymbol)
    {
        $translations = [
            'يوم' => 'days',
            'شهر' => 'months',
            'ر.س' => 'SAR',
        ];

        return $translations[$arabicSymbol] ?? $arabicSymbol;
    }
}
