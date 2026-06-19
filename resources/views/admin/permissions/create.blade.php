@extends('admin.layouts.app')

@section('title', __('admin.permissions.create'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.permissions.create') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> {{ __('admin.permissions.back_to_list') }}
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.permissions.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <h5 class="mb-3">{{ __('admin.permissions.basic_info') }}</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('admin.permissions.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="{{ __('admin.permissions.name_placeholder') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">{{ __('admin.permissions.name_help') }}</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('admin.permissions.description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="{{ __('admin.permissions.description_placeholder') }}">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="guard_name" class="form-label">{{ __('admin.permissions.guard_name') }}</label>
                                    <input type="text" class="form-control @error('guard_name') is-invalid @enderror" 
                                           id="guard_name" name="guard_name" value="{{ old('guard_name', 'web') }}" 
                                           placeholder="{{ __('admin.permissions.guard_placeholder') }}">
                                    @error('guard_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('admin.permissions.is_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- الترجمات -->
                            <div class="col-md-6">
                                <h5 class="mb-3">{{ __('admin.permissions.translations') }}</h5>

                                @include('components.translations-repeater', [
                                    'locales' => config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'fields' => [
                                        [
                                            'type' => 'input',
                                            'key' => 'name',
                                            'label' => __('admin.permissions.name'),
                                            'requiredFirst' => true,
                                        ],
                                        [
                                            'type' => 'input',
                                            'key' => 'display_name',
                                            'label' => __('admin.permissions.display_name'),
                                        ],
                                        [
                                            'type' => 'input',
                                            'key' => 'description',
                                            'label' => __('admin.permissions.description'),
                                        ],
                                    ],
                                    'addLabel' => __('admin.ui.layout.add_new'),
                                    'removeLabel' => __('admin.actions.delete'),
                                    'minItems' => 1,
                                ])
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('admin.permissions.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.permissions.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
