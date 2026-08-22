@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" data-intro="{{ __('admin.tour.products_create_header_desc') }}" data-step="62">
            <h5 class="mb-0">{{ __('admin.products.create') }}</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('admin.products.back') }}
            </a>
        </div>
        <div class="card-body">

            <!-- Progress Stepper -->
            <div class="card mb-4 sticky-top" style="top: 10px; z-index: 1020;">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2" id="form-stepper">
                        <button type="button" class="btn btn-sm btn-primary step-btn" data-target="section-basic">
                            <span class="badge bg-white text-primary rounded-pill me-1 step-number">1</span>
                            {{ __('admin.products.basic_info') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary step-btn" data-target="section-media">
                            <span class="badge bg-light text-primary rounded-pill me-1 step-number">2</span>
                            {{ __('admin.products.media') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary step-btn" data-target="section-features">
                            <span class="badge bg-light text-primary rounded-pill me-1 step-number">3</span>
                            {{ __('admin.products.features') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary step-btn" data-target="section-attributes">
                            <span class="badge bg-light text-primary rounded-pill me-1 step-number">4</span>
                            {{ __('admin.products.attributes') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary step-btn" data-target="section-location">
                            <span class="badge bg-light text-primary rounded-pill me-1 step-number">5</span>
                            {{ __('admin.products.location') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary step-btn" data-target="section-settings">
                            <span class="badge bg-light text-primary rounded-pill me-1 step-number">6</span>
                            {{ __('admin.products.settings') }}
                        </button>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-8" id="section-basic">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.basic_info') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <button type="button" id="ai-generate-description" class="btn btn-sm btn-info">
                                        <i class="fas fa-magic me-2"></i>توليد الوصف بالذكاء الاصطناعي
                                    </button>
                                    <small class="text-muted d-block mt-1">املأ الاسم والفئة والموقع أولاً للحصول على وصف أفضل.</small>
                                </div>

                                @include('components.translations-repeater', [
                                    'locales' => $locales ?? config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'fields' => [
                                        [
                                            'type' => 'input',
                                            'key' => 'name',
                                            'label' => __('admin.products.name'),
                                            'requiredFirst' => true,
                                        ],
                                        [
                                            'type' => 'textarea',
                                            'key' => 'description',
                                            'label' => __('admin.products.description'),
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
                                            <label for="price" class="form-label">{{ __('admin.products.price') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                                <span class="input-group-text">{!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</span>
                                            </div>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facility_id" class="form-label">{{ __('admin.products.facility') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('facility_id') is-invalid @enderror" id="facility_id" name="facility_id" required>
                                                <option value="">{{ __('admin.products.select_facility') }}</option>
                                                @foreach($facilities as $facility)
                                                    <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                                        {{ $facility->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('facility_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">{{ __('admin.products.category') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                                <option value="">{{ __('admin.products.select_category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->getTranslatedName('ar') }}
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
                                            <label for="city_id" class="form-label">{{ __('admin.products.city') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                                                <option value="">{{ __('admin.products.select_city') }}</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->localized_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_id" class="form-label">{{ __('admin.products.status') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                                                <option value="">{{ __('admin.products.select_status') }}</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_user_id" class="form-label">{{ __('admin.products.owner') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('owner_user_id') is-invalid @enderror" id="owner_user_id" name="owner_user_id" required>
                                                <option value="">{{ __('admin.products.select_owner') }}</option>
                                                @foreach($facilities as $facility)
                                                    <option value="{{ $facility->owner->id }}" {{ old('owner_user_id') == $facility->owner->id ? 'selected' : '' }}>
                                                        {{ $facility->owner->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('owner_user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="col-md-4" id="section-media">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.media') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="main_image" class="form-label">{{ __('admin.products.main_image') }}</label>
                                    <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image" accept="image/*">
                                    <small class="text-muted d-block mt-2">{{ __('admin.products.main_image_dimensions') }}</small>
                                    <div class="mt-2" id="main-image-preview"></div>
                                    @error('main_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="col-md-6" id="section-features">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.features') }}</h6>
                            </div>
                            <div class="card-body">
                                <div id="features-container">
                                    <p class="text-muted">{{ __('admin.products.select_category_first_features') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attributes -->
                    <div class="col-md-6" id="section-attributes">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.attributes') }}</h6>
                            </div>
                            <div class="card-body">
                                <div id="attributes-container">
                                    <p class="text-muted">{{ __('admin.products.select_category_first_attributes') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Location -->
                    <div class="col-md-6" id="section-location">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.location') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('admin.products.address') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">{{ __('admin.products.latitude') }}</label>
                                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">{{ __('admin.products.longitude') }}</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="google_maps_url" class="form-label">{{ __('admin.products.google_maps_url') }}</label>
                                    <input type="url" class="form-control @error('google_maps_url') is-invalid @enderror" id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url') }}">
                                    @error('google_maps_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">{{ __('admin.products.select_location_on_map') }}</label>
                                    <small class="text-muted">{{ __('admin.products.map_help') }}</small>
                                </div>
                                <div id="mapPicker" class="w-100" style="height: 350px; border-radius: .5rem; overflow: hidden; background: #eef2ff; direction: ltr;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="col-md-6" id="section-settings">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.settings') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">{{ __('admin.products.is_active') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">{{ __('admin.products.is_verified') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">{{ __('admin.products.is_featured') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="available_for_rent" name="available_for_rent" value="1" {{ old('available_for_rent') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_for_rent">{{ __('admin.products.available_for_rent') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="available_for_sale" name="available_for_sale" value="1" {{ old('available_for_sale') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_for_sale">{{ __('admin.products.available_for_sale') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('admin.products.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

    // Preview main image
    $('#main_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#main-image-preview').html(`<img src="${e.target.result}" class="img-thumbnail" width="200">`);
            }
            reader.readAsDataURL(file);
        }
    });

    // Update owner based on facility
    $('#facility_id').change(function() {
        let facilityId = $(this).val();
        if (facilityId) {
            let option = $(this).find(`option[value="${facilityId}"]`);
            let ownerId = option.data('owner-id');
            $('#owner_user_id').val(ownerId).trigger('change');
        }
    });

    // Load attributes and features based on selected category
    $('#category_id').change(function() {
        let categoryId = $(this).val();
        if (categoryId) {
            loadAttributesByCategory(categoryId);
            loadFeaturesByCategory(categoryId);
        } else {
            $('#attributes-container').html('<p class="text-muted">اختر فئة أولاً لعرض الخصائص المتاحة</p>');
            $('#features-container').html('<p class="text-muted">اختر فئة أولاً لعرض المميزات المتاحة</p>');
        }
    });

    function loadAttributesByCategory(categoryId) {
        $.ajax({
            url: '/api/v1/attributes/by-category',
            method: 'GET',
            data: { 
                category_id: categoryId,
                locale: '{{ app()->getLocale() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let attributesHtml = '<div class="row">';
                    response.data.forEach(function(attribute) {
                        let requiredMark = attribute.required ? ' <span class="text-danger">*</span>' : '';
                        let iconHtml = attribute.icon ? `<img src="${attribute.icon}" alt="icon" width="20" class="me-1">` : '';
                        let currentValue = getOldAttributeValue(attribute.id);

                        if (attribute.type === 'file') {
                            attributesHtml += `
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="attribute_${attribute.id}" class="form-label">
                                            ${iconHtml}${attribute.name}${requiredMark}
                                        </label>
                                        <input type="file" class="form-control"
                                               id="attribute_${attribute.id}"
                                               name="attributes[${attribute.id}][value]"
                                               ${attribute.required ? 'required' : ''}>
                                        <input type="hidden" name="attributes[${attribute.id}][attribute_id]" value="${attribute.id}">
                                    </div>
                                </div>
                            `;
                        } else {
                            attributesHtml += `
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="attribute_${attribute.id}" class="form-label">
                                            ${iconHtml}${attribute.name}${requiredMark}
                                        </label>
                                        <input type="text" class="form-control"
                                               id="attribute_${attribute.id}"
                                               name="attributes[${attribute.id}][value]"
                                               value="${currentValue}"
                                               ${attribute.required ? 'required' : ''}>
                                        <input type="hidden" name="attributes[${attribute.id}][attribute_id]" value="${attribute.id}">
                                    </div>
                                </div>
                            `;
                        }
                    });
                    attributesHtml += '</div>';
                    $('#attributes-container').html(attributesHtml);
                } else {
                    $('#attributes-container').html('<p class="text-muted">لا توجد خصائص متاحة لهذه الفئة</p>');
                }
            },
            error: function() {
                $('#attributes-container').html('<p class="text-danger">حدث خطأ في تحميل الخصائص</p>');
            }
        });
    }

    function getOldAttributeValue(attributeId) {
        // Get old input value if exists
        let oldValue = $('input[name="attributes[' + attributeId + '][value]"]').val();
        return oldValue || '';
    }

    function loadFeaturesByCategory(categoryId) {
        $.ajax({
            url: '/api/v1/features/by-category',
            method: 'GET',
            data: { 
                category_id: categoryId,
                locale: '{{ app()->getLocale() }}'
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let featuresHtml = '<div class="row">';
                    response.data.forEach(function(feature) {
                        let iconHtml = feature.icon ? `<img src="${feature.icon}" alt="icon" width="20" class="me-1">` : '';
                        let isChecked = getOldFeatureValue(feature.id) ? 'checked' : '';
                        
                        featuresHtml += `
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" 
                                           id="feature_${feature.id}" 
                                           name="features[]" 
                                           value="${feature.id}" ${isChecked}>
                                    <label class="form-check-label" for="feature_${feature.id}">
                                        ${iconHtml}${feature.name}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    featuresHtml += '</div>';
                    $('#features-container').html(featuresHtml);
                } else {
                    $('#features-container').html('<p class="text-muted">لا توجد مميزات متاحة لهذه الفئة</p>');
                }
            },
            error: function() {
                $('#features-container').html('<p class="text-danger">حدث خطأ في تحميل المميزات</p>');
            }
        });
    }

    function getOldFeatureValue(featureId) {
        // Check if feature was previously selected (for form validation errors)
        let oldFeatures = @json(old('features', []));
        return oldFeatures.includes(featureId.toString());
    }

    // Leaflet Map Picker
    (function initMapPicker() {
        const mapEl = document.getElementById('mapPicker');
        if (!mapEl || typeof L === 'undefined') return;

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const defaultLat = 24.7136; // Riyadh
        const defaultLng = 46.6753;

        const initialLat = latInput.value ? parseFloat(latInput.value) : defaultLat;
        const initialLng = lngInput.value ? parseFloat(lngInput.value) : defaultLng;
        const initialZoom = (latInput.value && lngInput.value) ? 14 : 11;

        const map = L.map('mapPicker', { scrollWheelZoom: true }).setView([initialLat, initialLng], initialZoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        function setInputs(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            setInputs(lat, lng);
        });

        marker.on('dragend', function(e) {
            const { lat, lng } = e.target.getLatLng();
            setInputs(lat, lng);
        });

        latInput.addEventListener('change', function() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value || defaultLng);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], map.getZoom());
            }
        });
        lngInput.addEventListener('change', function() {
            const lat = parseFloat(latInput.value || defaultLat);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], map.getZoom());
            }
        });
    })();

    // AI description assistant
    (function initAiDescription() {
        const aiBtn = document.getElementById('ai-generate-description');
        const descriptionTextarea = document.querySelector('textarea[name^="translations"][name$="[description]"]');
        if (!aiBtn || !descriptionTextarea) return;

        aiBtn.addEventListener('click', async function() {
            const titleInput = document.querySelector('input[name^="translations"][name$="[name]"]');
            const title = titleInput ? titleInput.value : '';
            const categorySelect = document.getElementById('category_id');
            const category = categorySelect?.options[categorySelect.selectedIndex]?.textContent?.trim() || '';
            const citySelect = document.getElementById('city_id');
            const city = citySelect?.options[citySelect.selectedIndex]?.textContent?.trim() || '';
            const address = document.getElementById('address')?.value || '';

            const attributes = [];
            document.querySelectorAll('#attributes-container input, #attributes-container select, #attributes-container textarea').forEach(input => {
                if (input.type === 'hidden' || input.type === 'file') return;
                if (input.type === 'checkbox' && !input.checked) return;
                const label = input.parentElement?.querySelector('label')?.textContent?.trim() || input.name;
                attributes.push({ name: label, value: input.value });
            });

            const features = [];
            document.querySelectorAll('#features-container input[type="checkbox"]:checked').forEach(cb => {
                const label = cb.parentElement?.querySelector('label')?.textContent?.trim() || cb.value;
                features.push(label);
            });

            aiBtn.disabled = true;
            const originalText = aiBtn.innerHTML;
            aiBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التوليد...';

            try {
                const response = await fetch('/api/v1/products/generate-description', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ title, category, city, address, attributes, features })
                });
                const data = await response.json();
                if (data.success) {
                    descriptionTextarea.value = data.description;
                } else {
                    alert(data.message || 'حدث خطأ أثناء توليد الوصف.');
                }
            } catch (e) {
                alert('حدث خطأ في الاتصال بالذكاء الاصطناعي.');
            } finally {
                aiBtn.disabled = false;
                aiBtn.innerHTML = originalText;
            }
        });
    })();

    // Section stepper navigation
    (function initStepper() {
        const stepper = document.getElementById('form-stepper');
        if (!stepper) return;

        const stepButtons = stepper.querySelectorAll('.step-btn');
        const sectionIds = ['section-basic', 'section-media', 'section-features', 'section-attributes', 'section-location', 'section-settings'];

        stepButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        const setActiveStep = (activeTarget) => {
            stepButtons.forEach(btn => {
                const isActive = btn.dataset.target === activeTarget;
                const badge = btn.querySelector('.step-number');
                btn.classList.toggle('btn-primary', isActive);
                btn.classList.toggle('btn-outline-primary', !isActive);
                if (badge) {
                    badge.classList.toggle('bg-white', isActive);
                    badge.classList.toggle('text-primary', isActive);
                    badge.classList.toggle('bg-light', !isActive);
                }
            });
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setActiveStep(entry.target.id);
                }
            });
        }, { threshold: 0.15 });

        sectionIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });
    })();
});
</script>
@endpush
