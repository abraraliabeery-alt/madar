<?php

namespace App\Services\AI;

use App\Models\LandStudy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LandStudyService
{
    public function generate(LandStudy $study): LandStudy
    {
        $inputs = $study->inputs ?? [];

        $enabled = config('ai.enabled');
        if (!$enabled) {
            $study->status = 'disabled';
            $study->report = 'الميزة غير مفعلة حالياً.';
            $study->save();
            return $study;
        }

        try {
            $llmKey = config('ai.llm.api_key');
            $provider = config('ai.llm.provider');
            $model = config('ai.llm.model');

            $report = null;
            $scenarios = null;
            $images = null;

            if ($llmKey && $provider === 'openai') {
                [$report, $scenarios] = $this->generateWithOpenAI($inputs, $model, $llmKey);
            }

            if (!$report) {
                $report = $this->buildFallbackReport($inputs);
            }
            if (!$scenarios) {
                $scenarios = $this->buildFallbackScenarios($inputs);
            }

            $images = $this->maybeGenerateImages($scenarios);

            $study->report = $report;
            $study->scenarios = $scenarios;
            $study->images = $images;
            $study->status = 'completed';
            $study->cost_usd = 0.00;
            $study->save();
        } catch (\Throwable $e) {
            Log::error('LandStudy generation failed: '.$e->getMessage(), [
                'study_id' => $study->id,
            ]);
            $study->status = 'failed';
            $study->error = $e->getMessage();
            $study->save();
        }

        return $study;
    }

    private function generateWithOpenAI(array $inputs, string $model, string $apiKey): array
    {
        try {
            $timeout = (int) config('ai.cost.timeout_seconds', 45);
            $base = config('ai.llm.base_url', 'https://api.openai.com');
            $prompt = $this->buildPrompt($inputs);

            $resp = Http::withToken($apiKey)
                ->timeout($timeout)
                ->post(rtrim($base, '/').'/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'أنت خبير تطوير مشاريعي. اكتب بالعربية الفصحى وباقتضاب مهني.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.3,
                ]);

            if (!$resp->ok()) {
                Log::warning('OpenAI chat error', ['status' => $resp->status(), 'body' => $resp->body()]);
                return [null, null];
            }

            $content = optional($resp->json('choices.0.message'))['content'] ?? null;
            if (!$content) return [null, null];

            // Try to split report and scenarios if JSON present; otherwise heuristic
            [$report, $scenarios] = $this->parseModelOutput($content);
            return [$report, $scenarios];
        } catch (\Throwable $e) {
            Log::error('OpenAI chat exception: '.$e->getMessage());
            return [null, null];
        }
    }

    private function buildPrompt(array $inputs): string
    {
        $location = $inputs['location'] ?? '';
        $area = $inputs['area_sqm'] ?? '';
        $zoning = $inputs['zoning'] ?? '';
        $street = $inputs['street_width'] ?? '';
        $budget = $inputs['budget'] ?? '';
        $horizon = $inputs['horizon'] ?? '';
        $prefs = $inputs['preferences'] ?? '';

        return "مدخلات الأرض:\n- الموقع: {$location}\n- المساحة م²: {$area}\n- التصنيف: {$zoning}\n- عرض الشارع: {$street}\n- الميزانية: {$budget}\n- الأفق الزمني: {$horizon}\n- التفضيلات: {$prefs}\n\nأنتج تقريراً مهنياً مختصراً يتضمن:\n1) فقرة تحليل الموقع والسوق.\n2) ثلاث سيناريوهات استثمارية (عنوان، ملاحظات، تقدير CAPEX تقريبي، عائد/ROI تقريبي).\nأعد النتيجة بصيغة JSON بالمفتاحين: report (نص) و scenarios (مصفوفة عناصر بكل عنصر: title, notes, capex_estimate, roi).";
    }

    private function parseModelOutput(string $content): array
    {
        // Try JSON first
        $report = null; $scenarios = null;
        $json = null;
        try { $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR); } catch (\Throwable $e) { $json = null; }
        if (is_array($json)) {
            $report = $json['report'] ?? null;
            $scenarios = $json['scenarios'] ?? null;
        }
        if (!$report) { $report = trim($content); }
        if (!$scenarios || !is_array($scenarios)) { $scenarios = $this->buildFallbackScenarios([]); }
        return [$report, $scenarios];
    }

    private function maybeGenerateImages(array $scenarios): array
    {
        try {
            $provider = config('ai.images.provider');
            $size = config('ai.images.size', '1024x1024');
            $openaiKey = config('ai.images.openai_api_key');
            $stabilityKey = config('ai.images.api_key');

            if ($provider === 'openai' && $openaiKey) {
                return $this->generateImagesOpenAI($scenarios, $openaiKey, $size);
            }
        } catch (\Throwable $e) {
            Log::warning('Image generation failed, using fallbacks: '.$e->getMessage());
        }
        return $this->buildFallbackImages($scenarios);
    }

    private function generateImagesOpenAI(array $scenarios, string $apiKey, string $size): array
    {
        $base = config('ai.llm.base_url', 'https://api.openai.com');
        $timeout = (int) config('ai.cost.timeout_seconds', 45);
        $result = [];
        foreach ($scenarios as $i => $sc) {
            $prompt = 'تصميم مفاهيمي خارجي لـ: '.($sc['title'] ?? 'اقتراح').'. أسلوب واقعي بسيط.';
            try {
                $resp = Http::withToken($apiKey)
                    ->timeout($timeout)
                    ->post(rtrim($base, '/').'/v1/images/generations', [
                        'prompt' => $prompt,
                        'n' => 1,
                        'size' => $size,
                    ]);
                if ($resp->ok()) {
                    $url = $resp->json('data.0.url');
                    $result[] = [
                        'scenario' => $sc['title'] ?? ('سيناريو '.($i+1)),
                        'urls' => $url ? [$url] : [],
                    ];
                    continue;
                }
            } catch (\Throwable $e) {
                Log::warning('OpenAI image error: '.$e->getMessage());
            }
            // fallback for this scenario
            $result[] = [
                'scenario' => $sc['title'] ?? ('سيناريو '.($i+1)),
                'urls' => [
                    'https://placehold.co/1024x768?text='.urlencode($sc['title'] ?? 'Design')
                ],
            ];
        }
        return $result;
    }

    private function buildFallbackReport(array $inputs): string
    {
        $location = $inputs['location'] ?? 'غير محدد';
        $area = $inputs['area_sqm'] ?? 'غير محددة';
        $zoning = $inputs['zoning'] ?? 'غير محدد';
        $budget = $inputs['budget'] ?? 'غير محددة';

        return "تقرير مبدئي آلي\n\nالموقع: {$location}\nالمساحة: {$area} م²\nالتصنيف: {$zoning}\nالميزانية: {$budget}\n\nتحليل موجز: الموقع يبدو مناسباً لتطوير متعدد الاستخدامات وفقاً للمعايير العامة. يرجى تفعيل مزود الذكاء الاصطناعي لإخراج أكثر دقة.";
    }

    private function buildFallbackScenarios(array $inputs): array
    {
        return [
            [
                'title' => 'سيناريو سكني منخفض الارتفاع',
                'capex_estimate' => 'تقريبي',
                'roi' => 'متوسط',
                'notes' => 'بناء شقق 3-5 طوابق مع محلات أرضية إن أمكن.'
            ],
            [
                'title' => 'سيناريو تجاري مكتبي',
                'capex_estimate' => 'تقريبي',
                'roi' => 'متفاوت',
                'notes' => 'مكاتب صغيرة مع مساحات مشتركة وتجزئة مرنة.'
            ],
            [
                'title' => 'سيناريو مختلط',
                'capex_estimate' => 'تقريبي',
                'roi' => 'جيد',
                'notes' => 'كتل سكنية فوق واجهات تجارية لتقليل المخاطر.'
            ],
        ];
    }

    private function buildFallbackImages(array $scenarios): array
    {
        $images = [];
        foreach ($scenarios as $i => $sc) {
            $images[] = [
                'scenario' => $sc['title'] ?? ('سيناريو '.($i+1)),
                'urls' => [
                    'https://placehold.co/1024x768?text='.urlencode($sc['title'] ?? 'Design')
                ],
            ];
        }
        return $images;
    }
}
