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
}
