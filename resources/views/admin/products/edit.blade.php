@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تعديل المنتج: {{ $product->title }}</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
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
                                            <label for="title" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $product->title) }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                                <span class="input-group-text">ريال</span>
                                            </div>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facility_id" class="form-label">المنشأة <span class="text-danger">*</span></label>
                                            <select class="form-select @error('facility_id') is-invalid @enderror" id="facility_id" name="facility_id" required>
                                                <option value="">اختر المنشأة</option>
                                                @foreach($facilities as $facility)
                                                    <option value="{{ $facility->id }}" {{ old('facility_id', $product->facility_id) == $facility->id ? 'selected' : '' }}>
                                                        {{ $facility->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('facility_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">الفئة <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                                <option value="">اختر الفئة</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_id" class="form-label">الحالة <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                                                <option value="">اختر الحالة</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ old('status_id', $product->status ? $product->status->id : '') == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_user_id" class="form-label">المالك <span class="text-danger">*</span></label>
                                            <select class="form-select @error('owner_user_id') is-invalid @enderror" id="owner_user_id" name="owner_user_id" required>
                                                <option value="">اختر المالك</option>
                                                @foreach($facilities as $facility)
                                                    <option value="{{ $facility->owner->id }}" {{ old('owner_user_id', $product->owner_user_id) == $facility->owner->id ? 'selected' : '' }}>
                                                        {{ $facility->owner->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('owner_user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">الوصف</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">العنوان <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $product->address) }}" required>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">تفاصيل العقار</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="rooms" class="form-label">عدد الغرف</label>
                                            <input type="number" class="form-control @error('rooms') is-invalid @enderror" id="rooms" name="rooms" value="{{ old('rooms', $product->rooms) }}">
                                            @error('rooms')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="bathrooms" class="form-label">عدد الحمامات</label>
                                            <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $product->bathrooms) }}">
                                            @error('bathrooms')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="area" class="form-label">المساحة (متر مربع)</label>
                                            <input type="number" step="0.01" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ old('area', $product->area) }}">
                                            @error('area')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="parking_spaces" class="form-label">مواقف السيارات</label>
                                            <input type="number" class="form-control @error('parking_spaces') is-invalid @enderror" id="parking_spaces" name="parking_spaces" value="{{ old('parking_spaces', $product->parking_spaces) }}">
                                            @error('parking_spaces')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="floor" class="form-label">الطابق</label>
                                            <input type="text" class="form-control @error('floor') is-invalid @enderror" id="floor" name="floor" value="{{ old('floor', $product->floor) }}">
                                            @error('floor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="floors_count" class="form-label">عدد الطوابق</label>
                                            <input type="number" class="form-control @error('floors_count') is-invalid @enderror" id="floors_count" name="floors_count" value="{{ old('floors_count', $product->floors_count) }}">
                                            @error('floors_count')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">المميزات</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($features as $feature)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}"
                                                    {{ in_array($feature->id, $product->features->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                    {{ $feature->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Image Upload -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الصورة الرئيسية</h6>
                            </div>
                            <div class="card-body">
                                @if($product->image)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($product->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="image" class="form-label">تغيير الصورة</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggles -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">الحالة</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">نشط</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">مميز</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ $product->is_verified ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_verified">متحقق منه</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات الاتصال</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $product->contact_phone) }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $product->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize any JavaScript functionality here
});
</script>
@endpush
