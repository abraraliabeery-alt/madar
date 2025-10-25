@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل المنتج - {{ $product->name }}</h5>
            <div>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Product Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="product" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                                    <i class="fas fa-box text-muted fa-3x"></i>
                                </div>
                            @endif

                            <div class="mb-3">
                                <h4 class="mb-2">{{ number_format($product->price, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</h4>
                                @if($product->status)
    <span class="badge bg-{{ $product->status->color }} fs-6">{{ $product->status->name }}</span>
@else
    <span class="badge bg-secondary fs-6">لا توجد حالة</span>
@endif
                            </div>

                            <div class="mb-3">
                                @if($product->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif

                                @if($product->is_verified)
                                    <span class="badge bg-info fs-6">تم التحقق</span>
                                @else
                                    <span class="badge bg-warning fs-6">قيد التحقق</span>
                                @endif

                                @if($product->is_featured)
                                    <span class="badge bg-warning fs-6">مميز</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $product->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                        <i class="fas {{ $product->is_active ? 'fa-ban me-2' : 'fa-check me-2' }}"></i>
                                        {{ $product->is_active ? 'إلغاء تفعيل المنتج' : 'تفعيل المنتج' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.products.toggle-verification', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $product->is_verified ? 'btn-warning' : 'btn-info' }} w-100">
                                        <i class="fas {{ $product->is_verified ? 'fa-times me-2' : 'fa-shield-alt me-2' }}"></i>
                                        {{ $product->is_verified ? 'إلغاء التحقق من المنتج' : 'التحقق من المنتج' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $product->is_featured ? 'btn-secondary' : 'btn-warning' }} w-100">
                                        <i class="fas fa-star me-2"></i>
                                        {{ $product->is_featured ? 'إزالة من المميزة' : 'إضافة للمميزة' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 delete-confirm">
                                        <i class="fas fa-trash me-2"></i>حذف المنتج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المنتج</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">المنشأة</label>
                                    <p class="fs-5">
                                        <a href="{{ route('admin.facilities.show', $product->facility) }}">
                                            {{ $product->facility->name }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الفئة</label>
                                    <p class="fs-5">
                                        <span class="badge bg-info">{{ $product->category->name }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">المالك</label>
                                    <p class="fs-5">
                                        <a href="{{ route('admin.users.show', $product->owner) }}">
                                            {{ $product->owner->name }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">الوصف</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">تفاصيل العقار</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">عدد مواقف السيارات</label>
                                    <p class="fs-5">{{ $product->parking_spaces ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">مفروش</label>
                                    <p class="fs-5">
                                        @if($product->furnished)
                                            <span class="badge bg-success">نعم</span>
                                        @else
                                            <span class="badge bg-secondary">لا</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">متاح للإيجار</label>
                                    <p class="fs-5">
                                        @if($product->available_for_rent)
                                            <span class="badge bg-success">نعم</span>
                                        @else
                                            <span class="badge bg-secondary">لا</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">متاح للبيع</label>
                                    <p class="fs-5">
                                        @if($product->available_for_sale)
                                            <span class="badge bg-success">نعم</span>
                                        @else
                                            <span class="badge bg-secondary">لا</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attributes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الخصائص</h6>
                        </div>
                        <div class="card-body">
                            @if($product->attributes->count() > 0)
                                <div class="row">
                                    @foreach($product->attributes as $attribute)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted">{{ $attribute->name }}</label>
                                            <p class="fs-5">{{ $attribute->pivot->value }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">لا توجد خصائص محددة</p>
                            @endif
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
                            <div class="mb-3">
                                <label class="form-label text-muted">العنوان</label>
                                <p class="fs-5">{{ $product->address }}</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">خط العرض</label>
                                    <p class="fs-5">{{ $product->latitude ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">خط الطول</label>
                                    <p class="fs-5">{{ $product->longitude ?? '-' }}</p>
                                </div>
                            </div>
                            @if($product->google_maps_url)
                                <a href="{{ $product->google_maps_url }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-map-marker-alt me-2"></i>عرض على خرائط جوجل
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">المميزات</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @forelse($product->features as $feature)
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center p-2 border rounded">
                                            @if($feature->icon)
                                                @if(Str::startsWith($feature->icon, 'fas ') || Str::startsWith($feature->icon, 'fa ') || Str::startsWith($feature->icon, 'fab '))
                                                    <!-- FontAwesome Icon -->
                                                    <i class="{{ $feature->icon }} text-primary me-2"></i>
                                                @else
                                                    <!-- Image Icon -->
                                                    <img src="{{ Storage::url($feature->icon) }}" alt="icon" width="20" class="me-2">
                                                @endif
                                            @endif
                                            {{ $feature->getTranslatedName('ar') }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted mb-0">لا توجد مميزات</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attributes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الخصائص</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @forelse($product->attributes as $attribute)
                                    <div class="col-md-6">
                                        <div class="p-2 border rounded">
                                            <small class="text-muted d-block">{{ $attribute->name }}</small>
                                            <strong>{{ $attribute->value }}</strong>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted mb-0">لا توجد خصائص</p>
                                    </div>
                                @endforelse
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
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($product->bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->booking_number }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $booking->user) }}">
                                                    {{ $booking->user->name }}
                                                </a>
                                            </td>
                                            <td>{{ $booking->booking_date }}</td>
                                            <td>{{ number_format($booking->total_amount, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</td>
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
                                                <p class="mb-0">لا توجد حجوزات لهذا المنتج</p>
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
            confirmButtonText: 'نعم، احذف المنتج',
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
