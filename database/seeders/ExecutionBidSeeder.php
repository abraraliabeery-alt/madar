<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExecutionBid;
use App\Models\ExecutionBidTranslation;
use App\Models\ExecutionRequest;
use App\Models\Facility;
use App\Models\User;

class ExecutionBidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requests = ExecutionRequest::all();
        $facilities = Facility::all();
        $facilityUsers = User::where('primary_role', 'facility')->get();

        if ($requests->isEmpty() || $facilities->isEmpty() || $facilityUsers->isEmpty()) {
            $this->command?->warn('Missing execution requests/facilities/users. Please run ExecutionRequestSeeder, FacilitySeeder and UserSeeder first.');
            return;
        }

        $created = 0;

        foreach ($requests as $req) {
            $bidsCount = rand(1, 3);

            $executorFacilities = $facilities
                ->where('id', '!=', $req->facility_id)
                ->values();

            if ($executorFacilities->isEmpty()) {
                continue;
            }

            for ($i = 0; $i < $bidsCount; $i++) {
                $executorFacility = $executorFacilities->get($i % $executorFacilities->count());
                $executorUser = $facilityUsers->get($i % $facilityUsers->count());

                $price = rand(80000, 600000);
                $durationDays = rand(10, 120);
                $warrantyMonths = rand(6, 24);

                $bid = ExecutionBid::create([
                    'execution_request_id' => $req->id,
                    'executor_facility_id' => $executorFacility->id,
                    'executor_user_id' => $executorUser->id,
                    'price_total' => $price,
                    'currency' => 'SAR',
                    'duration_days' => $durationDays,
                    'warranty_months' => $warrantyMonths,
                    'status' => 'submitted',
                    'score' => rand(60, 95),
                    'data' => [
                        'payment_terms' => 'milestones',
                        'notes' => 'Sample seeded bid',
                    ],
                ]);

                ExecutionBidTranslation::create([
                    'execution_bid_id' => $bid->id,
                    'locale' => 'ar',
                    'title' => 'عرض تنفيذ',
                    'notes' => 'عرض تنفيذ بسعر إجمالي ومدة ضمان وتفاصيل تسليم.',
                ]);

                ExecutionBidTranslation::create([
                    'execution_bid_id' => $bid->id,
                    'locale' => 'en',
                    'title' => 'Execution Bid',
                    'notes' => 'Seeded execution bid with total price, warranty and delivery details.',
                ]);

                $created++;
            }
        }

        $this->command?->info("Created {$created} execution bids.");
    }
}
