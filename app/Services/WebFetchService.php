<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebFetchService
{
    public function fetchMany(array $urls): array
    {
        $maxUrls = (int) config('web_fetch.max_urls', 20);
        $maxUrls = max(1, min($maxUrls, 50));

        $urls = array_values(array_filter(array_map('trim', $urls)));
        $urls = array_slice($urls, 0, $maxUrls);

        $items = [];
        foreach ($urls as $url) {
            $items[] = $this->fetchOne($url);
        }
        return $items;
    }

    public function fetchOne(string $url): array
    {
        $url = trim($url);

        if (!$this->isAllowedUrl($url)) {
            return [
                'url' => $url,
                'ok' => false,
                'error' => 'domain_not_allowed',
                'title' => null,
                'description' => null,
            ];
        }

        $cacheKey = 'web_fetch:' . sha1($url);

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($url) {
            $timeout = (int) config('web_fetch.timeout_seconds', 15);
            $maxBytes = (int) config('web_fetch.max_bytes', 300000);

            try {
                $resp = Http::timeout($timeout)
                    ->withHeaders([
                        'User-Agent' => 'AqarSmartBroker/1.0 (+https://localhost)',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    ])
                    ->get($url);

                if (!$resp->ok()) {
                    return [
                        'url' => $url,
                        'ok' => false,
                        'error' => 'http_' . $resp->status(),
                        'title' => null,
                        'description' => null,
                    ];
                }

                $html = (string) $resp->body();
                if ($maxBytes > 0 && strlen($html) > $maxBytes) {
                    $html = substr($html, 0, $maxBytes);
                }

                [$title, $desc] = $this->extractMeta($html);

                return [
                    'url' => $url,
                    'ok' => true,
                    'error' => null,
                    'title' => $title,
                    'description' => $desc,
                ];
            } catch (\Throwable $e) {
                return [
                    'url' => $url,
                    'ok' => false,
                    'error' => 'exception',
                    'title' => null,
                    'description' => null,
                ];
            }
        });
    }

    public function isAllowedUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) return false;
        $host = strtolower($host);

        $allowed = (array) config('web_fetch.allowed_domains', []);
        $allowed = array_map('strtolower', $allowed);

        if (in_array($host, $allowed, true)) {
            return true;
        }

        // Allow subdomains of allowed roots (e.g. m.haraj.com.sa)
        foreach ($allowed as $a) {
            $a = trim($a);
            if ($a === '') continue;
            if (str_ends_with($host, '.' . $a)) {
                return true;
            }
        }

        return false;
    }

    private function extractMeta(string $html): array
    {
        $title = null;
        $desc = null;

        // OG tags (fast regex)
        $title = $this->matchMetaProperty($html, 'og:title') ?: $this->matchTitleTag($html);
        $desc = $this->matchMetaProperty($html, 'og:description') ?: $this->matchMetaName($html, 'description');

        if (!$desc) {
            $desc = $this->extractBodyTextSnippet($html);
        }

        $title = $title ? trim(Str::squish(html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'))) : null;
        $desc = $desc ? trim(Str::squish(html_entity_decode($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8'))) : null;

        if ($desc && mb_strlen($desc) > 300) {
            $desc = mb_substr($desc, 0, 300) . '...';
        }

        return [$title, $desc];
    }

    private function extractBodyTextSnippet(string $html): ?string
    {
        try {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);
            foreach ($xpath->query('//script|//style|//noscript') as $node) {
                $node->parentNode?->removeChild($node);
            }

            $bodyNodes = $xpath->query('//body');
            $body = $bodyNodes && $bodyNodes->length > 0 ? $bodyNodes->item(0) : $dom;

            $text = $body ? $body->textContent : '';
            $text = trim(Str::squish($text));

            if ($text === '') return null;
            if (mb_strlen($text) > 500) {
                $text = mb_substr($text, 0, 500) . '...';
            }

            return $text;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function matchMetaProperty(string $html, string $property): ?string
    {
        $re = '/<meta[^>]+property=["\']' . preg_quote($property, '/') . '["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i';
        if (preg_match($re, $html, $m)) return $m[1] ?? null;

        // Sometimes content comes before property
        $re2 = '/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']' . preg_quote($property, '/') . '["\'][^>]*>/i';
        if (preg_match($re2, $html, $m)) return $m[1] ?? null;

        return null;
    }

    private function matchMetaName(string $html, string $name): ?string
    {
        $re = '/<meta[^>]+name=["\']' . preg_quote($name, '/') . '["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i';
        if (preg_match($re, $html, $m)) return $m[1] ?? null;

        $re2 = '/<meta[^>]+content=["\']([^"\']+)["\'][^>]+name=["\']' . preg_quote($name, '/') . '["\'][^>]*>/i';
        if (preg_match($re2, $html, $m)) return $m[1] ?? null;

        return null;
    }

    private function matchTitleTag(string $html): ?string
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
            return $m[1] ?? null;
        }
        return null;
    }
}
