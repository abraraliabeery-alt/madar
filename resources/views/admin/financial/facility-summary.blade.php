@extends('admin.financial.layout')

@section('title', __('financial.facility_summary'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.facility_summary') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-4"><select name="facility_id" class="form-select" required><option value="">{{ __('financial.choose_facility') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"></div>
    <div class="col-md-3"><input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}"></div>
    <div class="col-md-2"><button class="btn btn-primary w-100">{{ __('financial.view_report') }}</button></div>
</form>

@isset($report)
<div class="row g-3">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_revenue') }}</small><h4>{{ number_format($report['summary']['total_revenue'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_payments') }}</small><h4>{{ number_format($report['summary']['total_payments'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_commissions') }}</small><h4>{{ number_format($report['summary']['total_commissions'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.net_income') }}</small><h4>{{ number_format($report['summary']['net_income'] ?? 0, 2) }}</h4></div></div>
</div>
<p class="text-muted mt-3">{{ $report['period']['start_date'] ?? '' }} — {{ $report['period']['end_date'] ?? '' }}</p>
@endisset
@endsection
