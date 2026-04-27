<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExecutionRequest;
use App\Models\ExecutionRequestTranslation;
use App\Models\Facility;

class ExecutionRequestSeeder extends Seeder
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
                'type' => 'construction',
                'status' => 'open',
                'priority' => 'high',
                'budget_min' => 250000,
                'budget_max' => 450000,
                'due_days' => 55,
                'translations' => [
                    'ar' => [
                        'title' => 'بناء فلة سكنية عظم — الرياض',
                        'description' => 'طلب تنفيذ أعمال الهيكل الإنشائي مع متطلبات فنية واضحة وتسليم حسب الجدول.',
                    ],
                    'en' => [
                        'title' => 'Villa Structure Works — Riyadh',
                        'description' => 'Execution request for structural works with clear technical requirements and schedule.',
                    ],
                ],
                'data' => [
                    'scope' => ['structural', 'concrete', 'steel'],
                    'location' => 'Riyadh',
                ],
            ],
            [
                'type' => 'finishing',
                'status' => 'open',
                'priority' => 'medium',
                'budget_min' => 90000,
                'budget_max' => 160000,
                'due_days' => 30,
                'translations' => [
                    'ar' => [
                        'title' => 'تشطيبات داخلية — جدة',
                        'description' => 'طلب تنفيذ التشطيبات الداخلية (دهانات، أرضيات، أبواب) وفق المواصفات.',
                    ],
                    'en' => [
                        'title' => 'Interior Finishing — Jeddah',
                        'description' => 'Execution request for interior finishing works per specifications.',
                    ],
                ],
                'data' => [
                    'scope' => ['paint', 'flooring', 'doors'],
                    'location' => 'Jeddah',
                ],
            ],
            [
                'type' => 'mep',
                'status' => 'open',
                'priority' => 'medium',
                'budget_min' => 120000,
                'budget_max' => 220000,
                'due_days' => 21,
                'translations' => [
                    'ar' => [
                        'title' => 'توريد وتركيب HVAC — الرياض',
                        'description' => 'طلب توريد وتركيب التكييف والتهوية مع اختبارات تشغيل وتسليم.',
                    ],
                    'en' => [
                        'title' => 'HVAC Supply & Installation — Riyadh',
                        'description' => 'Execution request for supplying and installing HVAC with commissioning and handover.',
                    ],
                ],
                'data' => [
                    'scope' => ['hvac', 'commissioning'],
                    'location' => 'Riyadh',
                ],
            ],
        ];

        $created = 0;

        foreach ($templates as $i => $tpl) {
            $facility = $facilities->get($i % $facilities->count());

            $req = ExecutionRequest::create([
                'facility_id' => $facility->id,
                'project_id' => null,
                'product_id' => null,
                'type' => $tpl['type'],
                'status' => $tpl['status'],
                'priority' => $tpl['priority'],
                'budget_min' => $tpl['budget_min'],
                'budget_max' => $tpl['budget_max'],
                'due_date' => now()->addDays($tpl['due_days'])->toDateString(),
                'data' => $tpl['data'],
            ]);

            foreach (($tpl['translations'] ?? []) as $locale => $t) {
                ExecutionRequestTranslation::create([
                    'execution_request_id' => $req->id,
                    'locale' => $locale,
                    'title' => $t['title'],
                    'description' => $t['description'],
                ]);
            }

            $created++;
        }

        $this->command?->info("Created {$created} execution requests.");
    }
}
