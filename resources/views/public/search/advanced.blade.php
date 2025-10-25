@extends('layouts.app')

@section('title', __('public.advanced_search.title'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('public.advanced_search.title') }}</h1>
        
        <!-- Advanced Search Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <!-- Search Type Selection -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.search.search_type') }}</h3>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="search_type" value="products" 
                               {{ request('search_type', 'products') == 'products' ? 'checked' : '' }}
                               class="mr-2 text-blue-600 focus:ring-blue-500" onchange="updateFormAction()">
                        <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.products') }}</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="search_type" value="facilities" 
                               {{ request('search_type') == 'facilities' ? 'checked' : '' }}
                               class="mr-2 text-blue-600 focus:ring-blue-500" onchange="updateFormAction()">
                        <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.facilities') }}</span>
                    </label>
                </div>
            </div>
            
            <form id="advancedSearchForm" method="GET">
                <!-- Basic Search -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.basic_search') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="q" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.search_term') }}</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('public.search.enter_search_term') }}">
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.common.category') }}</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    id="category_id" name="category_id">
                                <option value="">{{ __('public.search.all_categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->getTranslatedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.price_range') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.minimum_price') }}</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('public.search.minimum_price') }}">
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.maximum_price') }}</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('public.search.maximum_price') }}">
                        </div>
                    </div>
                </div>

                <!-- Property Details (Only for Products) -->
                <div class="mb-8" id="propertyDetails" style="display: {{ request('search_type', 'products') == 'products' ? 'block' : 'none' }};">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.property_details') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.bedrooms') }}</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    id="bedrooms" name="bedrooms">
                                <option value="">{{ __('public.advanced_search.any') }}</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.bathrooms') }}</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    id="bathrooms" name="bathrooms">
                                <option value="">{{ __('public.advanced_search.any') }}</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="min_area" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.min_area') }}</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   id="min_area" name="min_area" value="{{ request('min_area') }}" placeholder="{{ __('public.advanced_search.min_area_placeholder') }}">
                        </div>
                        <div>
                            <label for="max_area" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.max_area') }}</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   id="max_area" name="max_area" value="{{ request('max_area') }}" placeholder="{{ __('public.advanced_search.max_area_placeholder') }}">
                        </div>
                    </div>
                </div>

                <!-- Property Type (Only for Products) -->
                <div class="mb-8" id="propertyType" style="display: {{ request('search_type', 'products') == 'products' ? 'block' : 'none' }};">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.property_type') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="flex items-center">
                                <input type="radio" name="property_type" value="sale" 
                                       {{ request('property_type') == 'sale' ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.for_sale') }}</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="radio" name="property_type" value="rent" 
                                       {{ request('property_type') == 'rent' ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.for_rent') }}</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="radio" name="property_type" value="" 
                                       {{ !request('property_type') ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.both') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Facility Status (Only for Facilities) -->
                <div class="mb-8" id="facilityStatus" style="display: {{ request('search_type') == 'facilities' ? 'block' : 'none' }};">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.facility_status') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="verified" value="1" 
                                       {{ request('verified') == '1' ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.verified_only') }}</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="featured" value="1" 
                                       {{ request('featured') == '1' ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.featured_only') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                @if($features->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.features') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($features as $feature)
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="{{ $feature->id }}" 
                                   {{ in_array($feature->id, (array) request('features', [])) ? 'checked' : '' }}
                                   class="mr-2 text-blue-600 focus:ring-blue-500 rounded">
                            <span class="text-sm text-gray-700">{{ $feature->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Location -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.location') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.address_or_area') }}</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   id="address" name="address" value="{{ request('address') }}" placeholder="{{ __('public.advanced_search.enter_address_area') }}">
                        </div>
                        <div>
                            <label for="radius" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.search_radius') }}</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    id="radius" name="radius">
                                <option value="1" {{ request('radius') == '1' ? 'selected' : '' }}>1 km</option>
                                <option value="5" {{ request('radius') == '5' ? 'selected' : '' }}>5 km</option>
                                <option value="10" {{ request('radius') == '10' ? 'selected' : '' }}>10 km</option>
                                <option value="25" {{ request('radius') == '25' ? 'selected' : '' }}>25 km</option>
                                <option value="50" {{ request('radius') == '50' ? 'selected' : '' }}>50 km</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.advanced_search.sort_results') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.sort_by') }}</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    id="sort" name="sort">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('public.advanced_search.latest') }}</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('public.advanced_search.price_low_high') }}</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('public.advanced_search.price_high_low') }}</option>
                                <option value="area_low" {{ request('sort') == 'area_low' ? 'selected' : '' }}>{{ __('public.advanced_search.area_small_large') }}</option>
                                <option value="area_high" {{ request('sort') == 'area_high' ? 'selected' : '' }}>{{ __('public.advanced_search.area_large_small') }}</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('public.advanced_search.oldest') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-search mr-2"></i> {{ __('public.search.title') }}
                    </button>
                    <button type="button" onclick="resetForm()" class="inline-flex items-center px-8 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-undo mr-2"></i> {{ __('public.advanced_search.reset') }}
                    </button>
                    <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.map') }}" class="inline-flex items-center px-8 py-3 bg-cyan-200 text-cyan-800 font-medium rounded-md hover:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-map mr-2"></i> {{ __('public.search.map_search') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('advancedSearchForm').reset();
    updateFormAction();
}

function updateFormAction() {
    const form = document.getElementById('advancedSearchForm');
    const searchType = document.querySelector('input[name="search_type"]:checked').value;
    const propertyDetails = document.getElementById('propertyDetails');
    const propertyType = document.getElementById('propertyType');
    const facilityStatus = document.getElementById('facilityStatus');
    
    if (searchType === 'products') {
        form.action = '{{ route("public.search.products") }}';
        propertyDetails.style.display = 'block';
        propertyType.style.display = 'block';
        facilityStatus.style.display = 'none';
    } else {
        form.action = '{{ route("public.search.facilities") }}';
        propertyDetails.style.display = 'none';
        propertyType.style.display = 'none';
        facilityStatus.style.display = 'block';
    }
}

// Initialize form action on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFormAction();
});
</script>
@endsection
