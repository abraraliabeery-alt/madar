@extends('facility.financial.layout')

@section('title', __('financial.payments_management'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('financial.payments_management') }}</h1>
        <p class="text-muted mb-0">{{ __('financial.manage_payments_desc') }}</p>
    </div>
    <a href="{{ route('facility.financial.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right"></i> {{ __('financial.reports') }}
    </a>
</div>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value">{{ number_format($stats['total']) }}</div>
            <div class="stats-label">{{ __('financial.payments') }}</div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-warning">{{ number_format($stats['pending']) }}</div>
            <div class="stats-label">{{ __('financial.pending') }}</div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-success">{{ number_format($stats['confirmed']) }}</div>
            <div class="stats-label">{{ __('financial.confirmed') }}</div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-danger">{{ number_format($stats['failed']) }}</div>
            <div class="stats-label">{{ __('financial.failed') }}</div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-success">{{ number_format($stats['total_amount']) }}</div>
            <div class="stats-label">{{ __('financial.total_payments') }}</div>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm"><div class="card-body text-center">
            <div class="stats-value text-warning">{{ number_format($stats['pending_amount']) }}</div>
            <div class="stats-label">{{ __('financial.pending') }}</div>
        </div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('facility.financial.payments') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('financial.search') }}</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('financial.search_payments_placeholder') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('financial.status') }}</label>
                    <select class="form-select" name="status">
                        <option value="">{{ __('financial.all') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('financial.pending') }}</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('financial.confirmed') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('financial.failed') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('financial.payment_method') }}</label>
                    <select class="form-select" name="payment_method">
                        <option value="">{{ __('financial.all') }}</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('financial.cash') }}</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('financial.bank_transfer') }}</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>{{ __('financial.credit_card') }}</option>
                        <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>{{ __('financial.check') }}</option>
                        <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>{{ __('financial.online') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('financial.from_date') }}</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('financial.to_date') }}</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-credit-card text-primary"></i>
            {{ __('financial.payments') }}
            <span class="badge bg-primary">{{ $payments->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('financial.date') }}</th>
                            <th>{{ __('financial.reference_number') }}</th>
                            <th>{{ __('financial.client') }}</th>
                            <th>{{ __('financial.contract') }}</th>
                            <th>{{ __('financial.payment_method') }}</th>
                            <th>{{ __('financial.amount') }}</th>
                            <th>{{ __('financial.status') }}</th>
                            <th>{{ __('financial.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td><small>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</small></td>
                            <td><span class="fw-semibold">{{ $payment->reference_number ?? '—' }}</span></td>
                            <td>{{ $payment->contract?->client?->name ?? '—' }}</td>
                            <td>{{ $payment->contract?->contract_number ?? '—' }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td><span class="fw-semibold">{{ number_format($payment->amount, 2) }}</span></td>
                            <td>
                                <span class="badge bg-{{ $payment->status === 'confirmed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ __('financial.' . $payment->status) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->status === 'pending')
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('facility.financial.confirm-payment', $payment->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm" title="{{ __('financial.confirm') }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('facility.financial.reject-payment', $payment->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="{{ __('financial.reject') }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">{{ __('financial.no_payments_found') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
