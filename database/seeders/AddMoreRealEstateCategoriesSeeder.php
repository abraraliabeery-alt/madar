<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;

class AddMoreRealEstateCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $realEstate = Category::whereNull('parent_id')
            ->whereHas('translations', function ($query) {
                $query->where('locale', 'ar')->where('name', 'عقارات');
            })
            ->first();

        if (! $realEstate) {
            return;
        }

        $newSubCategories = [
            ['icon' => 'fas fa-umbrella-beach', 'order' => 7, 'ar' => 'استراحة', 'en' => 'Rest House'],
            ['icon' => 'fas fa-tractor', 'order' => 8, 'ar' => 'مزرعة', 'en' => 'Farm'],
            ['icon' => 'fas fa-briefcase', 'order' => 9, 'ar' => 'مكتب', 'en' => 'Office'],
            ['icon' => 'fas fa-water', 'order' => 10, 'ar' => 'شاليه', 'en' => 'Chalet'],
            ['icon' => 'fas fa-layer-group', 'order' => 11, 'ar' => 'دوبلكس', 'en' => 'Duplex'],
            ['icon' => 'fas fa-compress', 'order' => 12, 'ar' => 'ستوديو', 'en' => 'Studio'],
            ['icon' => 'fas fa-gem', 'order' => 13, 'ar' => 'قصر', 'en' => 'Palace'],
        ];

        foreach ($newSubCategories as $data) {
            $exists = CategoryTranslation::where('locale', 'ar')
                ->where('name', $data['ar'])
                ->whereHas('category', function ($q) use ($realEstate) {
                    $q->where('parent_id', $realEstate->id);
                })
                ->exists();

            if ($exists) {
                continue;
            }

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
    }
}
