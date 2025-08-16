@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل المنشأة - {{ $facility->name }}</h5>
            <div>
                <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Facility Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($facility->logo)
                                <img src="{{ asset($facility->logo) }}" alt="logo" class="img-fluid rounded mb-3" style="max-height: 100px;">
                            @endif
                            <div class="mb-3">
                                @if($facility->status)
                                    <span class="badge bg-{{ $facility->status->color }} fs-5 px-4 py-2">{{ $facility->status->name }}</span>
                                @else
                                    <span class="badge bg-secondary fs-5 px-4 py-2">لا توجد حالة</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                @if($facility->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif

                                @if($facility->is_verified)
                                    <span class="badge bg-info fs-6">تم التحقق</span>
                                @else
                                    <span class="badge bg-warning fs-6">قيد التحقق</span>
                                @endif

                                @if($facility->is_featured)
                                    <span class="badge bg-warning fs-6">مميزة</span>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.facilities.toggle-status', $facility) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $facility->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                        <i class="fas {{ $facility->is_active ? 'fa-ban me-2' : 'fa-check me-2' }}"></i>
                                        {{ $facility->is_active ? 'إلغاء تفعيل المنشأة' : 'تفعيل المنشأة' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.facilities.toggle-verification', $facility) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $facility->is_verified ? 'btn-warning' : 'btn-info' }} w-100">
                                        <i class="fas {{ $facility->is_verified ? 'fa-times me-2' : 'fa-shield-alt me-2' }}"></i>
                                        {{ $facility->is_verified ? 'إلغاء التحقق من المنشأة' : 'التحقق من المنشأة' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.facilities.toggle-featured', $facility) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $facility->is_featured ? 'btn-secondary' : 'btn-warning' }} w-100">
                                        <i class="fas fa-star me-2"></i>
                                        {{ $facility->is_featured ? 'إزالة من المميزة' : 'إضافة للمميزة' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 delete-confirm">
                                        <i class="fas fa-trash me-2"></i>حذف المنشأة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facility Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المنشأة</h6>
                        </div>
                        <div class="card-body">
                            @if($facility->cover_image)
                                <img src="{{ asset($facility->cover_image) }}" alt="cover" class="img-fluid rounded mb-3" style="max-height: 200px; width: 100%; object-fit: cover;">
                            @endif
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الفئة</label>
                                    <p class="fs-5">
                                        <span class="badge bg-info">{{ $facility->category->name }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">المالك</label>
                                    <p class="fs-5">
                                        <a href="{{ route('admin.users.show', $facility->owner) }}">
                                            {{ $facility->owner->name }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">الوصف</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! $facility->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات الاتصال</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">البريد الإلكتروني</label>
                                    <p><a href="mailto:{{ $facility->email }}">{{ $facility->email }}</a></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">رقم الهاتف</label>
                                    <p><a href="tel:{{ $facility->phone_number }}">{{ $facility->phone_number }}</a></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الموقع الإلكتروني</label>
                                    <p>
                                        @if($facility->website)
                                            <a href="{{ $facility->website }}" target="_blank">{{ $facility->website }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">رقم الواتساب</label>
                                    <p>
                                        @if($facility->whatsapp_number)
                                            <a href="https://wa.me/{{ $facility->whatsapp_number }}" target="_blank">{{ $facility->whatsapp_number }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">العنوان</label>
                                    <p>{{ $facility->address }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">ساعات العمل</label>
                                    <p>{{ $facility->working_hours ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">وسائل التواصل الاجتماعي</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @if($facility->facebook)
                                    <a href="{{ $facility->facebook }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-facebook me-2"></i>فيسبوك
                                    </a>
                                @endif
                                @if($facility->twitter)
                                    <a href="{{ $facility->twitter }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-twitter me-2"></i>تويتر
                                    </a>
                                @endif
                                @if($facility->instagram)
                                    <a href="{{ $facility->instagram }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-instagram me-2"></i>انستغرام
                                    </a>
                                @endif
                                @if($facility->linkedin)
                                    <a href="{{ $facility->linkedin }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-linkedin me-2"></i>لينكد إن
                                    </a>
                                @endif
                                @if($facility->youtube)
                                    <a href="{{ $facility->youtube }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-youtube me-2"></i>يوتيوب
                                    </a>
                                @endif
                                @if($facility->snapchat)
                                    <a href="https://www.snapchat.com/add/{{ $facility->snapchat }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-snapchat me-2"></i>سناب شات
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الموقع</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">خط العرض</label>
                                    <p>{{ $facility->latitude ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">خط الطول</label>
                                    <p>{{ $facility->longitude ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    @if($facility->google_maps_url)
                                        <a href="{{ $facility->google_maps_url }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-map-marker-alt me-2"></i>عرض على خرائط جوجل
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">المنتجات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>الصورة</th>
                                            <th>الاسم</th>
                                            <th>السعر</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($facility->products as $product)
                                        <tr>
                                            <td>
                                                @if($product->main_image)
                                                    <img src="{{ asset($product->main_image) }}" alt="product" width="50" class="rounded">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ number_format($product->price, 2) }} ريال</td>
                                            <td>
                                                @if($product->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الحجوزات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>رقم الحجز</th>
                                            <th>المستخدم</th>
                                            <th>المنتج</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($facility->bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->booking_number }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $booking->user) }}">
                                                    {{ $booking->user->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $booking->product) }}">
                                                    {{ $booking->product->name }}
                                                </a>
                                            </td>
                                            <td>{{ $booking->booking_date }}</td>
                                            <td>{{ number_format($booking->total_amount, 2) }} ريال</td>
                                            <td>
                                                @if($booking->status)
                                                    <span class="badge bg-{{ $booking->status->color }}">
                                                        {{ $booking->status->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">لا توجد حالة</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
