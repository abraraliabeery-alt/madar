<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Ads\AggregatedAdsService;
use Illuminate\Http\Request;

class AggregatedAdsController extends Controller
{
    public function __construct(private readonly AggregatedAdsService $adsService)
    {
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'city' => trim((string) $request->query('city', '')),
            'district' => trim((string) $request->query('district', '')),
            'property_type' => trim((string) $request->query('property_type', 'apartment')),
            'purpose' => trim((string) $request->query('purpose', 'rent')),
            'min_price' => $request->filled('min_price') ? (int) $request->query('min_price') : null,
            'max_price' => $request->filled('max_price') ? (int) $request->query('max_price') : null,
        ];

        // Always run a "general" search on load.
        // Provider(s) can apply their own fallback when city/district is empty.
        [$results, $sources] = $this->adsService->search($filters);
        $hasKeywordSearch = $this->adsService->hasKeywordSearch();

        return view('public.ads.index', [
            'filters' => $filters,
            'results' => $results,
            'sources' => $sources,
            'hasKeywordSearch' => $hasKeywordSearch,
        ]);
    }
}
