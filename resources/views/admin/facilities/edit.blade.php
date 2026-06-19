@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.facilities.edit') }} - {{ $facility->name }}</h5>
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('admin.facilities.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.facilities.basic_info') }}</h6>
                            </div>
                            <div class="card-body">
                                @include('components.translations-repeater', [
                                    'locales' => $locales ?? config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'items' => $facility->translations->map(function ($t) {
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
                                            'label' => __('admin.facilities.name'),
                                            'requiredFirst' => true,
                                        ],
                                        [
                                            'type' => 'textarea',
                                            'key' => 'description',
                                            'label' => __('admin.facilities.description'),
                                            'rows' => 4,
                                        ],
                                    ],
                                    'addLabel' => __('admin.ui.layout.add_new'),
                                    'removeLabel' => __('admin.actions.delete'),
                                    'minItems' => 1,
                                    'maxItems' => is_array($locales ?? null) ? count($locales) : null,
                                ])

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_user_id" class="form-label">{{ __('admin.facilities.owner') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('owner_user_id') is-invalid @enderror" id="owner_user_id" name="owner_user_id" required>
                                                <option value="">{{ __('admin.facilities.select_owner') }}</option>
                                                @foreach($owners as $owner)
                                                    <option value="{{ $owner->id }}" {{ old('owner_user_id', $facility->owner_user_id) == $owner->id ? 'selected' : '' }}>
                                                        {{ $owner->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('owner_user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facility_category_id" class="form-label">{{ __('admin.facilities.facility_category') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('facility_category_id') is-invalid @enderror" id="facility_category_id" name="facility_category_id" required>
                                                <option value="">{{ __('admin.facilities.select_facility_category') }}</option>
                                                @foreach($facilityCategories as $category)
                                                    <option value="{{ $category->id }}" {{ old('facility_category_id', $facility->facility_category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('facility_category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_id" class="form-label">{{ __('admin.facilities.status') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                                                <option value="">{{ __('admin.facilities.select_status') }}</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ old('status_id', $facility->status_id) == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.facilities.media') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">{{ __('admin.facilities.facility_logo') }}</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted d-block mt-2">{{ __('admin.facilities.logo_dimensions') }}</small>
                                    <div class="mt-2" id="logo-preview">
                                        @if($facility->logo)
                                            <img src="{{ asset($facility->logo) }}" alt="Current Logo" class="img-thumbnail" width="100">
                                            <small class="d-block text-muted mt-1">{{ __('admin.facilities.current_logo') }}</small>
                                        @endif
                                    </div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cover_image" class="form-label">{{ __('admin.facilities.cover_image') }}</label>
                                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                                    <small class="text-muted d-block mt-2">{{ __('admin.facilities.cover_dimensions') }}</small>
                                    <div class="mt-2" id="cover-preview">
                                        @if($facility->cover_image)
                                            <img src="{{ asset($facility->cover_image) }}" alt="Current Cover" class="img-thumbnail" width="200">
                                            <small class="d-block text-muted mt-1">{{ __('admin.facilities.current_cover') }}</small>
                                        @endif
                                    </div>
                                    @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.facilities.contact_info') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('admin.facilities.email') }} <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $facility->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">{{ __('admin.facilities.phone') }} <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $facility->phone_number) }}" required>
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="website" class="form-label">{{ __('admin.facilities.website') }}</label>
                                            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $facility->website) }}">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="whatsapp_number" class="form-label">{{ __('admin.facilities.whatsapp') }}</label>
                                            <input type="tel" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $facility->whatsapp_number) }}">
                                            @error('whatsapp_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">{{ __('admin.facilities.address') }} <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', $facility->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="working_hours" class="form-label">{{ __('admin.facilities.working_hours') }}</label>
                                            <textarea class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" rows="2">{{ old('working_hours', $facility->working_hours) }}</textarea>
                                            @error('working_hours')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.facilities.location') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">{{ __('admin.facilities.latitude') }}</label>
                                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $facility->latitude) }}">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">{{ __('admin.facilities.longitude') }}</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $facility->longitude) }}">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="google_maps_url" class="form-label">{{ __('admin.facilities.google_maps_url') }}</label>
                                            <input type="url" class="form-control @error('google_maps_url') is-invalid @enderror" id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url', $facility->google_maps_url) }}">
                                            @error('google_maps_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.facilities.social_media') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facebook" class="form-label">{{ __('admin.facilities.facebook') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $facility->facebook) }}">
                                            </div>
                                            @error('facebook')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="twitter" class="form-label">{{ __('admin.facilities.twitter') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter', $facility->twitter) }}">
                                            </div>
                                            @error('twitter')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="instagram" class="form-label">{{ __('admin.facilities.instagram') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('facebook', $facility->instagram) }}">
                                            </div>
                                            @error('instagram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="linkedin" class="form-label">{{ __('admin.facilities.linkedin') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                                <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ old('linkedin', $facility->linkedin) }}">
                                            </div>
                                            @error('linkedin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="youtube" class="form-label">{{ __('admin.facilities.youtube') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                                <input type="url" class="form-control @error('youtube') is-invalid @enderror" id="youtube" name="youtube" value="{{ old('youtube', $facility->youtube) }}">
                                            </div>
                                            @error('youtube')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="snapchat" class="form-label">{{ __('admin.facilities.snapchat') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-snapchat"></i></span>
                                                <input type="text" class="form-control @error('snapchat') is-invalid @enderror" id="snapchat" name="snapchat" value="{{ old('snapchat', $facility->snapchat) }}">
                                            </div>
                                            @error('snapchat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.facilities.settings') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $facility->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">{{ __('admin.facilities.is_active') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" {{ old('is_verified', $facility->is_verified) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">{{ __('admin.facilities.is_verified') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $facility->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">{{ __('admin.facilities.is_featured') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('admin.facilities.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
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

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Preview logo
    $('#logo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logo-preview').html(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" width="100" alt="New Logo Preview">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="clearFileInput('logo')">
                            <i class="fas fa-times"></i>
                        </button>
                        <small class="d-block text-muted mt-1">الشعار الجديد</small>
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview cover image
    $('#cover_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#cover-preview').html(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" width="200" alt="New Cover Preview">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="clearFileInput('cover_image')">
                            <i class="fas fa-times"></i>
                        </button>
                        <small class="d-block text-muted mt-1">صورة الغلاف الجديدة</small>
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        }
    });

    // Clear file input function
    window.clearFileInput = function(inputId) {
        $(`#${inputId}`).val('');
        // Restore original content if it exists
        if (inputId === 'logo' && '{{ $facility->logo }}') {
            $(`#${inputId}-preview`).html(`
                <img src="{{ asset($facility->logo) }}" alt="Current Logo" class="img-thumbnail" width="100">
                <small class="d-block text-muted mt-1">الشعار الحالي</small>
            `);
        } else if (inputId === 'cover_image' && '{{ $facility->cover_image }}') {
            $(`#${inputId}-preview`).html(`
                <img src="{{ asset($facility->cover_image) }}" alt="Current Cover" class="img-thumbnail" width="200">
                <small class="d-block text-muted mt-1">صورة الغلاف الحالية</small>
            `);
        } else {
            $(`#${inputId}-preview`).html('');
        }
    };
});
</script>
@endpush
