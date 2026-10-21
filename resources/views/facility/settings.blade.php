@extends('facility.layouts.app')

@section('title', __('facility.settings.title'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.settings.title') }}</h1>
        <p class="text-muted small mb-0">{{ $facility->name }}</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.settings.update') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">بريد الإشعارات</label>
                        <input type="email" name="notification_email" class="form-control" value="{{ old('notification_email', $facility->notification_email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">أيام المعاينة المسبقة</label>
                        <input type="number" name="booking_advance_days" min="1" class="form-control" value="{{ old('booking_advance_days', $facility->booking_advance_days) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">أقصى مدة للمعاينة (أيام)</label>
                        <input type="number" name="max_booking_duration" min="1" class="form-control" value="{{ old('max_booking_duration', $facility->max_booking_duration) }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="hidden" name="auto_approve_bookings" value="0">
                            <input class="form-check-input" type="checkbox" name="auto_approve_bookings" value="1" id="autoApprove" {{ old('auto_approve_bookings', $facility->auto_approve_bookings) ? 'checked' : '' }}>
                            <label class="form-check-label" for="autoApprove">اعتماد الحجوزات تلقائياً</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
