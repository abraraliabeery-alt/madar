<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>رد المناقصة</title>
    <style>
        @page { margin: 40px; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 13px;
            color: #1f2937;
            line-height: 1.8;
            direction: rtl;
        }
        .header {
            border-bottom: 3px solid {{ $brandColor }};
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo {
            max-height: 60px;
            max-width: 160px;
        }
        .facility-name {
            font-size: 18px;
            font-weight: bold;
            color: {{ $brandColor }};
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #111827;
        }
        .content {
            text-align: justify;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <div class="facility-name">{{ $facility->name }}</div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                        {{ $facility->facilityCategory?->name }} &middot; {{ $facility->city?->name }}
                    </div>
                </td>
                <td style="text-align: left;">
                    @if($facility->logo_path)
                        <img src="{{ storage_path('app/public/' . $facility->logo_path) }}" class="logo" alt="logo">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="title">رد المناقصة: {{ $tender->title }}</div>

    <div class="content">
        {!! nl2br(e($tender->data['response'] ?? '')) !!}
    </div>

    <div class="footer">
        تم توليد هذا الرد بمساعدة الذكاء الاصطناعي &middot; {{ now()->format('Y-m-d') }}
    </div>
</body>
</html>
