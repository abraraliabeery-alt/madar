<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of features
     */
    public function index()
    {
        $features = Feature::active()
            ->with(['products', 'translations'])
            ->ordered()
            ->paginate(20);

        return view('public.features.index', compact('features'));
    }

    /**
     * Display the specified feature
     */
    public function show(Feature $feature)
    {
        $feature->load(['products', 'translations']);

        return view('public.features.show', compact('feature'));
    }
}
