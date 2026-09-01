<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class AdminCityController extends Controller
{
    public function index()
    {
        $cities = City::ordered()->paginate(20);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cities,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', 'تم إضافة المدينة بنجاح.');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cities,slug,' . $city->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'تم تحديث المدينة بنجاح.');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'تم حذف المدينة بنجاح.');
    }
}
