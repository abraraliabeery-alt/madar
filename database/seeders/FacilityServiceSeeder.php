<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\FacilityService;

class FacilityServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();

        if ($facilities->isEmpty()) {
            $this->command?->warn('No facilities found. Please run FacilitySeeder first.');
            return;
        }

        $templates = [
            [
                'title' => 'إدارة المشاريع والإشراف',
                'excerpt' => 'إشراف ميداني وتقارير دورية وإدارة جداول التنفيذ.',
                'content' => 'نقدم خدمة إدارة المشاريع من التخطيط إلى التسليم مع متابعة الجودة والجدول الزمني.',
                'icon' => 'fas fa-diagram-project',
                'order' => 1,
            ],
            [
                'title' => 'تنفيذ الهيكل الإنشائي',
                'excerpt' => 'أعمال خرسانة وحدادة وقوالب وفق المخططات.',
                'content' => 'تنفيذ أعمال الهيكل الإنشائي وفق المخططات المعتمدة ومعايير السلامة.',
                'icon' => 'fas fa-helmet-safety',
                'order' => 2,
            ],
            [
                'title' => 'التشطيبات',
                'excerpt' => 'دهانات، أرضيات، أسقف، أبواب ونوافذ.',
                'content' => 'تشطيبات داخلية وخارجية بجودة عالية وخيارات مواد متعددة.',
                'icon' => 'fas fa-paint-roller',
                'order' => 3,
            ],
        ];

        $created = 0;

        foreach ($facilities as $facility) {
            foreach ($templates as $tpl) {
                FacilityService::updateOrCreate(
                    ['facility_id' => $facility->id, 'title' => $tpl['title']],
                    [
                        'facility_id' => $facility->id,
                        'title' => $tpl['title'],
                        'excerpt' => $tpl['excerpt'],
                        'content' => $tpl['content'],
                        'icon' => $tpl['icon'],
                        'image_path' => null,
                        'order' => $tpl['order'],
                        'is_active' => true,
                    ]
                );
                $created++;
            }
        }

        $this->command?->info("Seeded {$created} facility services.");
    }
}
