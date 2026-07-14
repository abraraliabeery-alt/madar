<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Facility;
use App\Models\Neighborhood;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Models\Street;
use App\Models\User;
use App\Models\ExecutionRequest;
use App\Models\ExecutionRequestTranslation;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RealisticProjectSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = Facility::query()->get();
        if ($facilities->isEmpty()) {
            $this->command?->warn('No facilities found. Please run FacilitySeeder first.');
            return;
        }

        $users = User::query()->get();
        if ($users->isEmpty()) {
            $this->command?->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $cities = City::query()->get();
        $neighborhoods = Neighborhood::query()->get();
        $streets = Street::query()->get();

        $locales = array_keys((array) config('locales.available'));
        if (empty($locales)) {
            $locales = ['ar', 'en'];
        }

        $fakerAr = FakerFactory::create('ar_SA');
        $fakerEn = FakerFactory::create('en_US');

        $projectTypes = ['residential', 'commercial', 'industrial', 'government', 'other'];
        $statuses = ['draft', 'open_for_bids', 'awarded', 'closed', 'cancelled'];

        $namesAr = [
            'فيلا سكنية حديثة',
            'مجمع سكني',
            'عمارة شقق',
            'مبنى إداري',
            'مركز تجاري',
            'مستودعات ولوجستيات',
            'مبنى حكومي',
            'تطوير أرض واستثمار',
        ];

        $scopeAr = [
            'تصميم معماري وإنشائي',
            'إشراف وتنفيذ كامل',
            'تشطيب داخلي وخارجي',
            'ترميم وتجديد',
            'أعمال كهرباء وميكانيكا',
            'أعمال تكييف وتهوية',
        ];

        $finishingAr = ['اقتصادي', 'متوسط', 'فاخر'];

        $created = 0;
        $perFacility = (int) (config('seed.realistic_projects.per_facility') ?? 10);

        foreach ($facilities as $facility) {
            for ($i = 0; $i < $perFacility; $i++) {
                $seller = $users->firstWhere('facility_id', $facility->id) ?? $users->random();
                $client = $users->where('id', '!=', optional($seller)->id)->random();

                $city = $cities->isNotEmpty() ? $cities->random() : null;
                $neighborhood = $neighborhoods->isNotEmpty() ? $neighborhoods->random() : null;
                $street = $streets->isNotEmpty() ? $streets->random() : null;

                $projectType = Arr::random($projectTypes);
                $status = Arr::random($statuses);

                $landArea = $fakerEn->numberBetween(250, 1200);
                $builtArea = (float) max(120, (int) round($landArea * $fakerEn->randomFloat(2, 0.45, 0.85)));
                $floors = $fakerEn->numberBetween(1, 5);
                $rooms = $fakerEn->numberBetween(3, 12);
                $bathrooms = $fakerEn->numberBetween(2, 8);

                $budgetMin = $fakerEn->numberBetween(250000, 2500000);
                $budgetMax = $budgetMin + $fakerEn->numberBetween(100000, 1500000);

                $startDate = now()->addDays($fakerEn->numberBetween(7, 90))->toDateString();
                $durationDays = $fakerEn->numberBetween(30, 360);

                $bidDeadline = $status === 'open_for_bids'
                    ? now()->addDays($fakerEn->numberBetween(7, 21))->toDateString()
                    : null;

                $qaDeadline = $status === 'open_for_bids'
                    ? now()->addDays($fakerEn->numberBetween(3, 10))->toDateString()
                    : null;

                $siteVisitDate = $status === 'open_for_bids'
                    ? now()->addDays($fakerEn->numberBetween(5, 14))->toDateString()
                    : null;

                $lat = $fakerEn->randomFloat(7, 16.0, 31.0);
                $lng = $fakerEn->randomFloat(7, 34.0, 50.0);

                $mapsUrl = 'https://www.google.com/maps?q=' . $lat . ',' . $lng;

                $attachments = array_values(array_filter([
                    'scope.pdf',
                    'plan.png',
                    'requirements.docx',
                    $fakerEn->boolean(40) ? 'boq.xlsx' : null,
                    $fakerEn->boolean(30) ? 'site-photos.zip' : null,
                ]));

                $project = Project::create([
                    'facility_id' => $facility->id,
                    'seller_user_id' => optional($seller)->id,
                    'client_user_id' => optional($client)->id,
                    'project_type' => $projectType,
                    'request_type' => $fakerEn->randomElement(['new_build', 'renovation', 'fit_out', 'maintenance', 'design_only']),
                    'scope_of_work' => Arr::random($scopeAr),
                    'finishing_level' => Arr::random($finishingAr),
                    'land_area' => $landArea,
                    'built_area' => $builtArea,
                    'floors_count' => $floors,
                    'rooms_count' => $rooms,
                    'bathrooms_count' => $bathrooms,
                    'budget_min' => $budgetMin,
                    'budget_max' => $budgetMax,
                    'start_date' => $startDate,
                    'duration_days' => $durationDays,
                    'requirements' => $fakerAr->realText(240),
                    'attachments' => $attachments,
                    'status' => $status,
                    'bid_deadline' => $bidDeadline,
                    'qa_deadline' => $qaDeadline,
                    'site_visit_date' => $siteVisitDate,
                    'city_id' => optional($city)->id,
                    'neighborhood_id' => optional($neighborhood)->id,
                    'street_id' => optional($street)->id,
                    'address' => $fakerAr->streetAddress,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'google_maps_url' => $mapsUrl,
                    'image' => null,
                ]);

                $nameAr = Arr::random($namesAr) . ' — ' . $fakerAr->city;
                $descAr = $fakerAr->realText(420);

                foreach ($locales as $locale) {
                    $name = $locale === 'en'
                        ? Str::title($fakerEn->words(4, true)) . ' Project'
                        : $nameAr;

                    $description = $locale === 'en'
                        ? $fakerEn->paragraphs(3, true)
                        : $descAr;

                    ProjectTranslation::updateOrCreate(
                        ['project_id' => $project->id, 'locale' => $locale],
                        ['name' => $name, 'description' => $description]
                    );
                }

                if (($project->status ?? 'draft') === 'open_for_bids') {
                    $exists = ExecutionRequest::query()->where('project_id', $project->id)->exists();
                    if (!$exists) {
                        $executionRequest = ExecutionRequest::create([
                            'facility_id' => null,
                            'project_id' => $project->id,
                            'product_id' => null,
                            'type' => $project->request_type ?? $project->project_type,
                            'status' => 'open',
                            'priority' => 'normal',
                            'budget_min' => $project->budget_min,
                            'budget_max' => $project->budget_max,
                            'due_date' => $project->bid_deadline,
                            'data' => [
                                'source' => 'realistic-project-seeder',
                                'client_user_id' => $project->client_user_id,
                                'scope_of_work' => $project->scope_of_work,
                                'finishing_level' => $project->finishing_level,
                                'start_date' => optional($project->start_date)->toDateString(),
                                'duration_days' => $project->duration_days,
                                'city_id' => $project->city_id,
                                'neighborhood_id' => $project->neighborhood_id,
                                'street_id' => $project->street_id,
                                'address' => $project->address,
                                'latitude' => $project->latitude,
                                'longitude' => $project->longitude,
                                'google_maps_url' => $project->google_maps_url,
                                'qa_deadline' => $project->qa_deadline,
                                'site_visit_date' => $project->site_visit_date,
                            ],
                        ]);

                        foreach ($locales as $locale) {
                            $translation = ProjectTranslation::query()
                                ->where('project_id', $project->id)
                                ->where('locale', $locale)
                                ->first();

                            ExecutionRequestTranslation::create([
                                'execution_request_id' => $executionRequest->id,
                                'locale' => $locale,
                                'title' => $translation?->name ?? ('طلب تنفيذ #' . $project->id),
                                'description' => $translation?->description,
                            ]);
                        }
                    }
                }

                $created++;
            }
        }

        $this->command?->info("Seeded {$created} realistic projects.");
    }
}
