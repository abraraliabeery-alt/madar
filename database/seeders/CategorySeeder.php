<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'شقق',
                'display_name' => 'شقق سكنية',
                'description' => 'شقق سكنية للإيجار أو البيع',
                'icon' => 'fas fa-building',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'فيلات',
                'display_name' => 'فيلات سكنية',
                'description' => 'فيلات فاخرة للإيجار أو البيع',
                'icon' => 'fas fa-home',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'مكاتب',
                'display_name' => 'مكاتب تجارية',
                'description' => 'مكاتب للاستخدام التجاري',
                'icon' => 'fas fa-briefcase',
                'is_active' => true,
                'is_featured' => false,
                'order' => 3,
            ],
            [
                'name' => 'محلات',
                'display_name' => 'محلات تجارية',
                'description' => 'محلات للبيع بالتجزئة',
                'icon' => 'fas fa-store',
                'is_active' => true,
                'is_featured' => false,
                'order' => 4,
            ],
            [
                'name' => 'مستودعات',
                'display_name' => 'مستودعات',
                'description' => 'مستودعات للتخزين',
                'icon' => 'fas fa-warehouse',
                'is_active' => true,
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'أراضي',
                'display_name' => 'أراضي سكنية',
                'description' => 'أراضي للبناء',
                'icon' => 'fas fa-map',
                'is_active' => true,
                'is_featured' => false,
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
