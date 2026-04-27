<?php

namespace App\Services\AI;

use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectStageAiService
{
    public function analyze(Project $project, string $stageKey, string $input): array
    {
        $enabled = config('ai.enabled');
        if (!$enabled) {
            return [
                'status' => 'disabled',
                'content' => 'ميزة الذكاء الاصطناعي غير مفعّلة حالياً في الإعدادات.',
            ];
        }

        $llmKey = config('ai.llm.api_key');
        $provider = config('ai.llm.provider');
        $model = config('ai.llm.model');

        if (!$llmKey || $provider !== 'openai') {
            return [
                'status' => 'fallback',
                'content' => $this->buildFallback($project, $stageKey, $input),
            ];
        }

        try {
            $timeout = (int) config('ai.cost.timeout_seconds', 45);
            $base = config('ai.llm.base_url', 'https://api.openai.com');
            $prompt = $this->buildPrompt($project, $stageKey, $input);

            $resp = Http::withToken($llmKey)
                ->timeout($timeout)
                ->post(rtrim($base, '/').'/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'أنت مستشار تطوير عقاري خبير. اكتب بالعربية الفصحى، وبأسلوب مختصر وعميق ومهني، في نقاط وعناوين فرعية واضحة.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.35,
                ]);

            if (!$resp->ok()) {
                Log::warning('ProjectStage AI error', ['status' => $resp->status(), 'body' => $resp->body()]);
                return [
                    'status' => 'fallback',
                    'content' => $this->buildFallback($project, $stageKey, $input),
                ];
            }

            $content = optional($resp->json('choices.0.message'))['content'] ?? null;
            if (!$content) {
                return [
                    'status' => 'fallback',
                    'content' => $this->buildFallback($project, $stageKey, $input),
                ];
            }

            return [
                'status' => 'ok',
                'content' => trim($content),
            ];
        } catch (\Throwable $e) {
            Log::error('ProjectStage AI exception: '.$e->getMessage(), [
                'project_id' => $project->id,
                'stage' => $stageKey,
            ]);

            return [
                'status' => 'error',
                'content' => $this->buildFallback($project, $stageKey, $input),
            ];
        }
    }

    private function buildPrompt(Project $project, string $stageKey, string $input): string
    {
        $stageLabel = $stageKey === 'feasibility' ? 'دراسة جدوى' : ($stageKey === 'design' ? 'التصميم' : $stageKey);

        $facilityId = $project->facility_id;
        $type = $project->project_type ?: 'غير محدد';
        $lat = $project->latitude ?: 'غير محدد';
        $lng = $project->longitude ?: 'غير محدد';

        $title = optional($project->translations->firstWhere('locale', app()->getLocale()))->name ?? ('مشروع رقم #'.$project->id);

        $userNotes = trim($input) ?: 'لا توجد ملاحظات إضافية من المستخدم، اعتمد على أفضل الممارسات العامة.';

        return "مشروع تطوير عقاري:
- اسم المشروع: {$title}
- رقم المشروع الداخلي: {$project->id}
- نوع المشروع: {$type}
- موقع تقريبي (إحداثيات): {$lat}, {$lng}
- معرف المنشأة المالكة: {$facilityId}

نريد مساعدتك في مرحلة {$stageLabel} ضمن دورة حياة المشروع.

ملاحظات وقيود من المستخدم (إن وجدت):
{$userNotes}

أعد مخرجاتك بالعربية الفصحى في أقسام واضحة مع عناوين فرعية ونقاط، بدون استخدام JSON أو كود. ركّز على:
- فرضيات أساسية مختصرة للمشروع
- توصيات عملية قابلة للتنفيذ
- مخاطر رئيسية مختصرة وكيفية التعامل معها
- اقتراحات لتحسين قيمة المشروع أو كفاءة التصميم.";
    }

    private function buildFallback(Project $project, string $stageKey, string $input): string
    {
        $stageLabel = $stageKey === 'feasibility' ? 'دراسة الجدوى' : ($stageKey === 'design' ? 'التصميم' : $stageKey);

        return "تقرير مبدئي تلقائي لمرحلة {$stageLabel}:

- لم يتم تفعيل مزود الذكاء الاصطناعي أو تعذَّر الاتصال به حالياً.
- يمكنك استخدام هذه المساحة لتوثيق ملاحظاتك الأساسية حول هذه المرحلة.
- يُنصح لاحقاً بتفعيل مزود الذكاء الاصطناعي من الإعدادات للحصول على تحليل أعمق وتوصيات أكثر دقة.

ملاحظة المستخدم المدخلة:
".($input ?: 'لم يتم إدخال تفاصيل إضافية.');
    }
}
