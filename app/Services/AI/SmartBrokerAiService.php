<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmartBrokerAiService
{
    public function matchTextLists(string $requestsText, string $offersText, int $topK = 3): array
    {
        $requests = $this->parseLines($requestsText, 20);
        $offers = $this->parseLines($offersText, 50);

        if (count($requests) === 0 || count($offers) === 0) {
            return [
                'status' => 'empty',
                'requests' => $requests,
                'offers' => $offers,
                'matches' => [],
            ];
        }

        $basic = $this->basicMatch($requests, $offers, $topK);

        $enabled = (bool) config('ai.enabled');
        $llmKey = config('ai.llm.api_key');
        $provider = config('ai.llm.provider');
        $model = config('ai.llm.model');

        if (!$enabled) {
            return $basic + ['status' => 'disabled'];
        }

        if (!$llmKey || $provider !== 'openai') {
            return $basic + ['status' => 'fallback'];
        }

        try {
            $timeout = (int) config('ai.cost.timeout_seconds', 45);
            $base = config('ai.llm.base_url', 'https://api.openai.com');

            $payload = $this->buildAiPayload($requests, $offers, $topK);

            $resp = Http::withToken($llmKey)
                ->timeout($timeout)
                ->post(rtrim($base, '/').'/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'أنت وسيط عقاري ذكي. مهمتك مطابقة طلبات عقارية مع عروض عقارية. اكتب بالعربية الفصحى، وبأسلوب مختصر ومهني. لا تستخدم JSON.'],
                        ['role' => 'user', 'content' => $payload],
                    ],
                    'temperature' => 0.25,
                ]);

            if (!$resp->ok()) {
                Log::warning('SmartBroker AI error', ['status' => $resp->status(), 'body' => $resp->body()]);
                return $basic + ['status' => 'fallback'];
            }

            $content = optional($resp->json('choices.0.message'))['content'] ?? null;
            if (!$content) {
                return $basic + ['status' => 'fallback'];
            }

            return $basic + [
                'status' => 'ok',
                'ai_summary' => trim($content),
            ];
        } catch (\Throwable $e) {
            Log::error('SmartBroker AI exception: '.$e->getMessage());
            return $basic + ['status' => 'error'];
        }
    }

    private function parseLines(string $text, int $maxLines): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $out = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $out[] = mb_substr($line, 0, 500);
            if (count($out) >= $maxLines) break;
        }
        return $out;
    }

    private function basicMatch(array $requests, array $offers, int $topK): array
    {
        $offerTokens = [];
        foreach ($offers as $i => $offer) {
            $offerTokens[$i] = $this->tokenize($offer);
        }

        $matches = [];
        foreach ($requests as $ri => $request) {
            $rt = $this->tokenize($request);
            $scored = [];
            foreach ($offers as $oi => $offer) {
                $score = $this->jaccard($rt, $offerTokens[$oi]);
                $scored[] = ['offer_index' => $oi, 'score' => $score];
            }
            usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
            $top = array_slice($scored, 0, $topK);
            $matches[$ri] = array_map(function ($item) use ($offers, $request) {
                $offerText = $offers[$item['offer_index']];
                return [
                    'offer' => $offerText,
                    'score' => round($item['score'] * 100, 1),
                    'reason' => $this->basicReason($request, $offerText),
                ];
            }, $top);
        }

        return [
            'status' => 'basic',
            'requests' => $requests,
            'offers' => $offers,
            'matches' => $matches,
        ];
    }

    private function tokenize(string $text): array
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
        $parts = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $stop = ['في','من','على','الى','إلى','عن','مع','هذا','هذه','ذلك','تلك','او','أو','و','ال','the','a','an','and','or','to','in','for'];
        $tokens = [];
        foreach ($parts as $p) {
            if (mb_strlen($p) < 2) continue;
            if (in_array($p, $stop, true)) continue;
            $tokens[] = $p;
        }
        return array_values(array_unique($tokens));
    }

    private function jaccard(array $a, array $b): float
    {
        if (!$a || !$b) return 0.0;
        $ia = array_intersect($a, $b);
        $ua = array_unique(array_merge($a, $b));
        return count($ua) ? (count($ia) / count($ua)) : 0.0;
    }

    private function basicReason(string $request, string $offer): string
    {
        $rt = $this->tokenize($request);
        $ot = $this->tokenize($offer);
        $common = array_slice(array_values(array_intersect($rt, $ot)), 0, 6);
        if (!$common) {
            return 'تمت المطابقة بناءً على تشابه عام في النص.';
        }
        return 'تطابق كلمات مشتركة: '.implode('، ', $common);
    }

    private function buildAiPayload(array $requests, array $offers, int $topK): string
    {
        $reqList = '';
        foreach ($requests as $i => $r) {
            $reqList .= ($i + 1).") طلب: ".$r."\n";
        }

        $offerList = '';
        foreach ($offers as $i => $o) {
            $offerList .= ($i + 1).") عرض: ".$o."\n";
        }

        return "لدي قائمتان:\n\nطلبات:\n{$reqList}\nعروض:\n{$offerList}\n\nالمطلوب:\n- لكل طلب: اختر أفضل {$topK} عروض مناسبة.\n- اذكر سبب قصير لكل اختيار.\n- أعط درجة ملاءمة من 0 إلى 100.\n\nاكتب الناتج بشكل منظم: لكل طلب عنوان ثم قائمة عروض.";
    }
}
