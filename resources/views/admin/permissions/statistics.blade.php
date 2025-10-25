@extends('admin.layouts.app')

@section('title', 'إحصائيات الصلاحيات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إحصائيات الصلاحيات</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- الإحصائيات العامة -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['total_permissions'] }}</h3>
                                    <p class="card-text">إجمالي الصلاحيات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['active_permissions'] }}</h3>
                                    <p class="card-text">الصلاحيات النشطة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['inactive_permissions'] }}</h3>
                                    <p class="card-text">الصلاحيات غير النشطة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['permissions_by_group']->count() }}</h3>
                                    <p class="card-text">عدد المجموعات</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- توزيع الصلاحيات حسب المجموعات -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">توزيع الصلاحيات حسب المجموعات</h5>
                                </div>
                                <div class="card-body">
                                    @if($stats['permissions_by_group']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>المجموعة</th>
                                                        <th>عدد الصلاحيات</th>
                                                        <th>النسبة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($stats['permissions_by_group'] as $group => $count)
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-secondary">{{ $group }}</span>
                                                            </td>
                                                            <td>{{ $count }}</td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar" role="progressbar" 
                                                                         style="width: {{ ($count / $stats['total_permissions']) * 100 }}%">
                                                                        {{ number_format(($count / $stats['total_permissions']) * 100, 1) }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            لا توجد بيانات متاحة
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- أكثر الصلاحيات استخداماً -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">أكثر الصلاحيات استخداماً</h5>
                                </div>
                                <div class="card-body">
                                    @if($stats['most_used_permissions']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>الصلاحية</th>
                                                        <th>عدد الأدوار</th>
                                                        <th>النسبة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($stats['most_used_permissions'] as $permission)
                                                        <tr>
                                                            <td>
                                                                <code>{{ $permission->name }}</code>
                                                                @if($permission->translations->count() > 0)
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ $permission->getTranslatedName() }}
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info">{{ $permission->roles_count }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                                         style="width: {{ ($permission->roles_count / $stats['most_used_permissions']->max('roles_count')) * 100 }}%">
                                                                        {{ number_format(($permission->roles_count / $stats['most_used_permissions']->max('roles_count')) * 100, 1) }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            لا توجد بيانات متاحة
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مخطط دائري للمجموعات -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">توزيع الصلاحيات (مخطط دائري)</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="permissionsChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- مخطط أعمدة للاستخدام -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">أكثر الصلاحيات استخداماً (مخطط أعمدة)</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="usageChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تقرير مفصل -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">تقرير مفصل</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>الصلاحيات النشطة</h6>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ ($stats['active_permissions'] / $stats['total_permissions']) * 100 }}%">
                                                    {{ $stats['active_permissions'] }} ({{ number_format(($stats['active_permissions'] / $stats['total_permissions']) * 100, 1) }}%)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>الصلاحيات غير النشطة</h6>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-danger" role="progressbar" 
                                                     style="width: {{ ($stats['inactive_permissions'] / $stats['total_permissions']) * 100 }}%">
                                                    {{ $stats['inactive_permissions'] }} ({{ number_format(($stats['inactive_permissions'] / $stats['total_permissions']) * 100, 1) }}%)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>المجموعات المختلفة</h6>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-info" role="progressbar" 
                                                     style="width: 100%">
                                                    {{ $stats['permissions_by_group']->count() }} مجموعة
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    // مخطط دائري للمجموعات
    const permissionsCtx = document.getElementById('permissionsChart').getContext('2d');
    const permissionsChart = new Chart(permissionsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($stats['permissions_by_group']->keys()->toArray()) !!},
            datasets: [{
                data: {!! json_encode($stats['permissions_by_group']->values()->toArray()) !!},
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#FF6384',
                    '#C9CBCF'
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

    // مخطط أعمدة للاستخدام
    const usageCtx = document.getElementById('usageChart').getContext('2d');
    const usageChart = new Chart(usageCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['most_used_permissions']->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'عدد الأدوار',
                data: {!! json_encode($stats['most_used_permissions']->pluck('roles_count')->toArray()) !!},
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
