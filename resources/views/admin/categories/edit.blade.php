@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تعديل الفئة - {{ $category->name }}</h5>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <!-- Hierarchy Warning -->
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-lg me-3"></i>
                                <div>
                                    <h6 class="mb-1">قاعدة التسلسل الهرمي</h6>
                                    <p class="mb-0">يمكن إنشاء فئات رئيسية أو فئات فرعية من فئات رئيسية فقط. لا يمكن إنشاء فئات فرعية من فئات فرعية.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">المعلومات الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <!-- Locale Tabs -->
                                <div class="mb-3">
                                    <ul class="nav nav-tabs" id="localeTabs" role="tablist">
                                        @foreach($locales as $localeCode => $localeData)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                                    id="tab-{{ $localeCode }}" 
                                                    data-bs-toggle="tab" 
                                                    data-bs-target="#content-{{ $localeCode }}" 
                                                    type="button" 
                                                    role="tab" 
                                                    aria-controls="content-{{ $localeCode }}" 
                                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                <span class="me-2">{{ $localeData['flag'] }}</span>
                                                {{ $localeData['name'] }}
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                    
                                    <div class="tab-content mt-3" id="localeTabContent">
                                        @foreach($locales as $localeCode => $localeData)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                             id="content-{{ $localeCode }}" 
                                             role="tabpanel" 
                                             aria-labelledby="tab-{{ $localeCode }}">
                                            
                                            <div class="mb-3">
                                                <label for="name_{{ $localeCode }}" class="form-label">
                                                    اسم الفئة <span class="text-danger">*</span>
                                                    <span class="badge bg-secondary ms-2">{{ $localeData['flag'] }} {{ $localeData['name'] }}</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('translations.'.$localeCode.'.name') is-invalid @enderror" 
                                                       id="name_{{ $localeCode }}" 
                                                       name="translations[{{ $localeCode }}][name]" 
                                                       value="{{ old('translations.'.$localeCode.'.name', $translations->get($localeCode)?->name ?? $category->name) }}" 
                                                       {{ $loop->first ? 'required' : '' }}
                                                       placeholder="أدخل اسم الفئة بـ {{ $localeData['name'] }}">
                                                <input type="hidden" name="translations[{{ $localeCode }}][locale]" value="{{ $localeCode }}">
                                                @error('translations.'.$localeCode.'.name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="description_{{ $localeCode }}" class="form-label">
                                                    وصف الفئة
                                                    <span class="badge bg-secondary ms-2">{{ $localeData['flag'] }} {{ $localeData['name'] }}</span>
                                                </label>
                                                <textarea class="form-control summernote @error('translations.'.$localeCode.'.description') is-invalid @enderror" 
                                                          id="description_{{ $localeCode }}" 
                                                          name="translations[{{ $localeCode }}][description]" 
                                                          rows="4" 
                                                          placeholder="أدخل وصف الفئة بـ {{ $localeData['name'] }}">{{ old('translations.'.$localeCode.'.description', $translations->get($localeCode)?->description ?? $category->description) }}</textarea>
                                                @error('translations.'.$localeCode.'.description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">الفئة الأب</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">فئة رئيسية</option>
                                        @foreach($categories->where('parent_id', null)->where('id', '!=', $category->id) as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                <i class="fas fa-folder-open me-2"></i>{{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        يمكنك اختيار فئة رئيسية فقط. لا يمكن إنشاء فئات فرعية من فئات فرعية.
                                    </small>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="order" class="form-label">ترتيب الفئة</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $category->order) }}" min="0">
                                    @error('order')
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
                                    <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*" data-max-size="2048">
                                    <small class="text-muted d-block mt-2">الأبعاد المثالية: 32x32 بكسل - الحد الأقصى: 2 ميجابايت</small>
                                    <div class="mt-2" id="icon-preview">
                                        @if($category->icon)
                                            <div class="position-relative">
                                                <img src="{{ asset($category->icon) }}" alt="Current Icon" class="img-thumbnail" width="64">
                                                <small class="d-block text-muted mt-1">الأيقونة الحالية</small>
                                            </div>
                                        @endif
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="icon-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">صورة الفئة</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" data-max-size="2048">
                                    <small class="text-muted d-block mt-2">الأبعاد المثالية: 800x600 بكسل - الحد الأقصى: 2 ميجابايت</small>
                                    <div class="mt-2" id="image-preview">
                                        @if($category->image)
                                            <div class="position-relative">
                                                <img src="{{ asset($category->image) }}" alt="Current Image" class="img-thumbnail" width="200">
                                                <small class="d-block text-muted mt-1">الصورة الحالية</small>
                                            </div>
                                        @endif
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="image-error"></div>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">مميزة</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.alert-info {
    border-left: 4px solid #17a2b8;
    background-color: #f8f9fa;
}

.alert-info .fas {
    color: #17a2b8;
}

.alert-info h6 {
    color: #2c3e50;
    font-weight: 600;
}

.alert-info p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Locale Tabs Styling */
.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #495057;
    background-color: #f8f9fa;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    background-color: transparent;
    border-bottom-color: #007bff;
    font-weight: 600;
}

.nav-tabs .nav-link .badge {
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
}

/* Parent category select styling */
#parent_id option {
    padding: 8px;
}

#parent_id option:first-child {
    font-weight: 600;
    color: #495057;
}

/* Validation feedback styling */
.invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

/* Tab content styling */
.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 1.5rem;
    background-color: #fff;
    border-radius: 0 0 0.375rem 0.375rem;
}

.tab-pane {
    min-height: 200px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize summernote for all textareas
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

    // File validation function
    function validateFile(file, maxSizeMB, allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml']) {
        const maxSizeBytes = maxSizeMB * 1024 * 1024;

        if (file.size > maxSizeBytes) {
            return `حجم الملف يجب أن يكون أقل من ${maxSizeMB} ميجابايت`;
        }

        if (!allowedTypes.includes(file.type)) {
            return 'نوع الملف غير مدعوم. يرجى اختيار صورة بصيغة JPEG, PNG, JPG, GIF, أو SVG';
        }

        return null;
    }

    // Preview icon with validation
    $('#icon').change(function() {
        const file = this.files[0];
        const maxSize = parseInt($(this).data('max-size'));
        const previewDiv = $('#icon-preview');
        const errorDiv = $('#icon-error');

        // Clear previous errors
        errorDiv.html('').hide();
        $(this).removeClass('is-invalid');

        if (file) {
            // Validate file
            const error = validateFile(file, maxSize);
            if (error) {
                $(this).addClass('is-invalid');
                errorDiv.html(error).show();
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.html(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" width="64" alt="New Icon Preview">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="clearFileInput('icon')">
                            <i class="fas fa-times"></i>
                        </button>
                        <small class="d-block text-muted mt-1">الأيقونة الجديدة</small>
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview image with validation
    $('#image').change(function() {
        const file = this.files[0];
        const maxSize = parseInt($(this).data('max-size'));
        const previewDiv = $('#image-preview');
        const errorDiv = $('#image-error');

        // Clear previous errors
        errorDiv.html('').hide();
        $(this).removeClass('is-invalid');

        if (file) {
            // Validate file
            const error = validateFile(file, maxSize);
            if (error) {
                $(this).addClass('is-invalid');
                errorDiv.html(error).show();
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.html(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" width="200" alt="New Image Preview">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="clearFileInput('image')">
                            <i class="fas fa-times"></i>
                        </button>
                        <small class="d-block text-muted mt-1">الصورة الجديدة</small>
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        }
    });

    // Form submission validation
    $('#categoryForm').on('submit', function(e) {
        let hasErrors = false;

        // Check if at least one translation name is filled
        let hasTranslation = false;
        $('input[name*="[name]"]').each(function() {
            if ($(this).val().trim()) {
                hasTranslation = true;
                return false;
            }
        });

        if (!hasTranslation) {
            // Show error on first tab
            $('#tab-ar').tab('show');
            $('#name_ar').addClass('is-invalid');
            hasErrors = true;
        }

        // Check file sizes if files are selected
        const iconFile = $('#icon')[0].files[0];
        const imageFile = $('#image')[0].files[0];

        if (iconFile) {
            const maxSize = parseInt($('#icon').data('max-size'));
            if (iconFile.size > maxSize * 1024 * 1024) {
                $('#icon').addClass('is-invalid');
                $('#icon-error').html(`حجم الملف يجب أن يكون أقل من ${maxSize} ميجابايت`).show();
                hasErrors = true;
            }
        }

        if (imageFile) {
            const maxSize = parseInt($('#image').data('max-size'));
            if (imageFile.size > maxSize * 1024 * 1024) {
                $('#image').addClass('is-invalid');
                $('#image-error').html(`حجم الملف يجب أن يكون أقل من ${maxSize} ميجابايت`).show();
                hasErrors = true;
            }
        }

        if (hasErrors) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
        }
    });

    // Clear file input function
    window.clearFileInput = function(inputId) {
        $(`#${inputId}`).val('');
        // Restore original content if it exists
        if (inputId === 'icon' && '{{ $category->icon }}') {
            $(`#${inputId}-preview`).html(`
                <div class="form-control">
                    <img src="{{ asset($category->icon) }}" alt="Current Icon" class="img-thumbnail" width="64">
                    <small class="d-block text-muted mt-1">الأيقونة الحالية</small>
                </div>
            `);
        } else if (inputId === 'image' && '{{ $category->image }}') {
            $(`#${inputId}-preview`).html(`
                <div class="form-control">
                    <img src="{{ asset($category->image) }}" alt="Current Image" class="img-thumbnail" width="200">
                    <small class="d-block text-muted mt-1">الصورة الحالية</small>
                </div>
            `);
        } else {
            $(`#${inputId}-preview`).html('');
        }
        $(`#${inputId}`).removeClass('is-invalid');
        $(`#${inputId}-error`).html('').hide();
    };

    // Parent category validation
    $('#parent_id').change(function() {
        const selectedValue = $(this).val();
        const parentSelect = $(this);
        
        if (selectedValue) {
            // Check if the selected category is a main category (no parent)
            $.ajax({
                url: '{{ route("admin.categories.check-parent") }}',
                method: 'GET',
                data: { category_id: selectedValue },
                success: function(response) {
                    if (response.is_main_category) {
                        parentSelect.removeClass('is-invalid');
                        parentSelect.next('.invalid-feedback').hide();
                    } else {
                        parentSelect.addClass('is-invalid');
                        parentSelect.next('.invalid-feedback').html('لا يمكن اختيار فئة فرعية كفئة أب').show();
                        parentSelect.val('{{ $category->parent_id }}'); // Reset to original value
                    }
                },
                error: function() {
                    parentSelect.addClass('is-invalid');
                    parentSelect.next('.invalid-feedback').html('خطأ في التحقق من الفئة').show();
                }
            });
        }
    });
});
</script>
@endpush
