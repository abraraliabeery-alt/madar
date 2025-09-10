@extends('facility.layouts.app')

@section('title', 'تقرير الميزانية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تقرير الميزانية</h3>
                    <div>
                        <a href="{{ route('facility.accounting.reports.export-budget-report', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير PDF
                        </a>
                        <a href="{{ route('facility.accounting.reports.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للتقارير
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- معلومات التقرير -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">معلومات التقرير</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>الميزانية:</strong><br>
                                            <span class="text-muted">{{ $budget->name ?? 'جميع الميزانيات' }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>الفترة:</strong><br>
                                            <span class="text-muted">{{ $startDate->format('Y-m-d') }} - {{ $endDate->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">ملخص سريع</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>المبلغ المخصص:</strong><br>
                                            <span class="text-primary h5">{{ $summary['total_budget'] ?? '0.00' }} ر.س</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>المبلغ المنفق:</strong><br>
                                            <span class="text-warning h5">{{ $summary['total_spent'] ?? '0.00' }} ر.س</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تقرير الميزانية -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">تقرير الميزانية</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>اسم الميزانية</th>
                                                    <th>الفترة</th>
                                                    <th class="text-end">المبلغ المخصص</th>
                                                    <th class="text-end">المبلغ المنفق</th>
                                                    <th class="text-end">المتبقي</th>
                                                    <th class="text-end">نسبة الاستهلاك</th>
                                                    <th>الحالة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($budgets) && $budgets->count() > 0)
                                                    @foreach($budgets as $budget)
                                                        @php
                                                            $spentAmount = $budget->spent_amount ?? 0;
                                                            $remainingAmount = $budget->amount - $spentAmount;
                                                            $usagePercentage = $budget->amount > 0 ? ($spentAmount / $budget->amount) * 100 : 0;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <strong>{{ $budget->name }}</strong>
                                                                    @if($budget->is_current)
                                                                        <br><small class="text-success">ميزانية حالية</small>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <small class="text-muted">{{ $budget->start_date->format('Y-m-d') }}</small>
                                                                    <br>
                                                                    <small class="text-muted">{{ $budget->end_date->format('Y-m-d') }}</small>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <strong class="text-primary">{{ number_format($budget->amount, 2) }} ر.س</strong>
                                                            </td>
                                                            <td class="text-end">
                                                                <strong class="text-warning">{{ number_format($spentAmount, 2) }} ر.س</strong>
                                                            </td>
                                                            <td class="text-end">
                                                                <strong class="{{ $remainingAmount >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ number_format($remainingAmount, 2) }} ر.س
                                                                </strong>
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar bg-{{ $usagePercentage > 90 ? 'danger' : ($usagePercentage > 75 ? 'warning' : 'success') }}" 
                                                                         role="progressbar" style="width: {{ min(100, $usagePercentage) }}%">
                                                                        {{ number_format($usagePercentage, 1) }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if($budget->status === 'pending')
                                                                    <span class="badge bg-warning">معلقة</span>
                                                                @elseif($budget->status === 'active')
                                                                    <span class="badge bg-success">نشطة</span>
                                                                @elseif($budget->status === 'approved')
                                                                    <span class="badge bg-info">معتمدة</span>
                                                                @else
                                                                    <span class="badge bg-secondary">مكتملة</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">لا توجد ميزانيات</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="2"><strong>الإجمالي</strong></td>
                                                    <td class="text-end">
                                                        <strong class="text-primary">{{ $summary['total_budget'] ?? '0.00' }} ر.س</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong class="text-warning">{{ $summary['total_spent'] ?? '0.00' }} ر.س</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong class="{{ ($summary['total_remaining'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $summary['total_remaining'] ?? '0.00' }} ر.س
                                                        </strong>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong>{{ $summary['average_usage_percentage'] ?? '0.0' }}%</strong>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تحليل إضافي -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تحليل الاستهلاك</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>ميزانيات تحت الميزانية:</span>
                                            <span class="text-success">{{ $summary['under_budget_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>ميزانيات فوق الميزانية:</span>
                                            <span class="text-danger">{{ $summary['over_budget_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>ميزانيات متوازنة:</span>
                                            <span class="text-info">{{ $summary['balanced_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>إجمالي الميزانيات:</span>
                                            <span class="text-dark">{{ $summary['total_budgets_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">مؤشرات الأداء</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة الاستهلاك الإجمالي:</small>
                                        <div class="fw-bold">{{ $summary['total_usage_percentage'] ?? '0.0' }}%</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة التوفير:</small>
                                        <div class="fw-bold">{{ $summary['savings_percentage'] ?? '0.0' }}%</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة التجاوز:</small>
                                        <div class="fw-bold">{{ $summary['overage_percentage'] ?? '0.0' }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">التوصيات</h6>
                                </div>
                                <div class="card-body">
                                    @if(($summary['total_usage_percentage'] ?? 0) > 90)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>استهلاك عالي</strong><br>
                                            <small>يجب مراجعة الميزانيات</small>
                                        </div>
                                    @elseif(($summary['total_usage_percentage'] ?? 0) < 50)
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>استهلاك منخفض</strong><br>
                                            <small>يمكن زيادة الاستثمار</small>
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            <strong>استهلاك متوازن</strong><br>
                                            <small>الميزانيات تحت السيطرة</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل إضافية -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تفاصيل إضافية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-primary">{{ $summary['total_budget'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي الميزانية</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-warning">{{ $summary['total_spent'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي المنفق</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-success">{{ $summary['total_remaining'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي المتبقي</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-info">{{ $summary['average_usage_percentage'] ?? '0.0' }}%</h5>
                                                <p class="text-muted">متوسط الاستهلاك</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">ملاحظات</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-info-circle text-info me-2"></i>التقرير يعرض مقارنة الميزانية المخططة مع الفعلية</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>جميع المبالغ بالريال السعودي</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>نسبة الاستهلاك = (المبلغ المنفق / المبلغ المخصص) × 100</li>
                                        @if(($summary['over_budget_count'] ?? 0) > 0)
                                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>يوجد {{ $summary['over_budget_count'] }} ميزانية تجاوزت المبلغ المخصص</li>
                                        @endif
                                    </ul>
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

@push('styles')
<style>
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-dark th {
    background-color: #343a40;
    border-color: #454d55;
}

.table-light {
    background-color: #f8f9fa;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.progress {
    background-color: #e9ecef;
    border-radius: 0.375rem;
}

.progress-bar {
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush
