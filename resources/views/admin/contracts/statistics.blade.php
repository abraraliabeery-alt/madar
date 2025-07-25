@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إحصائيات العقود</h5>
            <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
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
                                    <h6 class="mb-0">إجمالي العقود</h6>
                                    <h3 class="mb-0">{{ $stats['total_contracts'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-file-contract"></i>
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
                                    <h6 class="mb-0">العقود النشطة</h6>
                                    <h3 class="mb-0">{{ $stats['active_contracts'] }}</h3>
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
                                    <h6 class="mb-0">العقود المتحقق منها</h6>
                                    <h3 class="mb-0">{{ $stats['verified_contracts'] }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-shield-alt"></i>
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
                                    <h6 class="mb-0">إجمالي القيمة</h6>
                                    <h3 class="mb-0">{{ number_format($stats['total_value'], 2) }} ريال</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Types Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">توزيع أنواع العقود</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="contractTypesChart"></canvas>
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
                                <h3 class="mb-0">{{ number_format($stats['monthly_revenue'], 2) }} ريال</h3>
                                <small class="text-muted">إجمالي الإيرادات لهذا الشهر</small>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Contracts -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">أحدث العقود</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>رقم العقد</th>
                                            <th>المستخدم</th>
                                            <th>المنتج</th>
                                            <th>نوع العقد</th>
                                            <th>المبلغ</th>
                                            <th>التاريخ</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['recent_contracts'] as $contract)
                                        <tr>
                                            <td>{{ $contract->contract_number }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $contract->user) }}">
                                                    {{ $contract->user->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $contract->product) }}">
                                                    {{ $contract->product->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @switch($contract->contract_type)
                                                    @case('sale')
                                                        <span class="badge bg-success">بيع</span>
                                                        @break
                                                    @case('rent')
                                                        <span class="badge bg-info">إيجار</span>
                                                        @break
                                                    @case('lease')
                                                        <span class="badge bg-warning">تأجير</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ number_format($contract->total_amount, 2) }} ريال</td>
                                            <td>{{ $contract->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $contract->status->color }}">
                                                    {{ $contract->status->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-sm btn-info">
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
    // Contract Types Distribution Chart
    const typesCtx = document.getElementById('contractTypesChart').getContext('2d');
    new Chart(typesCtx, {
        type: 'pie',
        data: {
            labels: ['بيع', 'إيجار', 'تأجير'],
            datasets: [{
                data: [
                    {{ $stats['sale_contracts'] }},
                    {{ $stats['rent_contracts'] }},
                    {{ $stats['lease_contracts'] }}
                ],
                backgroundColor: [
                    'rgb(40, 167, 69)',
                    'rgb(23, 162, 184)',
                    'rgb(255, 193, 7)'
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

    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            datasets: [{
                label: 'الإيرادات الشهرية',
                data: [
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }},
                    {{ $stats['monthly_revenue'] }}
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
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
