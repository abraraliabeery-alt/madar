@extends('facility.layouts.app')

@section('title', 'التقارير المالية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">التقارير المالية</h3>
                    <div>
                        <a href="{{ route('facility.accounting.reports.export-all') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير جميع التقارير
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلترة التقارير -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="period_id" class="form-label">الفترة المحاسبية</label>
                            <select name="period_id" id="period_id" class="form-select">
                                <option value="">جميع الفترات</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start_date" class="form-label">من تاريخ</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="end_date" class="form-label">إلى تاريخ</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="currency" class="form-label">العملة</label>
                            <select name="currency" id="currency" class="form-select">
                                <option value="SAR" {{ request('currency') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                <option value="USD" {{ request('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                                <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>يورو</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> تطبيق
                                </button>
                                <a href="{{ route('facility.accounting.reports.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> مسح
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- تقارير أساسية -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                    <h5 class="card-title">قائمة الدخل</h5>
                                    <p class="card-text">عرض الإيرادات والمصروفات والأرباح</p>
                                    <a href="{{ route('facility.accounting.reports.income-statement', request()->query()) }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-balance-scale fa-2x text-info mb-2"></i>
                                    <h5 class="card-title">الميزانية العمومية</h5>
                                    <p class="card-text">عرض الأصول والخصوم وحقوق الملكية</p>
                                    <a href="{{ route('facility.accounting.reports.balance-sheet', request()->query()) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i> عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-coins fa-2x text-success mb-2"></i>
                                    <h5 class="card-title">التدفق النقدي</h5>
                                    <p class="card-text">عرض التدفقات النقدية الداخلة والخارجة</p>
                                    <a href="{{ route('facility.accounting.reports.cash-flow', request()->query()) }}" class="btn btn-success">
                                        <i class="fas fa-eye"></i> عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-calculator fa-2x text-warning mb-2"></i>
                                    <h5 class="card-title">ميزان المراجعة</h5>
                                    <p class="card-text">عرض جميع الحسابات وأرصدتها</p>
                                    <a href="{{ route('facility.accounting.reports.trial-balance', request()->query()) }}" class="btn btn-warning">
                                        <i class="fas fa-eye"></i> عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تقارير إضافية -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-pie fa-2x text-secondary mb-2"></i>
                                    <h5 class="card-title">تقرير الميزانية</h5>
                                    <p class="card-text">مقارنة الميزانية المخططة مع الفعلية</p>
                                    <a href="{{ route('facility.accounting.reports.budget-report', request()->query()) }}" class="btn btn-secondary">
                                        <i class="fas fa-eye"></i> عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-bar fa-2x text-dark mb-2"></i>
                                    <h5 class="card-title">تقرير الحسابات</h5>
                                    <p class="card-text">تفاصيل حركات الحسابات</p>
                                    <a href="{{ route('facility.accounting.reports.account-details', request()->query()) }}" class="btn btn-dark">
                                        <i class="fas fa-eye"></i> عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                                    <h5 class="card-title">تقرير مخصص</h5>
                                    <p class="card-text">إنشاء تقرير مخصص حسب الحاجة</p>
                                    <a href="{{ route('facility.accounting.reports.custom', request()->query()) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-plus"></i> إنشاء تقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملخص سريع -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">ملخص سريع</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success">{{ $summary['total_revenue'] ?? '0.00' }} ر.س</h4>
                                                <p class="text-muted">إجمالي الإيرادات</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-danger">{{ $summary['total_expenses'] ?? '0.00' }} ر.س</h4>
                                                <p class="text-muted">إجمالي المصروفات</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-{{ ($summary['net_profit'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                    {{ $summary['net_profit'] ?? '0.00' }} ر.س
                                                </h4>
                                                <p class="text-muted">صافي الربح</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info">{{ $summary['total_assets'] ?? '0.00' }} ر.س</h4>
                                                <p class="text-muted">إجمالي الأصول</p>
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
<script>
    // Auto-submit form on period change
    document.getElementById('period_id').addEventListener('change', function() {
        if (this.value) {
            // Get period dates and fill start/end date fields
            const periodOption = this.options[this.selectedIndex];
            const periodText = periodOption.text;
            
            // You can implement logic to extract dates from period name
            // For now, just submit the form
            this.form.submit();
        }
    });

    // Set default date range to current month
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        if (!document.getElementById('start_date').value) {
            document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
        }
        if (!document.getElementById('end_date').value) {
            document.getElementById('end_date').value = lastDay.toISOString().split('T')[0];
        }
    });
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

.card-body {
    padding: 1.5rem;
}

.text-center .card-body {
    padding: 2rem 1rem;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
