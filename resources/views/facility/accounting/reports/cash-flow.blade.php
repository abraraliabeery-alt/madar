@extends('facility.layouts.app')

@section('title', 'التدفق النقدي')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">التدفق النقدي</h3>
                    <div>
                        <a href="{{ route('facility.accounting.reports.export-cash-flow', request()->query()) }}" class="btn btn-success">
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
                                            <strong>صافي التدفق النقدي:</strong><br>
                                            <span class="text-{{ ($summary['net_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }} h5">
                                                {{ $summary['net_cash_flow'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <strong>الرصيد النهائي:</strong><br>
                                            <span class="text-{{ ($summary['ending_cash'] ?? 0) >= 0 ? 'success' : 'danger' }} h5">
                                                {{ $summary['ending_cash'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- التدفق النقدي -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">التدفق النقدي</h5>
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
                                                <!-- التدفق النقدي من الأنشطة التشغيلية -->
                                                <tr class="table-primary">
                                                    <td><strong>التدفق النقدي من الأنشطة التشغيلية</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['operating_cash_flow'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($operatingFlows) && $operatingFlows->count() > 0)
                                                    @foreach($operatingFlows as $flow)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $flow->description }}</td>
                                                            <td class="text-end {{ $flow->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $flow->amount >= 0 ? '+' : '' }}{{ number_format($flow->amount, 2) }} ر.س
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- التدفق النقدي من الأنشطة الاستثمارية -->
                                                <tr class="table-info">
                                                    <td><strong>التدفق النقدي من الأنشطة الاستثمارية</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['investing_cash_flow'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($investingFlows) && $investingFlows->count() > 0)
                                                    @foreach($investingFlows as $flow)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $flow->description }}</td>
                                                            <td class="text-end {{ $flow->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $flow->amount >= 0 ? '+' : '' }}{{ number_format($flow->amount, 2) }} ر.س
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- التدفق النقدي من الأنشطة التمويلية -->
                                                <tr class="table-warning">
                                                    <td><strong>التدفق النقدي من الأنشطة التمويلية</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['financing_cash_flow'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($financingFlows) && $financingFlows->count() > 0)
                                                    @foreach($financingFlows as $flow)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $flow->description }}</td>
                                                            <td class="text-end {{ $flow->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $flow->amount >= 0 ? '+' : '' }}{{ number_format($flow->amount, 2) }} ر.س
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- صافي التدفق النقدي -->
                                                <tr class="table-{{ ($summary['net_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                    <td><strong>صافي التدفق النقدي</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['net_cash_flow'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>

                                                <!-- الرصيد النقدي في بداية الفترة -->
                                                <tr class="table-light">
                                                    <td><strong>الرصيد النقدي في بداية الفترة</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['beginning_cash'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>

                                                <!-- الرصيد النقدي في نهاية الفترة -->
                                                <tr class="table-{{ ($summary['ending_cash'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                    <td><strong>الرصيد النقدي في نهاية الفترة</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['ending_cash'] ?? '0.00' }} ر.س</strong></td>
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
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تحليل التدفق النقدي</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>التشغيلية</span>
                                            <span class="text-{{ ($summary['operating_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                {{ $summary['operating_cash_flow'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                        <div class="progress mt-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ ($summary['operating_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}" 
                                                 style="width: {{ min(100, abs(($summary['operating_cash_flow'] ?? 0) / max(1, abs($summary['net_cash_flow'] ?? 1)) * 100) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>الاستثمارية</span>
                                            <span class="text-{{ ($summary['investing_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                {{ $summary['investing_cash_flow'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                        <div class="progress mt-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ ($summary['investing_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}" 
                                                 style="width: {{ min(100, abs(($summary['investing_cash_flow'] ?? 0) / max(1, abs($summary['net_cash_flow'] ?? 1)) * 100) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>التمويلية</span>
                                            <span class="text-{{ ($summary['financing_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                {{ $summary['financing_cash_flow'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                        <div class="progress mt-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ ($summary['financing_cash_flow'] ?? 0) >= 0 ? 'success' : 'danger' }}" 
                                                 style="width: {{ min(100, abs(($summary['financing_cash_flow'] ?? 0) / max(1, abs($summary['net_cash_flow'] ?? 1)) * 100) }}%"></div>
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
                                        <small class="text-muted">نسبة التدفق النقدي التشغيلي:</small>
                                        <div class="fw-bold">{{ $summary['operating_cash_ratio'] ?? '0.00' }}%</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة التدفق النقدي الحر:</small>
                                        <div class="fw-bold">{{ $summary['free_cash_flow_ratio'] ?? '0.00' }}%</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة التدفق النقدي إلى الإيرادات:</small>
                                        <div class="fw-bold">{{ $summary['cash_flow_to_revenue_ratio'] ?? '0.00' }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">التوقعات</h6>
                                </div>
                                <div class="card-body">
                                    @if(($summary['net_cash_flow'] ?? 0) > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-arrow-up"></i>
                                            <strong>تدفق نقدي إيجابي</strong><br>
                                            <small>الشركة تحقق تدفق نقدي إيجابي</small>
                                        </div>
                                    @elseif(($summary['net_cash_flow'] ?? 0) < 0)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-arrow-down"></i>
                                            <strong>تدفق نقدي سلبي</strong><br>
                                            <small>يجب مراجعة التدفق النقدي</small>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-equals"></i>
                                            <strong>تدفق نقدي متوازن</strong><br>
                                            <small>التدفق النقدي متوازن</small>
                                        </div>
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
                                        <li><i class="fas fa-info-circle text-info me-2"></i>التقرير يعرض التدفق النقدي للفترة المحددة</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>جميع المبالغ بالريال السعودي</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>الرصيد النهائي = الرصيد الابتدائي + صافي التدفق النقدي</li>
                                        @if(($summary['net_cash_flow'] ?? 0) < 0)
                                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>يوجد تدفق نقدي سلبي في هذه الفترة</li>
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

.table-primary {
    background-color: #cff4fc;
}

.table-info {
    background-color: #d1ecf1;
}

.table-warning {
    background-color: #fff3cd;
}

.table-success {
    background-color: #d1e7dd;
}

.table-danger {
    background-color: #f8d7da;
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
