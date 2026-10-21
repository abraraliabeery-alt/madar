@extends('admin.financial.layout')

@section('title', __('financial.revenue_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.revenue_report') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
</form>

<div class="card p-3 mb-4"><small class="text-muted">{{ __('financial.total_revenue') }}</small><h3>{{ number_format($report['total_revenue'] ?? 0, 2) }}</h3></div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('financial.by_type') }}</div>
            <ul class="list-group list-group-flush">
                @foreach(($report['by_type'] ?? []) as $type => $amount)
                    <li class="list-group-item d-flex justify-content-between"><span>{{ $type ?: '-' }}</span><strong>{{ number_format($amount, 2) }}</strong></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('financial.by_month') }}</div>
            <ul class="list-group list-group-flush">
                @foreach(($report['by_month'] ?? []) as $month => $amount)
                    <li class="list-group-item d-flex justify-content-between"><span>{{ $month }}</span><strong>{{ number_format($amount, 2) }}</strong></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
