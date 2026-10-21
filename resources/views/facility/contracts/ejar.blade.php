@extends('facility.layouts.app')

@section('title', 'بيانات إيجار: ' . ($contract->contract_number ?? '#'.$contract->id))

@section('content')
<div class="container-fluid" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">بيانات توثيق إيجار</h1>
            <span class="text-muted small">عقد {{ $contract->contract_number ?? '#'.$contract->id }} — انسخ البيانات عند إدخالها في منصة إيجار</span>
        </div>
        <a href="{{ route('facility.contracts.show', $contract) }}" class="btn btn-sm btn-outline-secondary">رجوع للعقد</a>
    </div>

    <div class="alert alert-info small">
        <i class="fas fa-info-circle me-1"></i>
        وثّق العقد في <a href="https://www.ejar.sa" target="_blank" class="alert-link">ejar.sa</a>، ثم ارجع وسجّل رقم التوثيق في صفحة العقد.
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">بيانات العقد</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><td class="ps-3 text-muted" style="width:45%">نوع العقد</td><td class="fw-semibold">{{ $contract->contract_type === 'rent' ? 'إيجار سكني/تجاري' : ($contract->contract_type ?? '—') }}</td></tr>
                            <tr><td class="ps-3 text-muted">رقم العقد الداخلي</td><td class="fw-semibold">{{ $contract->contract_number ?? '#'.$contract->id }}</td></tr>
                            <tr><td class="ps-3 text-muted">تاريخ البداية</td><td class="fw-semibold">{{ $contract->start_date?->format('Y-m-d') ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">تاريخ النهاية</td><td class="fw-semibold">{{ $contract->end_date?->format('Y-m-d') ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">مدة العقد</td><td class="fw-semibold">{{ $contract->contract_duration_months ?? '—' }} شهر</td></tr>
                            <tr><td class="ps-3 text-muted">قيمة الإيجار الإجمالية</td><td class="fw-semibold text-success">{{ number_format($contract->total_amount, 2) }} ريال</td></tr>
                            <tr><td class="ps-3 text-muted">التأمين (الضمان)</td><td class="fw-semibold">{{ number_format($contract->deposit_amount ?? 0, 2) }} ريال</td></tr>
                            <tr><td class="ps-3 text-muted">دورة السداد</td><td class="fw-semibold">{{ ['monthly'=>'شهري','quarterly'=>'ربع سنوي','yearly'=>'سنوي'][$contract->payment_frequency] ?? $contract->payment_frequency ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">عدد الدفعات</td><td class="fw-semibold">{{ $contract->total_installments ?? $contract->invoices->count() }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">بيانات الأطراف والعقار</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><td class="ps-3 text-muted" style="width:45%">المستأجر</td><td class="fw-semibold">{{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">جوال المستأجر</td><td class="fw-semibold" dir="ltr">{{ $contract->tenant?->phone ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">مالك العقار</td><td class="fw-semibold">{{ $contract->propertyOwner?->name ?? $contract->owner?->name ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">العقار/الوحدة</td><td class="fw-semibold">{{ $contract->product?->getTranslatedTitle() ?? '—' }}</td></tr>
                            <tr><td class="ps-3 text-muted">الوسيط (المنشأة)</td><td class="fw-semibold">{{ $facility->name ?? '—' }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white fw-bold">جدول الدفعات ({{ $contract->invoices->count() }})</div>
                <div class="card-body p-0" style="max-height:250px;overflow-y:auto">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th class="ps-3">الاستحقاق</th><th>المبلغ</th></tr></thead>
                        <tbody>
                            @forelse($contract->invoices as $inv)
                                <tr><td class="ps-3">{{ $inv->due_date?->format('Y-m-d') }}</td><td>{{ number_format($inv->amount, 2) }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">لا فواتير</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
