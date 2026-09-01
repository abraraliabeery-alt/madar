<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Neighborhood;
use App\Models\Street;
use Illuminate\Http\Request;

class AdminStreetController extends Controller
{
    public function index(Request $request)
    {
        $query = Street::with('neighborhood.city');
        if ($request->filled('neighborhood_id')) {
            $query->where('neighborhood_id', $request->neighborhood_id);
        }
        $streets = $query->latest()->paginate(20);
        $neighborhoods = Neighborhood::with('city')->active()->get();
        return view('admin.streets.index', compact('streets', 'neighborhoods'));
    }

    public function create()
    {
        $neighborhoods = Neighborhood::with('city')->active()->get();
        return view('admin.streets.create', compact('neighborhoods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'neighborhood_id' => 'required|exists:neighborhoods,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Street::create($validated);

        return redirect()->route('admin.streets.index')->with('success', __('admin.messages.street_created'));
    }

    public function edit(Street $street)
    {
        $neighborhoods = Neighborhood::with('city')->active()->get();
        return view('admin.streets.edit', compact('street', 'neighborhoods'));
    }

    public function update(Request $request, Street $street)
    {
        $validated = $request->validate([
            'neighborhood_id' => 'required|exists:neighborhoods,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $street->update($validated);

        return redirect()->route('admin.streets.index')->with('success', __('admin.messages.street_updated'));
    }

    public function destroy(Street $street)
    {
        $street->delete();
        return redirect()->route('admin.streets.index')->with('success', __('admin.messages.street_deleted'));
    }
}
