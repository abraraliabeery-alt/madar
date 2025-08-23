@extends('layouts.app')

@section('title', __('cities.facilities.title', ['city' => $city->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('cities.facilities.title', ['city' => @cityName($city)]) }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ __('cities.facilities.subtitle', ['city' => @cityName($city), 'count' => $facilities->total()]) }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-map-marker-alt text-xl mr-2"></i>
                            <span class="text-white">@cityName($city)</span>
                        </div>
                        <div class="bg-white text-primary-600 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $facilities->total() }} {{ __('cities.facilities.facilities_available') }}
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
                        <h2 class="text-2xl font-bold text-gray-900">@cityName($city)</h2>
                        <p class="text-gray-600">{{ $facilities->total() }} {{ __('cities.facilities.facilities_available') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('public.cities.show', $city) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-city ml-2"></i>{{ __('cities.facilities.view_city') }}
                    </a>
                    <a href="{{ route('public.facilities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-arrow-right ml-2"></i>{{ __('cities.facilities.view_all_facilities') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Facilities -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('cities.facilities.filters') }}</h3>
                    
                    <form method="GET" action="{{ route('public.cities.facilities', $city) }}" class="space-y-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.facilities.search') }}</label>
                            <input type="text" name="q" value="{{ request('q') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                   placeholder="{{ __('cities.facilities.search_placeholder') }}">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.facilities.category') }}</label>
                            <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">{{ __('cities.facilities.all_categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'en' && $category->name_en ? $category->name_en : $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.facilities.sort_by') }}</label>
                            <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('cities.facilities.latest') }}</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>{{ __('cities.facilities.name') }}</option>
                                <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>{{ __('cities.facilities.rating') }}</option>
                            </select>
                        </div>

                        <div>
                            <select name="sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>{{ __('cities.facilities.descending') }}</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>{{ __('cities.facilities.ascending') }}</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="pt-4 space-y-2">
                            <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 transition-colors">
                                {{ __('cities.facilities.apply_filters') }}
                            </button>
                            <a href="{{ route('public.cities.facilities', $city) }}" class="block w-full text-center bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition-colors">
                                {{ __('cities.facilities.clear_filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Facilities Grid -->
            <div class="lg:col-span-3">
                <!-- Global View Toggle -->
                <div class="flex justify-between items-center mb-8">
                    <div class="text-sm text-gray-600">
                        {{ __('cities.facilities.showing_results', ['from' => $facilities->firstItem() ?? 0, 'to' => $facilities->lastItem() ?? 0, 'total' => $facilities->total()]) }}
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

                @if($facilities->count() > 0)
                    <!-- Grid View -->
                    <div id="facilities-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($facilities as $facility)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                                <div class="relative">
                                    <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                         alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                                    @if($facility->is_featured)
                                        <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                            {{ __('facilities.facility_card.featured') }}
                                        </div>
                                    @endif
                                    @if($facility->is_verified)
                                        <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('public.facilities.show', $facility) }}" class="hover:text-primary-600 transition-colors">
                                            {{ $facility->name }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ $facility->address ?? __('facilities.facility_card.location_unknown') }}</p>

                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                        <span><i class="fas fa-th-large ml-1"></i>{{ $facility->category->name ?? __('facilities.facility_card.no_category') }}</span>
                                        <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                                            @endfor
                                            <span class="text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                        </div>
                                        <a href="{{ route('public.facilities.show', $facility) }}"
                                           class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                            {{ __('facilities.facility_card.view_details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Row View (Hidden by default) -->
                    <div id="facilities-row" class="hidden space-y-4">
                        @foreach($facilities as $facility)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                                <div class="flex">
                                    <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
                                        <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                             alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                        @if($facility->is_featured)
                                            <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                {{ __('facilities.facility_card.featured') }}
                                            </div>
                                        @endif
                                        @if($facility->is_verified)
                                            <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 p-6">
                                        <div class="flex justify-between items-start mb-3">
                                            <h3 class="text-xl font-semibold text-gray-900">
                                                <a href="{{ route('public.facilities.show', $facility) }}" class="hover:text-primary-600 transition-colors">
                                                    {{ $facility->name }}
                                                </a>
                                            </h3>
                                            <div class="flex items-center text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                                                @endfor
                                                <span class="text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-3">{{ $facility->address ?? __('facilities.facility_card.location_unknown') }}</p>

                                        <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                            <span><i class="fas fa-th-large ml-1"></i>{{ $facility->category->name ?? __('facilities.facility_card.no_category') }}</span>
                                            <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                        </div>

                                        <div class="flex justify-end">
                                            <a href="{{ route('public.facilities.show', $facility) }}"
                                               class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                                {{ __('facilities.facility_card.view_details') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($facilities->hasPages())
                        <div class="mt-12">
                            {{ $facilities->links() }}
                        </div>
                    @endif
                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <div class="bg-white rounded-lg shadow-md p-8">
                            <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('cities.facilities.no_facilities') }}</h3>
                            <p class="text-gray-600 mb-6">{{ __('cities.facilities.no_facilities_message', ['city' => @cityName($city)]) }}</p>
                            <a href="{{ route('public.facilities.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                                {{ __('cities.facilities.view_all_facilities') }}
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
    const facilitiesGridView = document.getElementById('facilities-grid');
    const facilitiesRowView = document.getElementById('facilities-row');
    const gridBtn = document.getElementById('grid-view');
    const rowBtn = document.getElementById('row-view');
    
    if (viewType === 'grid') {
        facilitiesGridView.classList.remove('hidden');
        facilitiesRowView.classList.add('hidden');
        gridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        gridBtn.classList.add('bg-primary-600', 'text-white');
        rowBtn.classList.remove('bg-primary-600', 'text-white');
        rowBtn.classList.add('bg-gray-200', 'text-gray-600');
    } else {
        facilitiesRowView.classList.remove('hidden');
        facilitiesGridView.classList.add('hidden');
        rowBtn.classList.remove('bg-gray-200', 'text-gray-600');
        rowBtn.classList.add('bg-primary-600', 'text-white');
        gridBtn.classList.remove('bg-primary-600', 'text-white');
        gridBtn.classList.add('bg-gray-200', 'text-gray-600');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('cityFacilitiesPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('cityFacilitiesPreferredView') || 'grid';
    switchView(preferredView);
});
</script>
@endpush
@endsection
