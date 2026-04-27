<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WebSearchService
{
    public function hasApiKey(): bool
    {
        $provider = (string) config('web_search.provider', 'serpapi');

        if ($provider === 'google_cse') {
            $key = (string) config('web_search.google_cse.api_key');
            $cx = (string) config('web_search.google_cse.cx');
            return trim($key) !== '' && trim($cx) !== '';
        }

        $key = (string) config('web_search.serpapi.api_key');
        return trim($key) !== '';
    }

    public function searchSourceDomain(string $domain, string $query, int $limit = 10): array
    {
        $provider = (string) config('web_search.provider', 'serpapi');

        if ($provider === 'google_cse') {
            return $this->searchGoogleCse($domain, $query, $limit);
        }

        return $this->searchSerpApi($domain, $query, $limit);
    }

    private function searchSerpApi(string $domain, string $query, int $limit = 10): array
    {
        $apiKey = (string) config('web_search.serpapi.api_key');
        if (trim($apiKey) === '') return [];

        $baseUrl = rtrim((string) config('web_search.serpapi.base_url', 'https://serpapi.com'), '/');
        $engine = (string) config('web_search.serpapi.engine', 'google');
        $gl = (string) config('web_search.serpapi.gl', 'sa');
        $hl = (string) config('web_search.serpapi.hl', 'ar');
        $timeout = (int) config('web_search.serpapi.timeout_seconds', 25);

        $limit = max(1, min($limit, 20));

        $q = 'site:' . $domain . ' ' . $query;

        try {
            $resp = Http::timeout($timeout)->get($baseUrl . '/search.json', [
                'engine' => $engine,
                'q' => $q,
                'api_key' => $apiKey,
                'gl' => $gl,
                'hl' => $hl,
                'num' => $limit,
            ]);

            if (!$resp->ok()) return [];

            $data = $resp->json();
            $results = $data['organic_results'] ?? [];

            $urls = [];
            foreach ($results as $r) {
                $link = $r['link'] ?? null;
                if (is_string($link) && $link !== '') {
                    $urls[] = $link;
                }
            }

            return array_values(array_unique($urls));
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function searchGoogleCse(string $domain, string $query, int $limit = 10): array
    {
        $apiKey = (string) config('web_search.google_cse.api_key');
        $cx = (string) config('web_search.google_cse.cx');
        if (trim($apiKey) === '' || trim($cx) === '') return [];

        $baseUrl = rtrim((string) config('web_search.google_cse.base_url', 'https://www.googleapis.com'), '/');
        $timeout = (int) config('web_search.google_cse.timeout_seconds', 25);
        $gl = (string) config('web_search.google_cse.gl', 'sa');
        $hl = (string) config('web_search.google_cse.hl', 'ar');

        $limit = max(1, min($limit, 10));

        $q = 'site:' . $domain . ' ' . $query;

        try {
            $resp = Http::timeout($timeout)->get($baseUrl . '/customsearch/v1', [
                'key' => $apiKey,
                'cx' => $cx,
                'q' => $q,
                'num' => $limit,
                'gl' => $gl,
                'hl' => $hl,
            ]);

            if (!$resp->ok()) return [];

            $data = $resp->json();
            $results = $data['items'] ?? [];

            $urls = [];
            foreach ($results as $r) {
                $link = $r['link'] ?? null;
                if (is_string($link) && $link !== '') {
                    $urls[] = $link;
                }
            }

            return array_values(array_unique($urls));
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function defaultRealEstateQuery(): string
    {
        return '(عقار OR شقة OR شقق OR فيلا OR فلل OR أرض OR أراضي OR عمارة OR دوبلكس OR استراحة OR مزرعة) (للبيع OR للإيجار OR للتمليك OR مطلوب)';
    }
}
