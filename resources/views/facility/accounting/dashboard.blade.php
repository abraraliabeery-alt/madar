@extends('facility.layouts.app')

@section('title', 'لوحة التحكم المحاسبية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">لوحة التحكم المحاسبية</h3>
                    <div>
                        <a href="{{ route('facility.accounting.entries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> قيد محاسبي جديد
                        </a>
                        <a href="{{ route('facility.accounting.reports.index') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> التقارير المالية
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- إحصائيات سريعة -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card stats-card-sm">
                                <div class="card-body text-center">
                                    <div class="stats-icon text-primary mb-2">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <div class="stats-value">{{ number_format($stats['total_accounts']) }}</div>
                                    <div class="stats-label">إجمالي الحسابات</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card stats-card-sm">
                                <div class="card-body text-center">
                                    <div class="stats-icon text-success mb-2">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="stats-value">{{ number_format($stats['active_periods']) }}</div>
                                    <div class="stats-label">الفترات النشطة</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card stats-card-sm">
                                <div class="card-body text-center">
                                    <div class="stats-icon text-info mb-2">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div class="stats-value">{{ number_format($stats['total_entries']) }}</div>
                                    <div class="stats-label">إجمالي القيود</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card stats-card-sm">
                                <div class="card-body text-center">
                                    <div class="stats-icon text-warning mb-2">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stats-value">{{ number_format($stats['pending_entries']) }}</div>
                                    <div class="stats-label">القيود المعلقة</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- الفترة المحاسبية الحالية -->
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calendar text-primary"></i>
                                        الفترة المحاسبية الحالية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($currentPeriod)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">{{ $currentPeriod->period_name }}</h6>
                                            <span class="badge bg-success">{{ $currentPeriod->status }}</span>
                                        </div>
                                        <p class="text-muted mb-2">
                                            من {{ $currentPeriod->start_date->format('Y-m-d') }} 
                                            إلى {{ $currentPeriod->end_date->format('Y-m-d') }}
                                        </p>
                                        <div class="progress mb-3" style="height: 8px;">
                                            @php
                                                $progress = $currentPeriod->duration > 0 ? 
                                                    (now()->diffInDays($currentPeriod->start_date) / $currentPeriod->duration) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" style="width: {{ min($progress, 100) }}%"></div>
                                        </div>
                                        <small class="text-muted">
                                            {{ round(min($progress, 100)) }}% من الفترة المحاسبية
                                        </small>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-calendar-times text-muted fa-2x mb-2"></i>
                                            <p class="text-muted">لا توجد فترة محاسبية نشطة</p>
                                            <a href="{{ route('facility.accounting.periods.create') }}" class="btn btn-primary btn-sm">
                                                إنشاء فترة جديدة
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ملخص مالي -->
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-pie text-success"></i>
                                        الملخص المالي
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <div class="border-end">
                                                <h4 class="text-success mb-1">{{ number_format($financialSummary['total_revenue'], 2) }}</h4>
                                                <small class="text-muted">الإيرادات</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h4 class="text-danger mb-1">{{ number_format($financialSummary['total_expenses'], 2) }}</h4>
                                            <small class="text-muted">المصروفات</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h4 class="text-info mb-1">{{ number_format($financialSummary['total_assets'], 2) }}</h4>
                                                <small class="text-muted">الأصول</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-warning mb-1">{{ number_format($financialSummary['total_liabilities'], 2) }}</h4>
                                            <small class="text-muted">الخصوم</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h5 class="mb-0 {{ $financialSummary['net_income'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($financialSummary['net_income'], 2) }} ريال
                                        </h5>
                                        <small class="text-muted">صافي الدخل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- آخر القيود المحاسبية -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-file-invoice text-info"></i>
                                        آخر القيود المحاسبية
                                    </h5>
                                    <a href="{{ route('facility.accounting.entries.index') }}" class="btn btn-outline-primary btn-sm">
                                        عرض الكل
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if($recentEntries->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>التاريخ</th>
                                                        <th>نوع القيد</th>
                                                        <th>الحساب</th>
                                                        <th>المبلغ</th>
                                                        <th>الوصف</th>
                                                        <th>منشئ القيد</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentEntries as $entry)
                                                        <tr>
                                                            <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $entry->entry_type === 'debit' ? 'primary' : 'success' }}">
                                                                    {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $entry->account->account_name ?? 'غير محدد' }}</td>
                                                            <td>{{ $entry->formatted_amount }}</td>
                                                            <td>{{ Str::limit($entry->description, 50) }}</td>
                                                            <td>{{ $entry->createdBy->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-file-invoice text-muted fa-3x mb-3"></i>
                                            <h5 class="text-muted">لا توجد قيود محاسبية</h5>
                                            <p class="text-muted">ابدأ بإنشاء قيد محاسبي جديد</p>
                                            <a href="{{ route('facility.accounting.entries.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إنشاء قيد جديد
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الميزانيات النشطة -->
                    @if($activeBudgets->count() > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-line text-warning"></i>
                                        الميزانيات النشطة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($activeBudgets as $budget)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0">{{ $budget->budget_name }}</h6>
                                                            <span class="badge bg-success">{{ $budget->status }}</span>
                                                        </div>
                                                        <p class="text-muted small mb-2">{{ $budget->description }}</p>
                                                        <div class="progress mb-2" style="height: 8px;">
                                                            <div class="progress-bar bg-{{ $budget->utilization_percentage > 100 ? 'danger' : ($budget->utilization_percentage > 80 ? 'warning' : 'success') }}" 
                                                                 style="width: {{ min($budget->utilization_percentage, 100) }}%"></div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-muted">
                                                                {{ number_format($budget->spent_amount, 2) }} / {{ number_format($budget->total_budget, 2) }} ريال
                                                            </small>
                                                            <small class="text-muted">
                                                                {{ round($budget->utilization_percentage, 1) }}%
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-card-sm {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card-sm:hover {
    transform: translateY(-2px);
}

.stats-icon {
    font-size: 1.5rem;
}

.stats-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.stats-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

@media (max-width: 768px) {
    .stats-value {
        font-size: 1.25rem;
    }
    
    .stats-label {
        font-size: 0.7rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
