<?php

namespace App\Services\AI;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ChatGatewayService
{
    /**
     * Entry point used by controllers. Selects provider and dispatches.
     */
    public function chat(string $message, ?string $provider = null): string
    {
        // فلتر مبدئي بسيط للتأكد أن السؤال ضمن نطاق العقار والتطوير والمقاولات في السعودية
        if (!$this->isMessageAllowed($message)) {
            return 'هذا المساعد متخصص فقط في الاستثمار والتطوير العقاري في السعودية.';
        }

        $provider = $provider ?: config('ai_chat.default');

        // محاولة بناء سياق من الروابط باستخدام خدمات المتصفح وخرائط جوجل (إن وُجِدت)
        $browserContext = '';
        $urls = $this->extractUrls($message);
        if (!empty($urls)) {
            /** @var BrowserService $browser */
            $browser = app(BrowserService::class);

            $snippets = [];

            // لا نبالغ في عدد الروابط حتى لا يتأخر الرد
            foreach (array_slice($urls, 0, 2) as $url) {
                $mapsSummary = $this->buildGoogleMapsSummary($url);

                $result = $browser->fetchUrl($url);

                $title = $result['title'] ?? '';
                $content = $result['content'] ?? '';

                // إذا لم يرجع المتصفح محتوى، نعتمد على ملخص خرائط جوجل إن وُجد
                if (empty($content) && $mapsSummary === null) {
                    continue;
                }

                $snippet = "رابط: {$url}";

                if ($mapsSummary !== null) {
                    $snippet .= "\nبيانات من خرائط قوقل:\n".$mapsSummary;
                }

                if ($title !== '') {
                    $snippet .= "\nالعنوان من الصفحة: {$title}";
                }
                if ($content !== '') {
                    // تقليص النص حتى لا يصبح البرومبت ضخمًا جدًا
                    $snippet .= "\nمقتطف من محتوى الصفحة:\n".
                        mb_substr($content, 0, 2000, 'UTF-8');
                }

                $snippets[] = $snippet;
            }

            if (!empty($snippets)) {
                $browserContext = "معلومات مستخرجة من الروابط المذكورة في سؤالك:\n\n".
                    implode("\n\n", $snippets);
            }
        }

        // إزالة الروابط من نص رسالة المستخدم قبل إرسالها للنموذج
        $cleanedMessage = $this->removeUrlsFromMessage($message, $urls);

        return match ($provider) {
            'openai'    => $this->chatWithOpenAI($cleanedMessage, $browserContext),
            'gemini'    => $this->chatWithGemini($cleanedMessage),
            'anthropic' => $this->chatWithAnthropic($cleanedMessage),
            // أي قيمة غير معروفة ترجع إلى OpenAI بدل جيميني
            default     => $this->chatWithOpenAI($cleanedMessage, $browserContext),
        };
    }

    /**
     * Shared system prompt specialized for Saudi real-estate investment.
     */
    protected function systemPrompt(): string
    {
        return <<<PROMPT
أنت مساعد ذكاء اصطناعي متخصص بشكل أساسي في:
- الاستثمار العقاري في السعودية
- تطوير الأراضي والمخططات
- المقاولات والبناء
- التحليل السوقي السعودي
- القوانين والأنظمة العقارية السعودية

المراجع الرسمية المعتمدة:
1. لائحة المكاتب العقارية – وزارة الشؤون البلدية والقروية والإسكان.
2. نظام التسجيل العيني للعقار – هيئة عقارات الدولة.
3. نظام ملكية الوحدات وفرزها – وزارة العدل.
4. كود البناء السعودي SBC.
5. اشتراطات البناء في الرياض.
6. المعايير السعرية واتجاهات السوق السعودي (2024–2025).

القواعد:
- ركّز إجاباتك على (العقار + المقاولات + التطوير + الاستثمار في السعودية) قدر الإمكان.
- إذا كان سؤال المستخدم بعيداً تماماً عن هذه المواضيع، فاشرح له باختصار أن نطاق عملك هو الاستثمار والتطوير العقاري في السعودية، ثم اقترح عليه كيف يعيد صياغة سؤاله ليصبح مناسباً لهذا النطاق.
- يتم تزويدك دائماً بالنصوص والمقتطفات المستخرجة من الروابط (مثل خرائط قوقل أو المواقع الأخرى) داخل المحادثة، لذلك لا تقل أبداً عبارات مثل: "لا أستطيع فتح الروابط" أو "لا أستطيع الوصول للمواقع"، بل استخدم المحتوى النصي المقدم لك لتحليل الموقع أو المصدر.
- لا تقدم نصائح شخصية أو اجتماعية أو طبية.
- استخدم لغة عربية رسمية ومختصرة.
- اعتمد على المراجع أعلاه قدر الإمكان، وإذا لم تتوفر المعلومة قل:
  "لا توجد بيانات مؤكدة ضمن الأنظمة الرسمية السعودية."، ولا تخترع أنظمة أو أرقام من عندك.
PROMPT;
    }

    protected function chatWithOpenAI(string $message, string $browserContext = ''): string
    {
        $config = config('ai_chat.providers.openai');
        $apiKey = Arr::get($config, 'api_key');
        $base  = rtrim(Arr::get($config, 'base_url', ''), '/');
        $model = Arr::get($config, 'model');

        if (!$apiKey || !$base || !$model) {
            throw new RuntimeException('OpenAI configuration is missing.');
        }

        $client = Http::withToken($apiKey);

        // في بيئة التطوير المحلية قد يكون إعداد شهادات SSL على ويندوز غير مكتمل
        // لذلك نعطّل التحقق من الشهادة فقط على local لتفادي cURL error 77
        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        // دمج معرفة بسيطة من ملفات التخزين (knowledge base) إن وجدت
        $context = $this->searchKnowledge($message);

        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt()],
        ];

        if ($context !== '') {
            $messages[] = [
                'role' => 'assistant',
                'content' => "معلومات مرجعية:\n".$context,
            ];
        }

        if ($browserContext !== '') {
            $messages[] = [
                'role' => 'assistant',
                'content' => $browserContext,
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = $client->post($base.'/chat/completions', [
            'model' => $model,
            'messages' => $messages,
        ])->throw();

        $data = $response->json();
        return Arr::get($data, 'choices.0.message.content', 'لم يصل رد من مزود OpenAI.');
    }

    /**
     * استخراج الروابط من نص رسالة المستخدم.
     */
    protected function extractUrls(string $text): array
    {
        $pattern = '/https?:\/\/[\w\-\.\?\,\'\/\\\+&%\$#_=:@]+/iu';
        if (!preg_match_all($pattern, $text, $matches)) {
            return [];
        }

        $urls = array_unique($matches[0] ?? []);

        return array_values($urls);
    }

    /**
     * إزالة الروابط من نص رسالة المستخدم، مع تعويض بسيط إذا أصبح النص فارغًا.
     */
    protected function removeUrlsFromMessage(string $message, array $urls): string
    {
        if (empty($urls)) {
            return $message;
        }

        $cleaned = $message;
        foreach ($urls as $url) {
            $cleaned = str_replace($url, '', $cleaned);
        }

        $cleaned = trim($cleaned);

        if ($cleaned === '') {
            $cleaned = 'حلّل البيانات والمعلومات الواردة أعلاه عن الموقع، وقدّم أفضل تحليل وفرص استثمارية مناسبة لهذا العقار في السعودية.';
        }

        return $cleaned;
    }

    /**
     * بناء ملخص نصي لموقع من رابط خرائط جوجل (إن أمكن).
     */
    protected function buildGoogleMapsSummary(string $url): ?string
    {
        if (!str_contains($url, 'maps.app.goo.gl') && !str_contains($url, 'google.com/maps')) {
            return null;
        }

        try {
            /** @var \App\Services\Maps\GoogleMapsService $maps */
            $maps = app(\App\Services\Maps\GoogleMapsService::class);
        } catch (\Throwable $e) {
            return null;
        }

        $coords = $maps->extractLatLngFromUrl($url);
        if (!$coords) {
            return null;
        }

        $reverse = $maps->reverseGeocode($coords['lat'], $coords['lng']);
        if (!$reverse) {
            return null;
        }

        $address = $reverse['formatted_address'] ?? '';

        $summary = 'الإحداثيات التقريبية للموقع: '.$coords['lat'].', '.$coords['lng'];
        if ($address !== '') {
            $summary .= "\nالعنوان بحسب خرائط قوقل: ".$address;
        }

        return $summary;
    }

    /**
     * Simple keyword-based filter to ensure messages are within Saudi real-estate scope.
     */
    protected function isMessageAllowed(string $message): bool
    {
        $text = mb_strtolower($message, 'UTF-8');

        // السماح مباشرة إذا كان هناك رابط خرائط أو تعبير عن مساحة
        if (str_contains($text, 'maps.app.goo.gl') ||
            str_contains($text, 'google.com/maps') ||
            str_contains($text, 'متر مربع') ||
            str_contains($text, 'م²') ||
            str_contains($text, 'مساحة ')
        ) {
            return true;
        }

        $allowed = [
            'عقار', 'عقاري', 'العقار', 'العقاري',
            'أرض', 'ارض', 'الأرض', 'الارض', 'أراضي', 'اراضي', 'الاراضي',
            'حي', 'احياء', 'سعر', 'أسعار', 'اسعار',
            'استثمار', 'استثماري', 'استثمار عقاري', 'التطوير العقاري',
            'مقاول', 'مقاولات', 'مقاولين',
            'تطوير', 'تطوير عقاري', 'تطوير الأراضي',
            'بناء', 'عمارة', 'فلة', 'فيلا', 'شقة', 'وحدات سكنية',
            'تمويل', 'رهن', 'إفراغ', 'افراغ',
            'مخطط', 'مخططات', 'قطع', 'قطعة أرض', 'وحدة', 'ملكية',
            'كود البناء', 'كود البناء السعودي', 'بلدية', 'اشتراطات البناء',
        ];

        foreach ($allowed as $word) {
            if ($word !== '' && str_contains($text, mb_strtolower($word, 'UTF-8'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Naive knowledge search across files in storage/app/ai-knowledge.
     * This is intentionally simple and can be replaced later with a proper index.
     */
    protected function searchKnowledge(string $query): string
    {
        if (!config('filesystems.default')) {
            return '';
        }

        $path = 'ai-knowledge';

        if (!Storage::exists($path)) {
            return '';
        }

        $files = Storage::files($path);
        $matched = '';
        $lowerQuery = mb_strtolower($query, 'UTF-8');

        foreach ($files as $file) {
            try {
                $content = Storage::get($file);
            } catch (\Throwable $e) {
                continue;
            }

            $lowerContent = mb_strtolower($content, 'UTF-8');

            if (str_contains($lowerContent, $lowerQuery)) {
                $matched .= "\n".$content;
            }
        }

        return trim($matched);
    }

    protected function chatWithGemini(string $message): string
    {
        $config = config('ai_chat.providers.gemini');
        $apiKey = Arr::get($config, 'api_key');
        $base  = rtrim(Arr::get($config, 'base_url', ''), '/');
        $model = Arr::get($config, 'model');

        if (!$apiKey || !$base || !$model) {
            throw new RuntimeException('Gemini configuration is missing.');
        }

        $url = $base.'/models/'.$model.':generateContent?key='.$apiKey;

        $payload = [
            'systemInstruction' => [
                'parts' => [['text' => $this->systemPrompt()]],
            ],
            'contents' => [[
                'parts' => [['text' => $message]],
            ]],
        ];

        $response = Http::post($url, $payload)->throw();
        $data = $response->json();

        return Arr::get($data, 'candidates.0.content.parts.0.text', 'لم يصل رد من مزود Gemini.');
    }

    protected function chatWithAnthropic(string $message): string
    {
        $config = config('ai_chat.providers.anthropic');
        $apiKey = Arr::get($config, 'api_key');
        $base  = rtrim(Arr::get($config, 'base_url', ''), '/');
        $model = Arr::get($config, 'model');

        if (!$apiKey || !$base || !$model) {
            throw new RuntimeException('Anthropic configuration is missing.');
        }

        $response = Http::withHeaders([
                'x-api-key'      => $apiKey,
                'anthropic-version' => '2023-06-01',
            ])->post($base.'/messages', [
                'model' => $model,
                'max_tokens' => 800,
                'system' => $this->systemPrompt(),
                'messages' => [[
                    'role' => 'user',
                    'content' => $message,
                ]],
            ])->throw();

        $data = $response->json();
        return Arr::get($data, 'content.0.text', 'لم يصل رد من مزود Anthropic.');
    }
}
