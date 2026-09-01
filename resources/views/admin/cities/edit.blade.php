@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تعديل مدينة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cities.update', $city) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $city->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الرابط المختصر (slug)</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $city->slug) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $city->description) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الصورة (رابط)</label>
                        <input type="text" name="image" class="form-control" value="{{ old('image', $city->image) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $city->sort_order) }}">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">نشط</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" {{ old('is_featured', $city->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label">مميز</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">تحديث</button>
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
