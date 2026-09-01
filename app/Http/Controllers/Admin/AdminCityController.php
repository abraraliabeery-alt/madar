<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('cities', 'public');
        } else {
            $validated['image'] = $validated['image'] ?? null;
        }

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
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', $city->is_active);
        $validated['is_featured'] = $request->boolean('is_featured', $city->is_featured);
        $validated['sort_order'] = $validated['sort_order'] ?? $city->sort_order;

        if ($request->hasFile('image_file')) {
            if ($city->image && !str_starts_with($city->image, 'http') && Storage::disk('public')->exists($city->image)) {
                Storage::disk('public')->delete($city->image);
            }
            $validated['image'] = $request->file('image_file')->store('cities', 'public');
        } else {
            $validated['image'] = $validated['image'] ?? $city->image;
        }

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'تم تحديث المدينة بنجاح.');
    }

    public function destroy(City $city)
    {
        if ($city->image && !str_starts_with($city->image, 'http') && Storage::disk('public')->exists($city->image)) {
            Storage::disk('public')->delete($city->image);
        }
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'تم حذف المدينة بنجاح.');
    }
}
