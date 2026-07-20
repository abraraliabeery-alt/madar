<?php

namespace App\Services\Ads\Providers;

use Illuminate\Support\Str;

class AqarProvider extends BaseProvider
{
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

        if ($city === '' && $district === '') {
            return $base;
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
        $dom = new \DOMDocument();
        \libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        \libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $links = $xpath->query('//a[@href]');
        if (!$links) return [];

        $items = [];
        $seen = [];

        foreach ($links as $a) {
            $href = $a->getAttribute('href');
            $abs = $this->absUrl($this->baseUrl(), $href);
            if (!$abs) continue;

            // Aqar listings often contain a numeric id at the end.
            if (!preg_match('/-([0-9]{5,})\/?$/', $abs)) {
                continue;
            }

            if (isset($seen[$abs])) continue;
            $seen[$abs] = true;

            $title = $this->cleanText($a->textContent);
            if (!$title || mb_strlen($title) < 5) {
                continue;
            }

            // Try to find a nearby price text.
            $priceText = null;
            $node = $a;
            for ($i = 0; $i < 4; $i++) {
                $node = $node->parentNode;
                if (!$node) break;
                $text = $this->cleanText($node->textContent);
                if ($text && (Str::contains($text, ['ر.س', 'ريال', 'SAR']) || preg_match('/\d{2,}/', $text))) {
                    $priceText = $text;
                    break;
                }
            }

            $price = $this->toIntPrice($priceText);

            if (($filters['min_price'] ?? null) !== null && $price !== null && $price < (int) $filters['min_price']) {
                continue;
            }
            if (($filters['max_price'] ?? null) !== null && $price !== null && $price > (int) $filters['max_price']) {
                continue;
            }

            $items[] = [
                'title' => $title,
                'price' => $price,
                'url' => $abs,
            ];

            if (count($items) >= 20) {
                break;
            }
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
