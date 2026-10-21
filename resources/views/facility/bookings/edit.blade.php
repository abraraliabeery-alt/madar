@extends('facility.layouts.app')

@section('title', 'تعديل المعاينة #' . $booking->id)

@section('content')
<div class="container-fluid" style="max-width: 720px;">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">تعديل المعاينة #{{ $booking->id }}</h1>
        <a href="{{ route('facility.bookings.index') }}" class="text-muted small">← العودة للمعاينات</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.bookings.update', $booking) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">العميل (حساب مستخدم)</label>
                        <select name="user_id" class="form-select">
                            <option value="">— بدون حساب —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id', $booking->user_id) == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">المنتج</label>
                        <select name="product_id" class="form-select" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id', $booking->product_id) == $product->id)>{{ $product->getTranslatedTitle() ?: '#'.$product->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->name }}" @selected(old('status', $booking->status) === $status->name)>{{ $status->display_name ?? $status->name }}</option>
                            @endforeach
                            @if($statuses->isEmpty())
                                <option value="pending" @selected(old('status', $booking->status) === 'pending')>قيد الانتظار</option>
                                <option value="confirmed" @selected(old('status', $booking->status) === 'confirmed')>مؤكد</option>
                                <option value="cancelled" @selected(old('status', $booking->status) === 'cancelled')>ملغي</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">المبلغ الإجمالي</label>
                        <input type="number" name="total_amount" class="form-control" min="0" step="0.01" value="{{ old('total_amount', $booking->total_amount) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">اسم الزائر (بدون حساب)</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $booking->name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">جوال الزائر</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $booking->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تاريخ المعاينة</label>
                        <input type="date" name="preferred_date" class="form-control" value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">وقت المعاينة</label>
                        <input type="time" name="preferred_time" class="form-control" value="{{ old('preferred_time', $booking->preferred_time) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">طريقة الدفع</label>
                        <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method', $booking->payment_method) }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-4">
                        <div class="form-check">
                            <input type="hidden" name="is_confirmed" value="0">
                            <input class="form-check-input" type="checkbox" name="is_confirmed" value="1" id="isConfirmed" @checked(old('is_confirmed', $booking->is_confirmed))>
                            <label class="form-check-label" for="isConfirmed">مؤكد</label>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="is_paid" value="0">
                            <input class="form-check-input" type="checkbox" name="is_paid" value="1" id="isPaid" @checked(old('is_paid', $booking->is_paid))>
                            <label class="form-check-label" for="isPaid">مدفوع</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="message" class="form-control" rows="2">{{ old('message', $booking->message) }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
