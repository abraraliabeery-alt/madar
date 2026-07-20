<?php

namespace App\Services\Ads\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

abstract class BaseProvider implements ProviderInterface
{
    protected function httpGet(string $url): ?string
    {
        $timeout = (int) config('web_fetch.timeout_seconds', 15);

        try {
            $resp = Http::timeout($timeout)
                ->withHeaders([
                    'User-Agent' => 'MadarAds/1.0 (+https://madar.local)',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'ar,en;q=0.8',
                ])
                ->get($url);

            if (!$resp->ok()) {
                return null;
            }

            return (string) $resp->body();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function absUrl(string $baseUrl, ?string $href): ?string
    {
        if (!is_string($href) || trim($href) === '') {
            return null;
        }

        $href = trim($href);
        if (Str::startsWith($href, ['http://', 'https://'])) {
            return $href;
        }

        $baseUrl = rtrim($baseUrl, '/');

        if (Str::startsWith($href, '/')) {
            return $baseUrl . $href;
        }

        return $baseUrl . '/' . $href;
    }

    protected function toIntPrice(?string $text): ?int
    {
        if (!is_string($text)) return null;

        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(Str::squish($text));
        if ($text === '') return null;

        $digits = preg_replace('/[^0-9]/', '', $text);
        if (!is_string($digits) || $digits === '') return null;

        $v = (int) $digits;
        if ($v <= 0) return null;

        return $v;
    }

    protected function cleanText(?string $text): ?string
    {
        if (!is_string($text)) return null;
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(Str::squish($text));
        return $text !== '' ? $text : null;
    }
}
