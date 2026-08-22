<?php

namespace App\Services\AI;

use Illuminate\Http\UploadedFile;

class ProductAiService
{
    public function __construct(private StructuredAiService $ai)
    {
    }
    /**
     * تحليل نص عقاري عربي واستخراج الحقول باستخدام OpenAI.
     * ترجع مصفوفة تحتوي: price, rooms, bathrooms, area, floor_number,
     * parking_spaces, city, neighborhood, type, title, address.
     */
    public function extractFromVoice(string $text): array
    {
        $decoded = $this->ai->json(<<<'PROMPT'
أنت محلل نصوص عقارية سعودية. استخرج فقط المعلومات المذكورة صراحة في النص ولا تستنتج أو تخترع أي قيمة.
أعد JSON بالمفاتيح: price, rooms, bathrooms, area, floor_number, parking_spaces, city, neighborhood, type, title, address, confidence, evidence.
القيم العددية تكون أرقامًا بدون فواصل. إذا لم تتوفر قيمة ضع null. confidence رقم بين 0 و1، وevidence كائن يربط كل حقل مستخدم بالمقتطف الأصلي الداعم له.
PROMPT, ['text' => $text], 0.1);

        $fields = ['price', 'rooms', 'bathrooms', 'area', 'floor_number', 'parking_spaces', 'city', 'neighborhood', 'type', 'title', 'address'];
        $result = array_fill_keys($fields, null);

        foreach ($fields as $field) {
            if (array_key_exists($field, $decoded)) {
                $result[$field] = $decoded[$field];
            }
        }

        foreach (['price', 'rooms', 'bathrooms', 'area', 'floor_number', 'parking_spaces'] as $field) {
            $result[$field] = is_numeric($result[$field]) ? (float) $result[$field] : null;
        }

        $result['confidence'] = max(0, min(1, (float) ($decoded['confidence'] ?? 0)));
        $result['evidence'] = is_array($decoded['evidence'] ?? null) ? $decoded['evidence'] : [];

        return $result;
    }

    public function extractFromDocument(UploadedFile $file): array
    {
        return $this->ai->visionJson(<<<'PROMPT'
أنت محلل مستندات وصور عقارية سعودية. اقرأ الصورة واستخرج فقط المعلومات الظاهرة بوضوح. لا تخمن النصوص غير المقروءة ولا تستنتج ملكية أو صلاحية نظامية.
أعد JSON بالمفاتيح: document_type, title, address, city, neighborhood, area, price, plot_number, plan_number, latitude, longitude, warnings, evidence.
ضع null للحقل غير الموجود. warnings مصفوفة تنبيهات، وevidence كائن يربط كل قيمة بالنص الظاهر الداعم لها. لا تُعد أي بيانات شخصية غير لازمة لإنشاء إعلان العقار.
PROMPT, $file);
    }

    /**
     * Generate a real-estate product description from structured fields.
     */
    public function generateDescription(array $productData): string
    {
        return $this->ai->text(<<<'PROMPT'
أنت كاتب محتوى عقاري محترف في السعودية. اكتب وصفًا عربيًا فصيحًا بين 80 و150 كلمة اعتمادًا حصريًا على البيانات المرسلة. لا تضف موقعًا أو ميزة أو رقمًا غير موجود. تجاهل القيم الفارغة، وابدأ بجملة واضحة تشمل نوع العقار وموقعه إن توفرا. أعد الوصف فقط.
PROMPT, $productData, 0.6);
    }

    public function generateMarketingContent(array $productData): array
    {
        $result = $this->ai->json(<<<'PROMPT'
أنت كاتب تسويق عقاري سعودي. أنشئ محتوى من البيانات المرسلة فقط، ولا تخترع أي معلومة أو ضمان أو عائد.
أعد JSON بالمفاتيح التالية:
title: عنوان إعلان مختصر.
description: وصف احترافي من 80 إلى 150 كلمة.
whatsapp: رسالة واتساب مختصرة مع دعوة للتواصل.
x: منشور مناسب لمنصة X بحد أقصى 260 حرفًا.
instagram: نص إنستغرام مختصر مع 3 إلى 6 وسوم مناسبة.
email_subject: عنوان بريد.
email_body: رسالة بريد مختصرة.
PROMPT, $productData, 0.5);

        $limits = [
            'title' => 120,
            'description' => 1200,
            'whatsapp' => 700,
            'x' => 280,
            'instagram' => 1000,
            'email_subject' => 150,
            'email_body' => 1500,
        ];
        $content = [];

        foreach ($limits as $key => $limit) {
            $content[$key] = mb_substr(trim((string) ($result[$key] ?? '')), 0, $limit);
        }

        return $content;
    }

    public function generateFromImage(UploadedFile $file): array
    {
        $result = $this->ai->visionJson(<<<'PROMPT'
أنت محلل صور عقارية سعودي. انظر للصورة المرفقة واكتب عنوانًا ووصفًا مناسبين لإعلان عقار.
لا تخترع معلومات غير ظاهرة في الصورة. لا تذكر السعر أو الموقع إلا إذا كان واضحًا فيها.
أعد JSON بالمفاتيح: title (عنوان مختصر لا يتجاوز 120 حرفًا)، description (وصف بين 80 و150 كلمة).
PROMPT, $file);

        return [
            'title' => mb_substr(trim((string) ($result['title'] ?? '')), 0, 120),
            'description' => mb_substr(trim((string) ($result['description'] ?? '')), 0, 1200),
        ];
    }
}
