<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacilityCategory;

class FacilityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'شركات سيارات',
                'display_name' => 'شركات السيارات',
                'description' => 'شركات بيع وشراء وصيانة السيارات',
                'icon' => 'fas fa-car',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'شركات عقارات',
                'display_name' => 'شركات العقارات',
                'description' => 'شركات بيع وشراء وإدارة العقارات',
                'icon' => 'fas fa-building',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'شركات مستشفيات',
                'display_name' => 'شركات المستشفيات',
                'description' => 'شركات الرعاية الصحية والمستشفيات',
                'icon' => 'fas fa-hospital',
                'is_active' => true,
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'شركات تعليمية',
                'display_name' => 'شركات التعليم',
                'description' => 'شركات التعليم والتدريب',
                'icon' => 'fas fa-graduation-cap',
                'is_active' => true,
                'is_featured' => false,
                'order' => 4,
            ],
            [
                'name' => 'شركات سياحية',
                'display_name' => 'شركات السياحة',
                'description' => 'شركات السياحة والسفر',
                'icon' => 'fas fa-plane',
                'is_active' => true,
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'شركات تقنية',
                'display_name' => 'شركات التقنية',
                'description' => 'شركات التكنولوجيا والبرمجيات',
                'icon' => 'fas fa-laptop-code',
                'is_active' => true,
                'is_featured' => false,
                'order' => 6,
            ],
            [
                'name' => 'شركات تجارية',
                'display_name' => 'شركات تجارية',
                'description' => 'شركات تجارية عامة',
                'icon' => 'fas fa-store',
                'is_active' => true,
                'is_featured' => false,
                'order' => 7,
            ],
            [
                'name' => 'شركات خدمات',
                'display_name' => 'شركات الخدمات',
                'description' => 'شركات تقديم الخدمات المختلفة',
                'icon' => 'fas fa-tools',
                'is_active' => true,
                'is_featured' => false,
                'order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            FacilityCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
