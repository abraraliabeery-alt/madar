<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Facility;
use Illuminate\Http\Request;

class ApiSearchController extends Controller
{
    /**
     * Global search
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Query parameter is required'
            ], 400);
        }

        $products = Product::where('is_active', true)
            ->where('is_verified', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['facility', 'category'])
            ->take(10)
            ->get();

        $facilities = Facility::where('is_active', true)
            ->where('is_verified', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['category'])
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products,
                'facilities' => $facilities
            ]
        ]);
    }

    /**
     * Search products
     */
    public function searchProducts(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('is_verified', true);

        if ($request->has('q')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->with(['facility', 'category', 'features'])
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Search facilities
     */
    public function searchFacilities(Request $request)
    {
        $query = Facility::where('is_active', true)
            ->where('is_verified', true);

        if ($request->has('q')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $facilities = $query->with(['category', 'products'])
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $facilities
        ]);
    }

    /**
     * Advanced search
     */
    public function advancedSearch(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('is_verified', true);

        // Apply filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        if ($request->has('min_area')) {
            $query->where('area', '>=', $request->min_area);
        }

        if ($request->has('max_area')) {
            $query->where('area', '<=', $request->max_area);
        }

        // Location search
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius;

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        $products = $query->with(['facility', 'category', 'features'])
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
