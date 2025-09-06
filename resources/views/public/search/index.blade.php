@extends('layouts.app')

@section('title', __('public.search.title'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('public.search.title') }}</h1>
        
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <!-- Search Type Selection -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('public.search.search_type') }}</h3>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="search_type" value="products" 
                               {{ request('search_type', 'products') == 'products' ? 'checked' : '' }}
                               class="mr-2 text-blue-600 focus:ring-blue-500" onchange="updateSearchForm()">
                        <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.products') }}</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="search_type" value="facilities" 
                               {{ request('search_type') == 'facilities' ? 'checked' : '' }}
                               class="mr-2 text-blue-600 focus:ring-blue-500" onchange="updateSearchForm()">
                        <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.facilities') }}</span>
                    </label>
                </div>
            </div>
            
            <form id="searchForm" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" id="priceFilters" style="display: {{ request('search_type', 'products') == 'products' ? 'block' : 'none' }};">
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.min_price') }}</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('public.search.minimum_price') }}">
                    </div>
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.max_price') }}</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('public.search.maximum_price') }}">
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-search mr-2"></i> {{ __('public.search.title') }}
                    </button>
                    <a href="{{ route('public.search.advanced') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-cog mr-2"></i> {{ __('public.search.advanced_search') }}
                    </a>
                    <a href="{{ route('public.search.map') }}" class="inline-flex items-center px-6 py-3 bg-cyan-200 text-cyan-800 font-medium rounded-md hover:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-map mr-2"></i> {{ __('public.search.map_search') }}
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                <i class="fas fa-home text-4xl text-blue-600 mb-4"></i>
                <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ __('public.products.search') }}</h5>
                <p class="text-gray-600 mb-4">{{ __('public.search.find_properties_real_estate') }}</p>
                <a href="{{ route('public.search.products') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    {{ __('public.search.browse_products') }}
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                <i class="fas fa-building text-4xl text-green-600 mb-4"></i>
                <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ __('public.facilities.search') }}</h5>
                <p class="text-gray-600 mb-4">{{ __('public.search.find_real_estate_agencies') }}</p>
                <a href="{{ route('public.search.facilities') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    {{ __('public.search.browse_facilities') }}
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                <i class="fas fa-map-marked-alt text-4xl text-cyan-600 mb-4"></i>
                <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ __('public.search.map_search') }}</h5>
                <p class="text-gray-600 mb-4">{{ __('public.search.search_properties_map') }}</p>
                <a href="{{ route('public.search.map') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                    {{ __('public.search.view_map') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateSearchForm() {
    const form = document.getElementById('searchForm');
    const searchType = document.querySelector('input[name="search_type"]:checked').value;
    const priceFilters = document.getElementById('priceFilters');
    
    if (searchType === 'products') {
        form.action = '{{ route("public.search.products") }}';
        priceFilters.style.display = 'block';
    } else {
        form.action = '{{ route("public.search.facilities") }}';
        priceFilters.style.display = 'none';
    }
}

// Initialize form action on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSearchForm();
});
</script>
@endsection
