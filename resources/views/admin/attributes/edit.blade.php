@extends('admin.layouts.app')

@section('title', 'تعديل الخاصية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">تعديل الخاصية: {{ $attribute->getTranslatedName() ?? 'N/A' }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attributes.update', $attribute) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                                    <label for="name" class="form-label">اسم الخاصية <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                           id="name" name="name" value="{{ old('name', $attribute->getTranslatedName() ?? '') }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">نوع الخاصية <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                        <option value="">اختر النوع</option>
                                                        <option value="text" {{ old('type', $attribute->type) == 'text' ? 'selected' : '' }}>نص</option>
                                                        <option value="number" {{ old('type', $attribute->type) == 'number' ? 'selected' : '' }}>رقم</option>
                                                        <option value="boolean" {{ old('type', $attribute->type) == 'boolean' ? 'selected' : '' }}>نعم/لا</option>
                                                        <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>قائمة</option>
                                                        <option value="textarea" {{ old('type', $attribute->type) == 'textarea' ? 'selected' : '' }}>نص طويل</option>
                                                        <option value="date" {{ old('type', $attribute->type) == 'date' ? 'selected' : '' }}>تاريخ</option>
                                                        <option value="time" {{ old('type', $attribute->type) == 'time' ? 'selected' : '' }}>وقت</option>
                                                        <option value="datetime" {{ old('type', $attribute->type) == 'datetime' ? 'selected' : '' }}>تاريخ ووقت</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">الفئة</label>
                                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                                        <option value="">اختر الفئة</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category_id', $attribute->category_id) == $category->id ? 'selected' : '' }}>
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
                                                    <label for="Symbol" class="form-label">الرمز</label>
                                                    <input type="text" class="form-control @error('Symbol') is-invalid @enderror"
                                                           id="Symbol" name="Symbol" value="{{ old('Symbol', $attribute->Symbol) }}" placeholder="مثال: m², km, etc.">
                                                    @error('Symbol')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="symbol" class="form-label">الرمز المختصر</label>
                                                    <input type="text" class="form-control @error('symbol') is-invalid @enderror"
                                                           id="symbol" name="symbol" value="{{ old('symbol', $attribute->translations->first()->symbol ?? '') }}" placeholder="رمز مختصر للعرض">
                                                    @error('symbol')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input @error('required') is-invalid @enderror"
                                                               type="checkbox" id="required" name="required" value="1"
                                                               {{ old('required', $attribute->required) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="required">
                                                            خاصية إلزامية
                                                        </label>
                                                        @error('required')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Icon Upload -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">الأيقونة</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($attribute->icon)
                                            <div class="mb-3">
                                                <label class="form-label">الأيقونة الحالية</label>
                                                <div class="text-center">
                                                    <img src="{{ asset($attribute->icon) }}" alt="Current Icon" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label for="icon" class="form-label">تغيير الأيقونة</label>
                                            <input type="file" class="form-control @error('icon') is-invalid @enderror"
                                                   id="icon" name="icon" accept="image/*">
                                            <small class="form-text text-muted">اترك فارغاً للاحتفاظ بالأيقونة الحالية</small>
                                            @error('icon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div id="icon-preview" class="text-center" style="display: none;">
                                            <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-arrow-left"></i> رجوع
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const previewImage = document.getElementById('preview-image');

    iconInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                iconPreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            iconPreview.style.display = 'none';
        }
    });
});
</script>
@endpush
