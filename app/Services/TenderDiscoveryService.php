<?php

namespace App\Services;

use App\Models\TenderDiscovery;
use App\Models\TenderSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class TenderDiscoveryService
{
    public function discover(TenderSource $source): int
    {
        $url = $source->list_url ?: $source->base_url;

        if (! $url) {
            throw new RuntimeException('Source has no list_url or base_url.');
        }

        $html = $this->fetch($url);

        $config = $source->config ?? [];
        $itemSelector = $config['item_xpath'] ?? '//article';
        $titleXpath = $config['title_xpath'] ?? './/h2';
        $referenceXpath = $config['reference_xpath'] ?? null;
        $urlXpath = $config['url_xpath'] ?? './/a/@href';
        $closingDateXpath = $config['closing_date_xpath'] ?? null;
        $descriptionXpath = $config['description_xpath'] ?? null;

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        $items = $xpath->query($itemSelector);

        $created = 0;

        foreach ($items as $item) {
            $title = $this->nodeValue($xpath, $titleXpath, $item);
            if ($title === '') {
                continue;
            }

            $reference = $referenceXpath ? $this->nodeValue($xpath, $referenceXpath, $item) : null;
            $itemUrl = $this->resolveUrl($source->base_url, $this->nodeValue($xpath, $urlXpath, $item));
            $closingDateText = $closingDateXpath ? $this->nodeValue($xpath, $closingDateXpath, $item) : null;
            $description = $descriptionXpath ? $this->nodeValue($xpath, $descriptionXpath, $item) : null;

            $closingDate = $this->parseDate($closingDateText);

            TenderDiscovery::updateOrCreate(
                [
                    'source_id' => $source->id,
                    'reference' => $reference ?: $itemUrl,
                ],
                [
                    'title' => $title,
                    'url' => $itemUrl,
                    'closing_date' => $closingDate,
                    'description' => $description,
                    'raw_data' => [
                        'closing_text' => $closingDateText,
                        'fetched_at' => now()->toDateTimeString(),
                    ],
                ]
            );

            $created++;
        }

        $source->update(['last_crawled_at' => now()]);

        return $created;
    }

    private function fetch(string $url): string
    {
        $timeout = (int) config('web_fetch.timeout_seconds', 30);

        $response = Http::timeout($timeout)
            ->withHeaders([
                'User-Agent' => 'AqarTenderBot/1.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])
            ->get($url);

        if (! $response->ok()) {
            throw new RuntimeException('Failed to fetch source: HTTP '.$response->status());
        }

        return (string) $response->body();
    }

    private function nodeValue(\DOMXPath $xpath, string $expression, \DOMNode $context): string
    {
        $node = $xpath->query($expression, $context)->item(0);

        if (! $node) {
            return '';
        }

        return trim($node->textContent);
    }

    private function resolveUrl(string $base, string $path): ?string
    {
        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }

    private function parseDate(?string $text): ?Carbon
    {
        if (! $text) {
            return null;
        }

        $normalized = str_replace(['/', '–'], '-', $text);
        $normalized = preg_replace('/[^0-9\-:]/u', '', $normalized);

        try {
            return Carbon::parse($normalized);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
