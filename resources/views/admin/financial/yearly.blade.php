@extends('admin.financial.layout')

@section('title', __('financial.yearly_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.yearly_report') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-5"><select name="facility_id" class="form-select" required><option value="">{{ __('financial.choose_facility') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><input type="number" name="year" class="form-control" placeholder="{{ __('financial.year') }}" value="{{ request('year', date('Y')) }}" min="2020" max="2030" required></div>
    <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('financial.view_report') }}</button></div>
</form>

@isset($report)
<h5 class="mb-3">{{ $year }}</h5>
<div class="row g-3">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_revenue') }}</small><h4>{{ number_format($report['summary']['total_revenue'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_payments') }}</small><h4>{{ number_format($report['summary']['total_payments'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_commissions') }}</small><h4>{{ number_format($report['summary']['total_commissions'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.net_income') }}</small><h4>{{ number_format($report['summary']['net_income'] ?? 0, 2) }}</h4></div></div>
</div>
@endisset
@endsection
