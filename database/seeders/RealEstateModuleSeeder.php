<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Attribute;
use App\Models\AttributeTranslation;
use App\Models\Feature;
use App\Models\FeatureTranslation;

class RealEstateModuleSeeder extends Seeder
{
    public function run(): void
    {
        $realEstate = $this->ensureCategory(null, 'fas fa-building', 1, [
            'ar' => 'عقارات',
            'en' => 'Real Estate',
        ]);
        $realEstate->update(['requires_building' => true]);

        $subCategoriesData = [
            ['icon' => 'fas fa-map', 'order' => 1, 'names' => ['ar' => 'أرض', 'en' => 'Land']],
            ['icon' => 'fas fa-house-chimney', 'order' => 2, 'names' => ['ar' => 'فيلا', 'en' => 'Villa']],
            ['icon' => 'fas fa-building', 'order' => 3, 'names' => ['ar' => 'شقة', 'en' => 'Apartment']],
            ['icon' => 'fas fa-city', 'order' => 4, 'names' => ['ar' => 'عمارة', 'en' => 'Building']],
            ['icon' => 'fas fa-store', 'order' => 5, 'names' => ['ar' => 'محل تجاري', 'en' => 'Commercial Shop']],
            ['icon' => 'fas fa-warehouse', 'order' => 6, 'names' => ['ar' => 'مستودع', 'en' => 'Warehouse']],
            ['icon' => 'fas fa-briefcase', 'order' => 7, 'names' => ['ar' => 'مكتب', 'en' => 'Office']],
            ['icon' => 'fas fa-water', 'order' => 8, 'names' => ['ar' => 'شاليه', 'en' => 'Chalet']],
            ['icon' => 'fas fa-layer-group', 'order' => 9, 'names' => ['ar' => 'دوبلكس', 'en' => 'Duplex']],
            ['icon' => 'fas fa-compress', 'order' => 10, 'names' => ['ar' => 'استوديو', 'en' => 'Studio']],
            ['icon' => 'fas fa-gem', 'order' => 11, 'names' => ['ar' => 'قصر', 'en' => 'Palace']],
        ];

        $subCategories = [];
        foreach ($subCategoriesData as $sub) {
            $subCategories[$sub['names']['ar']] = $this->ensureCategory($realEstate->id, $sub['icon'], $sub['order'], $sub['names']);
        }

        $realEstateAttributes = [
            ['key' => 'area', 'type' => 'number', 'required' => true, 'icon' => 'fas fa-vector-square', 'Symbol' => 'م²', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'المساحة', 'en' => 'Area', 'symbol' => 'م²'],
            ['key' => 'bedrooms', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-bed', 'Symbol' => '', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'عدد الغرف', 'en' => 'Bedrooms', 'symbol' => ''],
            ['key' => 'bathrooms', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-bath', 'Symbol' => '', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'عدد الحمامات', 'en' => 'Bathrooms', 'symbol' => ''],
            ['key' => 'property_age', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-history', 'Symbol' => 'سنة', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'عمر العقار', 'en' => 'Property Age', 'symbol' => 'سنة'],
            ['key' => 'floor_number', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-building', 'Symbol' => '', 'show_in_card' => false, 'for_projects' => false, 'ar' => 'رقم الطابق', 'en' => 'Floor Number', 'symbol' => ''],
            ['key' => 'total_floors', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-building', 'Symbol' => '', 'show_in_card' => false, 'for_projects' => false, 'ar' => 'إجمالي الطوابق', 'en' => 'Total Floors', 'symbol' => ''],
            ['key' => 'parking_spaces', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-parking', 'Symbol' => '', 'show_in_card' => false, 'for_projects' => false, 'ar' => 'عدد المواقف', 'en' => 'Parking Spaces', 'symbol' => ''],
            ['key' => 'furnished', 'type' => 'boolean', 'required' => false, 'icon' => 'fas fa-couch', 'Symbol' => '', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'مؤثث', 'en' => 'Furnished', 'symbol' => ''],
            ['key' => 'garage', 'type' => 'boolean', 'required' => false, 'icon' => 'fas fa-car', 'Symbol' => '', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'كراج', 'en' => 'Garage', 'symbol' => ''],
            ['key' => 'view', 'type' => 'text', 'required' => false, 'icon' => 'fas fa-eye', 'Symbol' => '', 'show_in_card' => false, 'for_projects' => false, 'ar' => 'إطلالة', 'en' => 'View', 'symbol' => ''],
        ];

        $landAttributes = [
            ['key' => 'area', 'type' => 'number', 'required' => true, 'icon' => 'fas fa-vector-square', 'Symbol' => 'م²', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'المساحة', 'en' => 'Area', 'symbol' => 'م²'],
            ['key' => 'street_width', 'type' => 'number', 'required' => false, 'icon' => 'fas fa-road', 'Symbol' => 'م', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'عرض الشارع', 'en' => 'Street Width', 'symbol' => 'م'],
            ['key' => 'facade', 'type' => 'text', 'required' => false, 'icon' => 'fas fa-compass', 'Symbol' => '', 'show_in_card' => true, 'for_projects' => false, 'ar' => 'الواجهة', 'en' => 'Facade', 'symbol' => ''],
            ['key' => 'land_use', 'type' => 'text', 'required' => false, 'icon' => 'fas fa-city', 'Symbol' => '', 'show_in_card' => false, 'for_projects' => false, 'ar' => 'نوع الاستخدام', 'en' => 'Land Use', 'symbol' => ''],
        ];

        $featureNames = [
            ['icon' => 'fas fa-vector-square', 'ar' => 'بلكونة', 'en' => 'Balcony'],
            ['icon' => 'fas fa-elevator', 'ar' => 'مصعد', 'en' => 'Elevator'],
            ['icon' => 'fas fa-tree', 'ar' => 'حديقة', 'en' => 'Garden'],
            ['icon' => 'fas fa-car', 'ar' => 'موقف سيارة', 'en' => 'Parking'],
            ['icon' => 'fas fa-swimming-pool', 'ar' => 'مسبح', 'en' => 'Pool'],
            ['icon' => 'fas fa-shield-alt', 'ar' => 'أمن وحراسة', 'en' => 'Security'],
            ['icon' => 'fas fa-snowflake', 'ar' => 'مكيف', 'en' => 'AC'],
            ['icon' => 'fas fa-user', 'ar' => 'غرفة خادمة', 'en' => 'Maid Room'],
        ];

        foreach ($subCategories as $name => $category) {
            $attributes = $name === 'أرض' ? $landAttributes : $realEstateAttributes;

            foreach ($attributes as $attr) {
                $this->ensureAttribute($category->id, $attr);
            }

            foreach ($featureNames as $i => $feature) {
                $this->ensureFeature($category->id, $feature['icon'], $i + 1, $feature['ar'], $feature['en']);
            }
        }
    }

    private function ensureCategory(?int $parentId, string $icon, int $order, array $names): Category
    {
        $category = Category::firstOrCreate(
            ['parent_id' => $parentId, 'icon' => $icon],
            [
                'is_active' => true,
                'is_featured' => $parentId === null,
                'order' => $order,
                'sort_order' => $order,
            ]
        );

        foreach (['ar', 'en'] as $locale) {
            CategoryTranslation::updateOrCreate(
                ['category_id' => $category->id, 'locale' => $locale],
                ['name' => $names[$locale], 'description' => $names[$locale] ?? null]
            );
        }

        return $category;
    }

    private function ensureAttribute(int $categoryId, array $data): void
    {
        $attribute = Attribute::firstOrCreate(
            ['key' => $data['key'], 'category_id' => $categoryId],
            [
                'type' => $data['type'],
                'required' => $data['required'],
                'icon' => $data['icon'],
                'Symbol' => $data['Symbol'],
                'show_in_card' => $data['show_in_card'],
                'for_projects' => $data['for_projects'],
                'is_active' => true,
            ]
        );

        foreach (['ar', 'en'] as $locale) {
            AttributeTranslation::updateOrCreate(
                ['attribute_id' => $attribute->id, 'locale' => $locale],
                ['name' => $data[$locale], 'symbol' => $data['symbol']]
            );
        }
    }

    private function ensureFeature(int $categoryId, string $icon, int $order, string $nameAr, string $nameEn): void
    {
        $feature = Feature::firstOrCreate(
            ['category_id' => $categoryId, 'icon' => $icon],
            [
                'is_active' => true,
                'order' => $order,
                'description' => $nameAr,
            ]
        );

        FeatureTranslation::updateOrCreate(
            ['feature_id' => $feature->id, 'locale' => 'ar'],
            ['name' => $nameAr, 'description' => $nameAr]
        );

        FeatureTranslation::updateOrCreate(
            ['feature_id' => $feature->id, 'locale' => 'en'],
            ['name' => $nameEn, 'description' => $nameEn]
        );
    }
}
