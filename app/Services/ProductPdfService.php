<?php

namespace App\Services;

use App\Models\Product;
use App\Services\LanguageService;
use App\Services\PdfSettingsService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class ProductPdfService
{
    /** @return array{binary: string, filename: string} */
    /** @param array{format?: 'presentation'|'mobile', locale?: string|null} $options */
    public function render(Product $product, array $options = []): array
    {
        $html = $this->renderHtml($product, $options);

        $format = (string) ($options['format'] ?? 'presentation');
        $isMobile = $format === 'mobile';

        $pageWidthIn = $isMobile ? 7.5 : 13.333;
        $pageHeightIn = $isMobile ? 13.333 : 7.5;

        $headerHtml = $this->headerHtml($product, $options);

        $binary = Browsershot::html($html)
            ->setNodeModulePath(base_path('node_modules'))
            ->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
            ->windowSize(1920, 1080)
            ->deviceScaleFactor(2)
            ->addChromiumArguments(['--no-sandbox', '--disable-setuid-sandbox'])
            ->timeout(180)
            ->setOption('protocolTimeout', 180000)
            ->setOption('waitUntil', 'load')
            ->setOption('preferCSSPageSize', true)
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', $headerHtml)
            ->setOption('footerTemplate', '<style>html,body{margin:0;padding:0;} .pdf-footer{width:100%; height:12mm; line-height:12mm; font-size:16px; font-weight:700; color:#0b1220; background:transparent; padding:0 5%; box-sizing:border-box;} .pdf-footer .pageNumber{float:left;}</style><div class="pdf-footer"><span class="pageNumber"></span></div>')
            ->showBackground()
            ->margins(36, 0, 12, 0)
            ->pdf();

        return [
            'binary' => $binary,
            'filename' => 'product-'.$product->id.'-'.$this->resolveLocale($options).'-profile.pdf',
        ];
    }

    /**
     * Resolve the locale the document should be rendered in.
     *
     * Falls back to the active app locale so a PDF always matches the language
     * the visitor is browsing the site in.
     */
    private function resolveLocale(array $options): string
    {
        $locale = isset($options['locale']) ? (string) $options['locale'] : '';
        $supported = array_keys(app(LanguageService::class)->getAvailableLanguages());

        return in_array($locale, $supported, true) ? $locale : app()->getLocale();
    }

    /**
     * Run a renderer with the document locale active, then restore the previous locale.
     *
     * @template T
     * @param  callable(): T  $callback
     * @return T
     */
    private function withLocale(string $locale, callable $callback)
    {
        $previous = app()->getLocale();

        if ($locale === $previous) {
            return $callback();
        }

        App::setLocale($locale);

        try {
            return $callback();
        } finally {
            App::setLocale($previous);
        }
    }

    public function headerHtml(Product $product, array $options = []): string
    {
        $product->loadMissing(['city', 'neighborhood']);

        $pdfSettings = $this->pdfSettings();
        $pdfTheme = $this->resolvePdfTheme($options, $pdfSettings);
        $pdfStyle = is_array(($pdfSettings['style'] ?? null)) ? ($pdfSettings['style'] ?? []) : [];
        $pdfDarkStyle = is_array(($pdfSettings['dark_style'] ?? null)) ? ($pdfSettings['dark_style'] ?? []) : [];

        if ($pdfTheme === 'dark') {
            $pdfBg = ((string) ($pdfDarkStyle['bg_color'] ?? '') !== '') ? (string) $pdfDarkStyle['bg_color'] : '#0B1220';
            $pdfTextColor = ((string) ($pdfDarkStyle['text_color'] ?? '') !== '') ? (string) $pdfDarkStyle['text_color'] : '#F8FAFC';
            $pdfMuted = ((string) ($pdfDarkStyle['muted_color'] ?? '') !== '') ? (string) $pdfDarkStyle['muted_color'] : '#A1A1AA';
        } else {
            $pdfBg = (string) ($pdfStyle['bg_color'] ?? '#ffffff');
            $pdfTextColor = (string) ($pdfStyle['text_color'] ?? '#162222');
            $pdfMuted = '#667577';
        }

        return $this->withLocale($this->resolveLocale($options), function () use ($product, $pdfBg, $pdfTextColor, $pdfMuted) {
            $headerTitle = (string) ($product->address ?: __('pdf.document_type'));
            $cityName = \App\Helpers\LanguageHelper::getCityName($product->city) ?: (string) ($product->city?->name ?? '');
            $headerSubtitle = trim(implode(' - ', array_values(array_filter([
                $cityName !== '' ? $cityName : null,
                $product->neighborhood?->name ?? null,
            ], fn ($v) => is_string($v) && $v !== ''))));

            return view('pdf.header', [
                'product' => $product,
                'logoDataUri' => $this->logoDataUri(),
                'headerTitle' => $headerTitle,
                'headerSubtitle' => $headerSubtitle,
                'pdfBg' => $pdfBg,
                'pdfTextColor' => $pdfTextColor,
                'pdfMuted' => $pdfMuted,
            ])->render();
        });
    }

    /** @param array{format?: 'presentation'|'mobile', only_slide?: string|null, theme?: 'light'|'dark'|null, locale?: string|null} $options */
    public function renderHtml(Product $product, array $options = []): string
    {
        $locale = $this->resolveLocale($options);

        return $this->withLocale($locale, fn () => $this->buildHtml($product, $options, $locale));
    }

    private function buildHtml(Product $product, array $options, string $locale): string
    {
        $product->loadMissing(['facility', 'category', 'city', 'neighborhood', 'street', 'building', 'project', 'package', 'owner', 'seller', 'statuses', 'features', 'attributes.translations', 'activeOffers']);
        $baseUrl = $this->assetBaseUrl();

        $format = (string) ($options['format'] ?? 'presentation');
        $isMobile = $format === 'mobile';

        $pdfSettings = $this->pdfSettings();

        $categoryId = $product->subcategory_id ?? $product->category_id;
        $perCategory = is_array(($pdfSettings['attribute_order_by_category'] ?? null)) ? ($pdfSettings['attribute_order_by_category'] ?? []) : [];
        if ($categoryId && !empty($perCategory[$categoryId])) {
            $pdfSettings['attribute_order'] = $perCategory[$categoryId];
        } elseif ($product->category?->parent_id && !empty($perCategory[$product->category->parent_id])) {
            $pdfSettings['attribute_order'] = $perCategory[$product->category->parent_id];
        }

        $perCategoryGroups = is_array(($pdfSettings['attribute_groups'] ?? null)) ? ($pdfSettings['attribute_groups'] ?? []) : [];
        if ($categoryId && !empty($perCategoryGroups[$categoryId])) {
            $pdfSettings['attribute_groups'] = $perCategoryGroups[$categoryId];
        } elseif ($product->category?->parent_id && !empty($perCategoryGroups[$product->category->parent_id])) {
            $pdfSettings['attribute_groups'] = $perCategoryGroups[$product->category->parent_id];
        } else {
            $pdfSettings['attribute_groups'] = [];
        }

        $pageWidthIn = $isMobile ? 7.5 : 13.333;
        $pageHeightIn = $isMobile ? 13.333 : 7.5;

        $mobileDesign = $isMobile ? 'snap_9_16' : null;

        $mapZoom = $isMobile ? 14 : 15;
        $mapWidthPx = 1200;
        $mapHeightPx = 675;

        $mapDataUri = $this->staticMapDataUri($product->latitude, $product->longitude, $mapZoom, $mapWidthPx, $mapHeightPx) ?: $this->localMapDataUri($product->id);
        $mapQrDataUri = $this->remoteImageDataUri($this->qrUrl($product->google_maps_url));

        $galleryImageDataUris = $this->galleryImageDataUris($product->image_gallery ?? []);
        $mainImageDataUri = $this->imageDataUri($product->main_image ?? null);
        $coverImageDataUri = $mainImageDataUri ?: ($galleryImageDataUris[0] ?? null);

        if (! $mainImageDataUri && ! empty($galleryImageDataUris)) {
            array_shift($galleryImageDataUris);
        }

        $headerSubtitle = trim(implode(' - ', array_values(array_filter([
            \App\Helpers\LanguageHelper::getCityName($product->city) ?: (string) ($product->city?->name ?? ''),
            $product->neighborhood?->name ?? null,
        ], fn ($v) => is_string($v) && $v !== ''))));

        $onlySlide = isset($options['only_slide']) ? (string) $options['only_slide'] : null;
        $onlySlide = $onlySlide !== '' ? $onlySlide : null;

        return view('pdf.product', [
            'product' => $product,
            'logoDataUri' => $this->logoDataUri(),
            'headerTitle' => (string) ($product->address ?: __('pdf.document_type')),
            'headerSubtitle' => $headerSubtitle,
            'productImageDataUri' => $coverImageDataUri,
            'galleryImageDataUris' => $galleryImageDataUris,
            'mapImageDataUri' => $mapDataUri,
            'mapQr' => $mapQrDataUri,
            'productQrDataUri' => $this->remoteImageDataUri($this->qrUrl(route('public.products.show', $product))),
            'pdfFormat' => $format,
            'pageWidthIn' => $pageWidthIn,
            'pageHeightIn' => $pageHeightIn,
            'mobileDesign' => $mobileDesign,
            'pdfSettings' => $pdfSettings,
            'assetBaseUrl' => $baseUrl,
            'language' => $locale,
            'documentType' => __('pdf.document_type'),
            'documentNumber' => 'P'.$product->id,
            'documentDate' => now()->format('Y-m-d'),
            'watermark' => null,
            'qrDataUri' => null,
            'verificationPayload' => route('public.products.show', $product),
            'pdfOnlySlide' => $onlySlide,
            'pdfTheme' => $this->resolvePdfTheme($options, $pdfSettings),
        ])->render();
    }

    private function pdfSettings(): array
    {
        return app(PdfSettingsService::class)->load();
    }

    private function resolvePdfTheme(array $options, array $pdfSettings): string
    {
        $theme = isset($options['theme']) ? (string) $options['theme'] : '';
        if (in_array($theme, ['light', 'dark'], true)) {
            return $theme;
        }

        $default = (string) ($pdfSettings['theme_default'] ?? 'light');
        return in_array($default, ['light', 'dark'], true) ? $default : 'light';
    }



    private function qrUrl(?string $payload): ?string
    {
        if (empty($payload)) {
            return null;
        }

        return 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($payload);
    }

    private function staticMapDataUri(?float $lat, ?float $lng, int $zoom = 16, int $widthPx = 1200, int $heightPx = 675): ?string
    {
        if (! $lat || ! $lng) {
            return null;
        }

        $center = $lat.','.$lng;

        $query = 'center='.urlencode($center)
            .'&zoom='.$zoom
            .'&size='.$widthPx.'x'.$heightPx
            .'&maptype=mapnik'
            .'&markers='.urlencode($center.',red-pushpin');

        $providers = [
            'https://staticmap.openstreetmap.de/staticmap.php?'.$query,
            'http://staticmap.openstreetmap.de/staticmap.php?'.$query,
            'https://haukauntrie.de/online/api/staticmaps/staticmap.php?'.$query,
        ];

        foreach ($providers as $url) {
            $dataUri = $this->remoteImageDataUri($url);
            if (! empty($dataUri)) {
                return $dataUri;
            }
        }

        return $this->googleTileDataUri($lat, $lng);
    }

    private function googleTileDataUri(float $lat, float $lng): ?string
    {
        $z = 16;
        $n = 2 ** $z;
        $x = (int) floor((($lng + 180) / 360) * $n);
        $latRad = deg2rad($lat);
        $y = (int) floor((1 - log(tan($latRad) + (1 / cos($latRad))) / M_PI) / 2 * $n);

        $url = 'https://mt1.google.com/vt/lyrs=m&scale=2&x='.$x.'&y='.$y.'&z='.$z;

        try {
            $response = Http::withoutVerifying()->timeout(20)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $binary = $response->body();
            if (empty($binary)) {
                return null;
            }

            $mime = $response->header('Content-Type') ?: 'image/png';
            $mime = is_string($mime) ? trim(explode(';', $mime)[0]) : 'image/png';
            if (empty($mime) || ! str_starts_with($mime, 'image/')) {
                $mime = 'image/png';
            }

            return 'data:'.$mime.';base64,'.base64_encode($binary);
        } catch (\Throwable $e) {
            Log::warning('ProductPdfService: failed to fetch Google tile', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function remoteImageDataUri(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        try {
            $response = Http::retry(2, 200)
                ->withoutVerifying()
                ->timeout(20)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
                ])
                ->get($url);

            if (! $response->successful()) {
                Log::warning('ProductPdfService: failed to fetch remote image', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $binary = $response->body();

            if ($binary === '') {
                Log::warning('ProductPdfService: empty remote image body', [
                    'url' => $url,
                ]);
                return null;
            }

            $mime = $response->header('Content-Type');
            if (is_string($mime)) {
                $mime = trim(explode(';', $mime)[0]);
            }

            if (empty($mime) || ! str_starts_with($mime, 'image/')) {
                $mime = 'image/png';
            }

            return 'data:'.$mime.';base64,'.base64_encode($binary);
        } catch (\Throwable $e) {
            Log::warning('ProductPdfService: exception while fetching remote image', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function assetBaseUrl(): string
    {
        return rtrim(config('app.url') ?? url('/'), '/');
    }

    private function logoDataUri(): ?string
    {
        $candidates = [
            // Prefer the site's current logo/icon if present
            'images/madar-negotiation-icon.svg',
            'images/madar-negotiation-logo.svg',
            'images/sm-logo-ar.png',
            'favicon.svg',
            'logo.png',
        ];

        foreach ($candidates as $candidate) {
            try {
                $path = public_path($candidate);

                if (! is_file($path)) {
                    continue;
                }

                $binary = file_get_contents($path);

                if ($binary === false || $binary === '') {
                    continue;
                }

                $mime = mime_content_type($path) ?: 'image/png';

                return 'data:'.$mime.';base64,'.base64_encode($binary);
            } catch (\Throwable) {
                // try next
            }
        }

        return null;
    }

    /** @return array<int, string> */
    private function galleryImageDataUris(array $paths): array
    {
        $dataUris = [];

        foreach ($paths as $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }

            $dataUri = $this->imageDataUri($path);
            if ($dataUri) {
                $dataUris[] = $dataUri;
            }
        }

        return $dataUris;
    }

    private function imageDataUri(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                $binary = Storage::disk('public')->get($path);
                $mime = Storage::disk('public')->mimeType($path) ?: 'image/png';

                return 'data:'.$mime.';base64,'.base64_encode($binary);
            }

            $localPath = public_path($path);
            if (is_file($localPath)) {
                $binary = file_get_contents($localPath);
                $mime = mime_content_type($localPath) ?: 'image/png';

                return 'data:'.$mime.';base64,'.base64_encode($binary);
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    private function localMapDataUri(int $id): ?string
    {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            $path = public_path('maps/product-'.$id.'-map.'.$ext);
            if (is_file($path)) {
                $binary = file_get_contents($path);
                $mime = mime_content_type($path) ?: 'image/png';

                return 'data:'.$mime.';base64,'.base64_encode($binary);
            }
        }

        return null;
    }

    /**
     * @return array{svg: string, center?: array{lat: float, lng: float}}|null
     */
    private function parcelOverlay(Product $product, int $zoom, int $widthPx, int $heightPx): ?array
    {
        if (! $product->latitude || ! $product->longitude) {
            return null;
        }

        $polygon = $this->parcelPolygonLonLat($product);
        if (! $polygon) {
            return null;
        }

        $svg = $this->polygonToSvgOverlay($polygon, (float) $product->latitude, (float) $product->longitude, $zoom, $widthPx, $heightPx);
        if ($svg === null) {
            return null;
        }

        return ['svg' => $svg];
    }

    /** @return array<int, array{0: float, 1: float}>|null */
    private function parcelPolygonLonLat(Product $product): ?array
    {
        if ((int) $product->id !== 42) {
            return null;
        }

        return [
            [46.80729900005441, 24.53652699976476],
            [46.8072770000544, 24.53651599976476],
            [46.80726600005439, 24.536535999764755],
            [46.80689400005431, 24.53635899976476],
            [46.80707100005435, 24.536045999764763],
            [46.807092000054354, 24.536014999764763],
            [46.807118000054366, 24.535987999764757],
            [46.807147000054364, 24.53596599976476],
            [46.80718000005437, 24.53594799976477],
            [46.807215000054384, 24.535934999764756],
            [46.80740400005442, 24.535879999764752],
            [46.807539000054454, 24.536266999764752],
            [46.807429000054434, 24.536298999764757],
            [46.80729900005441, 24.53652699976476],
        ];
    }

    private function polygonToSvgOverlay(array $polygonLonLat, float $centerLat, float $centerLng, int $zoom, int $widthPx, int $heightPx): ?string
    {
        $points = [];
        foreach ($polygonLonLat as $pt) {
            if (! is_array($pt) || count($pt) < 2) {
                continue;
            }
            $points[] = [(float) $pt[0], (float) $pt[1]]; // [lon, lat]
        }
        if (count($points) < 3) {
            return null;
        }

        [$cx, $cy] = $this->webMercatorPixel($centerLng, $centerLat, $zoom);
        $x0 = $cx - ($widthPx / 2.0);
        $y0 = $cy - ($heightPx / 2.0);

        $path = [];
        foreach ($points as [$lon, $lat]) {
            [$px, $py] = $this->webMercatorPixel($lon, $lat, $zoom);
            $x = $px - $x0;
            $y = $py - $y0;
            $path[] = number_format($x, 2, '.', '').' '.number_format($y, 2, '.', '');
        }
        $d = 'M '.implode(' L ', $path).' Z';

        $maskId = 'm'.substr(md5($d), 0, 8);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$widthPx.' '.$heightPx.'" preserveAspectRatio="xMidYMid meet">'
            .'<defs>'
            .'<mask id="'.$maskId.'">'
            .'<rect x="0" y="0" width="'.$widthPx.'" height="'.$heightPx.'" fill="white"/>'
            .'<path d="'.$d.'" fill="black"/>'
            .'</mask>'
            .'</defs>'
            .'<rect x="0" y="0" width="'.$widthPx.'" height="'.$heightPx.'" fill="rgba(11,18,32,0.35)" mask="url(#'.$maskId.')"/>'
            .'<path d="'.$d.'" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.95)" stroke-width="4" vector-effect="non-scaling-stroke"/>'
            .'</svg>';

        return $svg;
    }

    /** @return array{0: float, 1: float} */
    private function webMercatorPixel(float $lng, float $lat, int $zoom): array
    {
        $sinLat = sin(deg2rad(max(-85.05112878, min(85.05112878, $lat))));
        $scale = 256 * (2 ** $zoom);
        $x = ($lng + 180.0) / 360.0 * $scale;
        $y = (0.5 - log((1 + $sinLat) / (1 - $sinLat)) / (4 * M_PI)) * $scale;

        return [$x, $y];
    }
}
