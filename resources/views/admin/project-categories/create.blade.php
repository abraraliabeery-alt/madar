@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إضافة تصنيف مشاريع</h5>
            <a href="{{ route('admin.project-categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.project-categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('components.translations-repeater', [
                    'locales' => $locales,
                    'namePrefix' => 'translations',
                    'fields' => [
                        [
                            'type' => 'input',
                            'key' => 'name',
                            'label' => 'اسم التصنيف',
                            'requiredFirst' => true,
                        ],
                        [
                            'type' => 'textarea',
                            'key' => 'description',
                            'label' => 'الوصف',
                            'rows' => 4,
                        ],
                    ],
                    'addLabel' => __('admin.ui.layout.add_new'),
                    'removeLabel' => __('admin.actions.delete'),
                    'minItems' => 1,
                    'maxItems' => is_array($locales) ? count($locales) : null,
                ])

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">التصنيف الأب</label>
                        <select class="form-select" name="parent_id">
                            <option value="">تصنيف رئيسي</option>
                            @foreach($projectCategories as $pc)
                                <option value="{{ $pc->id }}" {{ old('parent_id') == $pc->id ? 'selected' : '' }}>{{ $pc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" class="form-control" name="order" value="{{ old('order', 0) }}" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">أيقونة</label>
                        <input type="file" class="form-control" name="icon">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">صورة</label>
                        <input type="file" class="form-control" name="image">
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label">نشط</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label">مميز</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
