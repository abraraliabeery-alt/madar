<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\FacilityTender;

class FacilityTenderSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = Facility::all();

        if ($facilities->isEmpty()) {
            $this->command?->warn('No facilities found. Please run FacilitySeeder first.');
            return;
        }

        $created = 0;

        foreach ($facilities as $facility) {
            for ($i = 1; $i <= 2; $i++) {
                FacilityTender::create([
                    'facility_id' => $facility->id,
                    'title' => "منافسة تجريبية {$i} — {$facility->name}",
                    'reference' => "TN-{$facility->id}-{$i}",
                    'issue_date' => now()->subDays(10)->toDateString(),
                    'cover_style' => 'default',
                    'status' => 'published',
                    'data' => [
                        'closing_at' => now()->addDays(7)->toDateString(),
                        'scope' => 'نطاق تجريبي للمنافسة لعرض شكل منصة اعتماد.',
                    ],
                ]);
                $created++;
            }
        }

        $this->command?->info("Seeded {$created} facility tenders.");
    }
}
