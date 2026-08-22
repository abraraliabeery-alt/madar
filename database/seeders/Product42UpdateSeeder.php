<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductAttributeValue;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\City;
use App\Models\Neighborhood;
use App\Models\Status;
use App\Models\Offer;
use App\Models\Feature;
use App\Models\FeatureTranslation;
use App\Models\User;

class Product42UpdateSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::find(42);

        if (!$product) {
            $this->command?->error('المنتج 42 غير موجود.');
            return;
        }

        // البيانات الأساسية
        $landCategory = Category::whereHas('translations', fn ($q) => $q->where('locale', 'ar')->where('name', 'أرض'))->first();
        if (!$landCategory) {
            $this->command?->error('فئة الأرض غير موجودة.');
            return;
        }

        $city = City::firstOrCreate(
            ['name' => 'الرياض'],
            ['slug' => 'riyadh', 'is_active' => true]
        );

        $neighborhood = Neighborhood::firstOrCreate(
            ['city_id' => $city->id, 'name' => 'طيبة'],
            ['is_active' => true]
        );

        $saleStatus = Status::firstOrCreate(
            ['name' => 'sale'],
            ['display_name' => 'للبيع', 'is_active' => true, 'color' => 'success']
        );

        $creatorId = User::first()?->id ?? 1;

        // تحديث المنتج
        $product->fill([
            'category_id'    => $landCategory->id,
            'city_id'        => $city->id,
            'neighborhood_id'=> $neighborhood->id,
            'status_id'      => $saleStatus->id,
            'address'        => 'حي طيبة – الرياض',
            'google_maps_url'=> 'https://maps.app.goo.gl/54KaQyNFhk7LKYw49',
            'additional_info'=> "القطعة 369: 2,768.28 م² بقيمة 5,536,560 ر.س\nالقطعة 370: 2,737.92 م² بقيمة 5,475,840 ر.س\nالإجمالي: 5,506.20 م² بقيمة 11,012,400 ر.س",
            'is_featured'    => false,
        ]);
        $product->is_active = true;
        $product->save();

        // الترجمة
        ProductTranslation::updateOrCreate(
            ['product_id' => $product->id, 'locale' => 'ar'],
            [
                'title'       => 'أرض تجارية للبيع في حي طيبة - الرياض',
                'description' => 'أرض تجارية مميزة بحي طيبة بالرياض، على ثلاثة شوارع، مكونة من قطعتين متجاورتين بإجمالي مساحة 5,506.20 م².',
            ]
        );

        // قيم الخصائص
        $attributeValues = [
            'land_classification' => 'تجاري',
            'land_area'           => '5506.20',
            'plot_count'          => '2',
            'plot_numbers'        => '369 و370',
            'plan_number'         => '3533',
            'block_number'        => '49',
            'street_width'        => '60',
            'facade'              => 'غير متوفر',
            'land_use'            => 'تجاري',
            'price_per_meter'     => '2000',
            'street_count'        => '3',
            'facade_length'       => 'غير متوفر',
            'street_width_2'      => '60',
            'street_width_3'      => '15–18',
            'land_dimensions'     => 'غير متوفر',
            'land_nature'         => 'غير متوفر',
            'build_ratio'         => 'غير متوفر',
            'allowed_floors'      => 'غير متوفر',
            'setbacks'            => 'غير متوفر',
            'building_code'       => 'غير متوفر بشكل معتمد',
            'deed_number'         => 'غير متوفر',
            'site_type'           => 'زاوية',
        ];

        $attributes = Attribute::where('category_id', $landCategory->id)
            ->whereIn('key', array_keys($attributeValues))
            ->pluck('id', 'key');

        $syncData = [];
        foreach ($attributeValues as $key => $value) {
            if (!isset($attributes[$key])) {
                continue;
            }
            $syncData[$attributes[$key]] = ['value' => $value];
        }

        ProductAttributeValue::where('product_id', $product->id)
            ->whereIn('attribute_id', array_keys($syncData))
            ->delete();

        $product->attributes()->syncWithoutDetaching($syncData);

        // العرض/السعر
        Offer::updateOrCreate(
            ['product_id' => $product->id, 'offer_type' => 'sale'],
            [
                'price'       => 11012400,
                'is_active'   => true,
                'is_featured' => false,
                'created_by'  => $creatorId,
            ]
        );

        // المميزات
        $featureNames = [
            'قطعتان متجاورتان',
            'موقع زاوية',
            'على ثلاثة شوارع',
            'طريق العزيزية بعرض 60م',
            'شارع رئيسي إضافي بعرض 60م',
            'تصنيف تجاري',
            'تقارير سهيل متوفرة',
            'مخطط الموقع متوفر',
        ];

        $featureIds = [];
        foreach ($featureNames as $name) {
            $existing = FeatureTranslation::where('locale', 'ar')->where('name', $name)->first();

            if ($existing) {
                $featureIds[] = $existing->feature_id;
            } else {
                $feature = Feature::create(['is_active' => true, 'order' => 0]);
                FeatureTranslation::create([
                    'feature_id' => $feature->id,
                    'locale'     => 'ar',
                    'name'       => $name,
                ]);
                $featureIds[] = $feature->id;
            }
        }

        $product->features()->syncWithoutDetaching($featureIds);

        $this->command?->info('تم تحديث الأرض رقم 42.');
    }
}
