@extends('facility.layouts.app')

@section('title', 'إحصائيات المعاينات')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">إحصائيات المعاينات</h1>
        <a href="{{ route('facility.bookings.index') }}" class="text-muted small">← العودة للمعاينات</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-primary"><div class="card-body">
                <div class="text-muted small">إجمالي المعاينات</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total_bookings'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-success"><div class="card-body">
                <div class="text-muted small">مؤكدة</div>
                <div class="h4 fw-bold mb-0">{{ $stats['confirmed_bookings'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-info"><div class="card-body">
                <div class="text-muted small">مدفوعة</div>
                <div class="h4 fw-bold mb-0">{{ $stats['paid_bookings'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100 border-start border-4 border-warning"><div class="card-body">
                <div class="text-muted small">إيراد الشهر</div>
                <div class="h4 fw-bold mb-0">{{ number_format((float) $stats['monthly_revenue'], 2) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">أحدث المعاينات</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_bookings'] as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name ?? '—' }}</td>
                            <td>{{ $booking->product?->getTranslatedTitle() ?: '—' }}</td>
                            <td>{{ number_format((float) $booking->total_amount, 2) }}</td>
                            <td>{{ $booking->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد حجوزات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
