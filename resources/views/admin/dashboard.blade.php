@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">مرحباً بك في لوحة التحكم</h4>
                    <p class="text-muted mb-0">هذه نظرة عامة على نشاط النظام</p>
                </div>
                <div>
                    <a href="{{ route('admin.statistics') }}" class="btn btn-primary">
                        <i class="fas fa-chart-bar me-2"></i>الإحصائيات التفصيلية
                    </a>
                    <a href="{{ route('admin.reports') }}" class="btn btn-info">
                        <i class="fas fa-file-alt me-2"></i>التقارير
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">المستخدمين</h6>
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-right me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">المنشآت</h6>
                            <h3 class="mb-0">{{ $stats['total_facilities'] }}</h3>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-right me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">المنتجات</h6>
                            <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-right me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">الحجوزات</h6>
                            <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-right me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4">
        <!-- Recent Users -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">أحدث المستخدمين</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_users'] as $user)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="avatar" class="rounded-circle me-3" width="40">
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
                        عرض كل المستخدمين
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Facilities -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">أحدث المنشآت</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_facilities'] as $facility)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($facility->logo)
                                    <img src="{{ Storage::url($facility->logo) }}" alt="logo" class="rounded me-3" width="40">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $facility->name }}</h6>
                                    <small class="text-muted">{{ $facility->products_count }} منتج</small>
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
                        عرض كل المنشآت
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">أحدث الحجوزات</h6>
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
                                    <span class="badge bg-secondary">لا توجد حالة</span>
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
                        عرض كل الحجوزات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
