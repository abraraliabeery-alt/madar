<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebSearchService
{
    public function hasApiKey(): bool
    {
        $provider = (string) config('web_search.provider', 'serpapi');

        if ($provider === 'duckduckgo_html') {
            return true;
        }

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

        if ($provider === 'duckduckgo_html') {
            return $this->searchDuckDuckGoHtml($domain, $query, $limit);
        }

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

    private function searchDuckDuckGoHtml(string $domain, string $query, int $limit = 10): array
    {
        $baseUrl = rtrim((string) config('web_search.duckduckgo.base_url', 'https://duckduckgo.com'), '/');
        $timeout = (int) config('web_search.duckduckgo.timeout_seconds', 25);

        $limit = max(1, min($limit, 20));
        $q = 'site:' . $domain . ' ' . $query;

        try {
            $client = Http::timeout($timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'ar,en;q=0.8',
                ]);

            // DDG HTML often works better with POST (form).
            $resp = $client->asForm()->post($baseUrl . '/html/', [
                'q' => $q,
            ]);

            if (!$resp->ok()) {
                $resp = $client->get($baseUrl . '/html/', [
                    'q' => $q,
                ]);
            }

            if (!$resp->ok()) return [];

            $html = (string) $resp->body();
            if ($html === '') return [];

            preg_match_all('/<a[^>]+class=["\']result__a["\'][^>]+href=["\']([^"\']+)["\']/i', $html, $m);
            $hrefs = $m[1] ?? [];

            if (!is_array($hrefs) || count($hrefs) === 0) {
                preg_match_all('/href=["\']([^"\']+)["\']/i', $html, $m2);
                $hrefs = $m2[1] ?? [];
            }

            $urls = [];
            foreach ($hrefs as $h) {
                if (!is_string($h) || $h === '') continue;

                $u = $this->ddgDecodeUrl($baseUrl, $h);
                if (!is_string($u) || $u === '') continue;

                $host = (string) parse_url($u, PHP_URL_HOST);
                $host = strtolower($host);
                $domainLc = strtolower($domain);

                if ($host === $domainLc || Str::endsWith($host, '.' . $domainLc)) {
                    $urls[] = $u;
                }

                if (count($urls) >= $limit) break;
            }

            return array_values(array_unique($urls));
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function ddgDecodeUrl(string $baseUrl, string $href): ?string
    {
        $href = trim($href);

        if (Str::startsWith($href, ['http://', 'https://'])) {
            return $href;
        }

        $abs = Str::startsWith($href, '/')
            ? rtrim($baseUrl, '/') . $href
            : rtrim($baseUrl, '/') . '/' . $href;

        $path = (string) parse_url($abs, PHP_URL_PATH);
        if (Str::contains($path, '/l/')) {
            parse_str((string) parse_url($abs, PHP_URL_QUERY), $qs);
            $uddg = $qs['uddg'] ?? null;
            if (is_string($uddg) && $uddg !== '') {
                $decoded = urldecode($uddg);
                if (Str::startsWith($decoded, ['http://', 'https://'])) {
                    return $decoded;
                }
            }
        }

        return $abs;
    }

    public function defaultRealEstateQuery(): string
    {
        return '(مشروع OR شقة OR شقق OR فيلا OR فلل OR أرض OR أراضي OR عمارة OR دوبلكس OR استراحة OR مزرعة) (للبيع OR للإيجار OR للتمليك OR مطلوب)';
    }
}
