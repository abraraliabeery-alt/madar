<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectStageTranslation;

class ProjectStageSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::with('stages')->get();

        if ($projects->isEmpty()) {
            $this->command?->warn('No projects found. Please run ProjectSeeder first.');
            return;
        }

        $locales = array_keys((array) config('locales.available'));

        $names = [
            'idea' => ['ar' => 'الفكرة', 'en' => 'Idea'],
            'feasibility' => ['ar' => 'الجدوى', 'en' => 'Feasibility'],
            'design' => ['ar' => 'التصميم', 'en' => 'Design'],
            'permits' => ['ar' => 'التصاريح', 'en' => 'Permits'],
            'construction' => ['ar' => 'التنفيذ', 'en' => 'Construction'],
            'sales_marketing' => ['ar' => 'التسويق والبيع', 'en' => 'Sales & Marketing'],
            'post_sale' => ['ar' => 'ما بعد البيع', 'en' => 'Post-sale'],
        ];

        $createdTranslations = 0;
        $updatedStages = 0;

        foreach ($projects as $project) {
            $stages = $project->stages->sortBy('order')->values();

            foreach ($stages as $i => $stage) {
                if ($i <= 1) {
                    $stage->status = 'completed';
                    $stage->started_at = now()->subDays(30 - ($i * 10));
                    $stage->completed_at = now()->subDays(22 - ($i * 8));
                } elseif ($i === 2) {
                    $stage->status = 'in_progress';
                    $stage->started_at = now()->subDays(10);
                    $stage->completed_at = null;
                } else {
                    $stage->status = 'not_started';
                    $stage->started_at = null;
                    $stage->completed_at = null;
                }

                $stage->save();
                $updatedStages++;

                foreach ($locales as $locale) {
                    $fallbackLocale = in_array($locale, ['ar', 'en'], true) ? $locale : 'ar';
                    $key = $stage->key;

                    ProjectStageTranslation::updateOrCreate(
                        ['project_stage_id' => $stage->id, 'locale' => $locale],
                        [
                            'name' => $names[$key][$fallbackLocale] ?? ucfirst(str_replace('_', ' ', $key)),
                            'description' => null,
                        ]
                    );
                    $createdTranslations++;
                }
            }
        }

        $this->command?->info("Updated {$updatedStages} project stages and seeded {$createdTranslations} stage translations.");
    }
}
