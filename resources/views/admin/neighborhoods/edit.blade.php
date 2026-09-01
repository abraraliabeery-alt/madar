@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تعديل حي</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.neighborhoods.update', $neighborhood) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">المدينة</label>
                        <select name="city_id" class="form-select" required>
                            <option value="">اختر المدينة</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $neighborhood->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">اسم الحي</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $neighborhood->name) }}" required>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $neighborhood->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">نشط</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">تحديث</button>
                    <a href="{{ route('admin.neighborhoods.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
