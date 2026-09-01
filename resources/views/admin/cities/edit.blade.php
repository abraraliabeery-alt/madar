@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.cities.edit') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cities.update', $city) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.cities.name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $city->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.cities.slug') }}</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $city->slug) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('admin.cities.description') }}</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $city->description) }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.cities.image_url') }}</label>
                        <input type="text" name="image" class="form-control" value="{{ old('image', $city->image) }}" placeholder="https://...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.cities.image_upload') }}</label>
                        @if($city->image)
                            <div class="mb-2">
                                <img src="{{ $city->image_url }}" alt="{{ $city->name }}" style="max-height: 100px;" class="img-thumbnail">
                            </div>
                        @endif
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.cities.sort_order') }}</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $city->sort_order) }}">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('admin.cities.is_active') }}</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" {{ old('is_featured', $city->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('admin.cities.is_featured') }}</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('admin.cities.update') }}</button>
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">{{ __('admin.cities.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
