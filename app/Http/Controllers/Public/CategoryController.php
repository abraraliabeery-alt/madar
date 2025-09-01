<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children', 'products'])
            ->orderBy('order')
            ->paginate(12);

        return view('public.categories.index', compact('categories'));
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load(['children', 'products']);

        return view('public.categories.show', compact('category'));
    }
}
