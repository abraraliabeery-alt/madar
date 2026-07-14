@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4" data-intro="{{ __('admin.tour.welcome_card_desc') }}" data-step="10">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold">{{ __('admin.dashboard.welcome_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('admin.dashboard.welcome_subtitle') }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.statistics') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar me-2"></i>{{ __('admin.dashboard.detailed_statistics') }}
                    </a>
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-alt me-2"></i>{{ __('admin.dashboard.reports') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4" data-intro="{{ __('admin.tour.stats_cards_desc') }}" data-step="11">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.users.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="text-primary fs-4"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.facilities.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_facilities'] }}</div>
                        </div>
                        <div class="text-success fs-4"><i class="fas fa-building"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.facilities.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.products.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_products'] }}</div>
                        </div>
                        <div class="text-info fs-4"><i class="fas fa-box"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.bookings.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_bookings'] }}</div>
                        </div>
                        <div class="text-warning fs-4"><i class="fas fa-calendar-check"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.dashboard.this_month_revenue') }}</div>
                            <div class="h4 fw-bold mb-0">{{ number_format($stats['total_revenue_month'] ?? 0, 2) }}</div>
                        </div>
                        <div class="text-danger fs-4"><i class="fas fa-coins"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="small text-decoration-none">{{ __('admin.dashboard.booking_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New User Management Features -->
    <div class="row g-4 mb-4" data-intro="{{ __('admin.tour.user_management_desc') }}" data-step="12">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('admin.dashboard.advanced_user_management') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-cog fa-2x text-primary mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.permission_management') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.permission_management_desc') }}</p>
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.permission_management') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.user_statistics') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.user_statistics_desc') }}</p>
                                    <a href="{{ route('admin.users.statistics') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.view_statistics') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-download fa-2x text-info mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.export_data') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.export_data_desc') }}</p>
                                    <a href="{{ route('admin.users.export') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.export_data') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-history fa-2x text-warning mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.activity_log') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.activity_log_desc') }}</p>
                                    <a href="{{ route('admin.users.activity-logs') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.view_log') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4" data-intro="{{ __('admin.tour.recent_activities_desc') }}" data-step="13">
        <!-- Recent Users -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('admin.dashboard.recent_users') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_users'] as $user)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded-circle me-3" width="40">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light ms-auto">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                        {{ __('admin.dashboard.view_all_users') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Facilities -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('admin.dashboard.recent_facilities') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_facilities'] as $facility)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($facility->logo)
                                    <img src="{{ asset($facility->logo) }}" alt="logo" class="rounded me-3" width="40">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $facility->name }}</h6>
                                    <small class="text-muted">{{ __('admin.dashboard.products_count', ['count' => $facility->products_count]) }}</small>
                                </div>
                                <a href="{{ route('admin.facilities.show', $facility) }}" class="btn btn-sm btn-light ms-auto">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-primary">
                        {{ __('admin.dashboard.view_all_facilities') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('admin.dashboard.recent_bookings') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_bookings'] as $booking)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-light rounded p-2">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $booking->user->name }}</h6>
                                    <small class="text-muted">{{ $booking->product->name }}</small>
                                </div>
                                <div class="ms-auto text-end">
                                    @if($booking->status)
                                    <span class="badge bg-{{ $booking->status->color }}">{{ $booking->status->name }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.dashboard.no_status') }}</span>
                                @endif
                                    <div>
                                        <small class="text-muted">{{ $booking->created_at->format('Y-m-d') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">
                        {{ __('admin.dashboard.view_all_bookings') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
