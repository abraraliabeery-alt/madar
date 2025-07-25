@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل الفئة - {{ $category->name }}</h5>
            <div>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Category Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" alt="category" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            @endif
                            <div class="mb-3">
                                @if($category->icon)
                                    <img src="{{ Storage::url($category->icon) }}" alt="icon" width="32" class="me-2">
                                @endif
                                <h4 class="d-inline-block mb-0">{{ $category->name }}</h4>
                            </div>
                            <div class="mb-3">
                                @if($category->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif

                                @if($category->is_featured)
                                    <span class="badge bg-warning">مميزة</span>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $category->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                        <i class="fas {{ $category->is_active ? 'fa-ban me-2' : 'fa-check me-2' }}"></i>
                                        {{ $category->is_active ? 'إلغاء تفعيل الفئة' : 'تفعيل الفئة' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.categories.toggle-featured', $category) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $category->is_featured ? 'btn-secondary' : 'btn-warning' }} w-100">
                                        <i class="fas fa-star me-2"></i>
                                        {{ $category->is_featured ? 'إزالة من المميزة' : 'إضافة للمميزة' }}
                                    </button>
                                </form>

                                @if($category->products_count == 0 && $category->children_count == 0)
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 delete-confirm">
                                            <i class="fas fa-trash me-2"></i>حذف الفئة
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات الفئة</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الفئة الأب</label>
                                    <p>
                                        @if($category->parent)
                                            <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-decoration-none">
                                                {{ $category->parent->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">فئة رئيسية</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">ترتيب الفئة</label>
                                    <p>{{ $category->sort_order ?? '-' }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">الوصف</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! $category->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub Categories -->
                @if($category->children->count() > 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الفئات الفرعية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($category->children as $child)
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                @if($child->icon)
                                                    <img src="{{ Storage::url($child->icon) }}" alt="icon" width="32" class="me-2">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $child->name }}</h6>
                                                    <small class="text-muted">{{ $child->products_count }} منتج</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('admin.categories.show', $child) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Products -->
                @if($category->products->count() > 0)
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
                                        @foreach($category->products as $product)
                                        <tr>
                                            <td>
                                                @if($product->main_image)
                                                    <img src="{{ Storage::url($product->main_image) }}" alt="product" width="50" class="rounded">
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
