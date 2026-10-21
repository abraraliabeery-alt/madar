@extends('facility.financial.layout')

@section('title', __('financial.contract_details'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('financial.contract_details') }} — {{ $contract->contract_number ?? '#'.$contract->id }}</h1>
        <p class="text-muted mb-0">{{ $contract->created_at?->format('d/m/Y') }}</p>
    </div>
    <a href="{{ route('facility.financial.contracts') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right"></i> {{ __('financial.back') }}
    </a>
</div>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value">{{ number_format($paymentStats['total_amount'], 2) }}</div>
            <div class="stats-label">{{ __('financial.total_amount') }}</div>
        </div></div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-success">{{ number_format($paymentStats['paid_amount'], 2) }}</div>
            <div class="stats-label">{{ __('financial.paid_amount') }}</div>
        </div></div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-warning">{{ number_format($paymentStats['remaining_amount'], 2) }}</div>
            <div class="stats-label">{{ __('financial.remaining_amount') }}</div>
        </div></div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-danger">{{ number_format($paymentStats['overdue_invoices']) }}</div>
            <div class="stats-label">{{ __('financial.overdue_invoices') }}</div>
        </div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">{{ __('financial.payment_progress') }}</h6>
        <div class="progress" style="height: 24px;">
            <div class="progress-bar bg-{{ $paymentStats['payment_progress'] >= 100 ? 'success' : 'info' }}" style="width: {{ min(100, $paymentStats['payment_progress']) }}%">
                {{ $paymentStats['payment_progress'] }}%
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100"><div class="card-body">
            <h6 class="card-title text-muted">{{ __('financial.contract_info') }}</h6>
            <p class="mb-1"><strong>{{ __('financial.status') }}:</strong> {{ $contract->status }}</p>
            <p class="mb-1"><strong>{{ __('financial.type') }}:</strong> {{ $contract->offer?->offer_type ?? $contract->contract_type ?? '—' }}</p>
            <p class="mb-0"><strong>{{ __('financial.due_date') }}:</strong> {{ $contract->end_date ?? '—' }}</p>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card h-100"><div class="card-body">
            <h6 class="card-title text-muted">{{ __('financial.client_info') }}</h6>
            <p class="mb-1"><strong>{{ $contract->client?->name ?? '—' }}</strong></p>
            <p class="mb-1">{{ $contract->client?->email ?? '' }}</p>
            <p class="mb-0">{{ $contract->client?->phone ?? '' }}</p>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card h-100"><div class="card-body">
            <h6 class="card-title text-muted">{{ __('financial.product_info') }}</h6>
            @if($contract->offer?->product || $contract->product)
                @php $product = $contract->offer?->product ?? $contract->product; @endphp
                <p class="mb-1"><strong>{{ $product->name }}</strong></p>
                <p class="mb-0 text-muted">{{ $product->location ?? '' }}</p>
            @else
                <p class="text-muted mb-0">—</p>
            @endif
        </div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('financial.invoices_list') }} ({{ $paymentStats['total_invoices'] }})</h5>
    </div>
    <div class="card-body">
        @if($contract->invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('financial.invoice_number') }}</th>
                        <th>{{ __('financial.due_date') }}</th>
                        <th>{{ __('financial.amount') }}</th>
                        <th>{{ __('financial.paid_amount') }}</th>
                        <th>{{ __('financial.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contract->invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number ?? '#'.$invoice->id }}</td>
                        <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '—' }}</td>
                        <td>{{ number_format($invoice->amount, 2) }}</td>
                        <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                        <td><span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'secondary' }}">{{ $invoice->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted text-center py-3">{{ __('financial.no_data') }}</p>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('financial.payments_list') }}</h5>
    </div>
    <div class="card-body">
        @if($contract->payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('financial.payment_date') }}</th>
                        <th>{{ __('financial.reference_number') }}</th>
                        <th>{{ __('financial.payment_method') }}</th>
                        <th>{{ __('financial.amount') }}</th>
                        <th>{{ __('financial.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contract->payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                        <td>{{ $payment->reference_number ?? '—' }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td><span class="badge bg-{{ $payment->status === 'confirmed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">{{ $payment->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted text-center py-3">{{ __('financial.no_payments_found') }}</p>
        @endif
    </div>
</div>
@endsection
