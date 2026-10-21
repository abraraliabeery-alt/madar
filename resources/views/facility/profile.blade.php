@extends('facility.layouts.app')

@section('title', __('facility.sidebar.profile'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.sidebar.profile') }}</h1>
        <p class="text-muted small mb-0">{{ $facility->name }}</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">اسم المنشأة</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $facility->name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الجوال</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $facility->phone ?? $facility->phone_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $facility->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الموقع الإلكتروني</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $facility->website) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $facility->address) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $facility->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">السجل التجاري (PDF / صورة)</label>
                        <input type="file" name="qualification_commercial_register" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
