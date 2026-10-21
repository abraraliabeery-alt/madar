@extends('facility.layouts.app')

@section('title', 'تقويم المعاينات')

@push('styles')
    <style>
        .fc .confirmed-booking { background-color: var(--brand-brown); border-color: var(--brand-brown); }
        .fc .pending-booking { background-color: #d4a017; border-color: #d4a017; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">تقويم المعاينات</h1>
        <a href="{{ route('facility.bookings.index') }}" class="text-muted small">← العودة للمعاينات</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="bookingsCalendar"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendar = new FullCalendar.Calendar(document.getElementById('bookingsCalendar'), {
                initialView: 'dayGridMonth',
                locale: '{{ app()->getLocale() }}',
                direction: '{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}',
                headerToolbar: { start: 'prev,next today', center: 'title', end: 'dayGridMonth,listWeek' },
                events: @json($bookings),
            });
            calendar.render();
        });
    </script>
@endpush
