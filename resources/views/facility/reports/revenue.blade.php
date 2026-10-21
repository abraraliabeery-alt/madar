@extends('facility.layouts.app')

@section('title', __('facility.sidebar.reports_revenue'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.sidebar.reports_revenue') }}</h1>
        <a href="{{ route('facility.reports') }}" class="text-muted small">← العودة للتقارير</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">إيرادات الحجوزات</div>
                <div class="h4 fw-bold mb-0">{{ number_format($total, 2) }}</div>
            </div></div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">إجمالي الفواتير</div>
                <div class="h4 fw-bold mb-0">{{ number_format($invoicesTotal, 2) }}</div>
            </div></div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">المدفوعات المحصلة</div>
                <div class="h4 fw-bold mb-0">{{ number_format($paymentsTotal, 2) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">الإيرادات الشهرية (آخر 12 شهراً)</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>الشهر</th>
                        <th>عدد الحجوزات</th>
                        <th>الإيراد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly as $row)
                        <tr>
                            <td>{{ $row->y }}-{{ str_pad($row->m, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $row->cnt }}</td>
                            <td>{{ number_format((float) $row->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
