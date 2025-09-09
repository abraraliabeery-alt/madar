@extends('layouts.app')

@section('title', __('facility.products.create.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.products.create.title') }}</h4>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.basic_info') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-input 
                                        name="name"
                                        :label="__('facility.products.create.name')"
                                        required="true"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-select 
                                        name="category_id"
                                        :label="__('facility.products.create.category')"
                                        :options="$categoryOptions"
                                        :placeholder="__('facility.products.create.select_category')"
                                        required="true"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-select 
                                        name="city_id"
                                        :label="__('facility.products.create.city')"
                                        :options="$cities->pluck('localized_name', 'id')->toArray()"
                                        :placeholder="__('facility.products.create.select_city')"
                                        required="true"
                                    />
                                </div>
                            </div>

                            <div class="mb-4">
                                <x-form-textarea 
                                    name="description"
                                    :label="__('facility.products.create.description')"
                                    rows="4"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="price"
                                        :label="__('facility.products.create.price')"
                                        step="0.01"
                                        min="0"
                                        required="true"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-select 
                                        name="status_id"
                                        :label="__('facility.products.create.status')"
                                        :options="$statuses->pluck('name', 'id')->toArray()"
                                        :placeholder="__('facility.products.create.select_status')"
                                        required="true"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.location_info') }}
                            </h5>
                            
                            <div class="mb-4">
                                <x-form-input 
                                    name="address"
                                    :label="__('facility.products.create.address')"
                                    required="true"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="latitude"
                                        :label="__('facility.products.create.latitude')"
                                        step="any"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="longitude"
                                        :label="__('facility.products.create.longitude')"
                                        step="any"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-input 
                                        type="url"
                                        name="google_maps_url"
                                        :label="__('facility.products.create.google_maps')"
                                    />
                                </div>
                            </div>
                        </div>



                        <!-- Image Upload -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.media') }}
                            </h5>
                            
                            <div class="mb-4">
                                <x-form-file 
                                    name="main_image"
                                    :label="__('facility.products.create.main_image')"
                                    accept="image/*"
                                    :helpText="__('facility.form.image_help')"
                                />
                            </div>
                        </div>

                        <!-- Features & Options -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.features_options') }}
                            </h5>
                            
                            <!-- Boolean Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="furnished" name="furnished" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('furnished') ? 'checked' : '' }}>
                                    <label for="furnished" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.furnished') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="available_for_rent" name="available_for_rent" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('available_for_rent') ? 'checked' : '' }}>
                                    <label for="available_for_rent" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.for_rent') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="available_for_sale" name="available_for_sale" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('available_for_sale') ? 'checked' : '' }}>
                                    <label for="available_for_sale" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.for_sale') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.featured') }}
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Features Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('facility.products.create.features') }}</label>
                                <div id="features-container">
                                    <p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_features') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Attributes -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.attributes') }}
                            </h5>
                            
                            <div id="attributes-container">
                                <p class="text-gray-500 text-sm">{{ __('facility.products.create.select_category_for_attributes') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('facility.products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 text-center">
                                {{ __('facility.form.cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                                {{ __('facility.products.create.create_product') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store old input values from Laravel session
    const oldInputs = @json(old('attributes', []));
    const oldCategoryId = @json(old('category_id', ''));
    
    // Load attributes based on selected category
    const categorySelect = document.getElementById('category_id');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                loadAttributesByCategory(categoryId);
                loadFeaturesByCategory(categoryId);
            } else {
                document.getElementById('attributes-container').innerHTML = 
                    '<p class="text-gray-500 text-sm">{{ __("facility.products.create.select_category_for_attributes") }}</p>';
                document.getElementById('features-container').innerHTML = 
                    '<p class="text-gray-500 text-sm">{{ __("facility.products.create.select_category_for_features") }}</p>';
            }
        });
        
        // Load attributes and features on page load if category is selected and there are old inputs
        const initialCategoryId = categorySelect.value;
        if (initialCategoryId && Object.keys(oldInputs).length > 0) {
            loadAttributesByCategory(initialCategoryId);
            loadFeaturesByCategory(initialCategoryId);
        }
    }

    function loadAttributesByCategory(categoryId) {
        fetch(`/api/v1/attributes/by-category?category_id=${categoryId}&locale={{ app()->getLocale() }}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let attributesHtml = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
                    data.data.forEach(function(attribute) {
                        const requiredMark = attribute.required ? ' <span class="text-red-500">*</span>' : '';
                        const iconHtml = attribute.icon ? `<img src="${attribute.icon}" alt="icon" width="20" class="inline mr-1">` : '';
                        const oldValue = getOldAttributeValue(attribute.id);
                        
                        attributesHtml += `
                            <div>
                                <label for="attribute_${attribute.id}" class="block text-sm font-medium text-gray-700 mb-2">
                                    ${iconHtml}${attribute.name}${requiredMark}
                                </label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="attribute_${attribute.id}" 
                                       name="attributes[${attribute.id}][value]" 
                                       value="${oldValue}"
                                       ${attribute.required ? 'required' : ''}>
                                <input type="hidden" name="attributes[${attribute.id}][attribute_id]" value="${attribute.id}">
                            </div>
                        `;
                    });
                    attributesHtml += '</div>';
                    document.getElementById('attributes-container').innerHTML = attributesHtml;
                } else {
                    document.getElementById('attributes-container').innerHTML = 
                        '<p class="text-gray-500 text-sm">{{ __("facility.products.create.no_attributes_available") }}</p>';
                }
            })
            .catch(error => {
                document.getElementById('attributes-container').innerHTML = 
                    '<p class="text-red-500 text-sm">{{ __("facility.products.create.error_loading_attributes") }}</p>';
            });
    }

    function getOldAttributeValue(attributeId) {
        // First try to get from old inputs (Laravel session)
        if (oldInputs[attributeId] && oldInputs[attributeId].value) {
            return oldInputs[attributeId].value;
        }
        
        // Fallback to existing input element (if any)
        const input = document.querySelector(`input[name="attributes[${attributeId}][value]"]`);
        return input ? input.value : '';
    }

    function loadFeaturesByCategory(categoryId) {
        fetch(`/api/v1/features/by-category?category_id=${categoryId}&locale={{ app()->getLocale() }}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let featuresHtml = '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">';
                    data.data.forEach(function(feature) {
                        const iconHtml = feature.icon ? `<img src="${feature.icon}" alt="icon" width="20" class="inline mr-2">` : '';
                        const isChecked = getOldFeatureValue(feature.id) ? 'checked' : '';
                        
                        featuresHtml += `
                            <div class="flex items-center">
                                <input type="checkbox" id="feature_${feature.id}" name="features[]" value="${feature.id}" 
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" ${isChecked}>
                                <label for="feature_${feature.id}" class="ml-2 text-sm text-gray-700">
                                    ${iconHtml}${feature.name}
                                </label>
                            </div>
                        `;
                    });
                    featuresHtml += '</div>';
                    document.getElementById('features-container').innerHTML = featuresHtml;
                } else {
                    document.getElementById('features-container').innerHTML = 
                        '<p class="text-gray-500 text-sm">{{ __("facility.products.create.no_features_available") }}</p>';
                }
            })
            .catch(error => {
                document.getElementById('features-container').innerHTML = 
                    '<p class="text-red-500 text-sm">{{ __("facility.products.create.error_loading_features") }}</p>';
            });
    }

    function getOldFeatureValue(featureId) {
        // Check if feature was previously selected (for form validation errors)
        const oldFeatures = @json(old('features', []));
        return oldFeatures.includes(featureId.toString());
    }
});
</script>
@endpush
