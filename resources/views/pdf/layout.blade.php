@php
    // Resolve the document locale from the caller, falling back to the active app locale.
    $pdfLocale = (string) ($language ?? app()->getLocale());
    $pdfDir = in_array($pdfLocale, \App\Services\LanguageService::rtlLocales(), true) ? 'rtl' : 'ltr';
@endphp
<!doctype html>
<html lang="{{ $pdfLocale }}" dir="{{ $pdfDir }}">
<head>
    <meta charset="utf-8">

    @php
        $pageWidthIn = (float) ($pageWidthIn ?? 13.333);
        $pageHeightIn = (float) ($pageHeightIn ?? 7.5);
    @endphp
    <style>
        @page {
            size: {{ rtrim(rtrim(number_format($pageWidthIn, 3, '.', ''), '0'), '.') }}in {{ rtrim(rtrim(number_format($pageHeightIn, 3, '.', ''), '0'), '.') }}in;
            margin: 36mm 0 12mm 0;
        }

        :root {
            --page-w: {{ rtrim(rtrim(number_format($pageWidthIn, 3, '.', ''), '0'), '.') }}in;
            --page-h: {{ rtrim(rtrim(number_format($pageHeightIn, 3, '.', ''), '0'), '.') }}in;
            --brand: #126b61;
            --brand-2: #103f3b;
            --ink: #162222;
            --muted: #667577;
            --paper: #ffffff;
            --panel: #f4f8f7;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            color: var(--ink);
            /* Locale-aware stack: Arabic/Urdu shaping, CJK glyphs, then Latin fallbacks */
            font-family:
                @if($pdfDir === 'rtl') 'IBM Plex Sans Arabic', 'Cairo', 'Noto Naskh Arabic', 'Segoe UI', Tahoma,
                @elseif($pdfLocale === 'zh') 'Noto Sans SC', 'Microsoft YaHei', 'PingFang SC', 'Segoe UI',
                @else 'Figtree', 'Inter', 'Segoe UI',
                @endif
                'DejaVu Sans', Arial, sans-serif;
            font-size: 20px;
            line-height: 1.55;
            background: var(--paper);
        }

        .print-shell {
            width: 100%;
            border-collapse: collapse;
        }

        .print-shell td,
        .print-shell th {
            padding: 0;
        }

        thead {
            display: table-header-group;
        }


        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            break-inside: avoid;
            page-break-after: auto;
        }

        .print-shell tbody > tr {
            break-inside: auto;
            page-break-inside: auto;
        }

        td,
        th {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .print-content {
            padding: 0;
            vertical-align: top;
            break-inside: auto;
            page-break-inside: auto;
        }


        main {
            width: 100%;
            margin: 0;
            padding: 0;
            counter-reset: slide 1;
        }

        .slide {
            position: relative;
            min-height: calc(var(--page-h) - 18mm);
            padding: 8mm 5% 14mm;
            background-color: var(--paper);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            counter-increment: slide;
            page-break-after: auto;
            break-after: auto;
        }

        .slide::after {
            content: '';
            display: none;
        }

        .slide:not(:last-of-type) {
            page-break-after: always;
            break-after: page;
        }

        .slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                repeating-linear-gradient(0deg, rgba(18, 107, 97, 0.035) 0, rgba(18, 107, 97, 0.035) 1px, transparent 1px, transparent 12mm),
                repeating-linear-gradient(90deg, rgba(18, 107, 97, 0.035) 0, rgba(18, 107, 97, 0.035) 1px, transparent 1px, transparent 12mm),
                radial-gradient(400px 300px at 15% 20%, rgba(18, 107, 97, 0.08), transparent 60%),
                radial-gradient(360px 260px at 85% 18%, rgba(16, 63, 59, 0.06), transparent 55%),
                radial-gradient(520px 320px at 70% 90%, rgba(18, 107, 97, 0.05), transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .slide > * {
            position: relative;
            z-index: 1;
        }

        .header {
            height: 38mm;
            padding: 5mm 0 0;
            background: var(--paper);
            box-sizing: border-box;
        }

        .header-inner {
            padding: 0 5%;
        }

        .header-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 40%;
            text-align: left;
            direction: ltr;
            vertical-align: top;
        }

        .header-center {
            width: 20%;
            text-align: center;
            vertical-align: top;
        }

        .header-right {
            width: 40%;
            text-align: right;
            direction: rtl;
            vertical-align: top;
        }

        .brand-name {
            color: #7a8a8c;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.25;
        }

        .brand-sub {
            margin-top: 2mm;
            color: #7a8a8c;
            font-size: 20px;
            font-weight: 500;
            line-height: 1.2;
        }

        .brand-logo {
            display: inline-block;
            width: 18mm;
            height: 18mm;
        }

        .brand-logo img {
            display: block;
            width: 18mm;
            height: 18mm;
            object-fit: contain;
        }

        .header-divider {
            height: 0;
            margin-top: 0;
            border-top: 2px solid #8ea0a2;
            opacity: 0.85;
        }

        .footer {
            width: 100%;
            height: 12mm;
            padding: 0;
            background: red;
        }

        .footer-inner {
            padding: 0 5%;
        }

        .footer-divider {
            height: 0;
            border-top: 1px solid #d7e1e0;
        }

        .footer-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4mm;
            color: var(--muted);
            font-size: 20px;
        }

        .footer-grid .page-count {
            width: 28mm;
            text-align: center;
            direction: ltr;
            white-space: nowrap;
        }

        .page-number::before {
            content: '2';
        }

        .footer-grid td {
            vertical-align: middle;
        }

        .footer-grid .qr {
            width: 13mm;
            text-align: end;
        }

        .footer-grid .qr img {
            width: 11mm;
            height: 11mm;
        }

        .header {
            position: relative;
            height: 36mm;
            padding: 3mm 5% 0;
            background: transparent;
            box-sizing: border-box;
        }

        .header-meta {
            position: absolute;
            top: 1.5mm;
            left: 5%;
            right: auto;
            max-width: 45%;
            height: 36mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            text-align: left;
            direction: ltr;
            color: #0b1220;
        }

        .header-meta .title,
        .header-meta .sub {
            direction: rtl;
        }

        .header-meta .title {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-meta .sub {
            margin-top: 2mm;
            font-size: 18px;
            font-weight: 600;
            color: #667577;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-logo {
            position: absolute;
            top: 1.5mm;
            right: 5mm;
            display: inline-block;
            width: 36mm;
            height: 36mm;
        }

        .brand-logo img {
            display: block;
            width: 36mm;
            height: 36mm;
            object-fit: contain;
        }

        .cover-page {
            width: 100%;
            height: calc(var(--page-h) - 48mm);
            overflow: hidden;
            page-break-after: always;
            break-after: page;
        }
    </style>
    @stack('styles')
</head>

<body>

    @if(($watermark ?? null) !== null)
        <div class="watermark">
            {{ $watermark }}
        </div>
    @endif

    @hasSection('cover-content')
        <div class="cover-page">
            @yield('cover-content')
        </div>
    @endif

    @php
        $pdfContentHtml = trim((string) $__env->yieldContent('pdf-content'));
    @endphp
    @if($pdfContentHtml !== '')
    <main>
        {!! $pdfContentHtml !!}
    </main>
    @endif

</body>
</html>
