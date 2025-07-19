<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children', 'products', 'facilities'])
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load(['children', 'products', 'facilities']);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Get products by category
     */
    public function products(Category $category)
    {
        $products = $category->products()
            ->where('is_active', true)
            ->where('is_verified', true)
            ->with(['facility', 'category', 'features'])
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get facilities by category
     */
    public function facilities(Category $category)
    {
        $facilities = $category->facilities()
            ->where('is_active', true)
            ->where('is_verified', true)
            ->with(['category', 'products'])
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $facilities
        ]);
    }
}
