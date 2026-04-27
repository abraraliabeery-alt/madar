<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class BrowserService
{
    /**
     * استدعاء خدمة المتصفح الخارجية (Puppeteer/Playwright) لجلب محتوى صفحة ويب
     */
    public function fetchUrl(string $url): ?array
    {
        $baseUrl = config('services.browser_service.base_url');

        if (!$baseUrl) {
            return null;
        }

        try {
            $response = Http::timeout(35)->post(rtrim($baseUrl, '/').'/browse', [
                'url' => $url,
            ]);
        } catch (\Throwable $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        if (!is_array($data) || empty($data['ok'])) {
            return null;
        }

        return [
            'title'   => $data['title']   ?? null,
            'content' => $data['content'] ?? null,
            'url'     => $data['url']     ?? $url,
        ];
    }
}
