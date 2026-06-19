@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.products.edit') }}: {{ $product->title }}</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('admin.products.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.basic_info') }}</h6>
                            </div>
                            <div class="card-body">
                                @include('components.translations-repeater', [
                                    'locales' => $locales ?? config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'items' => $product->translations->map(function ($t) {
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
                                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
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
                                                    <option value="{{ $facility->id }}" {{ old('facility_id', $product->facility_id) == $facility->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $city->id }}" {{ old('city_id', $product->city_id) == $city->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $status->id }}" {{ old('status_id', $product->status ? $product->status->id : '') == $status->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $facility->owner->id }}" {{ old('owner_user_id', $product->owner_user_id) == $facility->owner->id ? 'selected' : '' }}>
                                                        {{ $facility->owner->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('owner_user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">{{ __('admin.products.address') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $product->address) }}" required>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.location') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">{{ __('admin.products.latitude') }}</label>
                                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $product->latitude) }}">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">{{ __('admin.products.longitude') }}</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $product->longitude) }}">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="google_maps_url" class="form-label">{{ __('admin.products.google_maps_url') }}</label>
                                    <input type="url" class="form-control @error('google_maps_url') is-invalid @enderror" id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url', $product->google_maps_url) }}">
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



                        <!-- Attributes -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.attributes') }}</h6>
                            </div>
                            <div class="card-body">
                                <div id="attributes-container">
                                    <p class="text-muted">{{ __('admin.products.select_category_first_attributes') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="card mt-4">
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

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Image Upload -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.main_image') }}</h6>
                            </div>
                            <div class="card-body">
                                @if($product->image)
                                    <div class="mb-3">
                                        <img src="{{ asset($product->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('admin.products.change_image') }}</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggles -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.settings') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">{{ __('admin.products.is_active') }}</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">{{ __('admin.products.is_featured') }}</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ $product->is_verified ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_verified">{{ __('admin.products.is_verified') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.products.contact_info') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">{{ __('admin.products.contact_phone') }}</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $product->contact_phone) }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">{{ __('admin.products.contact_email') }}</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $product->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ __('admin.products.save_changes') }}
                    </button>
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
    // Initialize any JavaScript functionality here
    
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

    // Load attributes and features on page load if category is selected
    let initialCategoryId = $('#category_id').val();
    if (initialCategoryId) {
        loadAttributesByCategory(initialCategoryId);
        loadFeaturesByCategory(initialCategoryId);
    }

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
                        let currentValue = getCurrentAttributeValue(attribute.id);
                        
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

    function getCurrentAttributeValue(attributeId) {
        // Get current value from product attributes or old input
        let currentValue = $('input[name="attributes[' + attributeId + '][value]"]').val();
        if (!currentValue) {
            // Try to get from product's existing attributes
            let productAttribute = @json($product->attributes->keyBy('id')->map(function($attr) { return $attr->pivot->value; }));
            currentValue = productAttribute[attributeId] || '';
        }
        return currentValue;
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
                        let isChecked = getCurrentFeatureValue(feature.id) ? 'checked' : '';
                        
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

    function getCurrentFeatureValue(featureId) {
        // Check if feature is currently selected for this product
        let productFeatures = @json($product->features->pluck('id')->toArray());
        let oldFeatures = @json(old('features', []));
        
        // Priority: old input values (in case of validation errors) > existing product features
        if (oldFeatures.length > 0) {
            return oldFeatures.includes(featureId.toString());
        }
        
        return productFeatures.includes(featureId);
    }
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
});
</script>
@endpush
