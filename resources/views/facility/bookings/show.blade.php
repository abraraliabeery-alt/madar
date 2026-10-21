@extends('facility.layouts.app')

@section('title', 'المعاينة #' . $booking->id)

@section('content')
<div class="container-fluid" style="max-width: 720px;">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="h4 fw-bold mb-1">المعاينة #{{ $booking->id }}</h1>
            <a href="{{ route('facility.bookings.index') }}" class="text-muted small">← العودة للمعاينات</a>
        </div>
        <a href="{{ route('facility.bookings.edit', $booking) }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-edit me-1"></i> تعديل
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted">العميل</dt>
                <dd class="col-sm-8">{{ $booking->user->name ?? '—' }}</dd>

                <dt class="col-sm-4 text-muted">المنتج</dt>
                <dd class="col-sm-8">{{ $booking->product?->getTranslatedTitle() ?: '—' }}</dd>

                <dt class="col-sm-4 text-muted">الحالة</dt>
                <dd class="col-sm-8"><span class="badge bg-secondary">{{ $booking->status ?? '—' }}</span></dd>

                <dt class="col-sm-4 text-muted">المبلغ الإجمالي</dt>
                <dd class="col-sm-8 fw-bold">{{ number_format((float) $booking->total_amount, 2) }}</dd>

                <dt class="col-sm-4 text-muted">طريقة الدفع</dt>
                <dd class="col-sm-8">{{ $booking->payment_method ?: '—' }}</dd>

                <dt class="col-sm-4 text-muted">مؤكد</dt>
                <dd class="col-sm-8">
                    @if($booking->is_confirmed)
                        <span class="badge bg-success">نعم</span>
                    @else
                        <span class="badge bg-warning text-dark">لا</span>
                    @endif
                </dd>

                <dt class="col-sm-4 text-muted">مدفوع</dt>
                <dd class="col-sm-8">
                    @if($booking->is_paid)
                        <span class="badge bg-success">نعم</span>
                    @else
                        <span class="badge bg-warning text-dark">لا</span>
                    @endif
                </dd>

                <dt class="col-sm-4 text-muted">تاريخ الإنشاء</dt>
                <dd class="col-sm-8">{{ $booking->created_at?->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
