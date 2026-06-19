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

                                @include('components.translations-repeater', [
                                    'locales' => config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'fields' => [
                                        [
                                            'type' => 'input',
                                            'key' => 'name',
                                            'label' => 'الاسم',
                                            'requiredFirst' => true,
                                        ],
                                        [
                                            'type' => 'input',
                                            'key' => 'display_name',
                                            'label' => 'اسم العرض',
                                        ],
                                        [
                                            'type' => 'input',
                                            'key' => 'description',
                                            'label' => 'الوصف',
                                        ],
                                    ],
                                    'addLabel' => 'إضافة ترجمة',
                                    'removeLabel' => 'حذف',
                                    'minItems' => 1,
                                ])
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
