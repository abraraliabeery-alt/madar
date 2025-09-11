@extends('facility.layouts.app')

@section('title', 'الميزانية العمومية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">الميزانية العمومية</h3>
                    <div>
                        <a href="{{ route('facility.accounting.reports.export-balance-sheet', request()->query()) }}" class="btn btn-success">
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
                                            <strong>إجمالي الأصول:</strong><br>
                                            <span class="text-success h5">{{ $summary['total_assets'] ?? '0.00' }} ر.س</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>صافي القيمة:</strong><br>
                                            <span class="text-{{ ($summary['net_worth'] ?? 0) >= 0 ? 'success' : 'danger' }} h5">
                                                {{ $summary['net_worth'] ?? '0.00' }} ر.س
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الميزانية العمومية -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">الميزانية العمومية</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>الأصول</th>
                                                    <th class="text-end">المبلغ</th>
                                                    <th>الخصوم وحقوق الملكية</th>
                                                    <th class="text-end">المبلغ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- الأصول -->
                                                <tr class="table-success">
                                                    <td><strong>الأصول</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_assets'] ?? '0.00' }} ر.س</strong></td>
                                                    <td><strong>الخصوم</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_liabilities'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>

                                                <!-- الأصول المتداولة -->
                                                <tr class="table-light">
                                                    <td><strong>الأصول المتداولة</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['current_assets'] ?? '0.00' }} ر.س</strong></td>
                                                    <td><strong>الخصوم المتداولة</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['current_liabilities'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($currentAssets) && $currentAssets && $currentAssets->count() > 0)
                                                    @foreach($currentAssets as $asset)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $asset->account_name ?? 'غير محدد' }}</td>
                                                            <td class="text-end">{{ number_format($asset->balance ?? 0, 2) }} ر.س</td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- الأصول الثابتة -->
                                                <tr class="table-light">
                                                    <td><strong>الأصول الثابتة</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['fixed_assets'] ?? '0.00' }} ر.س</strong></td>
                                                    <td><strong>الخصوم طويلة الأجل</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['long_term_liabilities'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($fixedAssets) && $fixedAssets && $fixedAssets->count() > 0)
                                                    @foreach($fixedAssets as $asset)
                                                        <tr>
                                                            <td style="padding-left: 2rem;">{{ $asset->account_name ?? 'غير محدد' }}</td>
                                                            <td class="text-end">{{ number_format($asset->balance ?? 0, 2) }} ر.س</td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- إجمالي الأصول -->
                                                <tr class="table-success">
                                                    <td><strong>إجمالي الأصول</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_assets'] ?? '0.00' }} ر.س</strong></td>
                                                    <td><strong>إجمالي الخصوم</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_liabilities'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>

                                                <!-- حقوق الملكية -->
                                                <tr class="table-info">
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>حقوق الملكية</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_equity'] ?? '0.00' }} ر.س</strong></td>
                                                </tr>
                                                @if(isset($equity) && $equity && $equity->count() > 0)
                                                    @foreach($equity as $equityItem)
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="padding-left: 2rem;">{{ $equityItem->account_name ?? 'غير محدد' }}</td>
                                                            <td class="text-end">{{ number_format($equityItem->balance ?? 0, 2) }} ر.س</td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                <!-- إجمالي الخصوم وحقوق الملكية -->
                                                <tr class="table-info">
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>إجمالي الخصوم وحقوق الملكية</strong></td>
                                                    <td class="text-end"><strong>{{ $summary['total_liabilities_equity'] ?? '0.00' }} ر.س</strong></td>
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
                                    <h6 class="card-title mb-0">تحليل الأصول</h6>
                                </div>
                                <div class="card-body">
                                    @if(isset($assetAnalysis) && count($assetAnalysis) > 0)
                                        @foreach($assetAnalysis as $item)
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
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">تحليل الخصوم</h6>
                                </div>
                                <div class="card-body">
                                    @if(isset($liabilityAnalysis) && count($liabilityAnalysis) > 0)
                                        @foreach($liabilityAnalysis as $item)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>{{ $item['name'] }}</span>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 100px; height: 8px;">
                                                        <div class="progress-bar bg-warning" style="width: {{ $item['percentage'] }}%"></div>
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
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">مؤشرات مالية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة السيولة:</small>
                                        <div class="fw-bold">{{ $summary['liquidity_ratio'] ?? '0.00' }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة الدين:</small>
                                        <div class="fw-bold">{{ $summary['debt_ratio'] ?? '0.00' }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">نسبة حقوق الملكية:</small>
                                        <div class="fw-bold">{{ $summary['equity_ratio'] ?? '0.00' }}</div>
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
                                        <li><i class="fas fa-info-circle text-info me-2"></i>التقرير يعرض البيانات حتى التاريخ المحدد</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>جميع المبالغ بالريال السعودي</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>الأصول = الخصوم + حقوق الملكية</li>
                                        @if(($summary['total_assets'] ?? 0) != ($summary['total_liabilities_equity'] ?? 0))
                                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>يوجد عدم توازن في الميزانية</li>
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

.table-info {
    background-color: #d1ecf1;
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
