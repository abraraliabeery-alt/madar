@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إحصائيات الحجوزات</h5>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Statistics Cards -->
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي الحجوزات</h6>
                                    <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الحجوزات المؤكدة</h6>
                                    <h3 class="mb-0">{{ $stats['confirmed_bookings'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الحجوزات المدفوعة</h6>
                                    <h3 class="mb-0">{{ $stats['paid_bookings'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">قيد الانتظار</h6>
                                    <h3 class="mb-0">{{ $stats['pending_bookings'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الإيرادات الشهرية</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 class="mb-0">{{ number_format($stats['monthly_revenue'], 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</h3>
                                <small class="text-muted">إجمالي الإيرادات لهذا الشهر</small>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Status -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">توزيع حالات الحجوزات</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">أحدث الحجوزات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>رقم الحجز</th>
                                            <th>المستخدم</th>
                                            <th>المنتج</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['recent_bookings'] as $booking)
                                        <tr>
                                            <td>{{ $booking->booking_number }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->product->name }}</td>
                                            <td>{{ $booking->booking_date }}</td>
                                            <td>{{ number_format($booking->total_amount, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</td>
                                            <td>
                                                @if($booking->is_confirmed)
                                                    <span class="badge bg-success">مؤكد</span>
                                                @else
                                                    <span class="badge bg-warning">قيد الانتظار</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            datasets: [{
                label: 'الإيرادات الشهرية',
                data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 75, 85, {{ $stats['monthly_revenue'] }}],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
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

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['مؤكد', 'قيد الانتظار', 'مدفوع', 'غير مدفوع'],
            datasets: [{
                data: [
                    {{ $stats['confirmed_bookings'] }},
                    {{ $stats['pending_bookings'] }},
                    {{ $stats['paid_bookings'] }},
                    {{ $stats['total_bookings'] - $stats['paid_bookings'] }}
                ],
                backgroundColor: [
                    'rgb(40, 167, 69)',
                    'rgb(255, 193, 7)',
                    'rgb(23, 162, 184)',
                    'rgb(220, 53, 69)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
