@extends('admin.financial.layout')

@section('title', __('financial.comprehensive_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.comprehensive_report') }}</h2>
    <a href="{{ route('admin.financial.reports.export', request()->query()) }}" class="btn btn-outline-secondary">{{ __('financial.export') }}</a>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_revenue') }}</small><h4>{{ number_format($summary['total_revenue'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_payments') }}</small><h4>{{ number_format($summary['total_payments'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_commissions') }}</small><h4>{{ number_format($summary['total_commissions'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.net_income') }}</small><h4>{{ number_format($summary['net_income'] ?? 0, 2) }}</h4></div></div>
</div>

<div class="card">
    <div class="card-header">{{ __('financial.contracts') }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('financial.client') }}</th><th>{{ __('financial.amount') }}</th><th>{{ __('financial.status') }}</th></tr></thead>
            <tbody>
                @forelse(($contractsReport['contracts'] ?? []) as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ optional($c->user)->name ?? '-' }}</td>
                        <td>{{ number_format($c->total_amount ?? 0, 2) }}</td>
                        <td>{{ is_string($c->status) ? $c->status : optional($c->status)->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
