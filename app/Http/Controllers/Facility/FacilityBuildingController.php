<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilityBuildingController extends Controller
{
    /**
     * عرض قائمة عمارات المنشأة.
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->buildings()->with('translations');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('translations', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            });
        }

        $buildings = $query->latest()->paginate(15);

        return view('facility.buildings.index', compact('buildings'));
    }

    /**
     * عرض نموذج إنشاء عمارة جديدة.
     */
    public function create()
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $locales = config('locales.available', []);
        $building = new Building();
        $building->is_active = true;
        $translations = [];

        return view('facility.buildings.create', compact('facility', 'locales', 'building', 'translations'));
    }

    /**
     * حفظ عمارة جديدة.
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $validated = $request->validate([
            'is_active' => 'nullable|boolean',
            'Number_of_floors' => 'nullable|integer|min:0',
            'Number_of_Apartments' => 'nullable|integer|min:0',
            'Office_ratio' => 'nullable|numeric|min:0|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.notes' => 'nullable|string',
            'translations.*.rules' => 'nullable|string',
        ]);

        $data = $validated;
        $data['facility_id'] = $facility->id;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/buildings/images', 'public');
        }

        $building = Building::create($data);

        foreach ($request->input('translations') as $locale => $values) {
            BuildingTranslation::create([
                'building_id' => $building->id,
                'locale' => $locale,
                'name' => $values['name'],
                'notes' => $values['notes'] ?? null,
                'rules' => $values['rules'] ?? null,
            ]);
        }

        return redirect()->route('facility.buildings.index')
            ->with('success', 'تم إضافة العمارة بنجاح');
    }

    /**
     * عرض نموذج تعديل العمارة.
     */
    public function edit($id)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $building = $facility->buildings()->with('translations')->findOrFail($id);
        $locales = config('locales.available', []);
        $translations = $building->translations->keyBy('locale');

        return view('facility.buildings.edit', compact('facility', 'building', 'locales', 'translations'));
    }

    /**
     * تحديث بيانات العمارة.
     */
    public function update(Request $request, $id)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $building = $facility->buildings()->findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'nullable|boolean',
            'Number_of_floors' => 'nullable|integer|min:0',
            'Number_of_Apartments' => 'nullable|integer|min:0',
            'Office_ratio' => 'nullable|numeric|min:0|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.notes' => 'nullable|string',
            'translations.*.rules' => 'nullable|string',
        ]);

        $data = $validated;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($building->image) {
                Storage::disk('public')->delete($building->image);
            }
            $data['image'] = $request->file('image')->store('uploads/buildings/images', 'public');
        }

        $building->update($data);

        foreach ($request->input('translations') as $locale => $values) {
            BuildingTranslation::updateOrCreate(
                ['building_id' => $building->id, 'locale' => $locale],
                [
                    'name' => $values['name'],
                    'notes' => $values['notes'] ?? null,
                    'rules' => $values['rules'] ?? null,
                ]
            );
        }

        return redirect()->route('facility.buildings.index')
            ->with('success', 'تم تحديث بيانات العمارة بنجاح');
    }

    /**
     * حذف العمارة.
     */
    public function destroy($id)
    {
        $facility = Auth::user()->mainFacility();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $building = $facility->buildings()->findOrFail($id);

        if ($building->image) {
            Storage::disk('public')->delete($building->image);
        }

        $building->delete();

        return redirect()->route('facility.buildings.index')
            ->with('success', 'تم حذف العمارة بنجاح');
    }
}
