@extends('admin.layouts.app')

@section('title', __('admin.attributes.edit'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('admin.attributes.edit') }}: {{ $attribute->getTranslatedName() ?? 'N/A' }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attributes.update', $attribute) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('admin.attributes.basic_info') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">{{ __('admin.attributes.type') }} <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                        <option value="">{{ __('admin.attributes.select_type') }}</option>
                                                        <option value="text" {{ old('type', $attribute->type) == 'text' ? 'selected' : '' }}>{{ __('admin.attributes.type_text') }}</option>
                                                        <option value="number" {{ old('type', $attribute->type) == 'number' ? 'selected' : '' }}>{{ __('admin.attributes.type_number') }}</option>
                                                        <option value="boolean" {{ old('type', $attribute->type) == 'boolean' ? 'selected' : '' }}>{{ __('admin.attributes.type_boolean') }}</option>
                                                        <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>{{ __('admin.attributes.type_select') }}</option>
                                                        <option value="textarea" {{ old('type', $attribute->type) == 'textarea' ? 'selected' : '' }}>{{ __('admin.attributes.type_textarea') }}</option>
                                                        <option value="date" {{ old('type', $attribute->type) == 'date' ? 'selected' : '' }}>{{ __('admin.attributes.type_date') }}</option>
                                                        <option value="time" {{ old('type', $attribute->type) == 'time' ? 'selected' : '' }}>{{ __('admin.attributes.type_time') }}</option>
                                                        <option value="datetime" {{ old('type', $attribute->type) == 'datetime' ? 'selected' : '' }}>{{ __('admin.attributes.type_datetime') }}</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @include('components.translations-repeater', [
                                            'locales' => $locales ?? config('locales.available', []),
                                            'namePrefix' => 'translations',
                                            'items' => $attribute->translations->map(function ($t) {
                                                return [
                                                    'locale' => $t->locale,
                                                    'name' => $t->name,
                                                    'symbol' => $t->symbol,
                                                ];
                                            })->values()->toArray(),
                                            'fields' => [
                                                [
                                                    'type' => 'input',
                                                    'key' => 'name',
                                                    'label' => __('admin.attributes.name'),
                                                    'requiredFirst' => true,
                                                ],
                                                [
                                                    'type' => 'input',
                                                    'key' => 'symbol',
                                                    'label' => __('admin.attributes.short_symbol'),
                                                    'placeholder' => __('admin.attributes.short_symbol_placeholder'),
                                                ],
                                            ],
                                            'addLabel' => __('admin.ui.layout.add_new'),
                                            'removeLabel' => __('admin.actions.delete'),
                                            'minItems' => 1,
                                            'maxItems' => is_array($locales ?? null) ? count($locales) : null,
                                        ])

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">{{ __('admin.attributes.category') }}</label>
                                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                                        <option value="">{{ __('admin.attributes.select_category') }}</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category_id', $attribute->category_id) == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="Symbol" class="form-label">{{ __('admin.attributes.symbol') }}</label>
                                                    <input type="text" class="form-control @error('Symbol') is-invalid @enderror"
                                                           id="Symbol" name="Symbol" value="{{ old('Symbol', $attribute->Symbol) }}" placeholder="{{ __('admin.attributes.symbol_placeholder') }}">
                                                    @error('Symbol')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input @error('required') is-invalid @enderror"
                                                               type="checkbox" id="required" name="required" value="1"
                                                               {{ old('required', $attribute->required) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="required">
                                                            {{ __('admin.attributes.required') }}
                                                        </label>
                                                        @error('required')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Icon Selection -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('admin.attributes.icon') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <x-icon-picker
                                            nameIcon="icon_name"
                                            nameImage="icon"
                                            :valueIconName="old('icon_name', $attribute->icon && !\Illuminate\Support\Str::contains($attribute->icon, '/') ? $attribute->icon : '')"
                                            :currentImagePath="$attribute->icon && \Illuminate\Support\Str::contains($attribute->icon, '/') ? $attribute->icon : null"
                                            labelIcon="{{ __('admin.attributes.icon_name') }}"
                                            labelImage="{{ __('admin.attributes.icon_image') }}"
                                            imageHelpText="{{ __('admin.attributes.icon_help') }}"
                                            showCurrentImageLabel="{{ __('admin.attributes.current_image') }}"
                                            pickerTitle="{{ __('admin.attributes.picker_title') }}"
                                            pickerButtonText="{{ __('admin.attributes.picker_button') }}"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-arrow-left"></i> {{ __('admin.attributes.back') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('admin.actions.update') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- مكوّن الأيقونة المشترك يتولى تحميل جميع سكربتات الاختيار والمعاينة --}}
@endpush
