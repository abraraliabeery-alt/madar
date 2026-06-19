<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();
        $sellerUsers = User::all();

        if ($facilities->isEmpty()) {
            $this->command?->warn('No facilities found. Please run FacilitySeeder first.');
            return;
        }

        $locales = array_keys((array) config('locales.available'));

        $templates = [
            [
                'type' => 'residential',
                'name' => 'مشروع سكني — الرياض',
                'description' => 'مشروع سكني نموذجي لعرض دورة حياة المشروع وربط المنتجات وطلبات التنفيذ.',
            ],
            [
                'type' => 'commercial',
                'name' => 'مشروع تجاري — جدة',
                'description' => 'مشروع تجاري نموذجي يساعد على تجربة ربط المنافسات والعقود والدفعات.',
            ],
        ];

        $created = 0;

        foreach ($facilities as $facility) {
            foreach ($templates as $tpl) {
                $seller = $sellerUsers->firstWhere('facility_id', $facility->id) ?? $sellerUsers->random();

                $project = Project::create([
                    'facility_id' => $facility->id,
                    'seller_user_id' => $seller?->id,
                    'project_type' => $tpl['type'],
                    'latitude' => 24.7136,
                    'longitude' => 46.6753,
                    'google_maps_url' => null,
                    'image' => null,
                ]);

                foreach ($locales as $locale) {
                    ProjectTranslation::create([
                        'project_id' => $project->id,
                        'locale' => $locale,
                        'name' => $tpl['name'],
                        'description' => $tpl['description'],
                    ]);
                }

                $created++;
            }
        }

        $this->command?->info("Seeded {$created} projects.");
    }
}
