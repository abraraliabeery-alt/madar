<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'name' => 'مكيف',
                'display_name' => 'مكيف هواء',
                'description' => 'مكيف هواء مركزي أو منفصل',
                'icon' => 'fas fa-snowflake',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'مطبخ',
                'display_name' => 'مطبخ مجهز',
                'description' => 'مطبخ كامل التجهيز',
                'icon' => 'fas fa-utensils',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'مصعد',
                'display_name' => 'مصعد',
                'description' => 'مصعد للعمارة',
                'icon' => 'fas fa-elevator',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'موقف سيارات',
                'display_name' => 'موقف سيارات',
                'description' => 'موقف سيارات خاص',
                'icon' => 'fas fa-car',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'حديقة',
                'display_name' => 'حديقة',
                'description' => 'حديقة خاصة',
                'icon' => 'fas fa-tree',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'مسبح',
                'display_name' => 'مسبح',
                'description' => 'مسبح خاص',
                'icon' => 'fas fa-swimming-pool',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'صالة رياضية',
                'display_name' => 'صالة رياضية',
                'description' => 'صالة رياضية مجهزة',
                'icon' => 'fas fa-dumbbell',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => 'أمن',
                'display_name' => 'خدمات أمنية',
                'description' => 'حراسة أمنية 24/7',
                'icon' => 'fas fa-shield-alt',
                'is_active' => true,
                'order' => 8,
            ],
            [
                'name' => 'إنترنت',
                'display_name' => 'إنترنت مجاني',
                'description' => 'اتصال إنترنت مجاني',
                'icon' => 'fas fa-wifi',
                'is_active' => true,
                'order' => 9,
            ],
            [
                'name' => 'غسالة',
                'display_name' => 'غسالة ملابس',
                'description' => 'غسالة ملابس في الوحدة',
                'icon' => 'fas fa-tshirt',
                'is_active' => true,
                'order' => 10,
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['name' => $feature['name']],
                $feature
            );
        }
    }
}
