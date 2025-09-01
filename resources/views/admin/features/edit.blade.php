@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تعديل المميزة</h5>
            <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.features.update', $feature) }}" method="POST" enctype="multipart/form-data">
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
                                <!-- Arabic Name -->
                                <div class="mb-3">
                                    <label for="name_ar" class="form-label">اسم المميزة (العربية) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $feature->getTranslation('ar')->name ?? '') }}" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- English Name -->
                                <div class="mb-3">
                                    <label for="name_en" class="form-label">اسم المميزة (الإنجليزية) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $feature->getTranslation('en')->name ?? '') }}" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Arabic Description -->
                                <div class="mb-3">
                                    <label for="description_ar" class="form-label">الوصف (العربية)</label>
                                    <textarea class="form-control summernote @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="4">{{ old('description_ar', $feature->getTranslation('ar')->description ?? '') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- English Description -->
                                <div class="mb-3">
                                    <label for="description_en" class="form-label">الوصف (الإنجليزية)</label>
                                    <textarea class="form-control summernote @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4">{{ old('description_en', $feature->getTranslation('en')->description ?? '') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">الترتيب</label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $feature->order ?? 0) }}" min="0">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $feature->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الوسائط</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">الأيقونة</label>
                                    <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*">
                                    <small class="text-muted d-block mt-2">الأبعاد المثالية: 100x100 بكسل</small>
                                    @if($feature->icon)
                                        <div class="mt-2">
                                            <img src="{{ asset($feature->icon) }}" alt="Current Icon" class="img-thumbnail" width="100">
                                            <small class="text-muted d-block">الأيقونة الحالية</small>
                                        </div>
                                    @endif
                                    <div class="mt-2" id="icon-preview"></div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize summernote
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol']],
        ]
    });

    // Preview icon
    $('#icon').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#icon-preview').html(`<img src="${e.target.result}" class="img-thumbnail" width="100">`);
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
