@php
    $t = $executionRequest->translations->firstWhere('locale', app()->getLocale());
    $title = $t->title ?? ('طلب تنفيذ #' . $executionRequest->id);
    $snap = is_array($bid->data ?? null) ? ($bid->data['final_snapshot'] ?? null) : null;
    $hasSnap = is_array($snap);

    $notes = $hasSnap ? ($snap['notes'] ?? null) : ($bid->data['notes'] ?? null);
    $technicalPlan = $hasSnap ? ($snap['technical_plan'] ?? null) : ($bid->data['technical']['plan'] ?? null);
    $financialBreakdown = $hasSnap ? ($snap['financial_breakdown'] ?? null) : ($bid->data['financial']['breakdown'] ?? null);
    $items = $hasSnap ? (($snap['financial_items'] ?? []) ?: []) : (($bid->data['financial']['items'] ?? []) ?: []);
    $attachments = $hasSnap ? (($snap['attachments'] ?? []) ?: []) : (($bid->data['attachments'] ?? []) ?: []);
@endphp

<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>عرض - {{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .muted { color: #6b7280; }
        .h1 { font-size: 18px; font-weight: 700; margin: 0 0 8px; }
        .h2 { font-size: 14px; font-weight: 700; margin: 16px 0 6px; }
        .box { border: 1px solid #e5e7eb; padding: 10px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; }
        th { background: #f9fafb; text-align: right; }
    </style>
</head>
<body>
    <div class="h1">العرض المقدم</div>
    <div class="muted">طلب: {{ $title }}</div>
    <div class="muted">رقم الطلب: {{ $executionRequest->id }}</div>
    <div class="muted">تاريخ الإرسال: {{ !empty($bid->data['submitted_at']) ? $bid->data['submitted_at'] : $bid->created_at }}</div>

    <div class="h2">بيانات العرض</div>
    <div class="box">
        <table>
            <tr>
                <th style="width: 25%">المنشأة</th>
                <td>{{ $facility->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>قيمة العرض</th>
                <td>{{ $bid->price_total !== null ? number_format((float)$bid->price_total, 2) . ' ' . ($bid->currency ?? 'SAR') : '—' }}</td>
            </tr>
            <tr>
                <th>مدة التنفيذ</th>
                <td>{{ $bid->duration_days ? ($bid->duration_days . ' يوم') : '—' }}</td>
            </tr>
            <tr>
                <th>الضمان</th>
                <td>{{ $bid->warranty_months !== null ? ($bid->warranty_months . ' شهر') : '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="h2">ملخص</div>
    <div class="box">{{ $notes ?: '—' }}</div>

    <div class="h2">العرض الفني</div>
    <div class="box">{!! nl2br(e($technicalPlan ?: '—')) !!}</div>

    <div class="h2">العرض المالي</div>
    <div class="box">{!! nl2br(e($financialBreakdown ?: '—')) !!}</div>

    @if(is_array($items) && count($items))
        <div class="h2">بنود العرض المالي</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40%">البند</th>
                    <th style="width: 15%">الكمية</th>
                    <th style="width: 20%">سعر الوحدة</th>
                    <th style="width: 25%">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $row)
                    @php
                        $name = $row['name'] ?? '';
                        $qty = $row['qty'] ?? '';
                        $unit = $row['unit_price'] ?? '';
                        $total = $row['total'] ?? '';
                    @endphp
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $qty }}</td>
                        <td>{{ $unit }}</td>
                        <td>{{ $total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="h2">المرفقات</div>
    <div class="box">
        @if(is_array($attachments) && count($attachments))
            @php
                $grouped = [];
                foreach ($attachments as $a) {
                    if (!is_array($a)) {
                        continue;
                    }
                    $type = (string) ($a['type'] ?? 'general');
                    $grouped[$type] = $grouped[$type] ?? [];
                    $grouped[$type][] = $a;
                }
            @endphp

            @foreach($grouped as $type => $rows)
                <div class="muted" style="margin-bottom:6px;"><strong>({{ $type }})</strong></div>
                <ul style="margin:0 0 10px; padding:0 18px;">
                    @foreach($rows as $a)
                        <li>{{ $a['original_name'] ?? ($a['path'] ?? 'ملف') }}</li>
                    @endforeach
                </ul>
            @endforeach
        @else
            —
        @endif
    </div>
</body>
</html>
