@extends('admin.layouts.app')

@section('title', __('admin.feature_statistics'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.feature_statistics') }}</h2>
    <a href="{{ route('admin.features.index') }}" class="btn btn-outline-secondary">{{ __('admin.back') }}</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.total_features') }}</small><h4>{{ $stats['total_features'] }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.active_features') }}</small><h4 class="text-success">{{ $stats['active_features'] }}</h4></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">{{ __('admin.features_with_products') }}</small><h4>{{ $stats['features_with_products'] }}</h4></div></div>
</div>

<div class="card">
    <div class="card-header">{{ __('admin.top_features') }}</div>
    <ul class="list-group list-group-flush">
        @forelse($stats['top_features'] as $feature)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $feature->name }}</span>
                <span class="badge bg-primary">{{ $feature->products_count }}</span>
            </li>
        @empty
            <li class="list-group-item text-center text-muted">{{ __('admin.no_data') }}</li>
        @endforelse
    </ul>
</div>
@endsection
