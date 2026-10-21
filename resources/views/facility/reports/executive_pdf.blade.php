<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التقرير التنفيذي — {{ $facility->name ?? '' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; font-size: 11px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 13px; margin: 16px 0 6px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
        .meta { color: #777; font-size: 10px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #ddd; padding: 5px 7px; text-align: right; }
        th { background: #f3f4f6; font-weight: bold; }
        .kpis { width: 100%; border-collapse: separate; border-spacing: 6px; margin: 8px 0; }
        .kpis td { border: 1px solid #ddd; border-radius: 6px; padding: 10px; text-align: center; width: 25%; }
        .kpis .v { font-size: 17px; font-weight: bold; }
        .kpis .l { font-size: 10px; color: #666; }
        .red { color: #dc2626; } .green { color: #16a34a; }
    </style>
</head>
<body>
    <h1>التقرير التنفيذي — {{ $facility->name ?? '' }}</h1>
    <div class="meta">تاريخ التقرير: {{ now()->format('Y-m-d H:i') }}</div>

    <table class="kpis">
        <tr>
            <td><div class="v">{{ $occupancyRate }}%</div><div class="l">نسبة الإشغال ({{ $occupiedUnits }}/{{ $totalUnits }})</div></td>
            <td><div class="v">{{ $collectionRate }}%</div><div class="l">نسبة التحصيل</div></td>
            <td><div class="v green">{{ number_format($totalCollected, 0) }}</div><div class="l">إجمالي التحصيل</div></td>
            <td><div class="v red">{{ number_format($totalArrears, 0) }}</div><div class="l">المتأخرات</div></td>
        </tr>
    </table>

    <h2>إيراد آخر 6 أشهر</h2>
    <table>
        <tr>
            @foreach($months as $m)<th>{{ $m['label'] }}</th>@endforeach
        </tr>
        <tr>
            @foreach($months as $m)<td>{{ number_format($m['revenue'], 0) }}</td>@endforeach
        </tr>
    </table>

    <h2>أعلى العقارات تحصيلاً</h2>
    <table>
        <thead><tr><th>العقار</th><th>إجمالي المحصّل</th></tr></thead>
        <tbody>
            @forelse($topProducts as $tp)
                <tr>
                    <td>{{ $topProductNames->get($tp->product_id)?->getTranslatedTitle() ?? '#'.$tp->product_id }}</td>
                    <td>{{ number_format($tp->total, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="2">لا بيانات</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>عقود تنتهي خلال 60 يوم ({{ $expiringContracts->count() }})</h2>
    <table>
        <thead><tr><th>العقد</th><th>العقار</th><th>المستأجر</th><th>تاريخ الانتهاء</th></tr></thead>
        <tbody>
            @forelse($expiringContracts as $c)
                <tr>
                    <td>{{ $c->contract_number ?? '#'.$c->id }}</td>
                    <td>{{ $c->product?->getTranslatedTitle() ?? '—' }}</td>
                    <td>{{ $c->tenant?->getTranslatedName() ?? $c->user?->name ?? '—' }}</td>
                    <td>{{ $c->end_date?->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">لا عقود منتهية قريباً</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>رادار المخاطر</h2>
    <table>
        <thead><tr><th>المؤشر</th><th>العدد</th></tr></thead>
        <tbody>
            <tr><td>وحدات شاغرة +60 يوم</td><td class="{{ $vacantLong ? 'red' : '' }}">{{ $vacantLong }}</td></tr>
            <tr><td>صيانة مفتوحة +14 يوم</td><td class="{{ $agingMaintenance ? 'red' : '' }}">{{ $agingMaintenance }}</td></tr>
            <tr><td>عقود متأخرة +15 يوم</td><td class="{{ $criticalContracts ? 'red' : '' }}">{{ $criticalContracts }}</td></tr>
            <tr><td>صيانة مفتوحة حالياً</td><td>{{ $openMaintenance }}</td></tr>
        </tbody>
    </table>
</body>
</html>
