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
        $names = [
            'مقاول تنفيذ',
            'مكتب هندسي',
            'استشاري هندسي',
            'مطور عقاري',
            'مورد',
            'تشغيل وصيانة',
            'تصميم داخلي',
            'إدارة مشاريع',
            'إشراف هندسي',
            'مقاولات عامة',
        ];

        FacilityCategory::query()->whereNotIn('name', $names)->delete();

        $order = 1;
        foreach ($names as $name) {
            FacilityCategory::updateOrCreate(
                ['name' => $name],
                [
                    'display_name' => $name,
                    'description' => null,
                    'icon' => null,
                    'is_active' => true,
                    'is_featured' => $order <= 6,
                    'order' => $order,
                    'sort_order' => $order,
                ]
            );
            $order++;
        }
    }
}
