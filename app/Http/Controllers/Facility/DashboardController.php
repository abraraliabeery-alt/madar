<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Facility;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $facilityId = $request->get('facility_id');

        $productQuery = Product::query();
        if ($facilityId) {
            $productQuery->where('facility_id', $facilityId);
        }

        $totalProducts = (clone $productQuery)->count();
        $featuredProducts = (clone $productQuery)->where('is_featured', true)->count();
        $verifiedProducts = (clone $productQuery)->where('is_verified', true)->count();

        $topProducts = (clone $productQuery)
            ->orderByDesc('views_count')
            ->take(5)
            ->get(['id', 'address', 'views_count', 'facility_id']);

        $totalFacilities = Facility::query()->count();

        return view('facility.dashboard.index', compact(
            'totalProducts',
            'featuredProducts',
            'verifiedProducts',
            'topProducts',
            'totalFacilities',
            'facilityId'
        ));
    }
}
