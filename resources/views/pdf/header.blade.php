<!doctype html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }
        .pdf-header {
            position: relative;
            width: 100%;
            height: 36mm;
            padding: 3mm 5% 0;
            background: {{ $pdfBg ?? '#ffffff' }};
            color: {{ $pdfTextColor ?? '#162222' }};
            font-family: DejaVu Sans, sans-serif;
            font-size: 20px;
            overflow: hidden;
        }
        .pdf-header .header-meta {
            position: absolute;
            top: 1.5mm;
            left: 5%;
            right: auto;
            max-width: 45%;
            height: 32mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            text-align: left;
            direction: ltr;
        }
        .pdf-header .title {
            direction: rtl;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: {{ $pdfTextColor ?? '#162222' }};
        }
        .pdf-header .sub {
            direction: rtl;
            margin-top: 2mm;
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: {{ $pdfMuted ?? '#667577' }};
        }
        .pdf-header .brand-logo {
            position: absolute;
            top: 1.5mm;
            right: 5mm;
            display: inline-block;
            width: 32mm;
            height: 32mm;
        }
        .pdf-header .brand-logo img {
            display: block;
            width: 32mm;
            height: 32mm;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="pdf-header">
        @if(($headerTitle ?? '') !== '' || ($headerSubtitle ?? '') !== '')
            <div class="header-meta">
                @if(($headerTitle ?? '') !== '')
                    <div class="title">{{ $headerTitle }}</div>
                @endif
                @if(($headerSubtitle ?? '') !== '')
                    <div class="sub">{{ $headerSubtitle }}</div>
                @endif
            </div>
        @endif

        @if(!empty($logoDataUri))
            <span class="brand-logo">
                <img src="{{ $logoDataUri }}" alt="Logo">
            </span>
        @endif
    </div>
</body>
</html>