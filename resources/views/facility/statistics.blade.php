@extends('facility.layouts.app')

@section('title', __('facility.sidebar.statistics'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.sidebar.statistics') }}</h1>
        <p class="text-muted small mb-0">إحصائيات الشهر الحالي — {{ now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">منتجات جديدة</div>
                        <div class="h4 fw-bold mb-0">{{ $monthlyStats['products'] ?? 0 }}</div>
                    </div>
                    <div class="text-primary fs-4"><i class="fas fa-box"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">حجوزات الشهر</div>
                        <div class="h4 fw-bold mb-0">{{ $monthlyStats['bookings'] ?? 0 }}</div>
                    </div>
                    <div class="text-success fs-4"><i class="fas fa-calendar-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">عقود الشهر</div>
                        <div class="h4 fw-bold mb-0">{{ $monthlyStats['contracts'] ?? 0 }}</div>
                    </div>
                    <div class="text-info fs-4"><i class="fas fa-file-signature"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">إيرادات الشهر</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($monthlyStats['revenue'] ?? 0, 2) }}</div>
                    </div>
                    <div class="text-warning fs-4"><i class="fas fa-coins"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
