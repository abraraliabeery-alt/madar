@extends('facility.layouts.app')

@section('title', 'لوحة المجموعة')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h1 class="h4 fw-bold mb-0">لوحة المجموعة</h1>
        <span class="badge bg-primary">{{ $totals['facilities'] }} منشأة</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">المنتجات</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($totals['products']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">العقود</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($totals['contracts']) }}</div>
                    <div class="text-success small">{{ number_format($totals['active_contracts']) }} نشط</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">طلبات التنفيذ</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($totals['execution_requests']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">صيانة مفتوحة</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($totals['open_maintenance']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">إجمالي الفواتير</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($totals['invoices_total'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">المحصّل</div>
                    <div class="h4 fw-bold mb-0 text-success">{{ number_format($totals['invoices_paid'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">أداء كل منشأة</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>المنشأة</th>
                            <th>القطاع</th>
                            <th class="text-center">المنتجات</th>
                            <th class="text-center">العقود</th>
                            <th class="text-center">طلبات التنفيذ</th>
                            <th class="text-center">صيانة مفتوحة</th>
                            <th class="text-center">الفواتير</th>
                            <th class="text-center">المحصّل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['facility']->name }}</td>
                                <td><span class="badge bg-secondary">{{ $row['facility']->facilityCategory?->name ?? '-' }}</span></td>
                                <td class="text-center">{{ number_format($row['products']) }}</td>
                                <td class="text-center">{{ number_format($row['contracts']) }} <span class="text-success small">({{ $row['active_contracts'] }})</span></td>
                                <td class="text-center">{{ number_format($row['execution_requests']) }}</td>
                                <td class="text-center">{{ number_format($row['open_maintenance']) }}</td>
                                <td class="text-center">{{ number_format($row['invoices_total'], 2) }}</td>
                                <td class="text-center text-success">{{ number_format($row['invoices_paid'], 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">لا توجد منشآت</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
