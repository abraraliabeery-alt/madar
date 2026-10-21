@extends('admin.layouts.app')

@section('title', __('admin.offer_statistics'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.offer_statistics') }}</h2>
    <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">{{ __('admin.back') }}</a>
</div>

<div class="row g-3">
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.total_offers') }}</small><h4>{{ $stats['total_offers'] ?? 0 }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.active_offers') }}</small><h4 class="text-success">{{ $stats['active_offers'] ?? 0 }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.expired_offers') }}</small><h4 class="text-danger">{{ $stats['expired_offers'] ?? 0 }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.sale_offers') }}</small><h4>{{ $stats['sale_offers'] ?? 0 }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.rent_offers') }}</small><h4>{{ $stats['rent_offers'] ?? 0 }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.total_value') }}</small><h4>{{ number_format($stats['total_value'] ?? 0, 2) }}</h4></div></div>
</div>
@endsection
