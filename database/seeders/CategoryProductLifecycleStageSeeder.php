<?php

namespace Database\Seeders;

use App\Models\CategoryProductLifecycleStage;
use Illuminate\Database\Seeder;

class CategoryProductLifecycleStageSeeder extends Seeder
{
    public function run(): void
    {
        $defaultStages = [
            ['key' => 'view', 'order' => 1],
            ['key' => 'favorite', 'order' => 2],
            ['key' => 'offer', 'order' => 3],
            ['key' => 'booking', 'order' => 4],
            ['key' => 'contract', 'order' => 5],
        ];

        foreach ($defaultStages as $stage) {
            CategoryProductLifecycleStage::firstOrCreate(
                [
                    'category_id' => null,
                    'key' => $stage['key'],
                ],
                [
                    'order' => $stage['order'],
                    'is_default' => true,
                    'is_terminal' => $stage['key'] === 'contract',
                ]
            );
        }
    }
}
