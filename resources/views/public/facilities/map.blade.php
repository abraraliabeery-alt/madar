@extends('layouts.app')

@section('title', __('facilities.map.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('facilities.map.title') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('facilities.map.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Map -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('facilities.map.title') }}</h2>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <span class="text-sm text-gray-600">{{ $facilities->count() }} {{ __('facilities.map.facilities_count') }}</span>
                            <button id="locateMe" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                                <i class="fas fa-location-arrow ml-2"></i>{{ __('facilities.map.locate_me') }}
                            </button>
                        </div>
                    </div>

                    <!-- Map Placeholder -->
                    <div id="map" class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-map text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">{{ __('facilities.map.interactive_map') }}</p>
                            <p class="text-sm text-gray-500 mt-2">{{ __('facilities.map.coming_soon') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facilities.map.nearby_facilities') }}</h3>

                    @if($facilities->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($facilities as $facility)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-3 space-x-reverse mb-3">
                                        <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}"
                                             alt="{{ $facility->name }}" class="w-12 h-12 rounded object-cover">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $facility->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $facility->address ?? __('facilities.facility_card.location_unknown') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                            <span class="text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                        </div>
                                        <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }}</span>
                                    </div>

                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('public.facilities.show', $facility) }}"
                                           class="flex-1 bg-primary-600 text-white px-3 py-2 rounded text-sm font-medium text-center hover:bg-primary-700 transition-colors">
                                            {{ __('facilities.common.view') }}
                                        </a>
                                        @if($facility->phone)
                                            <a href="tel:{{ $facility->phone }}"
                                               class="bg-green-600 text-white px-3 py-2 rounded text-sm font-medium hover:bg-green-700 transition-colors">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-map-marker-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">{{ __('facilities.map.no_facilities_map') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Map Features -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('facilities.map.map_features') }}</h2>
                <p class="text-lg text-gray-600">{{ __('facilities.map.map_features_subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search-location text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.map.location_search') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.map.location_search_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-route text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.map.routes_paths') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.map.routes_paths_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.map.detailed_info') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.map.detailed_info_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Locate me button functionality
    document.getElementById('locateMe').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Here you would typically update the map to center on user's location
                console.log('User location:', latitude, longitude);

                // Show success message
                alert('{{ __("facilities.map.location_success") }}');
            }, function(error) {
                console.error('Error getting location:', error);
                alert('{{ __("facilities.map.location_error") }}');
            });
        } else {
            alert('{{ __("facilities.map.location_not_supported") }}');
        }
    });
});
</script>
@endpush
@endsection
