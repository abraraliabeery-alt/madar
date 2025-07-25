@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إضافة فئة جديدة</h5>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">المعلومات الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الفئة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">وصف الفئة</label>
                                    <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">الفئة الأب</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">فئة رئيسية</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">ترتيب الفئة</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <small class="text-muted d-block mt-2">الأبعاد المثالية: 32x32 بكسل</small>
                                    <div class="mt-2" id="icon-preview"></div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">صورة الفئة</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    <small class="text-muted d-block mt-2">الأبعاد المثالية: 800x600 بكسل</small>
                                    <div class="mt-2" id="image-preview"></div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">مميزة</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
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

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Preview icon
    $('#icon').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#icon-preview').html(`<img src="${e.target.result}" class="img-thumbnail" width="64">`);
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview image
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html(`<img src="${e.target.result}" class="img-thumbnail" width="200">`);
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
