<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanLot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AdminPlanLotController extends Controller
{
    private function readExtractionParcelsWgs(): ?array
    {
        $viewPath = resource_path('views/public/plans/plans.blade.php');
        if (!File::exists($viewPath)) {
            return null;
        }

        $content = File::get($viewPath);
        $marker = 'const parcelsWGS = ';
        $start = strpos($content, $marker);
        if ($start === false) {
            return null;
        }

        $start += strlen($marker);
        $end = strpos($content, ';', $start);
        if ($end === false) {
            return null;
        }

        $json = trim(substr($content, $start, $end - $start));
        $payload = json_decode($json, true);
        if (!is_array($payload)) {
            return null;
        }

        return $payload;
    }

    private function importFeatureCollection(string $slug, array $payload): array
    {
        if (($payload['type'] ?? null) !== 'FeatureCollection' || !is_array($payload['features'] ?? null)) {
            return ['ok' => false, 'message' => 'صيغة GeoJSON غير صحيحة. المطلوب FeatureCollection'];
        }

        $plan = Plan::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => strtoupper($slug), 'plan_number' => null]
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
                $lot->area_m2 = is_numeric($area) ? (float) $area : $lot->area_m2;
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

        return ['ok' => true, 'created' => $created, 'updated' => $updated];
    }

    public function index(string $slug): View
    {
        $plan = Plan::query()->where('slug', $slug)->with(['lots' => function ($q) {
            $q->orderByRaw('CAST(lot_number AS UNSIGNED) ASC')->orderBy('lot_number');
        }])->first();

        return view('admin.plans.lots', [
            'slug' => $slug,
            'plan' => $plan,
            'lots' => $plan?->lots ?? collect(),
        ]);
    }

    public function publicIndex(string $slug): View
    {
        $plan = Plan::query()->where('slug', $slug)->first();

        $lots = collect();
        if ($plan) {
            $lots = PlanLot::query()
                ->where('plan_id', $plan->id)
                ->orderByRaw('CAST(lot_number AS UNSIGNED) ASC')
                ->orderBy('lot_number')
                ->paginate(200)
                ->withQueryString();
        }

        return view('public.plans.lots_manage', [
            'slug' => $slug,
            'plan' => $plan,
            'lots' => $lots,
        ]);
    }

    public function update(Request $request, string $slug, PlanLot $lot): RedirectResponse
    {
        $data = $request->validate([
            'excel_lot_number' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,reserved,sold'],
            'price' => ['nullable', 'integer', 'min:0'],
            'usage' => ['nullable', 'string', 'max:255'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
        ]);

        $plan = Plan::query()->where('slug', $slug)->first();
        if (!$plan || (int) $lot->plan_id !== (int) $plan->id) {
            abort(404);
        }

        $lot->fill($data);
        $lot->save();

        return back()->with('success', 'تم تحديث القطعة بنجاح');
    }

    public function import(Request $request, string $slug): RedirectResponse
    {
        $data = $request->validate([
            'geojson' => ['required', 'string'],
        ]);

        $payload = json_decode($data['geojson'], true);
        if (!is_array($payload)) {
            return back()->withErrors(['geojson' => 'لم يتمكن النظام من قراءة JSON']);
        }

        $result = $this->importFeatureCollection($slug, $payload);
        if (!($result['ok'] ?? false)) {
            return back()->withErrors(['geojson' => $result['message'] ?? 'فشل الاستيراد']);
        }

        return back()->with('success', "تم الاستيراد. جديد: {$result['created']} - تحديث: {$result['updated']}");
    }

    public function importFromExtractionView(Request $request, string $slug): RedirectResponse
    {
        $viewPath = resource_path('views/public/plans/plans.blade.php');
        if (!File::exists($viewPath)) {
            return back()->withErrors(['geojson' => 'ملف الاستخراج غير موجود داخل المشروع']);
        }

        $content = File::get($viewPath);
        $marker = 'const parcelsWGS = ';
        $start = strpos($content, $marker);
        if ($start === false) {
            return back()->withErrors(['geojson' => 'لم يتم العثور على parcelsWGS داخل ملف الاستخراج']);
        }

        $start += strlen($marker);
        $end = strpos($content, ';', $start);
        if ($end === false) {
            return back()->withErrors(['geojson' => 'فشل تحديد نهاية JSON داخل ملف الاستخراج']);
        }

        $json = trim(substr($content, $start, $end - $start));
        $payload = json_decode($json, true);
        if (!is_array($payload)) {
            return back()->withErrors(['geojson' => 'تعذر قراءة GeoJSON من ملف الاستخراج']);
        }

        $result = $this->importFeatureCollection($slug, $payload);
        if (!($result['ok'] ?? false)) {
            return back()->withErrors(['geojson' => $result['message'] ?? 'فشل الاستيراد']);
        }

        return back()->with('success', "تم الاستيراد من ملف الاستخراج. جديد: {$result['created']} - تحديث: {$result['updated']}");
    }
}
