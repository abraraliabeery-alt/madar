@extends('admin.layouts.app')

@section('title', 'إحصائيات المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">إحصائيات المستخدمين</li>
                    </ol>
                </div>
                <h4 class="page-title">إحصائيات المستخدمين</h4>
            </div>
        </div>
    </div>

    <!-- Basic Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">إجمالي المستخدمين</p>
                            <h4 class="mb-2">{{ number_format($stats['total_users']) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                </span>
                                +{{ $stats['new_users_today'] }} اليوم
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded">
                                <i class="mdi mdi-account-group font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">المستخدمين النشطين</p>
                            <h4 class="mb-2">{{ number_format($stats['active_users']) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-account-check"></i>
                                </span>
                                {{ round(($stats['active_users'] / $stats['total_users']) * 100, 1) }}% من الإجمالي
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded">
                                <i class="mdi mdi-account-check font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">المستخدمين المصدقين</p>
                            <h4 class="mb-2">{{ number_format($stats['verified_users']) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-shield-check"></i>
                                </span>
                                {{ round(($stats['verified_users'] / $stats['total_users']) * 100, 1) }}% من الإجمالي
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded">
                                <i class="mdi mdi-shield-check font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">المستخدمين الجدد هذا الشهر</p>
                            <h4 class="mb-2">{{ number_format($stats['new_users_this_month']) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-calendar-month"></i>
                                </span>
                                {{ now()->format('F Y') }}
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded">
                                <i class="mdi mdi-calendar-month font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">تسجيل المستخدمين - آخر 12 شهر</h5>
                </div>
                <div class="card-body">
                    <canvas id="registrationsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">توزيع الأدوار</h5>
                </div>
                <div class="card-body">
                    <canvas id="roleDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات النشاط</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي الأنشطة</h6>
                                <h4 class="text-primary">{{ number_format($activityStats['total_activities']) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الأنشطة اليوم</h6>
                                <h4 class="text-success">{{ number_format($activityStats['activities_today']) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الأنشطة هذا الشهر</h6>
                                <h4 class="text-info">{{ number_format($activityStats['activities_this_month']) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>متوسط الأنشطة/مستخدم</h6>
                                <h4 class="text-warning">{{ $stats['total_users'] > 0 ? round($activityStats['total_activities'] / $stats['total_users'], 2) : 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات التفاعل</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي الحجوزات</h6>
                                <h4 class="text-primary">{{ number_format($engagementStats['total_bookings']) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي العقود</h6>
                                <h4 class="text-success">{{ number_format($engagementStats['total_contracts']) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي الفواتير</h6>
                                <h4 class="text-info">{{ number_format($engagementStats['total_invoices']) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>متوسط الحجوزات/مستخدم</h6>
                                <h4 class="text-warning">{{ $engagementStats['average_bookings_per_user'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Active Users and Top Actions -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">أكثر المستخدمين نشاطاً</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>عدد الأنشطة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityStats['most_active_users'] as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-title bg-light text-primary rounded">
                                                    {{ substr($user->user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->user->name ?? 'غير محدد' }}</h6>
                                                <small class="text-muted">{{ $user->user->email ?? 'غير محدد' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->activity_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">لا توجد بيانات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">أكثر الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الإجراء</th>
                                    <th>العدد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityStats['top_actions'] as $action)
                                <tr>
                                    <td>{{ $action->action }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $action->count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">لا توجد بيانات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">النشاط الأخير</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الإجراء</th>
                                    <th>الوصف</th>
                                    <th>عنوان IP</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $activity)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-title bg-light text-primary rounded">
                                                    {{ substr($activity->user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $activity->user->name ?? 'غير محدد' }}</h6>
                                                <small class="text-muted">{{ $activity->user->email ?? 'غير محدد' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $activity->action }}</span>
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد أنشطة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    // Registrations Chart
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyRegistrations['months']),
            datasets: [{
                label: 'تسجيل المستخدمين',
                data: @json($monthlyRegistrations['registrations']),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
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
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // Role Distribution Chart
    const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
    const roleChart = new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: @json($roleDistribution->pluck('name')),
            datasets: [{
                data: @json($roleDistribution->pluck('count')),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
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
</script>
@endpush
