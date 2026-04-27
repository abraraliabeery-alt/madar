<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Services\AI\SmartBrokerAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FacilitySmartBrokerController extends Controller
{
    public function index()
    {
        return view('facility.smart-broker.index');
    }

    public function match(Request $request, SmartBrokerAiService $ai)
    {
        $data = $request->validate([
            'requests_text' => 'required|string|max:20000',
            'offers_text' => 'required|string|max:20000',
            'top_k' => 'nullable|integer|min:1|max:5',
        ]);

        $topK = (int) ($data['top_k'] ?? 3);

        $cacheKey = 'smart_broker:' . sha1(($data['requests_text'] ?? '') . '||' . ($data['offers_text'] ?? '') . '||' . $topK);

        $result = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($ai, $data, $topK) {
            return $ai->matchTextLists($data['requests_text'], $data['offers_text'], $topK);
        });

        return view('facility.smart-broker.index', [
            'requests_text' => $data['requests_text'],
            'offers_text' => $data['offers_text'],
            'top_k' => $topK,
            'result' => $result,
        ]);
    }
}
