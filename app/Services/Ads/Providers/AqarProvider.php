<?php

namespace App\Services\Ads\Providers;

use App\Services\WebFetchService;
use Illuminate\Support\Str;

class AqarProvider extends BaseProvider
{
    public function __construct(private readonly WebFetchService $webFetch)
    {
    }

    public function key(): string
    {
        return 'aqar';
    }

    public function label(): string
    {
        return 'عقار';
    }

    public function baseUrl(): string
    {
        return 'https://sa.aqar.fm';
    }

    public function search(array $filters): array
    {
        $url = $this->buildSearchUrl($filters);
        if (!$url) return [];

        $html = $this->httpGet($url);
        if (!is_string($html) || $html === '') return [];

        return $this->parseList($html, $filters);
    }

    private function buildSearchUrl(array $filters): ?string
    {
        // MVP: if city/district are present we use a generic path-based search fallback.
        // For now, we only support city/district path when provided as Arabic text by building a simple URL:
        // /[type]-[purpose]/[city]/حي-[district]
        // If inputs are empty, just return base.

        $city = trim((string) ($filters['city'] ?? ''));
        $district = trim((string) ($filters['district'] ?? ''));

        $purpose = (string) ($filters['purpose'] ?? 'rent');
        $propertyType = (string) ($filters['property_type'] ?? 'apartment');

        $purposeSlug = $purpose === 'sale' ? 'للبيع' : 'للإيجار';

        $typeMap = [
            'apartment' => 'شقق',
            'villa' => 'فلل',
            'land' => 'أراضي',
            'building' => 'عمائر',
            'office' => 'مكاتب',
            'shop' => 'محلات',
        ];

        $type = $typeMap[$propertyType] ?? 'شقق';

        $base = rtrim($this->baseUrl(), '/');

        // "بحث عام" fallback: if user leaves it empty, still show results.
        if ($city === '' && $district === '') {
            $city = 'الرياض';
        }

        if ($city === '') {
            return $base;
        }

        // Normalize
        $citySlug = $this->slugifyArabic($city);

        $path = $type . '-' . $purposeSlug . '/' . $citySlug;

        if ($district !== '') {
            $distSlug = $this->slugifyArabic('حي ' . $district);
            $path .= '/' . $distSlug;
        }

        return $base . '/' . $this->slugifyPath($path);
    }

    private function parseList(string $html, array $filters): array
    {
        // Step 1: collect listing URLs from the results HTML.
        preg_match_all('/https?:\\/\\/(?:sa\\.)?aqar\\.fm\\/[^\"\'\s>]+-\d{5,}(?:\/?)/u', $html, $m);
        $urls = $m[0] ?? [];

        if (!is_array($urls) || count($urls) === 0) {
            // Fallback: collect hrefs and turn them into absolute URLs.
            preg_match_all('/href=[\"\']([^\"\']+)[\"\']/i', $html, $m2);
            $hrefs = $m2[1] ?? [];
            $urls = [];
            foreach ($hrefs as $h) {
                $abs = $this->absUrl($this->baseUrl(), $h);
                if ($abs && preg_match('/-([0-9]{5,})\/?$/', $abs)) {
                    $urls[] = $abs;
                }
            }
        }

        $urls = array_values(array_unique($urls));
        $urls = array_slice($urls, 0, 12);

        // Step 2: fetch each listing page meta for a reliable title.
        $items = [];
        foreach ($urls as $u) {
            $meta = $this->webFetch->fetchOne($u);
            if (!($meta['ok'] ?? false)) {
                continue;
            }

            $title = $this->cleanText($meta['title'] ?? null);
            if (!$title) {
                continue;
            }

            $items[] = [
                'title' => $title,
                'price' => null,
                'url' => $u,
            ];
        }

        return $items;
    }

    private function slugifyArabic(string $text): string
    {
        $text = trim($text);
        $text = str_replace(['–', '—'], '-', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = str_replace(' ', '-', $text);
        return $text;
    }

    private function slugifyPath(string $path): string
    {
        $path = trim($path, '/');
        $parts = array_values(array_filter(explode('/', $path), fn ($p) => trim($p) !== ''));
        $parts = array_map(fn ($p) => $this->slugifyArabic($p), $parts);
        return implode('/', $parts);
    }
}
