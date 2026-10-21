@extends('admin.financial.layout')

@section('title', __('financial.reports'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.reports') }}</h2>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_revenue') }}</small><h4>{{ number_format($overallStats['total_revenue'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_payments') }}</small><h4>{{ number_format($overallStats['total_payments'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_commissions') }}</small><h4>{{ number_format($overallStats['total_commissions'] ?? 0, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.net_income') }}</small><h4>{{ number_format($overallStats['net_income'] ?? 0, 2) }}</h4></div></div>
</div>

<div class="card">
    <div class="card-header">{{ __('financial.facilities') }} ({{ $overallStats['total_facilities'] ?? $facilities->count() }})</div>
    <ul class="list-group list-group-flush">
        @foreach($facilities as $facility)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $facility->name }}</span>
                <a href="{{ route('admin.financial.facility-summary', ['facility_id' => $facility->id]) }}" class="btn btn-sm btn-outline-primary">{{ __('financial.view_report') }}</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
