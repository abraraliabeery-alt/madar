@extends('layouts.app')

@section('title', __('cities.products.title', ['city' => $city->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('cities.products.title', ['city' => \App\Helpers\LanguageHelper::getCityName($city)]) }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ __('cities.products.subtitle', ['city' => \App\Helpers\LanguageHelper::getCityName($city), 'count' => $products->total()]) }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-map-marker-alt text-xl mr-2"></i>
                            <span class="text-white">{{ \App\Helpers\LanguageHelper::getCityName($city) }}</span>
                        </div>
                        <div class="bg-white text-primary-600 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $products->total() }} {{ __('cities.products.properties_available') }}
                        </div>
                    </div>
                </div>
                <div class="relative">
                    @if($city->image)
                        <img src="{{ asset('storage/' . $city->image) }}" 
                             alt="{{ $city->name }}" class="rounded-lg shadow-xl w-full">
                    @else
                        <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg shadow-xl w-full h-64 flex items-center justify-center">
                            <i class="fas fa-city text-white text-8xl"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- City Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-primary-100 p-3 rounded-full">
                        <i class="fas fa-city text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ \App\Helpers\LanguageHelper::getCityName($city) }}</h2>
                        <p class="text-gray-600">{{ $products->total() }} {{ __('cities.products.properties_available') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('public.cities.show', $city) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-city ml-2"></i>{{ __('cities.products.view_city') }}
                    </a>
                    <a href="{{ route('public.products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-arrow-right ml-2"></i>{{ __('cities.products.view_all_properties') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Products -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('cities.products.filters') }}</h3>
                    
                    <form method="GET" action="{{ route('public.cities.products', $city) }}" class="space-y-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.products.search') }}</label>
                            <input type="text" name="q" value="{{ request('q') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                   placeholder="{{ __('cities.products.search_placeholder') }}">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.products.category') }}</label>
                            <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">{{ __('cities.products.all_categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'en' && $category->name_en ? $category->name_en : $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Facility Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.products.facility') }}</label>
                            <select name="facility_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">{{ __('cities.products.all_facilities') }}</option>
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.products.price_range') }}</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                       placeholder="{{ __('cities.products.min_price') }}">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                       placeholder="{{ __('cities.products.max_price') }}">
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.products.property_type') }}</label>
                            <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">{{ __('cities.products.all_types') }}</option>
                                <option value="sale" {{ request('property_type') == 'sale' ? 'selected' : '' }}>{{ __('cities.products.for_sale') }}</option>
                                <option value="rent" {{ request('property_type') == 'rent' ? 'selected' : '' }}>{{ __('cities.products.for_rent') }}</option>
                            </select>
                        </div>



                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.products.sort_by') }}</label>
                            <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('cities.products.latest') }}</option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>{{ __('cities.products.price') }}</option>
                                <option value="area" {{ request('sort_by') == 'area' ? 'selected' : '' }}>{{ __('cities.products.area') }}</option>
                                <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>{{ __('cities.products.rating') }}</option>
                            </select>
                        </div>

                        <div>
                            <select name="sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>{{ __('cities.products.descending') }}</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>{{ __('cities.products.ascending') }}</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="pt-4 space-y-2">
                            <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 transition-colors">
                                {{ __('cities.products.apply_filters') }}
                            </button>
                            <a href="{{ route('public.cities.products', $city) }}" class="block w-full text-center bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition-colors">
                                {{ __('cities.products.clear_filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Global View Toggle -->
                <div class="flex justify-between items-center mb-8">
                    <div class="text-sm text-gray-600">
                        {{ __('cities.products.showing_results', ['from' => $products->firstItem() ?? 0, 'to' => $products->lastItem() ?? 0, 'total' => $products->total()]) }}
                    </div>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="text-sm text-gray-600 mr-3 rtl:ml-3 rtl:mr-0">{{ __('general.view_toggle.display') }}</span>
                        <button id="grid-view" 
                                class="view-toggle-btn bg-primary-600 text-white p-2 rounded-lg transition-colors"
                                onclick="switchView('grid')">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button id="row-view" 
                                class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                                onclick="switchView('row')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                @if($products->count() > 0)
                    <!-- Grid View -->
                    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card-grid :product="$product" />
                        @endforeach
                    </div>

                    <!-- Row View (Hidden by default) -->
                    <div id="products-row" class="hidden space-y-4">
                        @foreach($products as $product)
                            <x-product-card-row :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-12">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <div class="bg-white rounded-lg shadow-md p-8">
                            <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('cities.products.no_properties') }}</h3>
                            <p class="text-gray-600 mb-6">{{ __('cities.products.no_properties_message', ['city' => \App\Helpers\LanguageHelper::getCityName($city)]) }}</p>
                            <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                                {{ __('cities.products.view_all_properties') }}
                            </a>
                        </div>
                    </div>
                @endif
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

.card-hover {
    transition: all 0.3s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
function switchView(viewType) {
    const productsGridView = document.getElementById('products-grid');
    const productsRowView = document.getElementById('products-row');
    const gridBtn = document.getElementById('grid-view');
    const rowBtn = document.getElementById('row-view');
    
    if (viewType === 'grid') {
        productsGridView.classList.remove('hidden');
        productsRowView.classList.add('hidden');
        gridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        gridBtn.classList.add('bg-primary-600', 'text-white');
        rowBtn.classList.remove('bg-primary-600', 'text-white');
        rowBtn.classList.add('bg-gray-200', 'text-gray-600');
    } else {
        productsRowView.classList.remove('hidden');
        productsGridView.classList.add('hidden');
        rowBtn.classList.remove('bg-gray-200', 'text-gray-600');
        rowBtn.classList.add('bg-primary-600', 'text-white');
        gridBtn.classList.remove('bg-primary-600', 'text-white');
        gridBtn.classList.add('bg-gray-200', 'text-gray-600');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('cityProductsPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('cityProductsPreferredView') || 'grid';
    switchView(preferredView);
});
</script>
@endpush
@endsection
