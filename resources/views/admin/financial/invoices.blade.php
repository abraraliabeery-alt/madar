@extends('admin.financial.layout')

@section('title', __('financial.invoices_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.invoices_report') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_invoices') }}</small><h4>{{ $report['total_invoices'] ?? 0 }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_amount') }}</small><h4>{{ number_format($report['total_amount'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.paid_amount') }}</small><h4>{{ number_format($report['paid_amount'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.remaining_amount') }}</small><h4>{{ number_format($report['remaining_amount'] ?? 0, 2) }}</h4></div></div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('financial.by_status') }}</div>
            <ul class="list-group list-group-flush">
                @foreach(($report['by_status'] ?? []) as $status => $row)
                    <li class="list-group-item d-flex justify-content-between"><span>{{ $status ?: '-' }} ({{ $row['count'] }})</span><strong>{{ number_format($row['amount'], 2) }}</strong></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('financial.by_type') }}</div>
            <ul class="list-group list-group-flush">
                @foreach(($report['by_type'] ?? []) as $type => $row)
                    <li class="list-group-item d-flex justify-content-between"><span>{{ $type ?: '-' }} ({{ $row['count'] }})</span><strong>{{ number_format($row['amount'], 2) }}</strong></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
