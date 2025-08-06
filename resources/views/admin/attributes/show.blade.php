@extends('admin.layouts.app')

@section('title', 'تفاصيل الخاصية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الخاصية: {{ $attribute->translations->first()->name ?? 'N/A' }}</h4>
                    <div>
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">المعلومات الأساسية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">اسم الخاصية:</label>
                                                <p class="form-control-plaintext">{{ $attribute->translations->first()->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">نوع الخاصية:</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge bg-info">{{ $attribute->type }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الفئة:</label>
                                                <p class="form-control-plaintext">
                                                    @if($attribute->category)
                                                        <span class="badge bg-secondary">{{ $attribute->category->name }}</span>
                                                    @else
                                                        <span class="text-muted">غير محدد</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الحالة:</label>
                                                <p class="form-control-plaintext">
                                                    @if($attribute->required)
                                                        <span class="badge bg-danger">إلزامية</span>
                                                    @else
                                                        <span class="badge bg-warning">اختيارية</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الرمز:</label>
                                                <p class="form-control-plaintext">{{ $attribute->Symbol ?? 'غير محدد' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الرمز المختصر:</label>
                                                <p class="form-control-plaintext">{{ $attribute->translations->first()->symbol ?? 'غير محدد' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                                <p class="form-control-plaintext">{{ $attribute->created_at->format('Y-m-d H:i:s') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">آخر تحديث:</label>
                                                <p class="form-control-plaintext">{{ $attribute->updated_at->format('Y-m-d H:i:s') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Icon -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">الأيقونة</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($attribute->icon)
                                        <img src="{{ Storage::url($attribute->icon) }}" alt="Attribute Icon" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-image fa-3x"></i>
                                            <p class="mt-2">لا توجد أيقونة</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Using This Attribute -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">المنتجات التي تستخدم هذه الخاصية ({{ $attribute->products->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    @if($attribute->products->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>اسم المنتج</th>
                                                        <th>القيمة</th>
                                                        <th>الفئة</th>
                                                        <th>الحالة</th>
                                                        <th>الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($attribute->products as $product)
                                                    <tr>
                                                        <td>{{ $product->id }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                                                {{ $product->title }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $attributeValue = $product->pivot->value ?? 'غير محدد';
                                                            @endphp
                                                            <span class="badge bg-light text-dark">{{ $attributeValue }}</span>
                                                        </td>
                                                        <td>
                                                            @if($product->category)
                                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($product->is_active)
                                                                <span class="badge bg-success">مفعل</span>
                                                            @else
                                                                <span class="badge bg-danger">معطل</span>
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
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <p>لا توجد منتجات تستخدم هذه الخاصية حالياً</p>
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
</div>
@endsection 