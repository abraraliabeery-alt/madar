<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class ApiFeatureController extends Controller
{
    /**
     * Display a listing of features
     */
    public function index()
    {
        $features = Feature::active()
            ->with(['products', 'translations'])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $features
        ]);
    }

    /**
     * Display the specified feature
     */
    public function show(Feature $feature)
    {
        $feature->load(['products', 'translations']);

        return response()->json([
            'success' => true,
            'data' => $feature
        ]);
    }

    /**
     * Get features by category
     */
    public function getByCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'locale' => 'nullable|string|in:ar,en'
        ]);

        // Get locale from request parameter, session, or fallback to default
        $locale = $request->get('locale') ?? \Illuminate\Support\Facades\Session::get('locale', config('app.locale'));
        
        $features = Feature::where('category_id', $request->category_id)
            ->where('is_active', true)
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->orderBy('order')
            ->get()
            ->map(function($feature) use ($locale) {
                return [
                    'id' => $feature->id,
                    'icon' => $feature->icon,
                    'description' => $feature->description,
                    'is_active' => $feature->is_active,
                    'order' => $feature->order,
                    'name' => $feature->getTranslatedName($locale),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $features
        ]);
    }
}
