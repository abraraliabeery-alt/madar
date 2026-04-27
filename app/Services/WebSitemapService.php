<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WebSitemapService
{
    public function discoverSitemaps(string $baseUrl): array
    {
        $robotsUrl = rtrim($baseUrl, '/').'/robots.txt';
        $timeout = (int) config('web_fetch.timeout_seconds', 15);

        try {
            $resp = Http::timeout($timeout)->get($robotsUrl);
            if (!$resp->ok()) return [];
            $body = (string) $resp->body();
            return $this->parseRobotsForSitemaps($body);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function fetchSitemapUrls(string $sitemapUrl, int $limit = 50): array
    {
        $timeout = (int) config('web_fetch.timeout_seconds', 15);
        $limit = max(1, min($limit, 500));

        try {
            $resp = Http::timeout($timeout)->get($sitemapUrl);
            if (!$resp->ok()) return [];

            $xml = (string) $resp->body();
            $path = (string) (parse_url($sitemapUrl, PHP_URL_PATH) ?: '');
            if (str_ends_with($path, '.gz')) {
                $decoded = @gzdecode($xml);
                if (is_string($decoded) && $decoded !== '') {
                    $xml = $decoded;
                }
            }
            $urls = $this->parseSitemapLocs($xml);

            return array_slice($urls, 0, $limit);
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function parseRobotsForSitemaps(string $robots): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $robots);
        $sitemaps = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            if (stripos($line, 'sitemap:') === 0) {
                $url = trim(substr($line, strlen('sitemap:')));
                if ($url !== '') $sitemaps[] = $url;
            }
        }
        return array_values(array_unique($sitemaps));
    }

    private function parseSitemapLocs(string $xml): array
    {
        $urls = [];
        if (preg_match_all('/<loc>\s*([^<\s]+)\s*<\/loc>/i', $xml, $m)) {
            foreach ($m[1] as $u) {
                $u = trim($u);
                if ($u !== '') $urls[] = $u;
            }
        }
        return array_values(array_unique($urls));
    }
}
