<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanLot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AjlanPlanLotsSeeder extends Seeder
{
    public function run(): void
    {
        $viewPath = resource_path('views/public/plans/plans.blade.php');
        if (!File::exists($viewPath)) {
            $this->command?->error('Extraction view not found: ' . $viewPath);
            return;
        }

        $content = File::get($viewPath);
        $marker = 'const parcelsWGS = ';
        $start = strpos($content, $marker);
        if ($start === false) {
            $this->command?->error('parcelsWGS marker not found in extraction view');
            return;
        }

        $start += strlen($marker);
        $nextMarker = 'const parcelsUTM = ';
        $end = strpos($content, $nextMarker, $start);
        if ($end === false) {
            $end = strpos($content, ';', $start);
        }

        if ($end === false) {
            $this->command?->error('Could not find end of parcelsWGS JSON');
            return;
        }

        $json = trim(substr($content, $start, $end - $start));
        $json = rtrim($json, ";\r\n\t ");
        $payload = json_decode($json, true);

        if (!is_array($payload)) {
            $this->command?->error('Failed to decode parcelsWGS JSON: ' . json_last_error_msg());
            return;
        }

        if (($payload['type'] ?? null) !== 'FeatureCollection' || !is_array($payload['features'] ?? null)) {
            $this->command?->error('Invalid parcelsWGS GeoJSON FeatureCollection');
            return;
        }

        DB::transaction(function () use ($payload) {
            $plan = Plan::query()->firstOrCreate(
                ['slug' => 'ajlan'],
                [
                    'name' => 'عجلان',
                    'plan_number' => '2705/5',
                    'center_lat' => 24.550964276,
                    'center_lng' => 46.824846268,
                    'area_km2' => 3.88,
                ]
            );

            $created = 0;
            $updated = 0;

            foreach ($payload['features'] as $feat) {
                if (!is_array($feat)) {
                    continue;
                }

                $props = is_array($feat['properties'] ?? null) ? $feat['properties'] : [];
                $geom = is_array($feat['geometry'] ?? null) ? $feat['geometry'] : null;

                if (!$geom) {
                    continue;
                }

                $lotNumber = (string) ($props['lot_number'] ?? $props['parcel_no'] ?? $props['id'] ?? '');
                if ($lotNumber === '') {
                    continue;
                }

                $area = $props['area_m2'] ?? $props['area'] ?? null;

                $lot = PlanLot::query()->where('plan_id', $plan->id)->where('lot_number', $lotNumber)->first();
                if ($lot) {
                    $lot->geometry = $geom;
                    $lot->usage = $props['usage'] ?? $lot->usage;
                    $lot->status = $props['status'] ?? $lot->status;
                    $lot->area_m2 = is_numeric($area) ? (float) $area : $lot->area_m2;
                    $lot->price = isset($props['price']) && is_numeric($props['price']) ? (int) $props['price'] : $lot->price;
                    $lot->save();
                    $updated++;
                } else {
                    PlanLot::query()->create([
                        'plan_id' => $plan->id,
                        'lot_number' => $lotNumber,
                        'usage' => $props['usage'] ?? null,
                        'status' => $props['status'] ?? 'available',
                        'area_m2' => is_numeric($area) ? (float) $area : null,
                        'price' => isset($props['price']) && is_numeric($props['price']) ? (int) $props['price'] : null,
                        'geometry' => $geom,
                    ]);
                    $created++;
                }
            }

            $this->command?->info('Ajlan lots seeded. created=' . $created . ' updated=' . $updated);
        });
    }
}
