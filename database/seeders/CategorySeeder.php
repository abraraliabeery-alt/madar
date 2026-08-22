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
        // Delete existing categories (children first to avoid self-referencing issues)
        Category::whereNotNull('parent_id')->delete();
        Category::whereNull('parent_id')->delete();

        // Clear category references where no foreign key exists
        \App\Models\Attribute::whereNotNull('category_id')->update(['category_id' => null]);

        // Main categories
        $realEstate = Category::create([
            'icon' => 'fas fa-building',
            'is_active' => true,
            'is_featured' => true,
            'order' => 1,
            'sort_order' => 1,
        ]);

        $contracting = Category::create([
            'icon' => 'fas fa-helmet-safety',
            'is_active' => true,
            'is_featured' => true,
            'order' => 2,
            'sort_order' => 2,
        ]);

        // Main category translations
        CategoryTranslation::create(['category_id' => $realEstate->id, 'locale' => 'ar', 'name' => 'عقارات', 'description' => 'فئة العقارات والأملاك']);
        CategoryTranslation::create(['category_id' => $realEstate->id, 'locale' => 'en', 'name' => 'Real Estate', 'description' => 'Real estate and properties']);
        CategoryTranslation::create(['category_id' => $contracting->id, 'locale' => 'ar', 'name' => 'مقاولات', 'description' => 'فئة أعمال المقاولات والتنفيذ']);
        CategoryTranslation::create(['category_id' => $contracting->id, 'locale' => 'en', 'name' => 'Contracting', 'description' => 'Contracting and execution works']);

        // Real estate subcategories
        $realEstateSubs = [
            ['icon' => 'fas fa-map', 'order' => 1, 'ar' => 'أرض', 'en' => 'Land'],
            ['icon' => 'fas fa-house-chimney', 'order' => 2, 'ar' => 'فيلا', 'en' => 'Villa'],
            ['icon' => 'fas fa-building', 'order' => 3, 'ar' => 'شقة', 'en' => 'Apartment'],
            ['icon' => 'fas fa-city', 'order' => 4, 'ar' => 'عمارة', 'en' => 'Building'],
            ['icon' => 'fas fa-store', 'order' => 5, 'ar' => 'محل تجاري', 'en' => 'Shop'],
            ['icon' => 'fas fa-warehouse', 'order' => 6, 'ar' => 'مستودع', 'en' => 'Warehouse'],
            ['icon' => 'fas fa-umbrella-beach', 'order' => 7, 'ar' => 'استراحة', 'en' => 'Rest House'],
            ['icon' => 'fas fa-tractor', 'order' => 8, 'ar' => 'مزرعة', 'en' => 'Farm'],
            ['icon' => 'fas fa-briefcase', 'order' => 9, 'ar' => 'مكتب', 'en' => 'Office'],
            ['icon' => 'fas fa-water', 'order' => 10, 'ar' => 'شاليه', 'en' => 'Chalet'],
            ['icon' => 'fas fa-layer-group', 'order' => 11, 'ar' => 'دوبلكس', 'en' => 'Duplex'],
            ['icon' => 'fas fa-compress', 'order' => 12, 'ar' => 'ستوديو', 'en' => 'Studio'],
            ['icon' => 'fas fa-gem', 'order' => 13, 'ar' => 'قصر', 'en' => 'Palace'],
        ];

        foreach ($realEstateSubs as $data) {
            $sub = Category::create([
                'parent_id' => $realEstate->id,
                'icon' => $data['icon'],
                'is_active' => true,
                'is_featured' => false,
                'order' => $data['order'],
                'sort_order' => $data['order'],
            ]);
            CategoryTranslation::create(['category_id' => $sub->id, 'locale' => 'ar', 'name' => $data['ar'], 'description' => null]);
            CategoryTranslation::create(['category_id' => $sub->id, 'locale' => 'en', 'name' => $data['en'], 'description' => null]);
        }

        // Contracting subcategories
        $contractingSubs = [
            ['icon' => 'fas fa-helmet-safety', 'order' => 1, 'ar' => 'تشييد وبناء', 'en' => 'Construction'],
            ['icon' => 'fas fa-paint-roller', 'order' => 2, 'ar' => 'تشطيبات', 'en' => 'Finishing'],
            ['icon' => 'fas fa-bolt', 'order' => 3, 'ar' => 'أعمال كهرباء', 'en' => 'Electrical'],
            ['icon' => 'fas fa-fan', 'order' => 4, 'ar' => 'ميكانيكا (HVAC)', 'en' => 'Mechanical (HVAC)'],
            ['icon' => 'fas fa-faucet', 'order' => 5, 'ar' => 'سباكة', 'en' => 'Plumbing'],
            ['icon' => 'fas fa-road', 'order' => 6, 'ar' => 'بنية تحتية', 'en' => 'Infrastructure'],
        ];

        foreach ($contractingSubs as $data) {
            $sub = Category::create([
                'parent_id' => $contracting->id,
                'icon' => $data['icon'],
                'is_active' => true,
                'is_featured' => false,
                'order' => $data['order'],
                'sort_order' => $data['order'],
            ]);
            CategoryTranslation::create(['category_id' => $sub->id, 'locale' => 'ar', 'name' => $data['ar'], 'description' => null]);
            CategoryTranslation::create(['category_id' => $sub->id, 'locale' => 'en', 'name' => $data['en'], 'description' => null]);
        }
    }
}
