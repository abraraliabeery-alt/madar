@extends('layouts.app')

@section('title', __('cities.title'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-4">
                <x-language-switcher :showFlags="true" :showNames="true" class="mb-4" />
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('cities.title') }}
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('cities.subtitle') }}
            </p>
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

        <!-- Small Grid View -->
        <div id="cities-small-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @forelse($cities as $city)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    @if($city->image)
                        <div class="h-32 bg-gray-200">
                            <img src="{{ asset('storage/' . $city->image) }}" 
                                 alt="{{ __('cities.city_image') }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-32 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-city text-white text-3xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-1">
                            @cityName($city)
                        </h3>
                        
                        @if($city->description)
                            <p class="text-gray-600 text-xs mb-2 line-clamp-2">
                                {{ $city->description }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                            <span>
                                <i class="fas fa-building ml-1"></i>
                                {{ $city->products_count ?? 0 }}
                            </span>
                            <span>
                                <i class="fas fa-store ml-1"></i>
                                {{ $city->facilities_count ?? 0 }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-1 space-x-reverse">
                            <a href="{{ route('public.cities.products', $city) }}" 
                               class="flex-1 bg-primary-600 text-white text-center py-1 px-2 rounded text-xs hover:bg-primary-700 transition-colors duration-200">
                                {{ __('cities.view_properties') }}
                            </a>
                            <a href="{{ route('public.cities.facilities', $city) }}" 
                               class="flex-1 bg-gray-600 text-white text-center py-1 px-2 rounded text-xs hover:bg-gray-700 transition-colors duration-200">
                                {{ __('cities.view_facilities') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-city text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        {{ __('cities.no_cities_available') }}
                    </h3>
                    <p class="text-gray-500">
                        {{ __('cities.check_back_later') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Large Grid View (Hidden by default) -->
        <div id="cities-large-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($cities as $city)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    @if($city->image)
                        <div class="h-48 bg-gray-200">
                            <img src="{{ asset('storage/' . $city->image) }}" 
                                 alt="{{ __('cities.city_image') }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-city text-white text-6xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            @cityName($city)
                        </h3>
                        
                        @if($city->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $city->description }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>
                                <i class="fas fa-building ml-1"></i>
                                {{ $city->products_count ?? 0 }} 
                                {{ __('cities.properties') }}
                            </span>
                            <span>
                                <i class="fas fa-store ml-1"></i>
                                {{ $city->facilities_count ?? 0 }}
                                {{ __('cities.facilities') }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ route('public.cities.products', $city) }}" 
                               class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors duration-200 text-sm">
                                {{ __('cities.view_properties') }}
                            </a>
                            <a href="{{ route('public.cities.facilities', $city) }}" 
                               class="flex-1 bg-gray-600 text-white text-center py-2 px-4 rounded-md hover:bg-gray-700 transition-colors duration-200 text-sm">
                                {{ __('cities.view_facilities') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-city text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        {{ __('cities.no_cities_available') }}
                    </h3>
                    <p class="text-gray-500">
                        {{ __('cities.check_back_later') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- List View (Hidden by default) -->
        <div id="cities-list" class="hidden space-y-4">
            @forelse($cities as $city)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex">
                        @if($city->image)
                            <div class="w-48 h-32 bg-gray-200 flex-shrink-0">
                                <img src="{{ asset('storage/' . $city->image) }}" 
                                     alt="{{ __('cities.city_image') }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-48 h-32 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-city text-white text-3xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1 p-6">
                            <div class="flex justify-between items-start mb-3">
                                                            <h3 class="text-xl font-semibold text-gray-900">
                                @cityName($city)
                            </h3>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-building ml-1"></i>
                                        {{ $city->products_count ?? 0 }} 
                                        {{ __('cities.properties') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-store ml-1"></i>
                                        {{ $city->facilities_count ?? 0 }}
                                        {{ __('cities.facilities') }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($city->description)
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $city->description }}
                                </p>
                            @endif
                            
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('public.products.index', ['city' => $city->id]) }}" 
                                   class="bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors duration-200 text-sm">
                                    {{ __('cities.view_properties') }}
                                </a>
                                <a href="{{ route('public.facilities.index', ['city' => $city->id]) }}" 
                                   class="bg-gray-600 text-white text-center py-2 px-4 rounded-md hover:bg-gray-700 transition-colors duration-200 text-sm">
                                    {{ __('cities.view_facilities') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-city text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        {{ __('cities.no_cities_available') }}
                    </h3>
                    <p class="text-gray-500">
                        {{ __('cities.check_back_later') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Load More Button -->
        @if($cities->count() >= 6)
            <div class="text-center mt-12">
                <button class="bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors duration-200 font-semibold">
                    {{ __('cities.load_more_cities') }}
                </button>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.view-toggle-btn {
    transition: all 0.2s ease-in-out;
}

.view-toggle-btn:hover {
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
function switchView(viewType) {
    const citiesSmallGridView = document.getElementById('cities-small-grid');
    const citiesLargeGridView = document.getElementById('cities-large-grid');
    const citiesListView = document.getElementById('cities-list');
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');
    
    // Hide all views first
    citiesSmallGridView.classList.add('hidden');
    citiesLargeGridView.classList.add('hidden');
    citiesListView.classList.add('hidden');
    
    // Reset all button styles
    smallGridBtn.classList.remove('bg-primary-600', 'text-white');
    smallGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    largeGridBtn.classList.remove('bg-primary-600', 'text-white');
    largeGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    listBtn.classList.remove('bg-primary-600', 'text-white');
    listBtn.classList.add('bg-gray-200', 'text-gray-600');
    
    if (viewType === 'small-grid') {
        citiesSmallGridView.classList.remove('hidden');
        smallGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        smallGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'large-grid') {
        citiesLargeGridView.classList.remove('hidden');
        largeGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        largeGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'list') {
        citiesListView.classList.remove('hidden');
        listBtn.classList.remove('bg-gray-200', 'text-gray-600');
        listBtn.classList.add('bg-primary-600', 'text-white');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('citiesPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('citiesPreferredView') || 'small-grid';
    switchView(preferredView);
});
</script>
@endpush
@endsection
