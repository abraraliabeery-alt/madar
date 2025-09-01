@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل المميزة - {{ $feature->getTranslatedName('ar') }}</h5>
            <div>
                <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Feature Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($feature->icon)
                                <img src="{{ asset($feature->icon) }}" alt="icon" class="img-fluid rounded mb-3" style="max-height: 100px;">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center mb-3" style="height: 100px;">
                                    <i class="fas fa-star text-muted fa-3x"></i>
                                </div>
                            @endif

                            <div class="mb-3">
                                @if($feature->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.features.toggle-status', $feature) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $feature->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                        <i class="fas {{ $feature->is_active ? 'fa-ban me-2' : 'fa-check me-2' }}"></i>
                                        {{ $feature->is_active ? 'إلغاء تفعيل المميزة' : 'تفعيل المميزة' }}
                                    </button>
                                </form>

                                @if($feature->products->isEmpty())
                                    <form action="{{ route('admin.features.destroy', $feature) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 delete-confirm">
                                            <i class="fas fa-trash me-2"></i>حذف المميزة
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المميزة</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الاسم</label>
                                    <p class="fs-5">{{ $feature->getTranslatedName('ar') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الترتيب</label>
                                    <p class="fs-5">
                                        <span class="badge bg-secondary">{{ $feature->order ?? 0 }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">اللون</label>
                                    <p class="fs-5">
                                        @if($feature->color)
                                            <span class="badge" style="background-color: {{ $feature->color }}">{{ $feature->color }}</span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">عدد المنتجات</label>
                                    <p class="fs-5">
                                        <span class="badge bg-info">{{ $feature->products->count() }}</span>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">الوصف</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! $feature->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Associated Products -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">المنتجات المرتبطة</h6>
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
                                        @forelse($feature->products as $product)
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
                                                <p class="mb-0">لا توجد منتجات مرتبطة بهذه المميزة</p>
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
            confirmButtonText: 'نعم، احذف المميزة',
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
