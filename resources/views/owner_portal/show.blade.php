<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة المالك — {{ $owner->facility->name ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:960px">

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">{{ $owner->facility->name ?? '' }}</h4>
        <div class="text-muted small">بوابة المالك — {{ $owner->name }}</div>
    </div>

    <div class="row g-3 mb-4 text-center">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm py-3"><div class="text-muted small">عقاراتك</div><div class="h4 fw-bold">{{ $totals['properties'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm py-3"><div class="text-muted small">المحصّل من عقاراتك</div><div class="h4 fw-bold text-success">{{ number_format($totals['collected'], 0) }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm py-3"><div class="text-muted small">سُدّد لك</div><div class="h4 fw-bold">{{ number_format($totals['paid'], 0) }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm py-3 {{ $totals['due'] > 0 ? 'border-danger' : '' }}">
                <div class="text-muted small">مستحق لك (صافي)</div>
                <div class="h4 fw-bold {{ $totals['due'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($totals['due'], 0) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold"><i class="fas fa-building me-1"></i> عقاراتك</div>
                <div class="card-body">
                    @forelse($owner->products as $product)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span class="small fw-semibold">{{ $product->getTranslatedTitle() ?? '#'.$product->id }}</span>
                            <span class="text-muted small">{{ $product->category?->getTranslatedName() ?? '' }}</span>
                        </div>
                    @empty
                        <div class="text-muted text-center small py-3">لا عقارات مرتبطة</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">العقود على عقاراتك ({{ $contracts->count() }})</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th class="ps-3">العقد</th><th>العقار</th><th>المحصّل</th><th>الحالة</th></tr>
                        </thead>
                        <tbody>
                            @forelse($contracts as $contract)
                                <tr>
                                    <td class="ps-3 small">{{ $contract->contract_number ?? '#'.$contract->id }}</td>
                                    <td class="small">{{ $contract->product?->getTranslatedTitle() ?? '—' }}</td>
                                    <td class="small text-success">{{ number_format($contract->payments_sum_amount ?? 0, 0) }}</td>
                                    <td><span class="badge {{ $contract->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $contract->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">لا عقود</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white fw-bold">التسويات المسددة لك ({{ $payouts->count() }})</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th class="ps-3">التاريخ</th><th>المبلغ</th><th>الطريقة</th><th>ملاحظات</th></tr></thead>
                <tbody>
                    @forelse($payouts as $payout)
                        <tr>
                            <td class="ps-3">{{ $payout->paid_at?->format('Y-m-d') }}</td>
                            <td class="text-success fw-semibold">{{ number_format($payout->amount, 0) }}</td>
                            <td>{{ $payout->payment_method ?? '—' }}</td>
                            <td class="text-muted small">{{ $payout->notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">لا تسويات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center text-muted small mt-4">بيانات لحظية — بوابة آمنة برابط موقّع</div>
</div>
</body>
</html>
