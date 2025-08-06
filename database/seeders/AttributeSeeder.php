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

        $attributes = [
            [
                'type' => 'number',
                'required' => true,
                'category_id' => $categories->first()?->id,
                'Symbol' => 'm²',
                'name' => 'المساحة',
                'symbol' => 'م²'
            ],
            [
                'type' => 'number',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => 'غرفة',
                'name' => 'عدد الغرف',
                'symbol' => 'غ'
            ],
            [
                'type' => 'number',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => 'حمام',
                'name' => 'عدد الحمامات',
                'symbol' => 'ح'
            ],
            [
                'type' => 'number',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => 'طابق',
                'name' => 'رقم الطابق',
                'symbol' => 'ط'
            ],
            [
                'type' => 'boolean',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => '',
                'name' => 'مصعد',
                'symbol' => 'مصعد'
            ],
            [
                'type' => 'boolean',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => '',
                'name' => 'موقف سيارات',
                'symbol' => 'موقف'
            ],
            [
                'type' => 'text',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => '',
                'name' => 'العنوان',
                'symbol' => 'عنوان'
            ],
            [
                'type' => 'select',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => '',
                'name' => 'نوع العقار',
                'symbol' => 'نوع'
            ],
            [
                'type' => 'number',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => 'سنة',
                'name' => 'سنة البناء',
                'symbol' => 'سنة'
            ],
            [
                'type' => 'boolean',
                'required' => false,
                'category_id' => $categories->first()?->id,
                'Symbol' => '',
                'name' => 'مكيف',
                'symbol' => 'مكيف'
            ]
        ];

        foreach ($attributes as $attributeData) {
            $name = $attributeData['name'];
            $symbol = $attributeData['symbol'];
            unset($attributeData['name'], $attributeData['symbol']);

            $attribute = Attribute::create($attributeData);

            // Create translation
            $attribute->translations()->create([
                'locale' => app()->getLocale(),
                'name' => $name,
                'symbol' => $symbol,
            ]);
        }
    }
} 