@extends('admin.layouts.app')

@section('title', __('admin.contract_statistics'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.contract_statistics') }}</h2>
    <a href="{{ route('admin.contracts.index') }}" class="btn btn-outline-secondary">{{ __('admin.back') }}</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('admin.total_contracts') }}</small><h4>{{ $stats['total_contracts'] }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('admin.verified_contracts') }}</small><h4 class="text-success">{{ $stats['verified_contracts'] }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('admin.total_value') }}</small><h4>{{ number_format($stats['total_value'], 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('admin.total_paid') }}</small><h4 class="text-primary">{{ number_format($stats['total_paid'], 2) }}</h4></div></div>
</div>

<div class="card">
    <div class="card-header">{{ __('admin.recent_contracts') }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('admin.client') }}</th><th>{{ __('admin.product') }}</th><th>{{ __('admin.facility') }}</th><th>{{ __('admin.price') }}</th></tr></thead>
            <tbody>
                @forelse($stats['recent_contracts'] as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ optional($c->user)->name ?? '-' }}</td>
                        <td>{{ optional($c->product)->getTranslatedTitle() ?: '-' }}</td>
                        <td>{{ optional($c->facility)->name ?? '-' }}</td>
                        <td>{{ number_format($c->total_amount ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted p-4">{{ __('admin.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
