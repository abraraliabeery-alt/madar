<?php

namespace App\Services\AI;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StructuredAiService
{
    public function json(string $systemPrompt, array $payload, float $temperature = 0.2): array
    {
        $content = $this->request($systemPrompt, $this->encode($payload), $temperature, true);
        $decoded = json_decode($content, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('AI response is not valid JSON.');
        }

        return $decoded;
    }

    public function text(string $systemPrompt, array $payload, float $temperature = 0.5): string
    {
        return trim($this->request($systemPrompt, $this->encode($payload), $temperature, false));
    }

    public function visionJson(string $systemPrompt, UploadedFile $file): array
    {
        $config = config('ai_chat.providers.openai') ?? [];
        $apiKey = Arr::get($config, 'api_key');
        $base = rtrim((string) Arr::get($config, 'base_url', ''), '/');
        $model = Arr::get($config, 'model');

        if (! $apiKey || $base === '' || ! $model) {
            throw new RuntimeException('OpenAI configuration is missing.');
        }

        $bytes = file_get_contents($file->getRealPath());
        if (! is_string($bytes) || $bytes === '') {
            throw new RuntimeException('Unable to read uploaded image.');
        }

        $client = Http::withToken($apiKey)
            ->timeout((int) config('ai.cost.timeout_seconds', 45))
            ->acceptJson();

        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        $content = $client->post($base.'/chat/completions', [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => [[
                    'type' => 'image_url',
                    'image_url' => ['url' => 'data:'.$file->getMimeType().';base64,'.base64_encode($bytes), 'detail' => 'high'],
                ]]],
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.1,
        ])->throw()->json('choices.0.message.content');

        $decoded = is_string($content) ? json_decode($content, true) : null;
        if (! is_array($decoded)) {
            throw new RuntimeException('AI response is not valid JSON.');
        }

        return $decoded;
    }

    private function request(string $systemPrompt, string $userPrompt, float $temperature, bool $json): string
    {
        $config = config('ai_chat.providers.openai') ?? [];
        $apiKey = Arr::get($config, 'api_key');
        $base = rtrim((string) Arr::get($config, 'base_url', ''), '/');
        $model = Arr::get($config, 'model');

        if (! $apiKey || $base === '' || ! $model) {
            throw new RuntimeException('OpenAI configuration is missing.');
        }

        $client = Http::withToken($apiKey)
            ->timeout((int) config('ai.cost.timeout_seconds', 45))
            ->acceptJson();

        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        $body = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => $temperature,
        ];

        if ($json) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        $content = $client->post($base.'/chat/completions', $body)
            ->throw()
            ->json('choices.0.message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new RuntimeException('Empty AI response.');
        }

        return $content;
    }

    private function encode(array $payload): string
    {
        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (! is_string($encoded)) {
            throw new RuntimeException('Unable to encode AI payload.');
        }

        return $encoded;
    }
}
