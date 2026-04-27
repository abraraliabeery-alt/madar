<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ExecutionRequest;
use App\Models\Offer;
use App\Services\AI\SmartBrokerAiService;
use App\Services\WebCrawlService;
use App\Services\WebFetchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SmartBrokerController extends Controller
{
    public function index(Request $request)
    {
        $topK = (int) $request->query('top_k', 3);
        $topK = max(1, min($topK, 5));

        $result = [];
        $crawlMeta = [];
        $requestsText = '';
        $offersText = '';

        try {
            $crawl = app(WebCrawlService::class)->crawlLatest();
            $items = $crawl['items'] ?? [];
            $crawlMeta = $crawl['meta'] ?? [];

            [$requestsText, $offersText] = $this->splitRequestsOffersFromItems($items);

            if ($requestsText !== '' && $offersText !== '') {
                $result = app(SmartBrokerAiService::class)->matchTextLists($requestsText, $offersText, $topK);
            } else {
                $result = [
                    'status' => 'empty',
                    'requests' => $requestsText !== '' ? preg_split('/\r\n|\r|\n/', $requestsText) : [],
                    'offers' => $offersText !== '' ? preg_split('/\r\n|\r|\n/', $offersText) : [],
                    'matches' => [],
                ];
            }
        } catch (\Throwable $e) {
            $result = [
                'status' => 'error',
                'requests' => [],
                'offers' => [],
                'matches' => [],
            ];
        }

        return view('public.smart-broker.index', [
            'top_k' => $topK,
            'crawl_meta' => $crawlMeta,
            'requests_text' => $requestsText,
            'offers_text' => $offersText,
            'result' => $result,
        ]);
    }

    public function data(Request $request)
    {
        try {
            $crawl = app(WebCrawlService::class)->crawlLatest();
            $items = $crawl['items'] ?? [];
            $meta = $crawl['meta'] ?? [];

            $publicItems = [];
            foreach ($items as $it) {
                $publicItems[] = [
                    'title' => (string) ($it['title'] ?? ''),
                    'snippet' => (string) ($it['description'] ?? ''),
                    'link' => (string) ($it['url'] ?? ''),
                    'source' => (string) ($it['source'] ?? ''),
                    'ok' => (bool) ($it['ok'] ?? false),
                    'error' => $it['error'] ?? null,
                ];
            }

            return response()->json([
                'ok' => true,
                'items' => $publicItems,
                'meta' => $meta,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'items' => [],
                'meta' => [],
            ], 500);
        }
    }

    public function fetch(Request $request, WebFetchService $fetcher)
    {
        $data = $request->validate([
            'request_urls' => 'required|string|max:20000',
            'offer_urls' => 'required|string|max:20000',
            'top_k' => 'nullable|integer|min:1|max:5',
        ]);

        $requestUrls = $this->parseLinesToUrls($data['request_urls']);
        $offerUrls = $this->parseLinesToUrls($data['offer_urls']);

        $requests = $fetcher->fetchMany($requestUrls);
        $offers = $fetcher->fetchMany($offerUrls);

        $requestsText = $this->formatFetchedItems($requests, 'طلب');
        $offersText = $this->formatFetchedItems($offers, 'عرض');

        return view('public.smart-broker.index', [
            'request_urls' => $data['request_urls'],
            'offer_urls' => $data['offer_urls'],
            'requests_text' => $requestsText,
            'offers_text' => $offersText,
            'top_k' => (int) ($data['top_k'] ?? 3),
            'fetch_status' => 'ok',
            'fetch_stats' => [
                'requests_ok' => count(array_filter($requests, fn($i) => ($i['ok'] ?? false) === true)),
                'requests_total' => count($requests),
                'offers_ok' => count(array_filter($offers, fn($i) => ($i['ok'] ?? false) === true)),
                'offers_total' => count($offers),
            ],
        ]);
    }

    public function match(Request $request, SmartBrokerAiService $ai)
    {
        $data = $request->validate([
            'requests_text' => 'required|string|max:20000',
            'offers_text' => 'required|string|max:20000',
            'top_k' => 'nullable|integer|min:1|max:5',
        ]);

        $topK = (int) ($data['top_k'] ?? 3);

        $cacheKey = 'public_smart_broker:' . sha1(($data['requests_text'] ?? '') . '||' . ($data['offers_text'] ?? '') . '||' . $topK);

        $result = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($ai, $data, $topK) {
            return $ai->matchTextLists($data['requests_text'], $data['offers_text'], $topK);
        });

        return view('public.smart-broker.index', [
            'requests_text' => $data['requests_text'],
            'offers_text' => $data['offers_text'],
            'top_k' => $topK,
            'result' => $result,
        ]);
    }

    private function buildRequestsText(int $limit): string
    {
        $locale = app()->getLocale();

        $requests = ExecutionRequest::query()
            ->with(['translations'])
            ->where('status', 'open')
            ->latest()
            ->take($limit)
            ->get();

        $lines = [];
        foreach ($requests as $r) {
            $title = $r->getTranslatedTitle($locale) ?: ('طلب #'.$r->id);
            $budgetMin = $r->budget_min ? number_format((float) $r->budget_min, 0) : null;
            $budgetMax = $r->budget_max ? number_format((float) $r->budget_max, 0) : null;
            $budget = null;
            if ($budgetMin || $budgetMax) {
                $budget = ($budgetMin ?: '—').' - '.($budgetMax ?: '—').' ريال';
            }
            $type = $r->type ?: 'غير محدد';
            $lines[] = trim($title.' | النوع: '.$type.($budget ? ' | الميزانية: '.$budget : ''));
        }

        return implode("\n", $lines);
    }

    private function buildOffersText(int $limit): string
    {
        $locale = app()->getLocale();

        $offers = Offer::query()
            ->active()
            ->valid()
            ->with(['translations', 'product'])
            ->latest()
            ->take($limit)
            ->get();

        $lines = [];
        foreach ($offers as $o) {
            $title = $o->getTranslatedTitle($locale) ?: ($o->offer_title ?: ('عرض #'.$o->id));
            $price = $o->price ? number_format((float) $o->price, 0).' ريال' : null;
            $type = $o->offer_type ?: '—';
            $lines[] = trim($title.' | '.$type.($price ? ' | السعر: '.$price : ''));
        }

        return implode("\n", $lines);
    }

    private function parseLinesToUrls(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $urls = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $urls[] = $line;
            if (count($urls) >= (int) config('web_fetch.max_urls', 20)) break;
        }
        return $urls;
    }

    private function formatFetchedItems(array $items, string $prefix): string
    {
        $lines = [];
        foreach ($items as $i => $item) {
            $url = $item['url'] ?? '';
            if (($item['ok'] ?? false) !== true) {
                $lines[] = $prefix.' #'.($i + 1).': (تعذر الجلب) '.$url;
                continue;
            }
            $title = trim((string) ($item['title'] ?? ''));
            $desc = trim((string) ($item['description'] ?? ''));
            $title = $title !== '' ? $title : ($prefix.' #'.($i + 1));
            $line = $title;
            if ($desc !== '') {
                $line .= ' | '.$desc;
            }
            if ($url !== '') {
                $line .= ' | '.$url;
            }
            $lines[] = $line;
        }
        return implode("\n", $lines);
    }

    private function splitRequestsOffersFromItems(array $items): array
    {
        $requests = [];
        $offers = [];

        foreach ($items as $it) {
            if (($it['ok'] ?? false) !== true) continue;

            $title = trim((string) ($it['title'] ?? ''));
            $desc = trim((string) ($it['description'] ?? ''));
            $url = trim((string) ($it['url'] ?? ''));
            $source = trim((string) ($it['source'] ?? ''));

            $line = ($title !== '' ? $title : 'إعلان').($desc !== '' ? ' | '.$desc : '').($url !== '' ? ' | '.$url : '');
            if ($source !== '') {
                $line .= ' | المصدر: '.$source;
            }

            $hay = mb_strtolower($title.' '.$desc);
            if ($this->looksLikeRequest($hay)) {
                $requests[] = $line;
            } else {
                $offers[] = $line;
            }
        }

        $requests = array_slice($requests, 0, 20);
        $offers = array_slice($offers, 0, 50);

        return [implode("\n", $requests), implode("\n", $offers)];
    }

    private function looksLikeRequest(string $text): bool
    {
        $needles = [
            'مطلوب',
            'ابغى',
            'أبغى',
            'ابي',
            'أبي',
            'احتاج',
            'أحتاج',
            'ابحث',
            'أبحث',
            'ارغب',
            'أرغب',
            'طلب',
        ];

        foreach ($needles as $n) {
            if (mb_strpos($text, mb_strtolower($n)) !== false) return true;
        }

        return false;
    }
}
