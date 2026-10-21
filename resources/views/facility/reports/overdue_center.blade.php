@extends('facility.layouts.app')

@section('title', 'مركز المتأخرات')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">مركز المتأخرات</h1>
            <span class="text-muted small">كل ما يحتاج متابعة تحصيل في مكان واحد</span>
        </div>
        <div class="badge bg-danger fs-6">إجمالي المتأخرات: {{ number_format($totalArrears, 0) }} ر.س</div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            فواتير متأخرة ({{ $overdueInvoices->count() }})
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">الفاتورة</th>
                        <th>العقد</th>
                        <th>المستأجر</th>
                        <th>العقار</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>أيام التأخير</th>
                        <th>المتبقي</th>
                        <th class="pe-3">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueInvoices as $invoice)
                        <tr>
                            <td class="ps-3">#{{ $invoice->invoice_number ?? $invoice->id }}</td>
                            <td>
                                @if($invoice->contract)
                                    <a href="{{ route('facility.contracts.show', $invoice->contract) }}">
                                        {{ $invoice->contract->contract_number ?? '#'.$invoice->contract->id }}
                                    </a>
                                @else — @endif
                            </td>
                            <td>{{ $invoice->contract?->tenant?->getTranslatedName() ?? $invoice->contract?->user?->name ?? '—' }}</td>
                            <td class="small">{{ $invoice->contract?->product?->getTranslatedTitle() ?? '—' }}</td>
                            <td>{{ $invoice->due_date?->format('Y-m-d') }}</td>
                            <td><span class="badge bg-danger">{{ $invoice->due_date ? now()->diffInDays($invoice->due_date) : 0 }} يوم</span></td>
                            <td class="fw-semibold text-danger">{{ number_format($invoice->remaining_amount, 0) }}</td>
                            <td class="pe-3">
                                @php
                                    $waPhone = preg_replace('/\D/', '', (string) ($invoice->contract?->tenant?->phone ?? ''));
                                    $waText = "تذكير ودي: فاتورة رقم " . ($invoice->invoice_number ?? $invoice->id) . " بقيمة " . number_format($invoice->remaining_amount, 0) . " متأخرة عن السداد. يرجى التسديد، وشكراً لكم.";
                                    $waUrl = 'https://wa.me/' . ($waPhone !== '' ? $waPhone . '?' : '?') . 'text=' . urlencode($waText);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="btn btn-sm btn-success" title="تذكير عبر واتساب">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">لا فواتير متأخرة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
            عقود بدفعة قادمة متأخرة ({{ $overdueContracts->count() }})
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">العقد</th>
                        <th>المستأجر</th>
                        <th>العقار</th>
                        <th>الدفعة المتأخرة منذ</th>
                        <th>أيام التأخير</th>
                        <th class="pe-3">قيمة العقد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueContracts as $contract)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('facility.contracts.show', $contract) }}">
                                    {{ $contract->contract_number ?? '#'.$contract->id }}
                                </a>
                            </td>
                            <td>{{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}</td>
                            <td class="small">{{ $contract->product?->getTranslatedTitle() ?? '—' }}</td>
                            <td>{{ $contract->next_payment_date?->format('Y-m-d') }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $contract->next_payment_date ? now()->diffInDays($contract->next_payment_date) : 0 }} يوم</span></td>
                            <td class="pe-3">{{ number_format($contract->total_amount, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">لا عقود متأخرة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
