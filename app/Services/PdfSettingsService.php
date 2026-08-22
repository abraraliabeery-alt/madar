<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PdfSettingsService
{
    private const SETTINGS_KEY = 'pdf.product_profile.settings';

    private const DEFAULT_SLIDE_ORDER = ['cover', 'details', 'location', 'features', 'offers', 'cta'];

    /**
     * Slide key => translation key used for its default heading.
     *
     * Slide labels are an optional admin override. When a stored label is just one
     * of the shipped defaults (in any locale) we ignore it, so the document heading
     * follows the reader's language instead of being pinned to Arabic.
     */
    private const SLIDE_LABEL_TRANSLATIONS = [
        'details'  => 'pdf.slides.summary.title',
        'location' => 'pdf.slides.location.title',
        'features' => 'pdf.slides.features.title',
        'offers'   => 'pdf.slides.offers.title',
        'cta'      => 'pdf.slides.cta.title',
        'gallery'  => 'pdf.slides.gallery.title',
    ];

    /**
     * Resolve the heading for a slide in the active locale.
     *
     * Returns the admin's custom label when one was genuinely set, otherwise the
     * translated default for the current locale.
     */
    public function slideLabel(string $key, array $settings, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $labels = $this->slideLabelsForLocale($settings, $locale);
        $stored = trim((string) ($labels[$key] ?? ''));
        $translationKey = self::SLIDE_LABEL_TRANSLATIONS[$key] ?? null;

        if ($translationKey === null) {
            return $stored !== '' ? $stored : null;
        }

        if ($stored !== '' && ! in_array($stored, $this->defaultSlideLabelVariants($translationKey), true)) {
            return $stored;
        }

        return trans($translationKey, [], $locale);
    }

    /**
     * Every shipped wording for a slide heading across all supported locales,
     * used to detect "not really customised" stored labels.
     *
     * @return array<int, string>
     */
    private function defaultSlideLabelVariants(string $translationKey): array
    {
        $variants = [];

        foreach (array_keys(app(LanguageService::class)->getAvailableLanguages()) as $locale) {
            $variants[] = trim((string) trans($translationKey, [], $locale));
        }

        return array_values(array_unique(array_filter($variants)));
    }

    /**
     * Resolve the slide-label array for a single locale.
     *
     * Stored labels are grouped by locale. Legacy flat arrays are migrated on load,
     * so the fallback here only protects against missing keys.
     */
    private function slideLabelsForLocale(array $settings, string $locale): array
    {
        $all = is_array($settings['slide_labels'] ?? null) ? $settings['slide_labels'] : [];

        if (isset($all[$locale]) && is_array($all[$locale])) {
            return $all[$locale];
        }

        return is_array($all) ? $all : [];
    }

    public function load(): array
    {
        $raw = Setting::getValue(self::SETTINGS_KEY);
        $decoded = [];

        if (is_string($raw) && $raw !== '') {
            $json = json_decode($raw, true);
            if (is_array($json)) {
                $decoded = $json;
                $decoded = $this->migrateSlideLabels($decoded);
            }
        }

        $merged   = array_replace_recursive($this->defaults(), $decoded);
        $defaults = $this->defaults();

        $brand  = trim((string) Arr::get($merged, 'style.brand_color', ''));
        $accent = trim((string) Arr::get($merged, 'style.accent_color', ''));

        if ($brand === '') {
            Arr::set($merged, 'style.brand_color', $defaults['style']['brand_color']);
        }
        if ($accent === '') {
            Arr::set($merged, 'style.accent_color', $defaults['style']['accent_color']);
        }

        return $merged;
    }

    public function save(array $data): void
    {
        Setting::setValue(self::SETTINGS_KEY, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public function storeFromRequest(Request $request): array
    {
        $existing = $this->load();

        $payload = [
            'font_base_px'  => (int) $request->input('font_base_px', 18),
            'font_title_px' => (int) $request->input('font_title_px', 28),
            'font_value_px' => (int) $request->input('font_value_px', 22),

            'theme_default' => in_array((string) $request->input('theme_default', 'light'), ['light', 'dark'], true)
                ? (string) $request->input('theme_default', 'light')
                : 'light',

            'style' => [
                'brand_color'     => (string) $request->input('style.brand_color', '#126B61'),
                'accent_color'    => (string) $request->input('style.accent_color', '#7C3AED'),
                'bg_color'        => (string) $request->input('style.bg_color', '#ffffff'),
                'title_color'     => (string) $request->input('style.title_color', ''),
                'text_color'      => (string) $request->input('style.text_color', '#162222'),
                'card_radius_mm'  => (float) $request->input('style.card_radius_mm', 4),
                'card_padding_mm' => (float) $request->input('style.card_padding_mm', 7),
                'grid_spacing_mm' => (float) $request->input('style.grid_spacing_mm', 5),
            ],

            'dark_style' => [
                'brand_color'  => (string) $request->input('dark_style.brand_color', ''),
                'accent_color' => (string) $request->input('dark_style.accent_color', ''),
                'bg_color'     => (string) $request->input('dark_style.bg_color', ''),
                'card_color'   => (string) $request->input('dark_style.card_color', ''),
                'title_color'  => (string) $request->input('dark_style.title_color', ''),
                'text_color'   => (string) $request->input('dark_style.text_color', ''),
                'muted_color'  => (string) $request->input('dark_style.muted_color', ''),
                'stroke_color' => (string) $request->input('dark_style.stroke_color', ''),
            ],

            'slides'      => $this->normalizeSlides($request->input('slides', [])),
            'slides_order' => $this->normalizeSlideOrder($request->input('slides_order', [])),

            'attribute_order' => $this->normalizeAttributeOrder(
                $request->input('attribute_order', [])
            ),

            'attribute_order_by_category' => $this->normalizeAttributeOrderByCategory(
                $request->input('attribute_order_by_category', [])
            ),

            'attribute_groups' => $this->normalizeAttributeGroups(
                $request->input('attribute_groups', [])
            ),

            'slide_labels' => $this->slideLabelsFromRequest(
                $request->input('slide_labels', [])
            ),
        ];

        $this->save($payload);

        return $payload;
    }

    public function colorFrom(array $style, string $key, string $fallback): string
    {
        return (string) (Arr::get($style, $key) ?: $fallback);
    }

    public function defaults(): array
    {
        return [
            'font_base_px'  => 18,
            'font_title_px' => 28,
            'font_value_px' => 22,

            'style' => [
                'brand_color'     => $this->siteBrandHex() ?? '#126B61',
                'accent_color'    => $this->siteBrandHex() ?? '#7C3AED',
                'bg_color'        => '#ffffff',
                'title_color'     => '',
                'text_color'      => '#162222',
                'card_radius_mm'  => 4,
                'card_padding_mm' => 7,
                'grid_spacing_mm' => 5,
            ],

            'theme_default' => 'light',

            'slides' => [
                'cover'    => true,
                'details'  => true,
                'location' => true,
                'features' => true,
                'offers'   => true,
                'cta'      => true,
            ],

            'dark_style' => [
                'brand_color'  => '',
                'accent_color' => '',
                'bg_color'     => '',
                'card_color'   => '',
                'title_color'  => '',
                'text_color'   => '',
                'muted_color'  => '',
                'stroke_color' => '',
            ],

            'slides_order'              => self::DEFAULT_SLIDE_ORDER,
            'attribute_order'           => [],
            'attribute_order_by_category' => [],
            'attribute_groups'          => [],

            'slide_labels' => $this->slideLabelsDefaults(),
        ];
    }

    /**
     * Build the default slide-label matrix for all supported locales.
     */
    private function slideLabelsDefaults(): array
    {
        $locales = array_keys(app(LanguageService::class)->getAvailableLanguages());
        $matrix = [];

        foreach ($locales as $locale) {
            foreach (array_keys(self::SLIDE_LABEL_TRANSLATIONS) as $key) {
                $matrix[$locale][$key] = '';
            }
        }

        return $matrix;
    }

    /**
     * Migrate a legacy flat slide_labels array into the per-locale structure.
     */
    private function migrateSlideLabels(array $data): array
    {
        $labels = $data['slide_labels'] ?? null;

        if (! is_array($labels) || empty($labels)) {
            return $data;
        }

        $firstKey = array_key_first($labels);
        $firstValue = $labels[$firstKey];

        // Already nested when the first key is a supported locale and its value is an array
        if (is_string($firstKey)
            && is_array($firstValue)
            && array_key_exists($firstKey, app(LanguageService::class)->getAvailableLanguages())
        ) {
            return $data;
        }

        // Legacy flat labels are most likely in Arabic, the site default.
        $data['slide_labels'] = [config('app.locale', 'ar') => $labels];

        return $data;
    }

    /**
     * Sanitise per-locale slide labels submitted from the admin form.
     */
    private function slideLabelsFromRequest(array $incoming): array
    {
        $locales = array_keys(app(LanguageService::class)->getAvailableLanguages());
        $clean = [];

        foreach ($locales as $locale) {
            foreach (array_keys(self::SLIDE_LABEL_TRANSLATIONS) as $key) {
                $value = isset($incoming[$locale][$key])
                    ? trim((string) $incoming[$locale][$key])
                    : '';

                $translationKey = self::SLIDE_LABEL_TRANSLATIONS[$key] ?? null;

                if ($translationKey !== null) {
                    $default = trim((string) trans($translationKey, [], $locale));

                    if ($value === '' || $value === $default) {
                        $clean[$locale][$key] = '';
                        continue;
                    }
                }

                $clean[$locale][$key] = $value;
            }
        }

        return $clean;
    }

    public function normalizeSlides(array $slides): array
    {
        foreach (['cover', 'details', 'location', 'features', 'offers', 'cta'] as $key) {
            $slides[$key] = (bool) ($slides[$key] ?? false);
        }

        return $slides;
    }

    public function normalizeSlideOrder(array $order): array
    {
        $allowed = ['cover', 'details', 'location', 'features', 'offers', 'cta'];

        $order = array_values(array_filter(
            $order,
            fn ($value) => is_string($value) && in_array($value, $allowed, true)
        ));

        $order = array_values(array_unique($order));

        return $order ?: self::DEFAULT_SLIDE_ORDER;
    }

    public function normalizeAttributeOrder(array $order): array
    {
        return array_values(array_filter($order, 'is_numeric'));
    }

    public function normalizeAttributeOrderByCategory(array $order): array
    {
        $normalized = [];
        foreach ($order as $categoryId => $ids) {
            if (! is_numeric($categoryId)) {
                continue;
            }
            $ids = is_array($ids) ? $ids : [];
            $normalized[(int) $categoryId] = array_values(array_filter($ids, 'is_numeric'));
        }

        return $normalized;
    }

    public function normalizeAttributeGroups(array $groups): array
    {
        $normalized = [];
        foreach ($groups as $categoryId => $catGroups) {
            if (! is_numeric($categoryId)) {
                continue;
            }
            $catGroups = is_array($catGroups) ? $catGroups : [];
            $cleanGroups = [];
            foreach ($catGroups as $group) {
                if (! is_array($group)) {
                    continue;
                }
                $name = trim((string) Arr::get($group, 'name', ''));
                $ids = Arr::get($group, 'attributes', []);
                if (is_string($ids)) {
                    $ids = array_values(array_filter(array_map('trim', explode(',', $ids)), 'is_numeric'));
                } elseif (is_array($ids)) {
                    $ids = array_values(array_filter($ids, 'is_numeric'));
                } else {
                    $ids = [];
                }
                if ($name === '' && empty($ids)) {
                    continue;
                }
                $cleanGroups[] = [
                    'name'       => $name,
                    'attributes' => $ids,
                ];
            }
            if (! empty($cleanGroups)) {
                $normalized[(int) $categoryId] = $cleanGroups;
            }
        }

        return $normalized;
    }

    private function siteBrandHex(): ?string
    {
        $path = public_path('theme.css');

        if (! is_file($path)) {
            return null;
        }

        try {
            $css = file_get_contents($path);
            if (! is_string($css) || $css === '') {
                return null;
            }

            if (preg_match('/--brand-brown\s*:\s*([^;\n}]+)\s*;/', $css, $m)) {
                $value = trim($m[1]);
                if ($value !== '' && preg_match('/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $value)) {
                    return $value;
                }
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }
}
