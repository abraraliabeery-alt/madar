@extends('facility.layouts.app')

@section('title', __('facility.reports.title'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.reports.title') }}</h1>
        <p class="text-muted small mb-0">اختر نوع التقرير</p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('facility.reports.bookings') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="text-primary fs-4"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="fw-bold">{{ __('facility.sidebar.reports_bookings') }}</div>
                            <div class="text-muted small">حالة وأداء الحجوزات</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('facility.reports.products') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="text-success fs-4"><i class="fas fa-box"></i></div>
                        <div>
                            <div class="fw-bold">{{ __('facility.sidebar.reports_products') }}</div>
                            <div class="text-muted small">المنتجات وعدد الحجوزات</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('facility.reports.revenue') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="text-warning fs-4"><i class="fas fa-coins"></i></div>
                        <div>
                            <div class="fw-bold">{{ __('facility.sidebar.reports_revenue') }}</div>
                            <div class="text-muted small">الإيرادات الشهرية</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('facility.reports.rentals.occupancy') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="text-info fs-4"><i class="fas fa-bed"></i></div>
                        <div>
                            <div class="fw-bold">{{ __('facility.sidebar.reports_occupancy') }}</div>
                            <div class="text-muted small">إشغال الوحدات</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('facility.reports.rentals.collections') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="text-danger fs-4"><i class="fas fa-money-check-dollar"></i></div>
                        <div>
                            <div class="fw-bold">{{ __('facility.sidebar.reports_collections') }}</div>
                            <div class="text-muted small">تحصيل الإيجارات</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
