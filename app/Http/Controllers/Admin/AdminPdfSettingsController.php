<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PdfSettingsRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Services\AI\PdfAiService;
use App\Services\LanguageService;
use App\Services\PdfSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminPdfSettingsController extends Controller
{
    public function __construct(
        private PdfSettingsService $pdfSettings,
        private PdfAiService $pdfAi
    ) {
    }

    public function edit(Request $request)
    {
        $adminLocale = app()->getLocale();
        $languages   = app(LanguageService::class)->getAvailableLanguages();
        $settings   = $this->pdfSettings->load();
        $attributes = $this->loadAttributes();

        $order            = $this->attributeOrderFrom($request, $settings);
        $sortedAttributes = $this->sortAttributes($attributes, $order);

        $style = $request->old('style', $settings['style']);

        $pdfThemeDefault = $this->themeDefaultFrom($request, $settings);

        $brandColor  = $this->pdfSettings->colorFrom($style, 'brand_color', $settings['style']['brand_color'] ?? '#126B61');
        $accentColor = $this->pdfSettings->colorFrom($style, 'accent_color', $settings['style']['accent_color'] ?? '#7C3AED');

        $darkStyle = $this->darkStyleFrom($request, $settings);

        $darkBrandColor  = $this->pdfSettings->colorFrom($darkStyle, 'brand_color', $darkStyle['brand_color'] ?? $brandColor);
        $darkAccentColor = $this->pdfSettings->colorFrom($darkStyle, 'accent_color', $darkStyle['accent_color'] ?? $accentColor);

        $slides      = $this->slidesFrom($request, $settings);
        $slidesOrder = $this->slidesOrderFrom($request, $settings);

        $attributeOrderByCategory = $request->old('attribute_order_by_category', $settings['attribute_order_by_category'] ?? []);
        $attributeOrderByCategory = is_array($attributeOrderByCategory) ? $attributeOrderByCategory : [];

        $attributeGroups = $request->old('attribute_groups', $settings['attribute_groups'] ?? []);
        $attributeGroups = is_array($attributeGroups) ? $attributeGroups : [];

        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with([
                'children' => fn ($q) => $q->where('is_active', true),
                'children.attributes' => fn ($q) => $q->where('is_active', true),
                'children.attributes.translations',
            ])
            ->orderBy('id')
            ->get();

        return view('admin.pdf.settings', [
            'settings'                 => $settings,
            'attributes'               => $attributes,
            'order'                    => $order,
            'sortedAttributes'         => $sortedAttributes,
            'style'                    => $style,
            'brandColor'               => $brandColor,
            'accentColor'              => $accentColor,
            'pdfThemeDefault'          => $pdfThemeDefault,
            'darkStyle'                => $darkStyle,
            'darkBrandColor'           => $darkBrandColor,
            'darkAccentColor'          => $darkAccentColor,
            'slides'                   => $slides,
            'slidesOrder'              => $slidesOrder,
            'slideLabels'              => $settings['slide_labels'] ?? $this->slideLabels(),
            'adminLocale'              => $adminLocale,
            'languages'                => $languages,
            'categories'               => $categories,
            'attributeOrderByCategory' => $attributeOrderByCategory,
            'attributeGroups'          => $attributeGroups,
        ]);
    }

    public function update(PdfSettingsRequest $request)
    {
        $this->pdfSettings->storeFromRequest($request);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ إعدادات PDF بنجاح',
            ]);
        }

        return redirect()->back()->with('success', 'تم حفظ إعدادات PDF بنجاح');
    }

    public function suggestCategorySlides(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $category = Category::query()
            ->whereNotNull('parent_id')
            ->where('is_active', true)
            ->findOrFail($validated['category_id']);

        try {
            $suggestion = $this->pdfAi->suggestCategorySlides($category);

            return response()->json([
                'success' => true,
                'suggestion' => $suggestion,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'تعذر توليد اقتراح الشرائح. تحقق من إعداد مزود الذكاء الاصطناعي.',
            ], 422);
        }
    }

    public function auditProduct(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);

        return response()->json([
            'success' => true,
            'audit' => $this->pdfAi->auditProduct($product),
        ]);
    }

    public function suggestPalette(Request $request)
    {
        $validated = $request->validate([
            'direction' => 'nullable|string|max:100',
        ]);

        try {
            return response()->json([
                'success' => true,
                'palette' => $this->pdfAi->suggestPalette($validated['direction'] ?? 'عقاري فاخر وهادئ'),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'تعذر اقتراح لوحة الألوان. تحقق من إعداد مزود الذكاء الاصطناعي.',
            ], 422);
        }
    }

    private function loadAttributes(): Collection
    {
        return Attribute::query()
            ->with('translations')
            ->orderBy('id')
            ->get();
    }

    private function attributeOrderFrom(Request $request, array $settings): array
    {
        $order = $request->old('attribute_order', $settings['attribute_order'] ?? []);

        return is_array($order) ? $order : [];
    }

    private function sortAttributes(Collection $attributes, array $order): Collection
    {
        $orderMap = array_flip(array_map('intval', $order));

        return $attributes->sortBy(function ($attribute) use ($orderMap) {
            $id = (int) data_get($attribute, 'id');

            return $orderMap[$id] ?? 100000 + $id;
        });
    }

    private function themeDefaultFrom(Request $request, array $settings): string
    {
        $theme = (string) $request->old('theme_default', $settings['theme_default'] ?? 'light');

        return in_array($theme, ['light', 'dark'], true) ? $theme : 'light';
    }

    private function darkStyleFrom(Request $request, array $settings): array
    {
        $darkStyle = is_array(($settings['dark_style'] ?? null)) ? ($settings['dark_style'] ?? []) : [];
        $darkStyle = is_array($request->old('dark_style', $darkStyle)) ? $request->old('dark_style', $darkStyle) : $darkStyle;

        return is_array($darkStyle) ? $darkStyle : [];
    }

    private function slidesFrom(Request $request, array $settings): array
    {
        $slides = $request->old('slides', $settings['slides'] ?? []);
        $slides = is_array($slides) ? $slides : [];

        foreach (['cover', 'details', 'location', 'features', 'offers', 'cta'] as $key) {
            $slides[$key] = ! empty($slides[$key]);
        }

        return $slides;
    }

    private function slidesOrderFrom(Request $request, array $settings): array
    {
        $order = $request->old('slides_order', $settings['slides_order'] ?? []);

        return is_array($order) ? $order : $this->pdfSettings->defaults()['slides_order'];
    }

    private function slideLabels(): array
    {
        return $this->pdfSettings->defaults()['slide_labels'];
    }
}
