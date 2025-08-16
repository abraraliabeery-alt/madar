@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">لوحة الإحصائيات</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">الإحصائيات</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="refreshStats()">
                <i class="fas fa-sync-alt me-2"></i>تحديث
            </button>
            <a href="{{ route('admin.reports') }}" class="btn btn-info">
                <i class="fas fa-file-alt me-2"></i>التقارير
            </a>
        </div>
    </div>

    <!-- Monthly Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-2">المستخدمين الجدد</h6>
                            <h3 class="mb-1">{{ number_format($monthlyStats['users']) }}</h3>
                            <div class="d-flex align-items-center">
                                @if(isset($growthRates['users']))
                                    <span class="badge bg-light text-primary me-2">
                                        @if($growthRates['users'] >= 0)
                                            <i class="fas fa-arrow-up me-1"></i>{{ $growthRates['users'] }}%
                                        @else
                                            <i class="fas fa-arrow-down me-1"></i>{{ abs($growthRates['users']) }}%
                                        @endif
                                    </span>
                                @endif
                                <small class="text-white-50">مقارنة بالشهر السابق</small>
                            </div>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-2">المنشآت الجديدة</h6>
                            <h3 class="mb-1">{{ number_format($monthlyStats['facilities']) }}</h3>
                            <div class="d-flex align-items-center">
                                @if(isset($growthRates['facilities']))
                                    <span class="badge bg-light text-success me-2">
                                        @if($growthRates['facilities'] >= 0)
                                            <i class="fas fa-arrow-up me-1"></i>{{ $growthRates['facilities'] }}%
                                        @else
                                            <i class="fas fa-arrow-down me-1"></i>{{ abs($growthRates['facilities']) }}%
                                        @endif
                                    </span>
                                @endif
                                <small class="text-white-50">مقارنة بالشهر السابق</small>
                            </div>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-2">المنتجات الجديدة</h6>
                            <h3 class="mb-1">{{ number_format($monthlyStats['products']) }}</h3>
                            <div class="d-flex align-items-center">
                                @if(isset($growthRates['products']))
                                    <span class="badge bg-light text-info me-2">
                                        @if($growthRates['products'] >= 0)
                                            <i class="fas fa-arrow-up me-1"></i>{{ $growthRates['products'] }}%
                                        @else
                                            <i class="fas fa-arrow-down me-1"></i>{{ abs($growthRates['products']) }}%
                                        @endif
                                    </span>
                                @endif
                                <small class="text-white-50">مقارنة بالشهر السابق</small>
                            </div>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-2">الحجوزات الجديدة</h6>
                            <h3 class="mb-1">{{ number_format($monthlyStats['bookings']) }}</h3>
                            <div class="d-flex align-items-center">
                                @if(isset($growthRates['bookings']))
                                    <span class="badge bg-light text-warning me-2">
                                        @if($growthRates['bookings'] >= 0)
                                            <i class="fas fa-arrow-up me-1"></i>{{ $growthRates['bookings'] }}%
                                        @else
                                            <i class="fas fa-arrow-down me-1"></i>{{ abs($growthRates['bookings']) }}%
                                        @endif
                                    </span>
                                @endif
                                <small class="text-white-50">مقارنة بالشهر السابق</small>
                            </div>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row g-4 mb-4">
        <!-- User Statistics -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>إحصائيات المستخدمين</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-primary text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($userStats['total_users']) }}</h6>
                                    <small class="text-muted">إجمالي المستخدمين</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($userStats['verified_users']) }}</h6>
                                    <small class="text-muted">مستخدمين موثقين</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-info text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($userStats['users_this_week']) }}</h6>
                                    <small class="text-muted">هذا الأسبوع</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-warning text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($userStats['unverified_users']) }}</h6>
                                    <small class="text-muted">في انتظار التوثيق</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Statistics -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-box me-2"></i>إحصائيات المنتجات</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-primary text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($productStats['total_products']) }}</h6>
                                    <small class="text-muted">إجمالي المنتجات</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($productStats['featured_products']) }}</h6>
                                    <small class="text-muted">منتجات مميزة</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-info text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($productStats['average_price'], 0) }}</h6>
                                    <small class="text-muted">متوسط السعر</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-warning text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ number_format($productStats['products_this_week']) }}</h6>
                                    <small class="text-muted">هذا الأسبوع</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        <!-- User Growth Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>نمو المستخدمين ({{ now()->year }})</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>إيرادات الحجوزات ({{ now()->year }})</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facility Performance Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>أداء المنشآت</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="facilityPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>النشاط الأخير</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">مستخدم جديد</h6>
                                <p class="mb-0 text-muted">تم تسجيل مستخدم جديد في النظام</p>
                                <small class="text-muted">{{ now()->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">حجز جديد</h6>
                                <p class="mb-0 text-muted">تم إنشاء حجز جديد</p>
                                <small class="text-muted">{{ now()->subMinutes(30)->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">منتج جديد</h6>
                                <p class="mb-0 text-muted">تم إضافة منتج جديد</p>
                                <small class="text-muted">{{ now()->subHours(2)->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>أفضل المنشآت</h6>
                </div>
                <div class="card-body">
                    @if($facilityPerformance->count() > 0)
                        @foreach($facilityPerformance->take(5) as $facility)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="bg-primary text-white rounded-circle p-2 me-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $facility['name'] }}</h6>
                                <div class="d-flex gap-3">
                                    <small class="text-muted">
                                        <i class="fas fa-box me-1"></i>{{ $facility['products_count'] }} منتج
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-check me-1"></i>{{ $facility['bookings_count'] }} حجز
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-dollar-sign me-1"></i>{{ number_format($facility['total_revenue']) }} ريال
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-building fa-2x text-muted mb-2"></i>
                            <p class="text-muted">لا توجد منشآت بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #e9ecef;
}

.timeline-content h6 {
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.timeline-content p {
    font-size: 0.8rem;
    margin-bottom: 5px;
}

.timeline-content small {
    font-size: 0.75rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-light {
    background-color: #f8f9fa !important;
}

.rounded-circle {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Dark mode support */
body.dark-mode .card {
    background-color: #2d2d2d;
    border-color: #404040;
}

body.dark-mode .card-header {
    background-color: #1f1f1f;
    border-color: #404040;
}

body.dark-mode .bg-light {
    background-color: #404040 !important;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Arabic month names
    const arabicMonths = [
        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: arabicMonths,
            datasets: [{
                label: 'المستخدمين الجدد',
                data: [
                    @foreach(range(1, 12) as $month)
                        {{ $yearlyStats['users'][$month] ?? 0 }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('ar-SA');
                        }
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: arabicMonths,
            datasets: [{
                label: 'إيرادات الحجوزات',
                data: [
                    @foreach(range(1, 12) as $month)
                        {{ $yearlyStats['bookings'][$month] * 1000 ?? 0 }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                backgroundColor: 'rgba(255, 193, 7, 0.7)',
                borderColor: 'rgb(255, 193, 7)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('ar-SA') + ' ريال';
                        }
                    }
                }
            }
        }
    });

    // Facility Performance Chart
    const facilityCtx = document.getElementById('facilityPerformanceChart').getContext('2d');
    new Chart(facilityCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($facilityPerformance->take(5) as $facility)
                    '{{ $facility['name'] }}'{{ !$loop->last ? ',' : '' }}
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($facilityPerformance->take(5) as $facility)
                        {{ $facility['bookings_count'] }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
});

// Refresh statistics function
function refreshStats() {
    location.reload();
}
</script>
@endpush
