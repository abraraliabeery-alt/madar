@extends('facility.layouts.app')

@section('title', __('facility.sidebar.reports_bookings'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.sidebar.reports_bookings') }}</h1>
        <a href="{{ route('facility.reports') }}" class="text-muted small">← العودة للتقارير</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">إجمالي الحجوزات</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">قيد الانتظار</div>
                <div class="h4 fw-bold mb-0">{{ $stats['pending'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">مؤكدة</div>
                <div class="h4 fw-bold mb-0">{{ $stats['confirmed'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">إجمالي الإيراد</div>
                <div class="h4 fw-bold mb-0">{{ number_format($stats['revenue'], 2) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>الحالة</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->product?->getTranslatedTitle() ?: '—' }}</td>
                            <td><span class="badge bg-secondary">{{ $booking->status ?? '—' }}</span></td>
                            <td>{{ number_format((float) $booking->total_amount, 2) }}</td>
                            <td>{{ $booking->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد حجوزات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="card-footer">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
@endsection
