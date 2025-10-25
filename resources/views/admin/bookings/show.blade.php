@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل الحجز #{{ $booking->booking_number }}</h5>
            <div>
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Booking Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                @if($booking->status)
                                    <span class="badge bg-{{ $booking->status->color }} fs-5 px-4 py-2">
                                        {{ $booking->status->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-5 px-4 py-2">
                                        لا توجد حالة
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                @if($booking->is_confirmed)
                                    <span class="badge bg-success fs-6">مؤكد</span>
                                @else
                                    <span class="badge bg-warning fs-6">قيد الانتظار</span>
                                @endif

                                @if($booking->is_paid)
                                    <span class="badge bg-success fs-6">مدفوع</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير مدفوع</span>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                @if(!$booking->is_confirmed)
                                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check-circle me-2"></i>تأكيد الحجز
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.bookings.unconfirm', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-times-circle me-2"></i>إلغاء تأكيد الحجز
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.bookings.update-payment-status', $booking) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $booking->is_paid ? 'btn-danger' : 'btn-success' }}">
                                        <i class="fas {{ $booking->is_paid ? 'fa-times me-2' : 'fa-check me-2' }}"></i>
                                        {{ $booking->is_paid ? 'إلغاء تأكيد الدفع' : 'تأكيد الدفع' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger delete-confirm">
                                        <i class="fas fa-trash me-2"></i>حذف الحجز
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">تفاصيل الحجز</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">تاريخ الحجز</label>
                                    <p class="fs-5">{{ $booking->booking_date }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">وقت الحجز</label>
                                    <p class="fs-5">{{ $booking->booking_time }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">المدة</label>
                                    <p class="fs-5">{{ $booking->duration }} ساعة</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">المبلغ الإجمالي</label>
                                    <p class="fs-5">{{ number_format($booking->total_amount, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">ملاحظات</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! $booking->notes !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المستخدم</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->user->avatar)
                                    <img src="{{ asset($booking->user->avatar) }}" alt="avatar" class="rounded-circle me-3" width="60">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        {{ substr($booking->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $booking->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $booking->user->email }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label text-muted">رقم الهاتف</label>
                                    <p>{{ $booking->user->phone_number }}</p>
                                </div>
                                <div class="col-12">
                                    <a href="{{ route('admin.users.show', $booking->user) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user me-2"></i>عرض الملف الشخصي
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المنتج</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->product->main_image)
                                    <img src="{{ asset($booking->product->main_image) }}" alt="product" class="rounded me-3" width="80">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $booking->product->name }}</h5>
                                    <p class="text-muted mb-0">{{ number_format($booking->product->price, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label text-muted">المنشأة</label>
                                    <p>{{ $booking->facility->name }}</p>
                                </div>
                                <div class="col-12">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.products.show', $booking->product) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-box me-2"></i>عرض المنتج
                                        </a>
                                        <a href="{{ route('admin.facilities.show', $booking->facility) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-building me-2"></i>عرض المنشأة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">سجل الحجز</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">تم إنشاء الحجز</h6>
                                        <small class="text-muted">{{ $booking->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                                @if($booking->is_confirmed)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">تم تأكيد الحجز</h6>
                                        <small class="text-muted">{{ $booking->updated_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($booking->is_paid)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">تم الدفع</h6>
                                        <small class="text-muted">{{ $booking->updated_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
}
.timeline-content {
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
}
</style>
@endsection
