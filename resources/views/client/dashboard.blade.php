@extends('client.layouts.app')

@section('title', __('client.dashboard.title'))

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">{{ __('client.dashboard.welcome', ['name' => auth()->user()->name]) }}</h1>
    <p class="text-muted mb-0">{{ __('client.dashboard.subtitle', ['default' => 'Here\'s what\'s happening with your account.']) }}</p>
</div>

@php
$clientAllowsRealEstate = \App\Helpers\PlatformModeHelper::allowsRealEstate();
$clientAllowsContracting = \App\Helpers\PlatformModeHelper::allowsContracting();

$clientActions = [];
if ($clientAllowsRealEstate && ($stats['pending_bookings'] ?? 0) > 0) {
    $clientActions[] = ['icon' => 'fa-calendar-check', 'color' => 'primary', 'count' => $stats['pending_bookings'], 'label' => __('client.dashboard.pending_bookings_text'), 'url' => route('client.bookings.index')];
}
if (($stats['unread_notifications'] ?? 0) > 0) {
    $clientActions[] = ['icon' => 'fa-bell', 'color' => 'danger', 'count' => $stats['unread_notifications'], 'label' => __('client.navigation.notifications'), 'url' => route('client.notifications')];
}
@endphp

@if(count($clientActions))
<div class="card border-0 shadow-sm mb-4" style="border-inline-start: 4px solid var(--bs-warning);">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-circle-exclamation text-warning"></i>
            <span class="fw-bold">{{ __('client.dashboard.pending_actions') }}</span>
        </div>
        <div class="d-flex flex-column gap-2">
            @foreach($clientActions as $item)
                <a href="{{ $item['url'] }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }}" style="width: 38px; height: 38px;">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </span>
                    <span class="flex-grow-1">{{ $item['label'] }}</span>
                    <span class="badge text-bg-{{ $item['color'] }} rounded-pill">{{ $item['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    @if($clientAllowsRealEstate)
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('client.dashboard.total_bookings') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_bookings'] }}</div>
                    </div>
                    <div class="text-primary fs-4"><i class="fas fa-calendar-check"></i></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('client.bookings.index') }}" class="small text-decoration-none">{{ __('client.dashboard.view_details') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('client.dashboard.total_appointments') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_appointments'] }}</div>
                    </div>
                    <div class="text-secondary fs-4"><i class="fas fa-clock"></i></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('client.appointments') }}" class="small text-decoration-none">{{ __('client.dashboard.view_appointments') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('client.dashboard.favorite_products') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['favorite_products'] }}</div>
                    </div>
                    <div class="text-danger fs-4"><i class="fas fa-heart"></i></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('client.favorites') }}" class="small text-decoration-none">{{ __('client.dashboard.view_favorites') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($clientAllowsContracting)
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('client.dashboard.total_contracts') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_contracts'] }}</div>
                    </div>
                    <div class="text-success fs-4"><i class="fas fa-file-contract"></i></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('client.contracts.index') }}" class="small text-decoration-none">{{ __('client.dashboard.view_contracts') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">{{ __('client.dashboard.quick_actions') }}</h2>
        </div>
        <div class="row g-2">
            @if($clientAllowsRealEstate)
            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.offers.index') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-tags ms-2"></i>
                    {{ __('client.dashboard.offers_title') }}
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.favorites') }}" class="btn btn-outline-danger w-100">
                    <i class="fas fa-heart ms-2"></i>
                    {{ __('client.navigation.favorites') }}
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.bookings.index') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-calendar-check ms-2"></i>
                    {{ __('client.navigation.bookings') }}
                </a>
            </div>
            @endif

            @if($clientAllowsContracting)
            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.financial.dashboard') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-chart-line ms-2"></i>
                    {{ __('client.dashboard.financial_management') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@if($clientAllowsRealEstate)
<div class="row g-3">
    <!-- Recent Bookings -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h6 fw-bold mb-0">{{ __('client.dashboard.my_bookings') }}</h2>
                    <a href="{{ route('client.bookings.index') }}" class="small text-decoration-none">{{ __('client.actions.view') }} {{ __('client.actions.all') }}</a>
                </div>
                    @if($stats['recent_bookings']->count() > 0)
                        <div class="vstack gap-2">
                            @foreach($stats['recent_bookings'] as $booking)
                                <div class="d-flex align-items-center justify-content-between border rounded p-3">
                                    <div>
                                        <div class="fw-semibold">{{ $booking->product->name ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $booking->facility->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="text-muted small">{{ $booking->created_at->format('M d') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <div class="mb-2"><i class="fas fa-calendar-times fs-3"></i></div>
                            <div>{{ __('client.bookings.no_bookings') }}</div>
                        </div>
                    @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h6 fw-bold mb-0">{{ __('client.dashboard.my_appointments') }}</h2>
                    <a href="{{ route('client.appointments') }}" class="small text-decoration-none">{{ __('client.actions.view') }} {{ __('client.actions.all') }}</a>
                </div>
                    @if($stats['recent_appointments']->count() > 0)
                        <div class="vstack gap-2">
                            @foreach($stats['recent_appointments'] as $appointment)
                                <div class="d-flex align-items-center justify-content-between border rounded p-3">
                                    <div>
                                        <div class="fw-semibold">{{ $appointment->facility->name ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $appointment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="text-muted small">{{ __('client.status.pending') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <div class="mb-2"><i class="fas fa-clock fs-3"></i></div>
                            <div>{{ __('client.appointments.no_appointments') }}</div>
                        </div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endif

@endsection

