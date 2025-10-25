@extends('admin.layouts.app')

@section('title', 'إضافة صلاحية جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة صلاحية جديدة</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.permissions.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <h5 class="mb-3">المعلومات الأساسية</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الصلاحية <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="مثال: users.create" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">استخدم تنسيق group.action (مثال: users.create, products.edit)</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="وصف الصلاحية...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="guard_name" class="form-label">اسم الحارس</label>
                                    <input type="text" class="form-control @error('guard_name') is-invalid @enderror" 
                                           id="guard_name" name="guard_name" value="{{ old('guard_name', 'web') }}" 
                                           placeholder="web">
                                    @error('guard_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            صلاحية نشطة
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- الترجمات -->
                            <div class="col-md-6">
                                <h5 class="mb-3">الترجمات</h5>
                                
                                <div id="translations-container">
                                    <div class="translation-item mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">اللغة</label>
                                                <select class="form-select" name="translations[0][locale]" required>
                                                    <option value="ar" {{ old('translations.0.locale') == 'ar' ? 'selected' : '' }}>العربية</option>
                                                    <option value="en" {{ old('translations.0.locale') == 'en' ? 'selected' : '' }}>English</option>
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <label class="form-label">الاسم</label>
                                                <input type="text" class="form-control" name="translations[0][name]" 
                                                       value="{{ old('translations.0.name') }}" placeholder="اسم الصلاحية" required>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label">اسم العرض</label>
                                                <input type="text" class="form-control" name="translations[0][display_name]" 
                                                       value="{{ old('translations.0.display_name') }}" placeholder="اسم العرض">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">الوصف</label>
                                                <input type="text" class="form-control" name="translations[0][description]" 
                                                       value="{{ old('translations.0.description') }}" placeholder="وصف الصلاحية">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTranslation()">
                                    <i class="fas fa-plus"></i> إضافة ترجمة
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ الصلاحية
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let translationIndex = 1;

    function addTranslation() {
        const container = document.getElementById('translations-container');
        const newTranslation = document.createElement('div');
        newTranslation.className = 'translation-item mb-3 p-3 border rounded';
        newTranslation.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">اللغة</label>
                    <select class="form-select" name="translations[${translationIndex}][locale]" required>
                        <option value="ar">العربية</option>
                        <option value="en">English</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">الاسم</label>
                    <input type="text" class="form-control" name="translations[${translationIndex}][name]" 
                           placeholder="اسم الصلاحية" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="form-label">اسم العرض</label>
                    <input type="text" class="form-control" name="translations[${translationIndex}][display_name]" 
                           placeholder="اسم العرض">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الوصف</label>
                    <input type="text" class="form-control" name="translations[${translationIndex}][description]" 
                           placeholder="وصف الصلاحية">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTranslation(this)">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newTranslation);
        translationIndex++;
    }

    function removeTranslation(button) {
        button.closest('.translation-item').remove();
    }

    // Auto-fill display name when name is entered
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('[name]')) {
            const displayNameInput = e.target.closest('.translation-item').querySelector('input[name*="[display_name]"]');
            if (displayNameInput && !displayNameInput.value) {
                displayNameInput.value = e.target.value;
            }
        }
    });
</script>
@endpush
