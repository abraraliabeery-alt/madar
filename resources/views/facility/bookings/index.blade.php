@extends('facility.layouts.app')

@section('title', __('facility.sidebar.bookings'))

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h1 class="h4 fw-bold mb-0">{{ __('facility.sidebar.bookings') }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('facility.bookings.calendar') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar-days me-1"></i> {{ __('facility.sidebar.bookings_calendar') }}
            </a>
            <a href="{{ route('facility.bookings.statistics') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-chart-line me-1"></i> {{ __('facility.sidebar.bookings_statistics') }}
            </a>
            <a href="{{ route('facility.bookings.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> معاينة جديدة
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>الحالة</th>
                        <th>المبلغ</th>
                        <th>موعد المعاينة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                {{ $booking->user->name ?? $booking->name ?? '—' }}
                                @if(!$booking->user_id && $booking->phone)
                                    <div class="small text-muted">{{ $booking->phone }}</div>
                                @endif
                            </td>
                            <td>{{ $booking->product?->getTranslatedTitle() ?: '—' }}</td>
                            <td><span class="badge bg-secondary">{{ $booking->status ?? '—' }}</span></td>
                            <td>{{ number_format((float) $booking->total_amount, 2) }}</td>
                            <td>
                                @if($booking->preferred_date)
                                    {{ $booking->preferred_date->format('Y-m-d') }}
                                    @if($booking->preferred_time)<span class="text-muted small"> {{ $booking->preferred_time }}</span>@endif
                                @else
                                    <span class="text-muted">{{ $booking->created_at?->format('Y-m-d') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('facility.bookings.show', $booking) }}" class="btn btn-sm btn-outline-secondary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('facility.bookings.edit', $booking) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">لا توجد حجوزات</td></tr>
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
