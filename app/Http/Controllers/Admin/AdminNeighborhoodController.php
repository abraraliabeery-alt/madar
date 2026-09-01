<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Http\Request;

class AdminNeighborhoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Neighborhood::with('city');
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        $neighborhoods = $query->latest()->paginate(20);
        $cities = City::active()->get();
        return view('admin.neighborhoods.index', compact('neighborhoods', 'cities'));
    }

    public function create()
    {
        $cities = City::active()->get();
        return view('admin.neighborhoods.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Neighborhood::create($validated);

        return redirect()->route('admin.neighborhoods.index')->with('success', 'تم إضافة الحي بنجاح.');
    }

    public function edit(Neighborhood $neighborhood)
    {
        $cities = City::active()->get();
        return view('admin.neighborhoods.edit', compact('neighborhood', 'cities'));
    }

    public function update(Request $request, Neighborhood $neighborhood)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $neighborhood->update($validated);

        return redirect()->route('admin.neighborhoods.index')->with('success', 'تم تحديث الحي بنجاح.');
    }

    public function destroy(Neighborhood $neighborhood)
    {
        $neighborhood->delete();
        return redirect()->route('admin.neighborhoods.index')->with('success', 'تم حذف الحي بنجاح.');
    }
}
