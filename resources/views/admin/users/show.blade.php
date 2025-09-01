@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل المستخدم - {{ $user->name }}</h5>
            <div>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- User Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="avatar" class="img-fluid rounded-circle mb-3" style="max-height: 200px;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 200px; height: 200px;">
                                    <span class="display-4">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>

                            <div class="mb-3">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary fs-6">{{ $role->getTranslatedDisplayName() }}</span>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                @if($user->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                        <i class="fas {{ $user->is_active ? 'fa-ban me-2' : 'fa-check me-2' }}"></i>
                                        {{ $user->is_active ? 'إلغاء تفعيل المستخدم' : 'تفعيل المستخدم' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 delete-confirm">
                                        <i class="fas fa-trash me-2"></i>حذف المستخدم
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات الاتصال</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">رقم الهاتف</label>
                                    <p class="fs-5">
                                        <a href="tel:{{ $user->phone_number }}">{{ $user->phone_number }}</a>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">رقم الواتساب</label>
                                    <p class="fs-5">
                                        @if($user->whatsapp_number)
                                            <a href="https://wa.me/{{ $user->whatsapp_number }}" target="_blank">
                                                {{ $user->whatsapp_number }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">تيليجرام</label>
                                    <p class="fs-5">
                                        @if($user->telegram)
                                            <a href="https://t.me/{{ $user->telegram }}" target="_blank">
                                                {{ $user->telegram }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">المعلومات البنكية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">البنك</label>
                                    <p class="fs-5">{{ $user->bank->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">رقم الحساب</label>
                                    <p class="fs-5">{{ $user->bank_account ?? '-' }}</p>
                                </div>
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
                                    <p class="fs-5">{{ $user->latitude ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">خط الطول</label>
                                    <p class="fs-5">{{ $user->longitude ?? '-' }}</p>
                                </div>
                            </div>
                            @if($user->google_maps_url)
                                <a href="{{ $user->google_maps_url }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-map-marker-alt me-2"></i>عرض على خرائط جوجل
                                </a>
                            @endif
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
                                @if($user->facebook)
                                    <a href="{{ $user->facebook }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-facebook me-2"></i>فيسبوك
                                    </a>
                                @endif
                                @if($user->twitter)
                                    <a href="{{ $user->twitter }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-twitter me-2"></i>تويتر
                                    </a>
                                @endif
                                @if($user->instagram)
                                    <a href="{{ $user->instagram }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-instagram me-2"></i>انستغرام
                                    </a>
                                @endif
                                @if($user->linkedin)
                                    <a href="{{ $user->linkedin }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-linkedin me-2"></i>لينكد إن
                                    </a>
                                @endif
                                @if($user->youtube)
                                    <a href="{{ $user->youtube }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-youtube me-2"></i>يوتيوب
                                    </a>
                                @endif
                                @if($user->snapchat)
                                    <a href="https://www.snapchat.com/add/{{ $user->snapchat }}" target="_blank" class="list-group-item list-group-item-action">
                                        <i class="fab fa-snapchat me-2"></i>سناب شات
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">المنشآت</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @forelse($user->facilities as $facility)
                                    <a href="{{ route('admin.facilities.show', $facility) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            @if($facility->logo)
                                                <img src="{{ asset($facility->logo) }}" alt="logo" width="40" class="rounded me-3">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-building text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $facility->name }}</h6>
                                                <small class="text-muted">{{ $facility->products_count }} منتج</small>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <p class="mb-0">لا توجد منشآت مرتبطة</p>
                                    </div>
                                @endforelse
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
                                            <th>المنشأة</th>
                                            <th>السعر</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->products as $product)
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
                                            <td>
                                                <a href="{{ route('admin.facilities.show', $product->facility) }}">
                                                    {{ $product->facility->name }}
                                                </a>
                                            </td>
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
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="mb-0">لا توجد منتجات</p>
                                            </td>
                                        </tr>
                                        @endforelse
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
                                            <th>المنتج</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->booking_number }}</td>
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
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                <p class="mb-0">لا توجد حجوزات</p>
                                            </td>
                                        </tr>
                                        @endforelse
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

@push('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكن التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف المستخدم',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
