@extends('facility.layouts.app')

@section('title', 'قائمة الدخل')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة الدخل</h3>
                    <div>
                        <a href="{{ route('facility.accounting.reports.export-income-statement', request()->query()) }}" class="btn btn-success">
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
                                            <strong>الفترة:</strong><br>
                                            <span class="text-muted">{{ $period->name ?? 'جميع الفترات' }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>التاريخ:</strong><br>
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
                                            <strong>إجمالي الإيرادات:</strong><br>
                                            <span class="text-success h5">{{ $summary['total_revenue'] ?? '0.00' }} ر.س</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>صافي الربح:</strong><br>
                                            <span class="text-{{ ($summary['net_profit'] ?? 0) >= 0 ? 'success' : 'danger' }} h5">
                                                {{ $summary['net_profit'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قائمة الدخل -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">قائمة الدخل</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>الوصف</th>
                                                    <th class="text-end">المبلغ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- الإيرادات -->
                                                <tr class="table-success">
                                                    <td><strong>الإيرادات</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_revenue'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($revenues) && $revenues->count() > 0)
                                                    @foreach($revenues as $revenue)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $revenue->account_name }}</td>
                                                            <td class="text-end">{{ number_format($revenue->total_amount, 2) }} ر.س</td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- إجمالي الإيرادات -->
                                                <tr class="table-success">
                                                    <td><strong>إجمالي الإيرادات</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_revenue'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>

                                                <!-- المصروفات -->
                                                <tr class="table-danger">
                                                    <td><strong>المصروفات</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_expenses'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($expenses) && $expenses->count() > 0)
                                                    @foreach($expenses as $expense)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $expense->account_name }}</td>
                                                            <td class="text-end">{{ number_format($expense->total_amount, 2) }} ر.س</td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- إجمالي المصروفات -->
                                                <tr class="table-danger">
                                                    <td><strong>إجمالي المصروفات</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_expenses'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>

                                                <!-- صافي الربح -->
                                                <tr class="table-{{ ($summary['net_profit'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                    <td><strong>صافي الربح</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['net_profit'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تحليل إضافي -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تحليل الإيرادات</h6>
                                </div>
                                <div class="card-body">
                                    @if(isset($revenueAnalysis) && count($revenueAnalysis) > 0)
                                        @foreach($revenueAnalysis as $item)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>{{ $item['name'] }}</span>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 100px; height: 8px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $item['percentage'] }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ $item['percentage'] }}%</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">لا توجد بيانات للتحليل</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تحليل المصروفات</h6>
                                </div>
                                <div class="card-body">
                                    @if(isset($expenseAnalysis) && count($expenseAnalysis) > 0)
                                        @foreach($expenseAnalysis as $item)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>{{ $item['name'] }}</span>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 100px; height: 8px;">
                                                        <div class="progress-bar bg-danger" style="width: {{ $item['percentage'] }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ $item['percentage'] }}%</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">لا توجد بيانات للتحليل</p>
                                    @endif
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
                                        <li><i class="fas fa-info-circle text-info me-2"></i>التقرير يعرض البيانات للفترة المحددة</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>جميع المبالغ بالريال السعودي</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>صافي الربح = إجمالي الإيرادات - إجمالي المصروفات</li>
                                        @if(($summary['net_profit'] ?? 0) < 0)
                                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>يوجد خسارة في هذه الفترة</li>
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

.table-success {
    background-color: #d1e7dd;
}

.table-danger {
    background-color: #f8d7da;
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
