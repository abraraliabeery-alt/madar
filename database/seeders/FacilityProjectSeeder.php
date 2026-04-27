<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\FacilityProject;

class FacilityProjectSeeder extends Seeder
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
                'title' => 'مشروع مبنى سكني — الرياض',
                'excerpt' => 'تنفيذ الهيكل الإنشائي والتشطيبات وفق جدول زمني.',
                'content' => 'مشروع نموذجي يوضح نطاق العمل ومراحل التنفيذ ومعايير الجودة.',
                'status' => 'published',
                'order' => 1,
            ],
            [
                'title' => 'مشروع مبنى تجاري/إداري — جدة',
                'excerpt' => 'أعمال تشطيبات وأنظمة كهرباء وتكييف.',
                'content' => 'مشروع إداري مع أنظمة MEP وتشطيبات داخلية وخارجية.',
                'status' => 'published',
                'order' => 2,
            ],
        ];

        $created = 0;

        foreach ($facilities as $facility) {
            foreach ($templates as $tpl) {
                FacilityProject::updateOrCreate(
                    ['facility_id' => $facility->id, 'title' => $tpl['title']],
                    [
                        'facility_id' => $facility->id,
                        'title' => $tpl['title'],
                        'excerpt' => $tpl['excerpt'],
                        'content' => $tpl['content'],
                        'cover_image' => null,
                        'gallery' => [],
                        'status' => $tpl['status'],
                        'order' => $tpl['order'],
                    ]
                );
                $created++;
            }
        }

        $this->command?->info("Seeded {$created} facility projects.");
    }
}
