<?php

namespace App\Services\AI;

use App\Models\Category;
use App\Models\Product;
use App\Services\PdfSettingsService;
use Illuminate\Support\Collection;

class PdfAiService
{
    public function __construct(
        private StructuredAiService $ai,
        private PdfSettingsService $pdfSettings
    ) {
    }

    public function suggestCategorySlides(Category $category): array
    {
        $category->loadMissing(['translations', 'attributes.translations']);
        $attributes = $category->attributes
            ->where('is_active', true)
            ->map(fn ($attribute) => [
                'id' => (int) $attribute->id,
                'key' => (string) $attribute->key,
                'name' => (string) ($attribute->getTranslatedName('ar') ?: $attribute->key),
                'type' => (string) $attribute->type,
                'required' => (bool) $attribute->required,
            ])
            ->values();

        if ($attributes->isEmpty()) {
            return ['groups' => [], 'rationale' => 'لا توجد خصائص مفعلة لهذه الفئة.'];
        }

        $result = $this->ai->json(<<<'PROMPT'
أنت منظم عروض عقارية احترافية في السعودية. اقترح شرائح خصائص مناسبة للفئة المعطاة.
أعد JSON فقط بالشكل: {"groups":[{"name":"عنوان عربي مختصر","attributes":[1,2]}],"rationale":"سبب مختصر"}.
استخدم فقط IDs المرسلة، ولا تضف أو تكرر أي ID. ضع كل خاصية في شريحة واحدة، ورتب الشرائح والخصائص حسب أهميتها للمشتري. اجعل عدد الشرائح بين 1 و6، وكل شريحة بحد أقصى 8 خصائص.
PROMPT, [
            'category' => $category->getTranslatedName('ar') ?: $category->name,
            'attributes' => $attributes->all(),
        ], 0.2);

        return $this->normalizeGroups($result, $attributes);
    }

    public function suggestPalette(string $direction = 'luxury'): array
    {
        $current = $this->pdfSettings->load();
        $result = $this->ai->json(<<<'PROMPT'
أنت مصمم هوية بصرية لعروض عقارية سعودية. اقترح لوحة ألوان هادئة واحترافية مناسبة للاتجاه المطلوب.
أعد JSON فقط بالمفاتيح: brand_color, accent_color, bg_color, title_color, text_color, dark_bg, dark_card, dark_title, dark_text, rationale.
كل لون يجب أن يكون HEX من 6 خانات. اجعل تباين النص واضحًا للطباعة والشاشات، وتجنب الألوان الصارخة.
PROMPT, [
            'direction' => $direction,
            'current_style' => $current['style'] ?? [],
        ], 0.3);

        $fallback = [
            'brand_color' => '#126B61', 'accent_color' => '#7C3AED', 'bg_color' => '#FFFFFF',
            'title_color' => '#111827', 'text_color' => '#374151', 'dark_bg' => '#0F172A',
            'dark_card' => '#111827', 'dark_title' => '#F8FAFC', 'dark_text' => '#E5E7EB',
        ];
        $palette = [];
        foreach ($fallback as $key => $default) {
            $value = strtoupper(trim((string) ($result[$key] ?? '')));
            $palette[$key] = preg_match('/^#[0-9A-F]{6}$/', $value) ? $value : $default;
        }

        if ($this->contrast($palette['text_color'], $palette['bg_color']) < 4.5) {
            $palette['text_color'] = $this->luminance($palette['bg_color']) > 0.5 ? '#111827' : '#F8FAFC';
        }
        if ($this->contrast($palette['title_color'], $palette['bg_color']) < 4.5) {
            $palette['title_color'] = $palette['text_color'];
        }

        $palette['rationale'] = mb_substr(trim((string) ($result['rationale'] ?? '')), 0, 300);

        return $palette;
    }

    public function auditProduct(Product $product): array
    {
        $product->loadMissing([
            'translations',
            'category.attributes.translations',
            'attributes.translations',
            'features',
            'offers',
            'city',
            'neighborhood',
        ]);

        $issues = collect();
        $this->auditCoreFields($product, $issues);
        $this->auditRequiredAttributes($product, $issues);
        $this->auditMedia($product, $issues);
        $this->auditPdfSettings($product, $issues);

        $counts = $issues->countBy('severity');
        $score = max(0, 100 - ($counts->get('error', 0) * 18) - ($counts->get('warning', 0) * 7));

        return [
            'product_id' => (int) $product->id,
            'score' => $score,
            'ready' => $counts->get('error', 0) === 0,
            'issues' => $issues->values()->all(),
            'summary' => [
                'errors' => $counts->get('error', 0),
                'warnings' => $counts->get('warning', 0),
            ],
        ];
    }

    private function normalizeGroups(array $result, Collection $attributes): array
    {
        $allowed = $attributes->pluck('id')->map(fn ($id) => (int) $id)->all();
        $used = [];
        $groups = [];

        foreach ((array) ($result['groups'] ?? []) as $group) {
            if (! is_array($group)) {
                continue;
            }

            $name = mb_substr(trim((string) ($group['name'] ?? '')), 0, 60);
            $ids = collect($group['attributes'] ?? [])
                ->filter('is_numeric')
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => in_array($id, $allowed, true) && ! in_array($id, $used, true))
                ->unique()
                ->take(8)
                ->values()
                ->all();

            if ($name === '' || $ids === []) {
                continue;
            }

            $used = array_merge($used, $ids);
            $groups[] = ['name' => $name, 'attributes' => $ids];

            if (count($groups) === 6) {
                break;
            }
        }

        $remaining = array_values(array_diff($allowed, $used));
        if ($remaining !== []) {
            if ($groups === []) {
                $groups[] = ['name' => 'المواصفات الأساسية', 'attributes' => array_slice($remaining, 0, 8)];
                $remaining = array_slice($remaining, 8);
            }

            foreach (array_chunk($remaining, 8) as $index => $chunk) {
                if (count($groups) === 6) {
                    $groups[5]['attributes'] = array_values(array_unique(array_merge($groups[5]['attributes'], $chunk)));
                    break;
                }
                $groups[] = ['name' => 'مواصفات إضافية'.($index ? ' '.($index + 1) : ''), 'attributes' => $chunk];
            }
        }

        return [
            'groups' => $groups,
            'rationale' => mb_substr(trim((string) ($result['rationale'] ?? '')), 0, 500),
        ];
    }

    private function auditCoreFields(Product $product, Collection $issues): void
    {
        $translation = $product->translations->firstWhere('locale', 'ar') ?: $product->translations->first();

        if (! $translation || trim((string) $translation->title) === '') {
            $this->addIssue($issues, 'error', 'title', 'عنوان العقار مفقود.');
        }
        if (! $translation || trim((string) $translation->description) === '') {
            $this->addIssue($issues, 'warning', 'description', 'وصف العقار مفقود.');
        }
        if (trim((string) $product->address) === '') {
            $this->addIssue($issues, 'error', 'address', 'عنوان الموقع مفقود.');
        }
        if (! $product->city_id) {
            $this->addIssue($issues, 'error', 'city_id', 'المدينة غير محددة.');
        }
        if (! $product->category_id) {
            $this->addIssue($issues, 'error', 'category_id', 'الفئة غير محددة.');
        }
        if ($product->offers->isEmpty()) {
            $this->addIssue($issues, 'warning', 'offers', 'لا يوجد عرض بيع أو إيجار.');
        }
        if (! $product->latitude || ! $product->longitude) {
            $this->addIssue($issues, 'warning', 'location', 'إحداثيات الخريطة غير مكتملة.');
        }
    }

    private function auditRequiredAttributes(Product $product, Collection $issues): void
    {
        $required = $product->category?->attributes?->where('required', true) ?? collect();
        $values = $product->attributes->keyBy('id');

        foreach ($required as $attribute) {
            $value = $values->get($attribute->id)?->pivot?->value;
            if ($value === null || trim((string) $value) === '') {
                $name = $attribute->getTranslatedName('ar') ?: $attribute->key;
                $this->addIssue($issues, 'error', 'attribute_'.$attribute->id, 'الخاصية المطلوبة مفقودة: '.$name);
            }
        }
    }

    private function auditMedia(Product $product, Collection $issues): void
    {
        if (! $product->main_image) {
            $this->addIssue($issues, 'error', 'main_image', 'صورة الغلاف مفقودة.');
        }
        if (count($product->image_gallery ?? []) < 3) {
            $this->addIssue($issues, 'warning', 'image_gallery', 'يفضل إضافة ثلاث صور على الأقل.');
        }
    }

    private function auditPdfSettings(Product $product, Collection $issues): void
    {
        $settings = $this->pdfSettings->load();
        $groups = $settings['attribute_groups'][$product->category_id] ?? [];

        if ($groups === []) {
            $this->addIssue($issues, 'warning', 'pdf_groups', 'لم يتم إعداد شرائح خصائص لهذه الفئة.');
        }

        $known = $product->category?->attributes?->pluck('id')->map(fn ($id) => (int) $id)->all() ?? [];
        foreach ($groups as $group) {
            foreach ((array) ($group['attributes'] ?? []) as $id) {
                if (! in_array((int) $id, $known, true)) {
                    $this->addIssue($issues, 'warning', 'pdf_attribute_'.$id, 'إعداد PDF يحتوي خاصية غير مرتبطة بالفئة: #'.$id);
                }
            }
        }
    }

    private function contrast(string $foreground, string $background): float
    {
        $a = $this->luminance($foreground);
        $b = $this->luminance($background);

        return (max($a, $b) + 0.05) / (min($a, $b) + 0.05);
    }

    private function luminance(string $hex): float
    {
        $channels = [substr($hex, 1, 2), substr($hex, 3, 2), substr($hex, 5, 2)];
        $values = array_map(function (string $channel) {
            $value = hexdec($channel) / 255;
            return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, $channels);

        return (0.2126 * $values[0]) + (0.7152 * $values[1]) + (0.0722 * $values[2]);
    }

    private function addIssue(Collection $issues, string $severity, string $field, string $message): void
    {
        $issues->push(compact('severity', 'field', 'message'));
    }
}
