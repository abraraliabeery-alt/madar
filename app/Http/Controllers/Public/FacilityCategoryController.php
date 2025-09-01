<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FacilityCategory;
use Illuminate\Http\Request;

class FacilityCategoryController extends Controller
{
    /**
     * Display a listing of facility categories
     */
    public function index()
    {
        $categories = FacilityCategory::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children', 'facilities'])
            ->orderBy('order')
            ->paginate(12);

        return view('public.facility-categories.index', compact('categories'));
    }

    /**
     * Display the specified facility category
     */
    public function show(FacilityCategory $category)
    {
        $category->load(['children', 'facilities']);

        return view('public.facility-categories.show', compact('category'));
    }
}
