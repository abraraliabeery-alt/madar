<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UnifonicSmsService
{
    public function isConfigured(): bool
    {
        return (bool) config('services.unifonic.api_key')
            && (bool) config('services.unifonic.app_sid')
            && (bool) config('services.unifonic.sender_id');
    }

    public function send(string $phoneNumber, string $message): array
    {
        $baseUrl = rtrim((string) config('services.unifonic.base_url'), '/');
        $path = (string) config('services.unifonic.send_path', '/rest/Messages/Send');
        $timeout = (int) config('services.unifonic.timeout', 15);

        $url = $baseUrl . $path;

        $response = Http::timeout($timeout)
            ->asForm()
            ->post($url, [
                'AppSid' => config('services.unifonic.app_sid'),
                'SenderID' => config('services.unifonic.sender_id'),
                'Recipient' => $phoneNumber,
                'Body' => $message,
                'responseType' => 'JSON',
            ]);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return [
            'ok' => true,
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }
}
