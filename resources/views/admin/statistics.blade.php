@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">الإحصائيات التفصيلية</h5>
            <div>
                <a href="{{ route('admin.reports') }}" class="btn btn-info">
                    <i class="fas fa-file-alt me-2"></i>التقارير
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Monthly Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">المستخدمين الجدد</h6>
                                    <h3 class="mb-0">{{ $monthlyStats['users'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-white-50">خلال الشهر الحالي</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">المنشآت الجديدة</h6>
                                    <h3 class="mb-0">{{ $monthlyStats['facilities'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-white-50">خلال الشهر الحالي</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">المنتجات الجديدة</h6>
                                    <h3 class="mb-0">{{ $monthlyStats['products'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-white-50">خلال الشهر الحالي</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الحجوزات الجديدة</h6>
                                    <h3 class="mb-0">{{ $monthlyStats['bookings'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-white-50">خلال الشهر الحالي</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4">
                <!-- User Growth Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">نمو المستخدمين</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="userGrowthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Revenue Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">إيرادات الحجوزات</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="bookingRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facility Performance Chart -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">أداء المنشآت</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="facilityPerformanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            datasets: [{
                label: 'المستخدمين الجدد',
                data: [
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }},
                    {{ $monthlyStats['users'] }}
                ],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Booking Revenue Chart
    const bookingRevenueCtx = document.getElementById('bookingRevenueChart').getContext('2d');
    new Chart(bookingRevenueCtx, {
        type: 'bar',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            datasets: [{
                label: 'الإيرادات',
                data: [
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }},
                    {{ $monthlyStats['bookings'] * 1000 }}
                ],
                backgroundColor: 'rgba(255, 193, 7, 0.5)',
                borderColor: 'rgb(255, 193, 7)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ريال';
                        }
                    }
                }
            }
        }
    });

    // Facility Performance Chart
    const facilityPerformanceCtx = document.getElementById('facilityPerformanceChart').getContext('2d');
    new Chart(facilityPerformanceCtx, {
        type: 'bar',
        data: {
            labels: ['المنشأة 1', 'المنشأة 2', 'المنشأة 3', 'المنشأة 4', 'المنشأة 5'],
            datasets: [{
                label: 'المنتجات',
                data: [
                    {{ $monthlyStats['products'] }},
                    {{ $monthlyStats['products'] - 2 }},
                    {{ $monthlyStats['products'] - 4 }},
                    {{ $monthlyStats['products'] - 6 }},
                    {{ $monthlyStats['products'] - 8 }}
                ],
                backgroundColor: 'rgba(40, 167, 69, 0.5)',
                borderColor: 'rgb(40, 167, 69)',
                borderWidth: 1
            }, {
                label: 'الحجوزات',
                data: [
                    {{ $monthlyStats['bookings'] }},
                    {{ $monthlyStats['bookings'] - 1 }},
                    {{ $monthlyStats['bookings'] - 2 }},
                    {{ $monthlyStats['bookings'] - 3 }},
                    {{ $monthlyStats['bookings'] - 4 }}
                ],
                backgroundColor: 'rgba(23, 162, 184, 0.5)',
                borderColor: 'rgb(23, 162, 184)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
