<?php

namespace Database\Seeders;

use App\Models\StageAttribute;
use Illuminate\Database\Seeder;

class ProjectLifecycleSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            'idea' => [
                ['type' => 'text', 'required' => false, 'icon' => 'lightbulb', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
            'feasibility' => [
                ['type' => 'text', 'required' => false, 'icon' => 'calculator', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
            'design' => [
                ['type' => 'text', 'required' => false, 'icon' => 'drafting-compass', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
            'permits' => [
                ['type' => 'text', 'required' => false, 'icon' => 'file-check', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
            'construction' => [
                ['type' => 'text', 'required' => false, 'icon' => 'building', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
            'sales_marketing' => [
                ['type' => 'text', 'required' => false, 'icon' => 'chart-line', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
            'post_sale' => [
                ['type' => 'text', 'required' => false, 'icon' => 'handshake', 'symbol' => null, 'show_in_stage_card' => true, 'order' => 1],
            ],
        ];

        foreach ($stages as $stageKey => $attributes) {
            foreach ($attributes as $data) {
                StageAttribute::firstOrCreate(
                    [
                        'stage_key' => $stageKey,
                        'type' => $data['type'],
                        'order' => $data['order'],
                    ],
                    $data + ['stage_key' => $stageKey]
                );
            }
        }
    }
}
