@extends('layouts.app')

@section('title', __('products.hero.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('products.hero.title') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('products.hero.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('public.products.index') }}" method="GET" class="space-y-6" id="products-filter-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.search.title') }}</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                               placeholder="{{ __('products.search.placeholder') }}">
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.search.category') }}</label>
                        <select name="category_id" id="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">{{ __('products.search.all_categories') }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.search.city') }}</label>
                        <select name="city" id="city"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">{{ __('products.search.all_cities') }}</option>
                            @foreach($cities ?? [] as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->localized_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.search.price_range') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('products.search.min_price') }}">
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('products.search.max_price') }}">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'created_at') }}">
                <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'desc') }}">
                <div class="flex justify-between items-center">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-search ml-2"></i>{{ __('products.search.button') }}
                    </button>
                    <a href="{{ route('public.products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('products.search.clear_filters') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('products.results.title') }}</h2>
                <p class="text-gray-600">{{ $products->total() ?? 0 }} {{ __('products.results.properties_available') }}</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <span class="text-sm text-gray-600">{{ __('products.search.sort_by') }}:</span>
                <select id="sort_selector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="created_at_desc" {{ (request('sort_by','created_at')==='created_at' && request('sort_order','desc')==='desc') ? 'selected' : '' }}>{{ __('products.search.latest') }}</option>
                    <option value="price_asc" {{ (request('sort_by')==='price' && request('sort_order')==='asc') ? 'selected' : '' }}>{{ __('products.search.price_low_to_high') }}</option>
                    <option value="price_desc" {{ (request('sort_by')==='price' && request('sort_order')==='desc') ? 'selected' : '' }}>{{ __('products.search.price_high_to_low') }}</option>
                    <option value="title_asc" {{ (request('sort_by')==='title' && request('sort_order')==='asc') ? 'selected' : '' }}>{{ __('products.search.name') }}</option>
                </select>
            </div>
        </div>

        <!-- Global View Toggle -->
        <div class="flex justify-end items-center mb-8">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm text-gray-600 mr-3 rtl:ml-3 rtl:mr-0">{{ __('general.view_toggle.display') }}</span>
                <button id="small-grid-view" 
                        class="view-toggle-btn bg-primary-600 text-white p-2 rounded-lg transition-colors"
                        onclick="switchView('small-grid')"
                        title="{{ __('general.view_toggle.small_grid') }}">
                    <i class="fas fa-th"></i>
                </button>
                <button id="large-grid-view" 
                        class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                        onclick="switchView('large-grid')"
                        title="{{ __('general.view_toggle.large_grid') }}">
                    <i class="fas fa-th-large"></i>
                </button>
                <button id="list-view" 
                        class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                        onclick="switchView('list')"
                        title="{{ __('general.view_toggle.list') }}">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        @if(isset($products) && $products->count() > 0)
            <x-multi-view-grid 
                :items="$products" 
                type="products" 
                :showPagination="true"
                :showViewToggle="true"
                idPrefix="products"
            />
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('products.results.no_results') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('products.results.no_results_message') }}</p>
                    <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('products.results.view_all_properties') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Featured Categories -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('products.categories.title') }}</h2>
                <p class="text-lg text-gray-600">{{ __('products.categories.subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories ?? [] as $category)
                    <a href="{{ route('public.products.by-category', $category) }}"
                       class="bg-gray-50 rounded-lg p-6 text-center hover:bg-primary-50 transition-colors">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-home text-primary-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 text-sm">
                            {{ \App\Models\Product::where('category_id', $category->id)->count() }} {{ __('products.categories.properties_count') }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.view-toggle-btn {
    transition: all 0.2s ease-in-out;
}

.view-toggle-btn:hover {
    transform: scale(1.05);
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@push('scripts')
<script>
function switchView(viewType) {
    const productsSmallGridView = document.getElementById('products-small-grid');
    const productsLargeGridView = document.getElementById('products-large-grid');
    const productsListView = document.getElementById('products-list');
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');
    
    // Hide all views first
    productsSmallGridView.classList.add('hidden');
    productsLargeGridView.classList.add('hidden');
    productsListView.classList.add('hidden');
    
    // Reset all button styles
    smallGridBtn.classList.remove('bg-primary-600', 'text-white');
    smallGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    largeGridBtn.classList.remove('bg-primary-600', 'text-white');
    largeGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    listBtn.classList.remove('bg-primary-600', 'text-white');
    listBtn.classList.add('bg-gray-200', 'text-gray-600');
    
    if (viewType === 'small-grid') {
        productsSmallGridView.classList.remove('hidden');
        smallGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        smallGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'large-grid') {
        productsLargeGridView.classList.remove('hidden');
        largeGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        largeGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'list') {
        productsListView.classList.remove('hidden');
        listBtn.classList.remove('bg-gray-200', 'text-gray-600');
        listBtn.classList.add('bg-primary-600', 'text-white');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('productsPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('productsPreferredView') || 'small-grid';
    switchView(preferredView);
});

// Sort functionality
(function(){
    const sortSelector = document.getElementById('sort_selector');
    if (!sortSelector) return;
    const sortBy = document.getElementById('sort_by');
    const sortOrder = document.getElementById('sort_order');
    const form = document.getElementById('products-filter-form');

    sortSelector.addEventListener('change', function(){
        const value = this.value;
        if (value === 'price_asc') { sortBy.value = 'price'; sortOrder.value = 'asc'; }
        else if (value === 'price_desc') { sortBy.value = 'price'; sortOrder.value = 'desc'; }
        else if (value === 'title_asc') { sortBy.value = 'title'; sortOrder.value = 'asc'; }
        else { sortBy.value = 'created_at'; sortOrder.value = 'desc'; }
        form.submit();
    });
})();
</script>
@endpush
@endsection
