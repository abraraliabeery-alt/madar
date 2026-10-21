<?php

namespace App\Services;

use App\Services\AI\StructuredAiService;
use Illuminate\Support\Arr;

class QuotePricingService
{
    public function __construct(private StructuredAiService $ai)
    {
    }

    public function canSuggest(): bool
    {
        $config = config('ai_chat.providers.openai') ?? [];

        return ! empty(Arr::get($config, 'api_key'))
            && ! empty(Arr::get($config, 'model'));
    }

    public function suggest(string $itemName, ?string $unit = null): ?float
    {
        if (! $this->canSuggest()) {
            return null;
        }

        $system = 'أنت خبير تسعير في قطاع المقاولات والبناء. ' .
            'بناءً على اسم البند والوحدة، قدّر سعر الوحدة بالريال السعودي. ' .
            'أرجع JSON فقط بالشكل: {"price": number, "currency": "SAR"}. ' .
            'لا تضف أي نص خارج JSON.';

        $result = $this->ai->json($system, [
            'name' => $itemName,
            'unit' => $unit ?? 'وحدة',
        ]);

        $price = $result['price'] ?? null;

        return is_numeric($price) ? (float) $price : null;
    }
}
