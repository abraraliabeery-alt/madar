@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.cities.create') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.cities.name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.cities.slug') }}</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('admin.cities.description') }}</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.cities.image_url') }}</label>
                        <input type="text" name="image" class="form-control" value="{{ old('image') }}" placeholder="https://...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.cities.image_upload') }}</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.cities.sort_order') }}</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                            <label class="form-check-label">{{ __('admin.cities.is_active') }}</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input">
                            <label class="form-check-label">{{ __('admin.cities.is_featured') }}</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('admin.cities.save') }}</button>
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">{{ __('admin.cities.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
