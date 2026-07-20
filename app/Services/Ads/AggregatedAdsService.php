<?php

namespace App\Services\Ads;

use App\Services\Ads\Providers\AqarProvider;
use App\Services\Ads\Providers\BayutProvider;
use App\Services\Ads\Providers\DealAppProvider;
use App\Services\Ads\Providers\HarajProvider;
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

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: array<int, array<string, mixed>>}
     */
    public function search(array $filters): array
    {
        $cacheMinutes = (int) config('web_fetch.crawl.cache_minutes', 15);
        $cacheMinutes = max(1, min($cacheMinutes, 60));

        $cacheKey = 'aggregated_ads:' . sha1(json_encode($filters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () use ($filters) {
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
}
