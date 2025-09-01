@extends('layouts.app')

@section('title', __('facilities.hero.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('facilities.hero.title') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('facilities.hero.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('public.facilities.index') }}" method="GET" class="space-y-6" id="facilities-filter-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facilities.search.title') }}</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                               placeholder="{{ __('facilities.search.placeholder') }}">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facilities.search.category') }}</label>
                        <select name="category" id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">{{ __('facilities.search.all_categories') }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facilities.search.location') }}</label>
                        <select name="location" id="location"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">{{ __('facilities.search.all_locations') }}</option>
                            <option value="riyadh" {{ request('location') == 'riyadh' ? 'selected' : '' }}>{{ __('facilities.search.riyadh') }}</option>
                            <option value="jeddah" {{ request('location') == 'jeddah' ? 'selected' : '' }}>{{ __('facilities.search.jeddah') }}</option>
                            <option value="dammam" {{ request('location') == 'dammam' ? 'selected' : '' }}>{{ __('facilities.search.dammam') }}</option>
                            <option value="makkah" {{ request('location') == 'makkah' ? 'selected' : '' }}>{{ __('facilities.search.makkah') }}</option>
                            <option value="medina" {{ request('location') == 'medina' ? 'selected' : '' }}>{{ __('facilities.search.medina') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facilities.search.rating') }}</label>
                        <select name="rating" id="rating"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">{{ __('facilities.search.all_ratings') }}</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>{{ __('facilities.search.5_stars') }}</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>{{ __('facilities.search.4_stars_plus') }}</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>{{ __('facilities.search.3_stars_plus') }}</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'created_at') }}">
                <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'desc') }}">
                <div class="flex justify-between items-center">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-search ml-2"></i>{{ __('facilities.search.button') }}
                    </button>
                    <a href="{{ route('public.facilities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('facilities.search.clear_filters') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Facilities Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('facilities.results.title') }}</h2>
                <p class="text-gray-600">{{ $facilities->total() ?? 0 }} {{ __('facilities.results.facilities_available') }}</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <span class="text-sm text-gray-600">{{ __('facilities.search.sort_by') }}:</span>
                <select id="sort_selector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="created_at_desc" {{ (request('sort_by','created_at')==='created_at' && request('sort_order','desc')==='desc') ? 'selected' : '' }}>{{ __('facilities.search.latest') }}</option>
                    <option value="rating_desc" {{ (request('sort_by')==='rating' && request('sort_order')==='desc') ? 'selected' : '' }}>{{ __('facilities.search.highest_rating') }}</option>
                    <option value="name_asc" {{ (request('sort_by')==='name' && request('sort_order')==='asc') ? 'selected' : '' }}>{{ __('facilities.search.name') }}</option>
                    <option value="products_desc" {{ (request('sort_by')==='products_count' && request('sort_order')==='desc') ? 'selected' : '' }}>{{ __('facilities.search.properties_count') }}</option>
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

        @if(isset($facilities) && $facilities->count() > 0)
            <x-multi-view-grid
                :items="$facilities"
                type="facilities"
                :showPagination="true"
                :showViewToggle="false"
                idPrefix="facilities"
            />
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.results.no_results') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('facilities.results.no_results_message') }}</p>
                    <a href="{{ route('public.facilities.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('facilities.results.view_all_facilities') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Featured Categories -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('facilities.categories.title') }}</h2>
                <p class="text-lg text-gray-600">{{ __('facilities.categories.subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories ?? [] as $category)
                    <a href="{{ route('public.facilities.by-category', $category) }}"
                       class="bg-gray-50 rounded-lg p-6 text-center hover:bg-primary-50 transition-colors">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-building text-primary-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $category->facilities_count ?? 0 }} {{ __('facilities.categories.facilities_count') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-primary-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">{{ __('facilities.stats.title') }}</h2>
                <p class="text-primary-100">{{ __('facilities.stats.subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['total_facilities'] ?? 0 }}</div>
                    <div class="text-primary-100">{{ __('facilities.stats.total_facilities') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['verified_facilities'] ?? 0 }}</div>
                    <div class="text-primary-100">{{ __('facilities.stats.verified_facilities') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['total_products'] ?? 0 }}</div>
                    <div class="text-primary-100">{{ __('facilities.stats.total_products') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['satisfied_clients'] ?? 0 }}</div>
                    <div class="text-primary-100">{{ __('facilities.stats.satisfied_clients') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchView(viewType) {
    const facilitiesSmallGridView = document.getElementById('facilities-small-grid');
    const facilitiesLargeGridView = document.getElementById('facilities-large-grid');
    const facilitiesListView = document.getElementById('facilities-list');
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');

    // Hide all views first
    facilitiesSmallGridView.classList.add('hidden');
    facilitiesLargeGridView.classList.add('hidden');
    facilitiesListView.classList.add('hidden');

    // Reset all button styles
    smallGridBtn.classList.remove('bg-primary-600', 'text-white');
    smallGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    largeGridBtn.classList.remove('bg-primary-600', 'text-white');
    largeGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    listBtn.classList.remove('bg-primary-600', 'text-white');
    listBtn.classList.add('bg-gray-200', 'text-gray-600');

    if (viewType === 'small-grid') {
        facilitiesSmallGridView.classList.remove('hidden');
        smallGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        smallGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'large-grid') {
        facilitiesLargeGridView.classList.remove('hidden');
        largeGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        largeGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'list') {
        facilitiesListView.classList.remove('hidden');
        listBtn.classList.remove('bg-gray-200', 'text-gray-600');
        listBtn.classList.add('bg-primary-600', 'text-white');
    }

    // Store user preference in localStorage
    localStorage.setItem('preferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('preferredView') || 'small-grid';
    switchView(preferredView);
});

// Sort functionality
(function(){
    const sortSelector = document.getElementById('sort_selector');
    if (!sortSelector) return;
    const sortBy = document.getElementById('sort_by');
    const sortOrder = document.getElementById('sort_order');
    const form = document.getElementById('facilities-filter-form');

    sortSelector.addEventListener('change', function(){
        const value = this.value;
        if (value === 'rating_desc') { sortBy.value = 'rating'; sortOrder.value = 'desc'; }
        else if (value === 'name_asc') { sortBy.value = 'name'; sortOrder.value = 'asc'; }
        else if (value === 'products_desc') { sortBy.value = 'products_count'; sortOrder.value = 'desc'; }
        else { sortBy.value = 'created_at'; sortOrder.value = 'desc'; }
        form.submit();
    });
})();
</script>
@endpush

@endsection
