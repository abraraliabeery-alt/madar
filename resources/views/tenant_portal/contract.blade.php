<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة المستأجر — {{ $contract->facility->name ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px">

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">{{ $contract->facility->name ?? 'بوابة المستأجر' }}</h4>
        <div class="text-muted small">بوابة المستأجر — كشف العقد</div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}</div>
    @endif

    {{-- ملخص العقد --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold"><i class="fas fa-file-signature me-1"></i> عقدك</div>
        <div class="card-body">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="text-muted small">رقم العقد</div>
                    <div class="fw-bold">{{ $contract->contract_number ?? '#'.$contract->id }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">العقار</div>
                    <div class="fw-bold">{{ $contract->product?->getTranslatedTitle() ?? '—' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">من</div>
                    <div class="fw-bold">{{ $contract->start_date?->format('Y-m-d') }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small">إلى</div>
                    <div class="fw-bold">{{ $contract->end_date?->format('Y-m-d') ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- الفواتير --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">الفواتير</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th class="ps-3">رقم</th><th>الاستحقاق</th><th>المبلغ</th><th>المتبقي</th><th>الحالة</th><th class="pe-3"></th></tr>
                        </thead>
                        <tbody>
                            @forelse($contract->invoices as $inv)
                                <tr>
                                    <td class="ps-3">{{ $inv->invoice_number ?? '#'.$inv->id }}</td>
                                    <td>{{ $inv->due_date?->format('Y-m-d') }}</td>
                                    <td>{{ number_format($inv->amount, 2) }}</td>
                                    <td class="{{ $inv->remaining_amount > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                        {{ number_format($inv->remaining_amount, 2) }}
                                    </td>
                                    <td>
                                        @if($inv->remaining_amount <= 0)
                                            <span class="badge bg-success">مسدّدة</span>
                                        @elseif($inv->due_date && $inv->due_date->isPast())
                                            <span class="badge bg-danger">متأخرة</span>
                                        @else
                                            <span class="badge bg-warning text-dark">قادمة</span>
                                        @endif
                                    </td>
                                    <td class="pe-3">
                                        @if($inv->remaining_amount > 0 && $moyasarEnabled)
                                            <a href="{{ URL::signedRoute('tenant-portal.pay', ['contract' => $contract->id, 'invoice' => $inv->id]) }}"
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-credit-card me-1"></i>ادفع الآن
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">لا فواتير</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- طلب صيانة --}}
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-screwdriver-wrench me-1"></i> طلب صيانة جديد</div>
                <div class="card-body">
                    <form method="POST" action="{{ URL::signedRoute('tenant-portal.maintenance', ['contract' => $contract->id]) }}">
                        @csrf
                        <div class="mb-2">
                            <select name="type" class="form-select form-select-sm">
                                <option value="maintenance">صيانة</option>
                                <option value="cleaning">نظافة</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="عنوان الطلب *" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="اشرح المشكلة..."></textarea>
                        </div>
                        <button class="btn btn-primary btn-sm w-100"><i class="fas fa-paper-plane me-1"></i> إرسال الطلب</button>
                    </form>
                </div>
            </div>

            {{-- طلباته السابقة --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">طلبات الصيانة السابقة</div>
                <div class="card-body">
                    @forelse($maintenanceRequests as $req)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div class="small">{{ $req->title }}</div>
                            <span class="badge {{ $req->status === 'completed' ? 'bg-success' : ($req->status === 'open' ? 'bg-warning text-dark' : 'bg-info') }}">
                                {{ ['open' => 'جديد', 'in_progress' => 'قيد التنفيذ', 'completed' => 'مكتمل', 'cancelled' => 'ملغي'][$req->status] ?? $req->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted small text-center">لا طلبات سابقة</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- آخر الدفعات --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white fw-bold">آخر الدفعات</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th class="ps-3">التاريخ</th><th>المبلغ</th><th>الطريقة</th><th>المرجع</th></tr></thead>
                <tbody>
                    @forelse($contract->payments->take(10) as $payment)
                        <tr>
                            <td class="ps-3">{{ $payment->payment_date?->format('Y-m-d') }}</td>
                            <td class="text-success fw-semibold">{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_method ?? '—' }}</td>
                            <td class="text-muted small">{{ $payment->reference_number ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">لا دفعات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center text-muted small mt-4">بوابة آمنة برابط موقّع — {{ $contract->facility->name ?? '' }}</div>
</div>
</body>
</html>
