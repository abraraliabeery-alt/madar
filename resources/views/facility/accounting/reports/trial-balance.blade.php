@extends('facility.layouts.app')

@section('title', 'ميزان المراجعة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">ميزان المراجعة</h3>
                    <div>
                        <a href="{{ route('facility.accounting.reports.export-trial-balance', request()->query()) }}" class="btn btn-success">
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
                                            <span class="text-muted">{{ $endDate->format('Y-m-d') }}</span>
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
                                            <strong>إجمالي المدين:</strong><br>
                                            <span class="text-primary h5">{{ $summary['total_debit'] ?? '0.00' }} ر.س</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>إجمالي الدائن:</strong><br>
                                            <span class="text-success h5">{{ $summary['total_credit'] ?? '0.00' }} ر.س</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ميزان المراجعة -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">ميزان المراجعة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>كود الحساب</th>
                                                    <th>اسم الحساب</th>
                                                    <th>النوع</th>
                                                    <th class="text-end">الرصيد المدين</th>
                                                    <th class="text-end">الرصيد الدائن</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($accounts) && $accounts->count() > 0)
                                                    @foreach($accounts as $account)
                                                        <tr>
                                                            <td><strong>{{ $account->account_code }}</strong></td>
                                                            <td>{{ $account->account_name }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $account->account_type === 'asset' ? 'primary' : ($account->account_type === 'liability' ? 'warning' : ($account->account_type === 'equity' ? 'info' : ($account->account_type === 'revenue' ? 'success' : 'danger'))) }}">
                                                                    {{ $accountTypes[$account->account_type] ?? $account->account_type }}
                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                @if($account->debit_balance > 0)
                                                                    <span class="text-primary">{{ number_format($account->debit_balance, 2) }} ر.س</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end">
                                                                @if($account->credit_balance > 0)
                                                                    <span class="text-success">{{ number_format($account->credit_balance, 2) }} ر.س</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">لا توجد حسابات</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3"><strong>الإجمالي</strong></td>
                                                    <td class="text-end">
                                                        <strong class="text-primary">{{ $summary['total_debit'] ?? '0.00' }} ر.س</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong class="text-success">{{ $summary['total_credit'] ?? '0.00' }} ر.س</strong>
                                                    </td>
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
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تحليل الأرصدة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>الحسابات المدينة:</span>
                                            <span class="text-primary">{{ $summary['debit_accounts_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>الحسابات الدائنة:</span>
                                            <span class="text-success">{{ $summary['credit_accounts_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>الحسابات المتوازنة:</span>
                                            <span class="text-info">{{ $summary['balanced_accounts_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>إجمالي الحسابات:</span>
                                            <span class="text-dark">{{ $summary['total_accounts_count'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">حالة التوازن</h6>
                                </div>
                                <div class="card-body">
                                    @if(($summary['total_debit'] ?? 0) == ($summary['total_credit'] ?? 0))
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            <strong>ميزان المراجعة متوازن</strong><br>
                                            <small>إجمالي المدين = إجمالي الدائن</small>
                                        </div>
                                    @else
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>ميزان المراجعة غير متوازن</strong><br>
                                            <small>يوجد فرق قدره {{ abs(($summary['total_debit'] ?? 0) - ($summary['total_credit'] ?? 0)) }} ر.س</small>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>الفرق:</span>
                                            <span class="text-{{ (($summary['total_debit'] ?? 0) - ($summary['total_credit'] ?? 0)) == 0 ? 'success' : 'danger' }}">
                                                {{ (($summary['total_debit'] ?? 0) - ($summary['total_credit'] ?? 0)) >= 0 ? '+' : '' }}{{ number_format(($summary['total_debit'] ?? 0) - ($summary['total_credit'] ?? 0), 2) }} ر.س
                                            </span>
                                        </div>
                                    </div>
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
                                                <h5 class="text-primary">{{ $summary['asset_balance'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي الأصول</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-warning">{{ $summary['liability_balance'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي الخصوم</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-info">{{ $summary['equity_balance'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي حقوق الملكية</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h5 class="text-success">{{ $summary['revenue_balance'] ?? '0.00' }} ر.س</h5>
                                                <p class="text-muted">إجمالي الإيرادات</p>
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
                                        <li><i class="fas fa-info-circle text-info me-2"></i>التقرير يعرض أرصدة الحسابات حتى التاريخ المحدد</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>جميع المبالغ بالريال السعودي</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>ميزان المراجعة يجب أن يكون متوازناً (المدين = الدائن)</li>
                                        @if(($summary['total_debit'] ?? 0) != ($summary['total_credit'] ?? 0))
                                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>يوجد عدم توازن في ميزان المراجعة</li>
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
