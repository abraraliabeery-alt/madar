<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; direction: rtl; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        .muted { color: #777; font-size: 10px; }
        .summary { width: 100%; border-collapse: collapse; margin: 14px 0; }
        .summary td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .summary .label { color: #777; font-size: 9px; }
        .summary .value { font-size: 13px; font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #f3f4f6; border: 1px solid #ddd; padding: 6px; font-size: 10px; }
        table.data td { border: 1px solid #ddd; padding: 6px; font-size: 10px; }
        .green { color: #047857; } .red { color: #dc2626; }
        .section-title { font-size: 13px; font-weight: bold; margin-top: 18px; border-bottom: 2px solid #2563eb; padding-bottom: 4px; }
    </style>
</head>
<body>
    <h1>كشف حساب مالك</h1>
    <div class="muted">
        {{ $facility->name ?? '' }} — صدر بتاريخ {{ now()->format('Y-m-d') }}<br>
        المالك: <strong>{{ $owner->name }}</strong>{{ $owner->is_company ? ' (شركة)' : '' }}
        {{ $owner->phone ? ' — ' . $owner->phone : '' }}
    </div>

    <table class="summary">
        <tr>
            <td><div class="label">قيمة العقود</div><div class="value">{{ number_format($totals['contracts_value'], 2) }}</div></td>
            <td><div class="label">المحصّل</div><div class="value green">{{ number_format($totals['collected'], 2) }}</div></td>
            <td><div class="label">عمولة المنشأة</div><div class="value">{{ number_format($totals['commission'], 2) }}</div></td>
            <td><div class="label">سُدّد للمالك</div><div class="value">{{ number_format($totals['paid_to_owner'], 2) }}</div></td>
            <td><div class="label">مستحق المالك</div><div class="value {{ $totals['owner_due'] >= 0 ? 'green' : 'red' }}">{{ number_format($totals['owner_due'], 2) }}</div></td>
        </tr>
    </table>

    <div class="section-title">العقود ({{ $contracts->count() }})</div>
    <table class="data">
        <thead>
            <tr><th>العقد</th><th>العقار</th><th>المستأجر</th><th>القيمة</th><th>المحصّل</th><th>الحالة</th></tr>
        </thead>
        <tbody>
            @forelse($contracts as $contract)
                <tr>
                    <td>{{ $contract->contract_number ?? '#'.$contract->id }}</td>
                    <td>{{ $contract->product?->getTranslatedTitle() ?? '—' }}</td>
                    <td>{{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}</td>
                    <td>{{ number_format($contract->total_amount, 2) }}</td>
                    <td class="green">{{ number_format($contract->payments_sum_amount ?? 0, 2) }}</td>
                    <td>{{ $contract->status }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center">لا عقود</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">سجل التسويات ({{ $payouts->count() }})</div>
    <table class="data">
        <thead>
            <tr><th>التاريخ</th><th>المبلغ</th><th>الطريقة</th><th>ملاحظات</th></tr>
        </thead>
        <tbody>
            @forelse($payouts as $payout)
                <tr>
                    <td>{{ $payout->paid_at?->format('Y-m-d') }}</td>
                    <td class="green">{{ number_format($payout->amount, 2) }}</td>
                    <td>{{ $payout->payment_method ?? '—' }}</td>
                    <td>{{ $payout->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center">لا تسويات</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
