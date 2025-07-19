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
        $features = Feature::where('is_active', true)
            ->with(['products'])
            ->orderBy('order')
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
        $feature->load(['products']);

        return response()->json([
            'success' => true,
            'data' => $feature
        ]);
    }
}
