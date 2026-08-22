<?php

namespace App\Services\AI;

use App\Models\Product;
use App\Models\ProductRequest;
use Illuminate\Http\UploadedFile;

class ProductRequestAiService
{
    public function __construct(
        private SmartBrokerAiService $matcher,
        private StructuredAiService $ai
    ) {
    }

    public function extractFromText(string $description): array
    {
        $prompt = 'You extract product-request details from Arabic or English user text. Return only a JSON object with these keys and values in Arabic where relevant: type (product/category name string), city (string), neighborhood (string), min_price (number or null), max_price (number or null), rooms (integer or null), bathrooms (integer or null), area (number or null), purpose ("buy" / "rent" / "service" / null), budget (number or null), phone (string or null), notes (string). If a value is missing, use null. Keep the output strictly as JSON.';

        try {
            $result = $this->ai->json($prompt, ['text' => $description], 0.2);

            return $this->normalizeExtraction($result);
        } catch (\Throwable $e) {
            \Log::warning('Product request text extraction failed: '.$e->getMessage());
            return $this->emptyExtraction($description);
        }
    }

    public function extractFromImage(UploadedFile $file): array
    {
        $prompt = 'أنت محلل رسائل واتساب عقارية. اقرأ الصورة المرفقة واستخرج تفاصيل طلب العقار. أعد JSON بالمفاتيح: type (نوع العقار المطلوب مثل: شقة، فيلا، مستودع)، city (المدينة)، neighborhood (الحي)، min_price (أقل سعر رقمي)، max_price (أعلى سعر رقمي)، rooms (عدد الغرف)، bathrooms (عدد الحمامات)، area (المساحة)، purpose (buy أو rent أو service)، budget (الميزانية)، phone (رقم الجوال المذكور في الرسالة)، notes (أي ملاحظات). ضع null للقيمة غير الظاهرة.';

        try {
            $result = $this->ai->visionJson($prompt, $file);

            return $this->normalizeExtraction($result);
        } catch (\Throwable $e) {
            \Log::warning('Product request image extraction failed: '.$e->getMessage());
            return $this->emptyExtraction('');
        }
    }

    private function normalizeExtraction(array $data): array
    {
        $fields = ['type', 'city', 'neighborhood', 'min_price', 'max_price', 'rooms', 'bathrooms', 'area', 'purpose', 'budget', 'phone', 'notes'];
        $result = array_fill_keys($fields, null);

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $result[$field] = is_numeric($data[$field]) ? (float) $data[$field] : ($data[$field] === '' ? null : $data[$field]);
            }
        }

        foreach (['min_price', 'max_price', 'budget', 'area'] as $num) {
            if (isset($result[$num]) && !is_numeric($result[$num])) {
                $result[$num] = null;
            }
        }

        foreach (['rooms', 'bathrooms'] as $num) {
            if (isset($result[$num]) && is_numeric($result[$num])) {
                $result[$num] = (int) $result[$num];
            } else {
                $result[$num] = null;
            }
        }

        return $result;
    }

    private function emptyExtraction(string $notes): array
    {
        return [
            'type' => null,
            'city' => null,
            'neighborhood' => null,
            'min_price' => null,
            'max_price' => null,
            'rooms' => null,
            'bathrooms' => null,
            'area' => null,
            'purpose' => null,
            'budget' => null,
            'phone' => null,
            'notes' => $notes,
        ];
    }

    public function match(ProductRequest $request, int $limit = 5): array
    {
        $products = Product::query()
            ->with(['translations', 'category.translations', 'city', 'neighborhood', 'offers', 'attributes.translations'])
            ->where('is_active', true)
            ->latest('id')
            ->limit(150)
            ->get();

        $offers = $products->map(fn (Product $product) => $this->productLine($product))->implode("\n");
        $result = $this->matcher->matchTextLists($request->description, $offers, max(1, min(10, $limit)));
        $matches = collect($result['matches'][0] ?? [])->map(function (array $match) use ($products) {
            preg_match('/#(\d+)/', (string) ($match['offer'] ?? ''), $found);
            $product = $products->firstWhere('id', (int) ($found[1] ?? 0));

            if (! $product) {
                return null;
            }

            return [
                'product_id' => (int) $product->id,
                'title' => $product->getTranslatedTitle('ar') ?: $product->getTranslatedTitle(),
                'score' => (float) ($match['score'] ?? 0),
                'reason' => (string) ($match['reason'] ?? ''),
                'price' => $product->getLowestPrice(),
                'address' => (string) $product->address,
            ];
        })->filter()->values()->all();

        return [
            'matches' => $matches,
            'status' => $result['status'] ?? 'basic',
            'summary' => (string) ($result['ai_summary'] ?? ''),
        ];
    }

    public function suggestReply(ProductRequest $request, array $matches): string
    {
        return $this->ai->text(<<<'PROMPT'
اكتب رد واتساب عربي مهني ومختصر على طلب عميل عقاري. استخدم فقط بيانات الطلب والعقارات المطابقة المرسلة. لا تدّع توفر معلومة غير موجودة، ولا تعد العميل بسعر أو حجز. اذكر أفضل الخيارات المتاحة باختصار، ثم اطلب منه تأكيد الخيار المناسب أو توضيح المتطلبات. أعد نص الرسالة فقط.
PROMPT, [
            'customer_name' => $request->name,
            'request' => $request->description,
            'matches' => $matches,
        ], 0.4);
    }

    private function productLine(Product $product): string
    {
        $attributes = $product->attributes->take(12)->map(function ($attribute) {
            $name = $attribute->getTranslatedName('ar') ?: $attribute->key;
            return $name.': '.$attribute->pivot->value;
        })->implode('، ');

        return implode(' | ', array_filter([
            '#'.$product->id,
            $product->getTranslatedTitle('ar') ?: $product->getTranslatedTitle(),
            $product->category?->getTranslatedName('ar'),
            $product->city?->name,
            $product->neighborhood?->name,
            $product->address,
            $product->getLowestPrice() ? 'السعر '.$product->getLowestPrice() : null,
            $attributes,
        ]));
    }
}
