@extends('layouts.app')

@section('title', __('facility.products.edit.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.products.edit.title') }}</h4>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.basic_info') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-input 
                                        name="name"
                                        :label="__('facility.products.edit.name')"
                                        :value="$product->name"
                                        required="true"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-select 
                                        name="category_id"
                                        :label="__('facility.products.edit.category')"
                                        :options="$categoryOptions"
                                        :selected="$product->category_id"
                                        :placeholder="__('facility.products.edit.select_category')"
                                        required="true"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-select 
                                        name="city_id"
                                        :label="__('facility.products.edit.city')"
                                        :options="$cities->pluck('localized_name', 'id')->toArray()"
                                        :selected="$product->city_id"
                                        :placeholder="__('facility.products.edit.select_city')"
                                        required="true"
                                    />
                                </div>
                            </div>

                            <div class="mb-4">
                                <x-form-textarea 
                                    name="description"
                                    :label="__('facility.products.edit.description')"
                                    :value="$product->description"
                                    rows="4"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="price"
                                        :label="__('facility.products.edit.price')"
                                        :value="$product->price"
                                        step="0.01"
                                        min="0"
                                        required="true"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-select 
                                        name="status_id"
                                        :label="__('facility.products.edit.status')"
                                        :options="$statuses->pluck('name', 'id')->toArray()"
                                        :selected="$product->status ? $product->status->id : ''"
                                        :placeholder="__('facility.products.edit.select_status')"
                                        required="true"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.location_info') }}
                            </h5>
                            
                            <div class="mb-4">
                                <x-form-input 
                                    name="address"
                                    :label="__('facility.products.edit.address')"
                                    :value="$product->address"
                                    required="true"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="latitude"
                                        :label="__('facility.products.edit.latitude')"
                                        :value="$product->latitude"
                                        step="any"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-input 
                                        type="number"
                                        name="longitude"
                                        :label="__('facility.products.edit.longitude')"
                                        :value="$product->longitude"
                                        step="any"
                                    />
                                </div>
                                
                                <div>
                                    <x-form-input 
                                        type="url"
                                        name="google_maps_url"
                                        :label="__('facility.products.edit.google_maps')"
                                        :value="$product->google_maps_url"
                                    />
                                </div>
                            </div>
                        </div>



                        <!-- Media -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.media') }}
                            </h5>
                            
                            <div class="mb-4">
                                <x-form-file 
                                    name="main_image"
                                    :label="__('facility.products.edit.main_image')"
                                    accept="image/*"
                                />
                                @if($product->main_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="Current image" class="w-32 h-32 object-cover rounded">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.features') }}
                            </h5>
                            
                            <div id="features-container">
                                <p class="text-gray-500 text-sm">{{ __('facility.products.edit.select_category_for_features') }}</p>
                            </div>
                        </div>

                        <!-- Attributes -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.attributes') }}
                            </h5>
                            
                            <div id="attributes-container">
                                <p class="text-gray-500 text-sm">{{ __('facility.products.edit.select_category_for_attributes') }}</p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 rtl:space-x-reverse">
                            <a href="{{ route('facility.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                {{ __('facility.products.edit.cancel') }}
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('facility.products.edit.update') }}
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
    // Store old input values from Laravel session and product attributes
    const oldInputs = @json(old('attributes', []));
    const productAttributes = @json($product->attributes->keyBy('id')->map(function($attr) { return $attr->pivot->value; }));
    
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
                    '<p class="text-gray-500 text-sm">{{ __("facility.products.edit.select_category_for_attributes") }}</p>';
                document.getElementById('features-container').innerHTML = 
                    '<p class="text-gray-500 text-sm">{{ __("facility.products.edit.select_category_for_features") }}</p>';
            }
        });

        // Load attributes and features on page load if category is selected
        const initialCategoryId = categorySelect.value;
        if (initialCategoryId) {
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
                        const currentValue = getCurrentAttributeValue(attribute.id);
                        
                        attributesHtml += `
                            <div>
                                <label for="attribute_${attribute.id}" class="block text-sm font-medium text-gray-700 mb-2">
                                    ${iconHtml}${attribute.name}${requiredMark}
                                </label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="attribute_${attribute.id}" 
                                       name="attributes[${attribute.id}][value]" 
                                       value="${currentValue}"
                                       ${attribute.required ? 'required' : ''}>
                                <input type="hidden" name="attributes[${attribute.id}][attribute_id]" value="${attribute.id}">
                            </div>
                        `;
                    });
                    attributesHtml += '</div>';
                    document.getElementById('attributes-container').innerHTML = attributesHtml;
                } else {
                    document.getElementById('attributes-container').innerHTML = 
                        '<p class="text-gray-500 text-sm">{{ __("facility.products.edit.no_attributes_available") }}</p>';
                }
            })
            .catch(error => {
                document.getElementById('attributes-container').innerHTML = 
                    '<p class="text-red-500 text-sm">{{ __("facility.products.edit.error_loading_attributes") }}</p>';
            });
    }

    function getCurrentAttributeValue(attributeId) {
        // First try to get from old inputs (Laravel session) - highest priority
        if (oldInputs[attributeId] && oldInputs[attributeId].value) {
            return oldInputs[attributeId].value;
        }
        
        // Then try to get from existing input element
        const input = document.querySelector(`input[name="attributes[${attributeId}][value]"]`);
        if (input && input.value) {
            return input.value;
        }
        
        // Finally, try to get from product's existing attributes
        return productAttributes[attributeId] || '';
    }

    function loadFeaturesByCategory(categoryId) {
        fetch(`/api/v1/features/by-category?category_id=${categoryId}&locale={{ app()->getLocale() }}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let featuresHtml = '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
                    data.data.forEach(function(feature) {
                        const iconHtml = feature.icon ? `<img src="${feature.icon}" alt="icon" width="20" class="inline mr-2">` : '';
                        const isChecked = getCurrentFeatureValue(feature.id) ? 'checked' : '';
                        
                        featuresHtml += `
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="feature_${feature.id}" name="features[]" value="${feature.id}" ${isChecked}>
                                <label class="form-check-label" for="feature_${feature.id}">
                                    ${iconHtml}${feature.name}
                                </label>
                            </div>
                        `;
                    });
                    featuresHtml += '</div>';
                    document.getElementById('features-container').innerHTML = featuresHtml;
                } else {
                    document.getElementById('features-container').innerHTML = 
                        '<p class="text-gray-500 text-sm">{{ __("facility.products.edit.no_features_available") }}</p>';
                }
            })
            .catch(error => {
                document.getElementById('features-container').innerHTML = 
                    '<p class="text-red-500 text-sm">{{ __("facility.products.edit.error_loading_features") }}</p>';
            });
    }

    function getCurrentFeatureValue(featureId) {
        // Check if feature is currently selected for this product
        const productFeatures = @json($product->features->pluck('id')->toArray());
        const oldFeatures = @json(old('features', []));
        
        // Priority: old input values (in case of validation errors) > existing product features
        if (oldFeatures.length > 0) {
            return oldFeatures.includes(featureId.toString());
        }
        
        return productFeatures.includes(featureId);
    }
});
</script>
@endpush
