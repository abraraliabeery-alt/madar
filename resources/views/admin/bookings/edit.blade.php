@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تعديل الحجز #{{ $booking->booking_number }}</h5>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات الحجز الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">اختر المستخدم</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $booking->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->phone_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="product_id" class="form-label">المنتج <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-facility="{{ $product->facility_id }}" data-price="{{ $product->price }}" {{ old('product_id', $booking->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - {{ number_format($product->price, 2) }} ريال
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="facility_id" id="facility_id" value="{{ old('facility_id', $booking->facility_id) }}">

                                <div class="mb-3">
                                    <label for="status_id" class="form-label">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                                        <option value="">اختر الحالة</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id', $booking->status_id) == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">تفاصيل الحجز</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="booking_date" class="form-label">تاريخ الحجز <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date', $booking->booking_date) }}" required>
                                    @error('booking_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="booking_time" class="form-label">وقت الحجز <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" value="{{ old('booking_time', $booking->booking_time) }}" required>
                                    @error('booking_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="duration" class="form-label">المدة (بالساعات) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $booking->duration) }}" required min="1" step="1">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="total_amount" class="form-label">المبلغ الإجمالي <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount', $booking->total_amount) }}" required min="0" step="0.01">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات إضافية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea class="form-control summernote @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $booking->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_confirmed" name="is_confirmed" value="1" {{ old('is_confirmed', $booking->is_confirmed) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_confirmed">تأكيد الحجز</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_paid" name="is_paid" value="1" {{ old('is_paid', $booking->is_paid) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_paid">تم الدفع</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Initialize summernote
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol']],
        ]
    });

    // Handle product selection
    $('#product_id').change(function() {
        let selectedOption = $(this).find('option:selected');
        let facilityId = selectedOption.data('facility');
        let price = selectedOption.data('price');

        $('#facility_id').val(facilityId);
        calculateTotal();
    });

    // Handle duration change
    $('#duration').change(function() {
        calculateTotal();
    });

    // Calculate total amount
    function calculateTotal() {
        let price = parseFloat($('#product_id option:selected').data('price') || 0);
        let duration = parseInt($('#duration').val() || 1);
        let total = price * duration;
        $('#total_amount').val(total.toFixed(2));
    }

    // Validate booking date
    $('#booking_date').change(function() {
        let selectedDate = new Date($(this).val());
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            alert('لا يمكن اختيار تاريخ سابق');
            $(this).val('');
        }
    });
});
</script>
@endpush
