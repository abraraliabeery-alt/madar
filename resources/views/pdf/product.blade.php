@extends('pdf.layout', [
    'language' => $language ?? app()->getLocale(),
    'documentType' => __('pdf.document_type'),
    'documentNumber' => 'P'.$product->id,
    'documentDate' => now()->format('Y-m-d'),
    'headerTitle' => (string) ($headerTitle ?: __('pdf.document_type')),
    'headerSubtitle' => (string) ($headerSubtitle ?? ''),
    'watermark' => null,
    'qrDataUri' => null,
    'verificationPayload' => route('public.products.show', $product),
])

@push('styles')
    <style>
        @php
            $pdf = is_array($pdfSettings ?? null) ? $pdfSettings : [];
            $pdfOnlySlideKey = is_string($pdfOnlySlide ?? null) && $pdfOnlySlide !== '' ? (string) $pdfOnlySlide : null;
            $pdfSlides = is_array(($pdf['slides'] ?? null)) ? ($pdf['slides'] ?? []) : [];
            $pdfFontBase = (int) ($pdf['font_base_px'] ?? 20);
            $pdfFontTitle = (int) ($pdf['font_title_px'] ?? 28);
            $pdfFontValue = (int) ($pdf['font_value_px'] ?? 22);
            $pdfStyle = is_array(($pdf['style'] ?? null)) ? ($pdf['style'] ?? []) : [];
            $pdfDarkStyle = is_array(($pdf['dark_style'] ?? null)) ? ($pdf['dark_style'] ?? []) : [];

            $pdfTheme = (string) ($pdfTheme ?? 'light');
            if (!in_array($pdfTheme, ['light', 'dark'], true)) {
                $pdfTheme = 'light';
            }

            $pdfBrand = (string) ($pdfStyle['brand_color'] ?? '#126B61');
            $pdfAccent = (string) ($pdfStyle['accent_color'] ?? '#7C3AED');

            $pdfBg = (string) ($pdfStyle['bg_color'] ?? '#ffffff');
            $pdfTitleColor = (($pdfStyle['title_color'] ?? '') !== '') ? (string) $pdfStyle['title_color'] : $pdfBrand;
            $pdfTextColor = (string) ($pdfStyle['text_color'] ?? '#162222');

            if ($pdfTheme === 'dark') {
                $pdfBrand = (string) ($pdfDarkStyle['brand_color'] ?? $pdfBrand);
                $pdfAccent = (string) ($pdfDarkStyle['accent_color'] ?? $pdfAccent);
                $pdfBg = (($pdfDarkStyle['bg_color'] ?? '') !== '') ? (string) $pdfDarkStyle['bg_color'] : '#0B1220';
                $pdfTitleColor = (($pdfDarkStyle['title_color'] ?? '') !== '') ? (string) $pdfDarkStyle['title_color'] : (($pdfDarkStyle['text_color'] ?? '') !== '' ? (string) $pdfDarkStyle['text_color'] : '#F8FAFC');
                $pdfTextColor = (($pdfDarkStyle['text_color'] ?? '') !== '') ? (string) $pdfDarkStyle['text_color'] : '#F8FAFC';
            }
            $pdfCardRadius = (float) ($pdfStyle['card_radius_mm'] ?? 4);
            $pdfCardPadding = (float) ($pdfStyle['card_padding_mm'] ?? 7);
            $pdfGridSpacing = (float) ($pdfStyle['grid_spacing_mm'] ?? 5);
        @endphp
        :root {
            --card: {{ $pdfTheme === 'dark' ? ((string)($pdfDarkStyle['card_color'] ?? '#111827')) : '#ffffff' }};
            --stroke: {{ $pdfTheme === 'dark' ? ((string)($pdfDarkStyle['stroke_color'] ?? 'rgba(255,255,255,0.14)')) : 'rgba(11, 18, 32, 0.12)' }};
            --muted: {{ $pdfTheme === 'dark' ? ((string)($pdfDarkStyle['muted_color'] ?? '#A1A1AA')) : '#667577' }};
            --brand: {{ $pdfBrand }};
            --brand-2: {{ $pdfTheme === 'dark' ? '#0B1220' : '#0D2B3B' }};
            --accent: {{ $pdfAccent }};
            --fs-base: {{ $pdfFontBase }}px;
            --fs-title: {{ $pdfFontTitle }}px;
            --fs-value: {{ $pdfFontValue }}px;
            --card-radius: {{ $pdfCardRadius }}mm;
            --card-pad: {{ $pdfCardPadding }}mm;
            --grid-gap: {{ $pdfGridSpacing }}mm;

            /* Match pdf/layout variables so slide backgrounds follow theme */
            --paper: {{ $pdfBg }};
            --ink: {{ $pdfTextColor }};
            --title: {{ $pdfTitleColor }};
            --panel: {{ $pdfTheme === 'dark' ? ((string)($pdfDarkStyle['card_color'] ?? '#111827')) : '#f4f8f7' }};
        }

        h2,
        .slide h2 {
            color: var(--title);
        }

        body{background: var(--paper); color: var(--ink);}

        @if(($pdfFormat ?? 'presentation') === 'mobile')
            :root {
                --fs-base: {{ max($pdfFontBase, 22) }}px;
                --fs-title: {{ max($pdfFontTitle, 34) }}px;
                --fs-value: {{ max($pdfFontValue, 26) }}px;
                --card-pad: {{ max($pdfCardPadding, 8) }}mm;
                --grid-gap: {{ max($pdfGridSpacing, 4) }}mm;
            }

            .cover {
                height: 100%;
            }

            .cover-row {
                display: block;
                height: 100%;
            }

            .cover-cell {
                display: block;
                width: 100%;
                padding: 8mm;
            }

            .cover-image-cell {
                width: 100%;
                padding: 0;
                height: 60%;
                overflow: hidden;
            }

            .cover-square-image {
                width: 100%;
                height: 100%;
                aspect-ratio: auto;
                object-fit: cover;
            }

            .cover-title {
                font-size: var(--fs-title);
                line-height: 1.15;
            }

            .badge {
                padding: 2.4mm 4.2mm;
            }

            .kpi-grid {
                border-spacing: 2.5mm;
            }

            .kpi-grid td {
                width: 50%;
            }

            .attr-grid {
                border-spacing: 3mm;
            }

            .attr-grid td {
                width: 100%;
                display: block;
            }

            .grid-2 td {
                width: 100%;
                display: block;
            }

            .map-hero {
                height: 100%;
            }

            .img-placeholder,
            .map-image {
                height: 70mm;
            }
        @endif

        .cover {
            display: table;
            table-layout: fixed;
            width: 100%;
            height: 100%;
            border-radius: 0;
            overflow: hidden;
            background-color: var(--brand-2);
            color: #ffffff;
            position: relative;
        }

        .cover-row {
            display: table-row;
            height: 100%;
        }

        .cover-cell {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
            padding: 8mm;
            position: relative;
            z-index: 1;
        }

        .cover-image-cell {
            width: 50%;
            padding: 0;
            text-align: center;
            vertical-align: middle;
        }

        .cover-square-image {
            display: block;
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }

        .cover-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .cover-grid h2 {
            margin: 0 0 4mm;
            font-size: var(--fs-base);
            line-height: 1.2;
            color: #ffffff;
            letter-spacing: -0.2px;
        }

        .cover-title {
            font-size: var(--fs-title);
            line-height: 1.15;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .cover-sub {
            margin-top: 5mm;
            font-size: var(--fs-base);
            opacity: 0.92;
        }

        .cover-price {
            margin-top: 6mm;
            font-size: var(--fs-value);
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .cover-badges {
            margin-top: 6mm;
        }

        .badge {
            display: inline-block;
            padding: 2mm 4mm;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            margin: 0 0 2mm 2mm;
            font-size: var(--fs-base);
            white-space: nowrap;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: var(--card-radius);
            padding: var(--card-pad);
            box-shadow: 0 14px 38px rgba(11, 18, 32, 0.10);
        }

        .card-title {
            color: var(--muted);
            font-size: var(--fs-base);
            margin-bottom: 2mm;
        }

        .section-lead {
            font-size: var(--fs-base);
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 6mm;
        }

        .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 12mm;
            height: 12mm;
            border-radius: 3mm;
            background: rgba(18, 107, 97, 0.10);
            border: 1px solid rgba(18, 107, 97, 0.18);
            color: var(--brand);
            flex: 0 0 auto;
        }

        .icon svg {
            width: 6mm;
            height: 6mm;
            display: block;
            fill: currentColor;
        }

        .attr-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4mm;
        }

        .attr-grid td {
            width: 50%;
            vertical-align: top;
        }

        .attr-row {
            display: table;
            width: 100%;
        }

        .attr-left {
            display: table-cell;
            width: 13mm;
            vertical-align: top;
        }

        .attr-right {
            display: table-cell;
            vertical-align: top;
            padding-inline-start: 3mm;
        }

        .attr-label {
            color: var(--muted);
            font-size: var(--fs-base);
            margin-bottom: 1mm;
        }

        .attr-value {
            color: var(--brand-2);
            font-size: var(--fs-value);
            font-weight: 900;
            line-height: 1.25;
        }

        .attr-sub {
            margin-top: 1.5mm;
            color: var(--muted);
            font-size: 20px;
        }

        .chips {
            margin-top: 2mm;
        }

        .chip {
            display: inline-table;
            border-collapse: separate;
            border-spacing: 2mm;
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 999px;
            padding: 2.4mm 3.8mm;
            margin: 0 0 3mm 3mm;
            box-shadow: 0 10px 22px rgba(11, 18, 32, 0.06);
        }

        .chip .chip-icon {
            display: table-cell;
            vertical-align: middle;
            width: 9mm;
        }

        .chip .chip-icon span {
            width: 8mm;
            height: 8mm;
            border-radius: 3mm;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(18, 107, 97, 0.10);
            border: 1px solid rgba(18, 107, 97, 0.18);
            color: var(--brand);
        }

        .chip .chip-icon svg {
            width: 4.5mm;
            height: 4.5mm;
            fill: currentColor;
            display: block;
        }

        .chip .chip-text {
            display: table-cell;
            vertical-align: middle;
            padding-inline-start: 1mm;
            color: var(--brand-2);
            font-size: var(--fs-base);
            font-weight: 700;
        }

        .attr-grid .card {
            background: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .chips .chip {
            background: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .map-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: var(--grid-gap);
        }

        .map-grid td {
            width: 50%;
            vertical-align: top;
        }

        .map-image {
            width: 100%;
            height: 60mm;
            object-fit: cover;
            border-radius: 4mm;
            border: 1px solid var(--stroke);
            box-shadow: 0 14px 34px rgba(11, 18, 32, 0.10);
        }

        .img-placeholder {
            width: 100%;
            height: 85mm;
            border-radius: 4mm;
            border: 1px solid var(--stroke);
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: var(--fs-base);
        }

        .qr {
            width: 26mm;
            height: 26mm;
            border-radius: 4mm;
            border: 1px solid var(--stroke);
            background: var(--card);
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            direction: ltr;
            text-align: left;
            font-size: var(--fs-base);
            word-break: break-all;
            color: var(--muted);
        }

        .grid-2 {
            width: 100%;
            border-collapse: separate;
            border-spacing: var(--grid-gap);
        }

        .grid-2 td {
            width: 50%;
            vertical-align: top;
        }

        .meta-row {
            display: table;
            width: 100%;
        }

        .meta-icon {
            display: table-cell;
            width: 12mm;
            vertical-align: top;
        }

        .meta-icon span {
            display: inline-block;
            width: 10mm;
            height: 10mm;
            border-radius: 3mm;
            background: var(--panel);
            border: 1px solid var(--stroke);
        }

        .meta-content {
            display: table-cell;
            vertical-align: top;
            padding-inline-start: 3mm;
        }

        .meta-label {
            color: var(--muted);
            font-size: 20px;
            margin-bottom: 1mm;
        }

        .meta-value {
            color: var(--brand-2);
            font-size: 20px;
            font-weight: 800;
            line-height: 1.25;
        }

        .kpi-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 3mm;
            margin-top: 2mm;
        }

        .kpi {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 3mm;
            padding: 4mm;
        }

        .kpi-label {
            color: #667577;
            font-size: 20px;
            margin-bottom: 1mm;
        }

        .kpi-value {
            color: #103f3b;
            font-size: 20px;
            font-weight: 800;
        }

        .section-lead {
            color: #667577;
            margin-top: -1mm;
            margin-bottom: 4mm;
        }

        .pill {
            display: inline-block;
            padding: 1.5mm 3mm;
            border-radius: 999px;
            background: #e8f4f1;
            color: #103f3b;
            margin: 0 0 2mm 2mm;
            font-size: 20px;
        }

        .profile-description {
            margin: 0;
            white-space: pre-line;
            line-height: 1.75;
        }

        .cta-row {
            margin-bottom: 5mm;
            font-size: var(--fs-value);
            line-height: 1.6;
        }

        .slide {
            box-sizing: border-box;
            min-height: calc(var(--page-h) - 48mm);
        }

        .slide:last-of-type,
        .slide:last-child {
            break-after: avoid !important;
            page-break-after: avoid !important;
        }

        .map-slide {
            padding: 0;
            height: calc(var(--page-h) - 48mm);
        }

        .map-canvas,
        .map-canvas.card {
            width: 100%;
            height: 100%;
            padding: 0;
            overflow: hidden;
            background: transparent;
            border: 0;
        }

        .map-hero {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
            border: 0;
            display: block;
        }

        .additional-info h2 {
            margin: 3mm 0 1.5mm;
        }

        .additional-info .section-lead {
            margin-bottom: 2mm;
        }

        .additional-info .info-grid {
            border-spacing: 1.5mm;
        }

        .additional-info .info-grid td {
            width: 33.333%;
            padding: 2mm;
        }

        .card .attr-label,
        .card .attr-sub,
        .card .mono,
        .card .muted,
        .card .card-title {
            color: {{ $pdfTheme === 'dark' ? ((string)($pdfDarkStyle['text_color'] ?? '#F8FAFC')) : '#0B1220' }};
        }
    </style>
@endpush

@section('cover-content')
    @php
        $pdf = is_array($pdfSettings ?? null) ? $pdfSettings : [];
        $pdfOnlySlideKey = is_string($pdfOnlySlide ?? null) && $pdfOnlySlide !== '' ? (string) $pdfOnlySlide : null;
        $pdfSlides = is_array(($pdf['slides'] ?? null)) ? ($pdf['slides'] ?? []) : [];
        $isEnabled = function (string $key) use ($pdfSlides): bool {
            return !array_key_exists($key, $pdfSlides) || !empty($pdfSlides[$key]);
        };
    @endphp

    @if((!$pdfOnlySlideKey || $pdfOnlySlideKey === 'cover') && $isEnabled('cover'))
        @include('pdf.product.slides.cover')
    @endif
@endsection

@section('pdf-content')
    @php
        $pdf = is_array($pdfSettings ?? null) ? $pdfSettings : [];
        $pdfSlides = is_array(($pdf['slides'] ?? null)) ? ($pdf['slides'] ?? []) : [];
        $pdfSlidesOrder = is_array(($pdf['slides_order'] ?? null)) ? array_values($pdf['slides_order']) : [];
        $pdfSlidesOrder = array_values(array_filter($pdfSlidesOrder, fn ($v) => is_string($v) && $v !== ''));
        $pdfSlidesOrder = $pdfSlidesOrder ?: ['details', 'location', 'features', 'offers', 'cta'];

        $isEnabled = function (string $key) use ($pdfSlides): bool {
            return !array_key_exists($key, $pdfSlides) || !empty($pdfSlides[$key]);
        };

        $pdfOnlySlideKey = is_string($pdfOnlySlide ?? null) && $pdfOnlySlide !== '' ? (string) $pdfOnlySlide : null;
        $onlyKey = $pdfOnlySlideKey;
        $order = $onlyKey ? [$onlyKey] : $pdfSlidesOrder;
    @endphp

    @php
        $svgIcons = [
            'map' => '<svg viewBox="0 0 24 24"><path d="M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3zm0-2.2l6 3V8.2l-6-3v10.6z"/></svg>',
            'grid' => '<svg viewBox="0 0 24 24"><path d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z"/></svg>',
            'layers' => '<svg viewBox="0 0 24 24"><path d="M12 3l9 6-9 6-9-6 9-6zm0 8.9L5.5 9 12 5.1 18.5 9 12 11.9zM3 13l2.2-1.5L12 16l6.8-4.5L21 13l-9 6-9-6z"/></svg>',
            'list' => '<svg viewBox="0 0 24 24"><path d="M7 5h14v2H7V5zm0 6h14v2H7v-2zm0 6h14v2H7v-2zM3 5h2v2H3V5zm0 6h2v2H3v-2zm0 6h2v2H3v-2z"/></svg>',
            'polygon' => '<svg viewBox="0 0 24 24"><path d="M6 3h4l2 4-2 4H6L4 7l2-4zm9 2h5l1 4-3 3h-3l-2-3 2-4zM7 13h5l2 4-3 4H6l-3-3 4-5z"/></svg>',
            'check' => '<svg viewBox="0 0 24 24"><path d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.5-1.5L9 16.2z"/></svg>',
            'ruler' => '<svg viewBox="0 0 24 24"><path d="M3 16l11-11 7 7-11 11H3v-7zm3 5h2v-2H6v2zm0-4h2v-2H6v2zm0-4h2v-2H6v2z"/></svg>',
            'road' => '<svg viewBox="0 0 24 24"><path d="M10 2h4l2 20h-4l-1-7-1 7H6L10 2zm1 5l-.5 3h3L13 7h-2z"/></svg>',
            'coins' => '<svg viewBox="0 0 24 24"><path d="M12 3c4.4 0 8 1.3 8 3s-3.6 3-8 3-8-1.3-8-3 3.6-3 8-3zm0 8c4.4 0 8-1.3 8-3v4c0 1.7-3.6 3-8 3s-8-1.3-8-3V8c0 1.7 3.6 3 8 3zm0 6c4.4 0 8-1.3 8-3v4c0 1.7-3.6 3-8 3s-8-1.3-8-3v-4c0 1.7 3.6 3 8 3z"/></svg>',
            'handshake' => '<svg viewBox="0 0 24 24"><path d="M7 13l3 3c1 1 2.6 1 3.6 0l2.8-2.8c.8-.8.8-2.1 0-2.9l-2.1-2.1c-1-1-2.6-1-3.6 0l-1.4 1.4L9 8.3 6.3 11l.7.7zm-4 0l3-3 3 3-3 3-3-3zm18 0l-3-3-3 3 3 3 3-3z"/></svg>',
            'store' => '<svg viewBox="0 0 24 24"><path d="M4 4h16l2 6v2h-2v8H4v-8H2v-2l2-6zm2 8v6h4v-6H6zm6 0v6h8v-6h-8z"/></svg>',
            'clone' => '<svg viewBox="0 0 24 24"><path d="M7 7h10v10H7V7zm-2 2H3V3h6v2H5v4zm16 10v4H15v-2h4v-2h2z"/></svg>',
            'target' => '<svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 4a6 6 0 110 12 6 6 0 010-12zm0 3a3 3 0 100 6 3 3 0 000-6z"/></svg>',
            'city' => '<svg viewBox="0 0 24 24"><path d="M3 21V8l7-5v18H3zm9 0V3h9v18h-9zm2-2h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V9h-2v2z"/></svg>',
            'pin' => '<svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 00-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z"/></svg>',
            'file' => '<svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v15a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2zm8 1v5h5"/></svg>',
        ];

        $additional = [];
        if (! empty($product->additional_info) && is_string($product->additional_info)) {
            $decoded = json_decode($product->additional_info, true);
            if (is_array($decoded)) {
                $additional = $decoded;
            }
        }

        $mapLat = $product->latitude;
        $mapLng = $product->longitude;
        $mapZoom = 16;

        $mapsUrl = $product->google_maps_url;
        $mapImageDataUri = $mapImageDataUri ?? null;

        $attrIconByKey = [];
        $featureIconByName = [];

        $headerBadges = [];
        if (!empty($cityName)) {
            $headerBadges[] = $cityName;
        }
        if (!empty($categoryName)) {
            $headerBadges[] = $categoryName;
        }
        if (!empty($product->reference_number)) {
            $headerBadges[] = 'Ref: '.$product->reference_number;
        }
    @endphp

    @php
        $attrs = $product->attributes ?? collect();
        $attrsByKey = method_exists($attrs, 'keyBy') ? $attrs->keyBy(fn ($a) => (string) ($a->key ?? '')) : collect();
        $firstAttrValue = function (array $keys) use ($attrsByKey) {
            foreach ($keys as $k) {
                $a = $attrsByKey->get($k);
                if ($a && isset($a->pivot) && (string) ($a->pivot->value ?? '') !== '') {
                    return (string) $a->pivot->value;
                }
            }
            return null;
        };

        // City and Category carry per-locale translations; fall back to the stored
        // name when the active locale has no translation row yet.
        $localCity = \App\Helpers\LanguageHelper::getCityName($product->city) ?: (string) ($product->city?->name ?? '');
        $localCategory = \App\Helpers\LanguageHelper::getCategoryName($product->category) ?: (string) ($product->category?->name ?? '');

        $summaryItems = [];
        if ($product->price) {
            $summaryItems[] = ['label' => __('pdf.summary.price'), 'value' => $product->formattedPrice()];
        }
        if ($localCategory !== '') {
            $summaryItems[] = ['label' => __('pdf.summary.type'), 'value' => $localCategory];
        }
        if ($localCity !== '') {
            $summaryItems[] = ['label' => __('pdf.summary.city'), 'value' => $localCity];
        }
        if ($product->neighborhood?->name) {
            $summaryItems[] = ['label' => __('pdf.summary.neighborhood'), 'value' => (string) $product->neighborhood->name];
        }
        if ($product->reference_number) {
            $summaryItems[] = ['label' => __('pdf.summary.reference'), 'value' => (string) $product->reference_number];
        }
        if ($product->facility?->name) {
            $summaryItems[] = ['label' => __('pdf.summary.provider'), 'value' => (string) $product->facility->name];
        }

        $area = $firstAttrValue(['area', 'total_area', 'land_area', 'building_area']);
        $rooms = $firstAttrValue(['rooms', 'bedrooms']);
        $baths = $firstAttrValue(['bathrooms', 'baths']);
        if ($area) {
            $summaryItems[] = ['label' => __('pdf.summary.area'), 'value' => $area];
        }
        if ($rooms) {
            $summaryItems[] = ['label' => __('pdf.summary.rooms'), 'value' => $rooms];
        }
        if ($baths) {
            $summaryItems[] = ['label' => __('pdf.summary.bathrooms'), 'value' => $baths];
        }

        $topAttrs = $attrs->filter(fn ($a) => (string) ($a->pivot->value ?? '') !== '')->take(6);
    @endphp

    @php
        // Headings follow the document locale unless an admin set a custom label.
        $slideLabel = fn (string $key) => app(\App\Services\PdfSettingsService::class)->slideLabel($key, $pdf, $language);

        // Output in requested order
    @endphp

    @if(!$onlyKey || $onlyKey === 'gallery')
        @include('pdf.product.slides.gallery', ['slideTitle' => $slideLabel('gallery')])
    @endif

    @php
        $regularOrder = array_values(array_filter($order, fn ($k) => $k !== 'cta'));
    @endphp

    @foreach($regularOrder as $k)
        @continue(!in_array($k, ['details', 'location', 'features', 'offers'], true))
        @continue(!$isEnabled($k))
        @includeIf('pdf.product.slides.' . $k, ['slideTitle' => $slideLabel($k)])
    @endforeach

    @if((!$onlyKey || $onlyKey === 'cta') && $isEnabled('cta'))
        @includeIf('pdf.product.slides.cta', ['slideTitle' => $slideLabel('cta')])
    @endif
@endsection
