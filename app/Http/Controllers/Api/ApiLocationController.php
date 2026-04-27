<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Neighborhood;
use App\Models\Street;

class ApiLocationController extends Controller
{
    /**
     * Get neighborhoods by city
     */
    public function neighborhoods(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
        ]);

        $items = Neighborhood::where('city_id', $request->city_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id','name']);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Get streets by neighborhood
     */
    public function streets(Request $request)
    {
        $request->validate([
            'neighborhood_id' => 'required|exists:neighborhoods,id',
        ]);

        $items = Street::where('neighborhood_id', $request->neighborhood_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id','name']);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}
