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
                                <img src="{{ asset($category->image) }}" alt="category" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            @endif
                            <div class="mb-3">
                                @if($category->icon)
                                    @if(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'fa ') || Str::startsWith($category->icon, 'fab '))
                                        <!-- FontAwesome Icon -->
                                        <i class="{{ $category->icon }} fa-2x text-primary me-2"></i>
                                    @else
                                        <!-- Image Icon -->
                                        <img src="{{ asset($category->icon) }}" alt="icon" width="32" class="me-2">
                                    @endif
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
                                    <p>{{ $category->order ?? '-' }}</p>
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

                <!-- Translations -->
                @if($category->translations->count() > 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-language text-info me-2"></i>
                                الترجمات المتاحة ({{ $category->translations->count() }})
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($category->translations as $translation)
                                <div class="col-md-6">
                                    <div class="card h-100 translation-card">
                                        <div class="card-header d-flex align-items-center">
                                            <span class="me-2">{{ config('locales.flags.' . $translation->locale, '🌐') }}</span>
                                            <strong>{{ config('locales.names.' . $translation->locale, $translation->locale) }}</strong>
                                            @if($translation->locale === config('app.locale'))
                                                <span class="badge bg-primary ms-auto">افتراضي</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h6 class="mb-2">{{ $translation->name }}</h6>
                                            @if($translation->description)
                                                <p class="text-muted small mb-0">{{ Str::limit($translation->description, 100) }}</p>
                                            @else
                                                <p class="text-muted small mb-0">لا يوجد وصف</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Sub Categories -->
                @if($category->children->count() > 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-sitemap text-info me-2"></i>
                                الفئات الفرعية ({{ $category->children->count() }})
                            </h6>
                            <span class="badge bg-info">{{ $category->children->sum('products_count') }} منتج إجمالي</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($category->children as $child)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 subcategory-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($child->icon)
                                                    @if(Str::startsWith($child->icon, 'fas ') || Str::startsWith($child->icon, 'fa ') || Str::startsWith($child->icon, 'fab '))
                                                        <i class="{{ $child->icon }} fa-2x text-info me-3"></i>
                                                    @else
                                                        <img src="{{ asset($child->icon) }}" alt="icon" width="40" class="me-3">
                                                    @endif
                                                @else
                                                    <i class="fas fa-folder fa-2x text-muted me-3"></i>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $child->name }}</h6>
                                                    <div class="d-flex gap-2">
                                                        @if($child->is_active)
                                                            <span class="badge bg-success">نشط</span>
                                                        @else
                                                            <span class="badge bg-danger">غير نشط</span>
                                                        @endif
                                                        @if($child->is_featured)
                                                            <span class="badge bg-warning">مميزة</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($child->description)
                                                <p class="text-muted small mb-3">{{ Str::limit($child->description, 80) }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="text-muted">
                                                    <i class="fas fa-box me-1"></i>
                                                    {{ $child->products_count }} منتج
                                                </span>
                                                <span class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $child->created_at->format('Y/m/d') }}
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.categories.show', $child) }}" 
                                                   class="btn btn-sm btn-outline-info flex-fill">
                                                    <i class="fas fa-eye me-1"></i>عرض
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $child) }}" 
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($child->products_count == 0)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-confirm"
                                                            data-category-id="{{ $child->id }}"
                                                            data-category-name="{{ $child->name }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
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
                                            <td>{{ number_format($product->price, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</td>
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

@push('styles')
<style>
.subcategory-card {
    transition: all 0.3s ease-in-out;
    border: 1px solid #e9ecef;
}

.subcategory-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    border-color: #17a2b8;
}

.subcategory-card .card-body {
    padding: 1.25rem;
}

.subcategory-card h6 {
    color: #2c3e50;
    font-weight: 600;
}

.subcategory-card .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.translation-card {
    transition: all 0.3s ease-in-out;
    border: 1px solid #e9ecef;
}

.translation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #17a2b8;
}

.translation-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

.translation-card .card-body {
    padding: 1rem;
}

.translation-card h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .subcategory-card .card-body {
        padding: 1rem;
    }
    
    .subcategory-card h6 {
        font-size: 1rem;
    }
    
    .translation-card .card-body {
        padding: 0.75rem;
    }
}
</style>
@endpush
