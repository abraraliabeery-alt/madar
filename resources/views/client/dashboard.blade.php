@extends('layouts.dashboard-bs')

@section('title', __('client.dashboard.title'))

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">{{ __('client.dashboard.welcome', ['name' => auth()->user()->name]) }}</h1>
    <p class="text-muted mb-0">{{ __('client.dashboard.subtitle', ['default' => 'Here\'s what\'s happening with your account.']) }}</p>
</div>

<div class="row g-3 mb-4">
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
                    <a href="{{ route('client.bookings.index') }}" class="small text-decoration-none">عرض التفاصيل</a>
                </div>
            </div>
        </div>
    </div>

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
                    <a href="{{ route('client.contracts.index') }}" class="small text-decoration-none">عرض العقود</a>
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
                    <a href="{{ route('client.appointments') }}" class="small text-decoration-none">عرض المواعيد</a>
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
                    <a href="{{ route('client.favorites') }}" class="small text-decoration-none">عرض المفضلة</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">{{ __('client.dashboard.quick_actions') }}</h2>
        </div>
        <div class="row g-2">
            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.projects.create') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-plus-circle ms-2"></i>
                    إنشاء مشروع
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.offers.index') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-tags ms-2"></i>
                    العروض المتاحة
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
            <div class="col-12 col-md-6 col-lg">
                <a href="{{ route('client.financial.dashboard') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-chart-line ms-2"></i>
                    الإدارة المالية
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">العروض المتاحة</h2>
            <a href="{{ route('client.offers.index') }}" class="small text-decoration-none">عرض جميع العروض</a>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="fw-bold">شقة 3 غرف</div>
                            <span class="badge bg-success">للبيع</span>
                        </div>
                        <div class="text-muted small mt-1">الرياض، حي النرجس</div>
                        <div class="fw-bold mt-2">450,000 ريال</div>
                        <button class="btn btn-primary w-100 mt-3">عرض التفاصيل</button>
                    </div>
                </div>
            </div>
                
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="fw-bold">فيلا 4 غرف</div>
                            <span class="badge bg-primary">إيجار شهري</span>
                        </div>
                        <div class="text-muted small mt-1">جدة، حي الروضة</div>
                        <div class="fw-bold mt-2">8,000 ريال/شهر</div>
                        <button class="btn btn-primary w-100 mt-3">عرض التفاصيل</button>
                    </div>
                </div>
            </div>
                
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="fw-bold">مكتب تجاري</div>
                            <span class="badge bg-secondary">إيجار سنوي</span>
                        </div>
                        <div class="text-muted small mt-1">الدمام، حي الفيصلية</div>
                        <div class="fw-bold mt-2">120,000 ريال/سنة</div>
                        <button class="btn btn-primary w-100 mt-3">عرض التفاصيل</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
