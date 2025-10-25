@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">التقارير</h5>
            <div>
                <a href="{{ route('admin.statistics') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- User Growth Report -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">تقرير نمو المستخدمين</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>عدد المستخدمين</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['user_growth'] as $data)
                                        <tr>
                                            <td>{{ $data->date }}</td>
                                            <td>{{ $data->count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div style="height: 300px;">
                                <canvas id="userGrowthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Revenue Report -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">تقرير إيرادات الحجوزات</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>الإيرادات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['booking_revenue'] as $data)
                                        <tr>
                                            <td>{{ $data->date }}</td>
                                            <td>{{ number_format($data->revenue, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div style="height: 300px;">
                                <canvas id="bookingRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facility Performance Report -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">تقرير أداء المنشآت</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>المنشأة</th>
                                            <th>المنتجات</th>
                                            <th>الحجوزات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['facility_performance'] as $facility)
                                        <tr>
                                            <td>{{ $facility->name }}</td>
                                            <td>{{ $facility->products_count }}</td>
                                            <td>{{ $facility->bookings_count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-8">
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
            labels: {!! json_encode($reports['user_growth']->pluck('date')) !!},
            datasets: [{
                label: 'عدد المستخدمين',
                data: {!! json_encode($reports['user_growth']->pluck('count')) !!},
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
            labels: {!! json_encode($reports['booking_revenue']->pluck('date')) !!},
            datasets: [{
                label: 'الإيرادات',
                data: {!! json_encode($reports['booking_revenue']->pluck('revenue')) !!},
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
                            return value.toLocaleString() + ' ' + '<?php echo \App\Helpers\LanguageHelper::getSaudiRiyalSymbol(); ?>';
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
            labels: {!! json_encode($reports['facility_performance']->pluck('name')) !!},
            datasets: [{
                label: 'المنتجات',
                data: {!! json_encode($reports['facility_performance']->pluck('products_count')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.5)',
                borderColor: 'rgb(40, 167, 69)',
                borderWidth: 1
            }, {
                label: 'الحجوزات',
                data: {!! json_encode($reports['facility_performance']->pluck('bookings_count')) !!},
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
