@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.features.edit') }}</h5>
            <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('admin.features.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.features.update', $feature) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.features.basic_info') }}</h6>
                            </div>
                            <div class="card-body">
                                @include('components.translations-repeater', [
                                    'locales' => $locales ?? config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'items' => $feature->translations->map(function ($t) {
                                        return [
                                            'locale' => $t->locale,
                                            'name' => $t->name,
                                            'description' => $t->description,
                                        ];
                                    })->values()->toArray(),
                                    'fields' => [
                                        [
                                            'type' => 'input',
                                            'key' => 'name',
                                            'label' => __('admin.features.name'),
                                            'requiredFirst' => true,
                                        ],
                                        [
                                            'type' => 'textarea',
                                            'key' => 'description',
                                            'label' => __('admin.features.description'),
                                            'rows' => 4,
                                        ],
                                    ],
                                    'addLabel' => __('admin.ui.layout.add_new'),
                                    'removeLabel' => __('admin.actions.delete'),
                                    'minItems' => 1,
                                    'maxItems' => is_array(($locales ?? null)) ? count($locales) : null,
                                ])

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">{{ __('admin.features.order') }}</label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $feature->order ?? 0) }}" min="0">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $feature->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">{{ __('admin.features.is_active') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.features.media') }}</h6>
                            </div>
                            <div class="card-body">
                                <x-icon-picker
                                    nameIcon="icon_name"
                                    nameImage="icon"
                                    :valueIconName="old('icon_name', (\Illuminate\Support\Str::contains($feature->icon, 'fa-') || !\Illuminate\Support\Str::contains($feature->icon, '/')) ? $feature->icon : '')"
                                    :currentImagePath="$feature->icon && \Illuminate\Support\Str::contains($feature->icon, '/') ? $feature->icon : null"
                                    labelIcon="{{ __('admin.features.icon_fontawesome') }}"
                                    labelImage="{{ __('admin.features.icon_image') }}"
                                    imageHelpText="{{ __('admin.features.icon_help') }}"
                                    showCurrentImageLabel="{{ __('admin.features.current_image') }}"
                                    pickerTitle="{{ __('admin.features.picker_title') }}"
                                    pickerButtonText="{{ __('admin.features.picker_button') }}"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('admin.features.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize summernote
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol']],
        ]
    });
});
</script>
@endpush
