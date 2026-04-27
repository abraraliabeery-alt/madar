<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class WebCrawlService
{
    public function __construct(
        private readonly WebSitemapService $sitemaps,
        private readonly WebFetchService $fetcher,
        private readonly WebSearchService $search,
    ) {}

    public function crawlLatest(): array
    {
        $cacheMinutes = (int) config('web_fetch.crawl.cache_minutes', 15);
        $cacheMinutes = max(1, min($cacheMinutes, 120));

        return Cache::remember('web_crawl:latest', now()->addMinutes($cacheMinutes), function () {
            return $this->crawlLatestUncached();
        });
    }

    private function crawlLatestUncached(): array
    {
        $sources = (array) config('web_fetch.sources', []);
        $sitemapUrlsLimit = (int) config('web_fetch.crawl.sitemap_urls_limit', 80);
        $perSource = (int) config('web_fetch.crawl.fetch_items_per_source', 10);
        $totalLimit = (int) config('web_fetch.crawl.fetch_total_limit', 25);

        $sitemapUrlsLimit = max(10, min($sitemapUrlsLimit, 500));
        $perSource = max(1, min($perSource, 30));
        $totalLimit = max(5, min($totalLimit, 80));

        $items = [];
        $meta = [
            'sources' => [],
        ];

        foreach ($sources as $source) {
            if (count($items) >= $totalLimit) break;

            $label = $source['label'] ?? ($source['key'] ?? 'source');
            $baseUrl = $source['base_url'] ?? null;
            if (!$baseUrl) continue;

            $sourceUrls = $this->collectUrlsForSource($source, $baseUrl, $sitemapUrlsLimit, $perSource);

            $fetched = $this->fetcher->fetchMany($sourceUrls);

            $okCount = 0;
            foreach ($fetched as $f) {
                if (count($items) >= $totalLimit) break;
                if (($f['ok'] ?? false) !== true) continue;
                $okCount++;
                $items[] = $f + ['source' => $label];
            }

            $meta['sources'][] = [
                'label' => $label,
                'base_url' => $baseUrl,
                'urls_selected' => count($sourceUrls),
                'items_ok' => $okCount,
            ];
        }

        return [
            'items' => $items,
            'meta' => $meta,
        ];
    }

    private function collectUrlsFromSitemaps(string $baseUrl, int $limit): array
    {
        $sitemaps = $this->sitemaps->discoverSitemaps($baseUrl);
        $urls = [];

        foreach ($sitemaps as $sm) {
            if (count($urls) >= $limit) break;

            $locs = $this->sitemaps->fetchSitemapUrls($sm, $limit);
            foreach ($locs as $loc) {
                if (count($urls) >= $limit) break;

                // If this loc looks like a sitemap itself, expand one more level
                if (str_ends_with(parse_url($loc, PHP_URL_PATH) ?: '', '.xml')) {
                    $subLocs = $this->sitemaps->fetchSitemapUrls($loc, $limit);
                    foreach ($subLocs as $sub) {
                        if (count($urls) >= $limit) break;
                        $urls[] = $sub;
                    }
                    continue;
                }

                $urls[] = $loc;
            }
        }

        // Keep only allowed domains (WebFetchService will also enforce)
        $urls = array_values(array_unique(array_filter($urls, fn($u) => is_string($u) && $u !== '')));

        return $urls;
    }

    private function collectUrlsForSource(array $source, string $baseUrl, int $sitemapLimit, int $perSource): array
    {
        // Prefer Search API when available
        $domain = parse_url($baseUrl, PHP_URL_HOST);
        $domain = is_string($domain) ? $domain : null;

        if ($domain && $this->search->hasApiKey()) {
            $query = $this->search->defaultRealEstateQuery();
            $urls = $this->search->searchSourceDomain($domain, $query, $perSource);
            if (!empty($urls)) {
                return array_slice($urls, 0, $perSource);
            }
        }

        // Fallback: sitemap/robots
        $urls = $this->collectUrlsFromSitemaps($baseUrl, $sitemapLimit);
        return array_slice($urls, 0, $perSource);
    }
}
