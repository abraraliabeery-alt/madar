@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.neighborhoods.create') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.neighborhoods.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.neighborhoods.city') }}</label>
                        <select name="city_id" class="form-select" required>
                            <option value="">{{ __('admin.neighborhoods.select_city') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('admin.neighborhoods.name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                            <label class="form-check-label">{{ __('admin.neighborhoods.is_active') }}</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('admin.neighborhoods.save') }}</button>
                    <a href="{{ route('admin.neighborhoods.index') }}" class="btn btn-secondary">{{ __('admin.neighborhoods.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
