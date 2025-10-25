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

        // Define attributes for different category types
        $categoryAttributes = [
            // Residential properties (apartments, villas, etc.)
            'residential' => [
                [
                    'type' => 'number',
                    'required' => true,
                    'Symbol' => 'm²',
                    'name' => 'المساحة',
                    'symbol' => 'م²'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'غرفة',
                    'name' => 'عدد الغرف',
                    'symbol' => 'غ'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'حمام',
                    'name' => 'عدد الحمامات',
                    'symbol' => 'ح'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'طابق',
                    'name' => 'رقم الطابق',
                    'symbol' => 'ط'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'مصعد',
                    'symbol' => 'مصعد'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'موقف سيارات',
                    'symbol' => 'موقف'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'مكيف',
                    'symbol' => 'مكيف'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'سنة',
                    'name' => 'سنة البناء',
                    'symbol' => 'سنة'
                ]
            ],
            
            // Commercial properties (offices, shops, etc.)
            'commercial' => [
                [
                    'type' => 'number',
                    'required' => true,
                    'Symbol' => 'm²',
                    'name' => 'المساحة',
                    'symbol' => 'م²'
                ],
                [
                    'type' => 'text',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'نوع العقار',
                    'symbol' => 'نوع'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'طابق',
                    'name' => 'رقم الطابق',
                    'symbol' => 'ط'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'مصعد',
                    'symbol' => 'مصعد'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'موقف سيارات',
                    'symbol' => 'موقف'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'مكيف',
                    'symbol' => 'مكيف'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'سنة',
                    'name' => 'سنة البناء',
                    'symbol' => 'سنة'
                ]
            ],
            
            // Land properties
            'land' => [
                [
                    'type' => 'number',
                    'required' => true,
                    'Symbol' => 'm²',
                    'name' => 'المساحة',
                    'symbol' => 'م²'
                ],
                [
                    'type' => 'text',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'نوع الأرض',
                    'symbol' => 'نوع'
                ],
                [
                    'type' => 'text',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'الاستخدام',
                    'symbol' => 'استخدام'
                ]
            ],
            
            // Industrial properties
            'industrial' => [
                [
                    'type' => 'number',
                    'required' => true,
                    'Symbol' => 'm²',
                    'name' => 'المساحة',
                    'symbol' => 'م²'
                ],
                [
                    'type' => 'text',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'نوع المصنع',
                    'symbol' => 'نوع'
                ],
                [
                    'type' => 'number',
                    'required' => false,
                    'Symbol' => 'سنة',
                    'name' => 'سنة البناء',
                    'symbol' => 'سنة'
                ],
                [
                    'type' => 'boolean',
                    'required' => false,
                    'Symbol' => '',
                    'name' => 'موقف سيارات',
                    'symbol' => 'موقف'
                ]
            ]
        ];

        // Assign attributes to categories based on their names or IDs
        foreach ($categories as $category) {
            $categoryName = strtolower($category->getTranslatedName('en'));
            $attributesToCreate = [];
            
            // Determine which attributes to assign based on category name
            if (str_contains($categoryName, 'apartment') || str_contains($categoryName, 'villa') || str_contains($categoryName, 'house') || str_contains($categoryName, 'residential')) {
                $attributesToCreate = $categoryAttributes['residential'];
            } elseif (str_contains($categoryName, 'office') || str_contains($categoryName, 'shop') || str_contains($categoryName, 'commercial')) {
                $attributesToCreate = $categoryAttributes['commercial'];
            } elseif (str_contains($categoryName, 'land') || str_contains($categoryName, 'plot')) {
                $attributesToCreate = $categoryAttributes['land'];
            } elseif (str_contains($categoryName, 'factory') || str_contains($categoryName, 'industrial')) {
                $attributesToCreate = $categoryAttributes['industrial'];
            } else {
                // Default to residential attributes for unknown categories
                $attributesToCreate = $categoryAttributes['residential'];
            }

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
        $translations = [
            'المساحة' => 'Area',
            'عدد الغرف' => 'Number of Rooms',
            'عدد الحمامات' => 'Number of Bathrooms',
            'رقم الطابق' => 'Floor Number',
            'مصعد' => 'Elevator',
            'موقف سيارات' => 'Parking',
            'العنوان' => 'Address',
            'نوع العقار' => 'Property Type',
            'سنة البناء' => 'Construction Year',
            'مكيف' => 'Air Conditioning',
            'نوع الأرض' => 'Land Type',
            'الاستخدام' => 'Usage',
            'نوع المصنع' => 'Factory Type',
        ];

        return $translations[$arabicName] ?? $arabicName;
    }

    /**
     * Get English symbol for Arabic symbols
     */
    private function getEnglishSymbol($arabicSymbol)
    {
        $translations = [
            'م²' => 'm²',
            'غ' => 'rooms',
            'ح' => 'bath',
            'ط' => 'floor',
            'مصعد' => 'elevator',
            'موقف' => 'parking',
            'عنوان' => 'address',
            'نوع' => 'type',
            'سنة' => 'year',
            'مكيف' => 'AC',
            'استخدام' => 'usage',
        ];

        return $translations[$arabicSymbol] ?? $arabicSymbol;
    }
}
