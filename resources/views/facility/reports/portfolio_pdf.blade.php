<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; direction: rtl; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        .muted { color: #777; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th { background: #f3f4f6; border: 1px solid #ddd; padding: 7px; font-size: 10px; }
        td { border: 1px solid #ddd; padding: 7px; font-size: 10px; }
        .green { color: #047857; } .red { color: #dc2626; }
        tfoot td { font-weight: bold; background: #f9fafb; }
    </style>
</head>
<body>
    <h1>محفظة الأصول</h1>
    <div class="muted">{{ $facility->name ?? '' }} — صدر بتاريخ {{ now()->format('Y-m-d') }}</div>

    <table>
        <thead>
            <tr><th>الأصل</th><th>الوحدات</th><th>الإشغال</th><th>عقود نشطة</th><th>المحصّل</th><th>متأخرات</th><th>صافي الربح</th></tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->asset->getTranslatedTitle() ?? '#'.$row->asset->id }}</td>
                    <td>{{ $row->units }}</td>
                    <td>{{ $row->occupancy }}%</td>
                    <td>{{ $row->contracts }}</td>
                    <td class="green">{{ number_format($row->collected, 0) }}</td>
                    <td class="{{ $row->arrears > 0 ? 'red' : '' }}">{{ number_format($row->arrears, 0) }}</td>
                    <td class="{{ $row->profit >= 0 ? 'green' : 'red' }}">{{ number_format($row->profit, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>الإجمالي</td>
                <td>{{ $rows->sum('units') }}</td>
                <td>{{ $rows->sum('units') ? round($rows->sum('occupied') / $rows->sum('units') * 100) : 0 }}%</td>
                <td>{{ $rows->sum('contracts') }}</td>
                <td class="green">{{ number_format($rows->sum('collected'), 0) }}</td>
                <td class="red">{{ number_format($rows->sum('arrears'), 0) }}</td>
                <td>{{ number_format($rows->sum('profit'), 0) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
