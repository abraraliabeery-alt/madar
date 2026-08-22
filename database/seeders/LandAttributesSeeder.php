<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeTranslation;
use App\Models\Category;
use App\Models\ProductAttributeValue;

class LandAttributesSeeder extends Seeder
{
    public function run(): void
    {
        $landCategory = Category::whereHas('translations', fn ($q) => $q->where('locale', 'ar')->where('name', 'أرض'))
            ->first();

        if (!$landCategory) {
            $this->command?->error('فئة الأرض غير موجودة.');
            return;
        }

        $attributes = [
            [
                'key'           => 'land_classification',
                'type'          => 'select',
                'required'      => true,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-tags',
                'Symbol'        => null,
                'show_in_card'  => true,
                'for_projects'  => false,
                'ar'            => ['name' => 'تصنيف الأرض', 'symbol' => null],
                'en'            => ['name' => 'Land Classification', 'symbol' => null],
            ],
            [
                'key'           => 'land_area',
                'type'          => 'number',
                'required'      => true,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-ruler-combined',
                'Symbol'        => 'م²',
                'show_in_card'  => true,
                'for_projects'  => false,
                'ar'            => ['name' => 'المساحة', 'symbol' => 'م²'],
                'en'            => ['name' => 'Land Area', 'symbol' => 'm²'],
            ],
            [
                'key'           => 'plot_count',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-layer-group',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'عدد القطع', 'symbol' => null],
                'en'            => ['name' => 'Plot Count', 'symbol' => null],
            ],
            [
                'key'           => 'plot_numbers',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-hashtag',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'أرقام القطع', 'symbol' => null],
                'en'            => ['name' => 'Plot Numbers', 'symbol' => null],
            ],
            [
                'key'           => 'plan_number',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-map',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'رقم المخطط', 'symbol' => null],
                'en'            => ['name' => 'Plan Number', 'symbol' => null],
            ],
            [
                'key'           => 'block_number',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-cubes',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'رقم البلوك', 'symbol' => null],
                'en'            => ['name' => 'Block Number', 'symbol' => null],
            ],
            [
                'key'           => 'street_width',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-road',
                'Symbol'        => 'م',
                'show_in_card'  => true,
                'for_projects'  => false,
                'ar'            => ['name' => 'عرض الشارع الأول', 'symbol' => 'م'],
                'en'            => ['name' => 'First Street Width', 'symbol' => 'm'],
            ],
            [
                'key'           => 'facade',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-building',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'اتجاه الواجهة', 'symbol' => null],
                'en'            => ['name' => 'Facade Direction', 'symbol' => null],
            ],
            [
                'key'           => 'land_use',
                'type'          => 'select',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-briefcase',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'نوع الاستخدام', 'symbol' => null],
                'en'            => ['name' => 'Land Use', 'symbol' => null],
            ],
            [
                'key'           => 'price_per_meter',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-money-bill',
                'Symbol'        => 'ر.س',
                'show_in_card'  => true,
                'for_projects'  => false,
                'ar'            => ['name' => 'سعر المتر', 'symbol' => 'ر.س'],
                'en'            => ['name' => 'Price per Meter', 'symbol' => 'SAR'],
            ],
            [
                'key'           => 'street_count',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-road',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'عدد الشوارع', 'symbol' => null],
                'en'            => ['name' => 'Street Count', 'symbol' => null],
            ],
            [
                'key'           => 'facade_length',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-ruler-horizontal',
                'Symbol'        => 'م',
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'طول الواجهة', 'symbol' => 'م'],
                'en'            => ['name' => 'Facade Length', 'symbol' => 'm'],
            ],
            [
                'key'           => 'street_width_2',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-road',
                'Symbol'        => 'م',
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'عرض الشارع الثاني', 'symbol' => 'م'],
                'en'            => ['name' => 'Second Street Width', 'symbol' => 'm'],
            ],
            [
                'key'           => 'street_width_3',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-road',
                'Symbol'        => 'م',
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'عرض الشارع الثالث', 'symbol' => 'م'],
                'en'            => ['name' => 'Third Street Width', 'symbol' => 'm'],
            ],
            [
                'key'           => 'land_dimensions',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-expand',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'أبعاد الأرض', 'symbol' => null],
                'en'            => ['name' => 'Land Dimensions', 'symbol' => null],
            ],
            [
                'key'           => 'land_nature',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-mountain',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'طبيعة الأرض', 'symbol' => null],
                'en'            => ['name' => 'Land Nature', 'symbol' => null],
            ],
            [
                'key'           => 'build_ratio',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-percent',
                'Symbol'        => '%',
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'نسبة البناء', 'symbol' => '%'],
                'en'            => ['name' => 'Build Ratio', 'symbol' => '%'],
            ],
            [
                'key'           => 'allowed_floors',
                'type'          => 'number',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-building',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'عدد الأدوار المسموح بها', 'symbol' => null],
                'en'            => ['name' => 'Allowed Floors', 'symbol' => null],
            ],
            [
                'key'           => 'setbacks',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-arrows-left-right',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'الارتدادات', 'symbol' => null],
                'en'            => ['name' => 'Setbacks', 'symbol' => null],
            ],
            [
                'key'           => 'building_code',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-barcode',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'كود/رمز البناء', 'symbol' => null],
                'en'            => ['name' => 'Building Code', 'symbol' => null],
            ],
            [
                'key'           => 'deed_number',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-file-contract',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'رقم الصك', 'symbol' => null],
                'en'            => ['name' => 'Deed Number', 'symbol' => null],
            ],
            [
                'key'           => 'site_type',
                'type'          => 'text',
                'required'      => false,
                'is_active'     => true,
                'icon'          => 'fa-solid fa-map-pin',
                'Symbol'        => null,
                'show_in_card'  => false,
                'for_projects'  => false,
                'ar'            => ['name' => 'نوع الموقع', 'symbol' => null],
                'en'            => ['name' => 'Site Type', 'symbol' => null],
            ],
        ];

        foreach ($attributes as $data) {
            $translations = [
                'ar' => $data['ar'] ?? null,
                'en' => $data['en'] ?? null,
            ];

            unset($data['ar'], $data['en']);

            $attribute = Attribute::updateOrCreate(
                ['key' => $data['key'], 'category_id' => $landCategory->id],
                $data
            );

            foreach ($translations as $locale => $translationData) {
                if (!$translationData) {
                    continue;
                }

                AttributeTranslation::updateOrCreate(
                    ['attribute_id' => $attribute->id, 'locale' => $locale],
                    $translationData
                );
            }
        }

        // حذف الخصائص القديمة غير المطلوبة للأرض نهائيًا
        $oldAttributeIds = $landCategory->attributes()
            ->whereNotIn('key', array_column($attributes, 'key'))
            ->pluck('id');

        if ($oldAttributeIds->isNotEmpty()) {
            ProductAttributeValue::whereIn('attribute_id', $oldAttributeIds)->delete();
            AttributeTranslation::whereIn('attribute_id', $oldAttributeIds)->delete();
            Attribute::whereIn('id', $oldAttributeIds)->delete();
        }

        $this->command?->info('تم تنظيف خصائص فئة الأرض.');
    }
}
