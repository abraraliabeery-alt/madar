@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تعديل شارع</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.streets.update', $street) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الحي</label>
                        <select name="neighborhood_id" class="form-select" required>
                            <option value="">اختر الحي</option>
                            @foreach($neighborhoods as $neighborhood)
                                <option value="{{ $neighborhood->id }}" {{ old('neighborhood_id', $street->neighborhood_id) == $neighborhood->id ? 'selected' : '' }}>{{ $neighborhood->city->name ?? '' }} - {{ $neighborhood->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">اسم الشارع</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $street->name) }}" required>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $street->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">نشط</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">تحديث</button>
                    <a href="{{ route('admin.streets.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
