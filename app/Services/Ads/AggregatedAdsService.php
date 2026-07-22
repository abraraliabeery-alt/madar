<?php

namespace App\Services\Ads;

use App\Services\Ads\Providers\AqarProvider;
use App\Services\Ads\Providers\BayutProvider;
use App\Services\Ads\Providers\DealAppProvider;
use App\Services\Ads\Providers\HarajProvider;
use App\Services\WebFetchService;
use App\Services\WebSearchService;
use Illuminate\Support\Facades\Cache;

class AggregatedAdsService
{
    /**
     * @var array<int, object>
     */
    private array $providers;

    public function __construct(
        AqarProvider $aqar,
        HarajProvider $haraj,
        BayutProvider $bayut,
        DealAppProvider $dealApp,
        private readonly WebSearchService $webSearch,
        private readonly WebFetchService $webFetch,
    ) {
        $this->providers = [$aqar, $haraj, $bayut, $dealApp];
    }

    public function sources(): array
    {
        $out = [];
        foreach ($this->providers as $p) {
            $out[] = [
                'key' => $p->key(),
                'label' => $p->label(),
                'base_url' => $p->baseUrl(),
            ];
        }
        return $out;
    }

    public function hasKeywordSearch(): bool
    {
        return $this->webSearch->hasApiKey();
    }

    private function keywordSources(): array
    {
        $src = (array) config('web_search.sources', []);

        $sources = [];
        foreach ($src as $key => $info) {
            $domain = trim((string) ($info['domain'] ?? ''));
            if ($domain === '') continue;

            $sources[] = [
                'key' => (string) $key,
                'label' => (string) ($info['label'] ?? $key),
                'domain' => $domain,
                'base_url' => 'https://' . $domain,
            ];
        }

        return $sources;
    }

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: array<int, array<string, mixed>>}
     */
    public function search(array $filters): array
    {
        $cacheMinutes = (int) config('web_fetch.crawl.cache_minutes', 15);
        $cacheMinutes = max(1, min($cacheMinutes, 60));

        $cacheKey = 'aggregated_ads:' . sha1(json_encode($filters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () use ($filters) {
            $q = trim((string) ($filters['q'] ?? ''));
            if ($q !== '') {
                return $this->keywordSearch($q);
            }

            $items = [];
            $sources = $this->sources();

            foreach ($this->providers as $provider) {
                $providerItems = $provider->search($filters);
                foreach ($providerItems as $it) {
                    $it['source_key'] = $provider->key();
                    $it['source_label'] = $provider->label();
                    $it['source_base_url'] = $provider->baseUrl();
                    $items[] = $it;
                }
            }

            usort($items, function ($a, $b) {
                $ap = (int) ($a['price'] ?? 0);
                $bp = (int) ($b['price'] ?? 0);
                return $bp <=> $ap;
            });

            $items = array_slice($items, 0, 60);

            return [$items, $sources];
        });
    }

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: array<int, array<string, mixed>>}
     */
    private function keywordSearch(string $q): array
    {
        $sources = $this->keywordSources();

        if (!$this->hasKeywordSearch()) {
            return [[], array_map(fn ($s) => [
                'key' => $s['key'],
                'label' => $s['label'],
                'base_url' => $s['base_url'],
            ], $sources)];
        }

        $items = [];
        $limit = (int) config('web_search.serpapi.results_limit', 10);
        $limit = max(1, min($limit, 20));

        foreach ($sources as $s) {
            $urls = $this->webSearch->searchSourceDomain((string) $s['domain'], $q, $limit);

            foreach ($urls as $u) {
                $meta = $this->webFetch->fetchOne($u);
                if (!($meta['ok'] ?? false)) continue;

                $title = trim((string) ($meta['title'] ?? ''));
                if ($title === '') continue;

                $items[] = [
                    'title' => $title,
                    'price' => null,
                    'url' => $u,
                    'source_key' => (string) $s['key'],
                    'source_label' => (string) $s['label'],
                    'source_base_url' => (string) $s['base_url'],
                ];

                if (count($items) >= 60) {
                    break 2;
                }
            }
        }

        return [$items, array_map(fn ($s) => [
            'key' => $s['key'],
            'label' => $s['label'],
            'base_url' => $s['base_url'],
        ], $sources)];
    }
}
