@extends('layouts.app')

@section('title', __('facilities.featured.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('facilities.featured.title') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('facilities.featured.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Featured Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('facilities.featured.title') }}</h2>
                        <p class="text-gray-600">{{ $facilities->total() }} {{ __('facilities.featured.facilities_available') }}</p>
                    </div>
                </div>
                <a href="{{ route('public.facilities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-arrow-right ml-2"></i>{{ __('facilities.featured.view_all_facilities') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Facilities Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Global View Toggle -->
        <div class="flex justify-end items-center mb-8">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm text-gray-600 mr-3 rtl:ml-3 rtl:mr-0">عرض:</span>
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
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover border-2 border-yellow-200">
                        <div class="relative">
                            <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                 alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                            <div class="absolute top-2 right-2 bg-yellow-600 text-white px-2 py-1 rounded text-xs font-medium">
                                <i class="fas fa-star ml-1"></i>{{ __('facilities.facility_card.featured') }}
                            </div>
                            @if($facility->is_verified)
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    <a href="{{ route('public.facilities.show', $facility) }}" class="hover:text-primary-600 transition-colors">
                                        {{ $facility->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-sm text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-4">{{ $facility->description ?? __('facilities.facility_card.no_description') }}</p>

                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                <span>{{ $facility->address ?? __('facilities.facility_card.location_unknown') }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                <span><i class="fas fa-calendar ml-1"></i>{{ $facility->created_at ? $facility->created_at->diffForHumans() : __('facilities.show.not_specified') }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('public.facilities.show', $facility) }}"
                                   class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    {{ __('facilities.facility_card.view_facility') }}
                                </a>
                                <a href="{{ route('public.products.by-facility', $facility) }}"
                                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ __('facilities.facility_card.view_properties') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Row View (Hidden by default) -->
            <div id="facilities-row" class="hidden space-y-4">
                @foreach($facilities as $facility)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover border-2 border-yellow-200">
                        <div class="flex">
                            <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
                                <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                     alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 bg-yellow-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-star ml-1"></i>{{ __('facilities.facility_card.featured') }}
                                </div>
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
                                            <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-sm text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                    </div>
                                </div>

                                <p class="text-gray-600 text-sm mb-4">{{ $facility->description ?? __('facilities.facility_card.no_description') }}</p>

                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-map-marker-alt ml-2"></i>
                                    <span>{{ $facility->address ?? __('facilities.facility_card.location_unknown') }}</span>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                    <span><i class="fas fa-calendar ml-1"></i>{{ $facility->created_at ? $facility->created_at->diffForHumans() : __('facilities.show.not_specified') }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <a href="{{ route('public.facilities.show', $facility) }}"
                                       class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                        {{ __('facilities.facility_card.view_facility') }}
                                    </a>
                                    <a href="{{ route('public.products.by-facility', $facility) }}"
                                       class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                        {{ __('facilities.facility_card.view_properties') }}
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
                    <i class="fas fa-star text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.featured.no_facilities') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('facilities.featured.no_facilities_message') }}</p>
                    <a href="{{ route('public.facilities.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('facilities.featured.view_all_facilities') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Why Featured Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('facilities.featured.why_featured') }}</h2>
                <p class="text-lg text-gray-600">{{ __('facilities.featured.why_featured_subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.featured.high_quality') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.featured.high_quality_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.featured.officially_verified') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.featured.officially_verified_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.featured.customer_experience') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.featured.customer_experience_desc') }}</p>
                </div>
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
    localStorage.setItem('facilitiesFeaturedPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('facilitiesFeaturedPreferredView') || 'grid';
    switchView(preferredView);
});
</script>
@endpush
@endsection
