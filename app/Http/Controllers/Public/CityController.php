<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;

class CityController extends Controller
{
    /**
     * Display cities index page
     */
    public function index()
    {
        $cities = City::active()->featured()->ordered()->get();
        return view('public.cities.index', compact('cities'));
    }

    /**
     * Display a specific city
     */
    public function show(City $city)
    {
        if (!$city->is_active) {
            abort(404);
        }

        return view('public.cities.show', compact('city'));
    }

    /**
     * Display products by city
     */
    public function products(City $city, Request $request)
    {
        if (!$city->is_active) {
            abort(404);
        }

        $query = Product::with(['facility', 'category'])
            ->where('city_id', $city->id)
            ->where('is_active', true)
            ->where('is_verified', true);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by rooms
        if ($request->filled('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        // Filter by area range
        if ($request->filled('min_area')) {
            $query->where('area', '>=', $request->min_area);
        }

        if ($request->filled('max_area')) {
            $query->where('area', '<=', $request->max_area);
        }

        // Search by keyword
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhere('address', 'like', "%{$request->q}%");
            });
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $facilities = Facility::where('is_active', true)
            ->where('is_verified', true)
            ->where('city_id', $city->id)
            ->get();

        return view('public.cities.products', compact('city', 'products', 'categories', 'facilities'));
    }

    /**
     * Display facilities by city
     */
    public function facilities(City $city, Request $request)
    {
        if (!$city->is_active) {
            abort(404);
        }

        $query = Facility::with(['owner', 'facilityCategory'])
            ->where('city_id', $city->id)
            ->where('is_active', true)
            ->where('is_verified', true);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by keyword
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhere('address', 'like', "%{$request->q}%");
            });
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $facilities = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('public.cities.facilities', compact('city', 'facilities', 'categories'));
    }
}
