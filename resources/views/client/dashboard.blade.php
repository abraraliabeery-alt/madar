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
@endphp

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

@if($clientAllowsContracting)
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('client.dashboard.total_projects') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_projects'] }}</div>
                    </div>
                    <div class="text-warning fs-4"><i class="fas fa-diagram-project"></i></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('client.projects.create') }}" class="small text-decoration-none">{{ __('client.dashboard.view_projects') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('client.dashboard.open_projects') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['open_projects'] }}</div>
                    </div>
                    <div class="text-info fs-4"><i class="fas fa-folder-open"></i></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('client.projects.create') }}" class="small text-decoration-none">{{ __('client.dashboard.view_projects') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">{{ __('client.dashboard.quick_actions') }}</h2>
        </div>
        <div class="row g-2">
            @if($clientAllowsContracting)
            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.projects.create') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-plus-circle ms-2"></i>
                    {{ __('client.navigation.create_project') }}
                </a>
            </div>
            @endif

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

@if($clientAllowsContracting)
<div class="row g-3">
    <!-- Recent Projects -->
    <div class="col-12">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h6 fw-bold mb-0">{{ __('client.dashboard.my_projects') }}</h2>
                    <a href="{{ route('client.projects.create') }}" class="small text-decoration-none">{{ __('client.navigation.create_project') }}</a>
                </div>
                @if($stats['recent_projects']->count() > 0)
                    <div class="vstack gap-2">
                        @foreach($stats['recent_projects'] as $project)
                            <div class="d-flex align-items-center justify-content-between border rounded p-3">
                                <div>
                                    <div class="fw-semibold">{{ $project->translations->first()?->name ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $project->status }}</div>
                                </div>
                                <div class="text-muted small">{{ $project->created_at->format('M d') }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <div class="mb-2"><i class="fas fa-diagram-project fs-3"></i></div>
                        <div>{{ __('client.dashboard.no_projects') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

