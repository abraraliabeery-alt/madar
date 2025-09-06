@extends('layouts.app')

@section('title', __('public.search.map_search'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('public.search.map_search') }}</h1>
        
        <!-- Search Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('public.search.map') }}" method="GET" id="mapSearchForm">
                <!-- Search Type Toggle -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('public.search.search_type') }}</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="search_type" value="products" 
                                   {{ request('search_type', 'products') == 'products' ? 'checked' : '' }}
                                   onchange="updateMapForm()" class="mr-2">
                            <span class="text-sm text-gray-700">{{ __('public.navigation.products') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="search_type" value="facilities" 
                                   {{ request('search_type') == 'facilities' ? 'checked' : '' }}
                                   onchange="updateMapForm()" class="mr-2">
                            <span class="text-sm text-gray-700">{{ __('public.navigation.facilities') }}</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.common.category') }}</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                id="category_id" name="category_id" onchange="filterMap()">
                            <option value="">{{ __('public.search.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->getTranslatedName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="priceFilters" style="display: block;">
                        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.min_price') }}</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('public.search.minimum_price') }}" onchange="filterMap()">
                    </div>
                    <div id="maxPriceFilter" style="display: block;">
                        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.max_price') }}</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('public.search.maximum_price') }}" onchange="filterMap()">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="filterMap()" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-search mr-2"></i> {{ __('public.search.filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Map Container -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div id="map" class="w-full h-96"></div>
        </div>

        <!-- Map Legend -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('public.map_search.map_legend') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-700">{{ __('public.map_search.properties') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-700">{{ __('public.map_search.for_sale') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-orange-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-700">{{ __('public.map_search.for_rent') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-700">{{ __('public.map_search.featured') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.advanced') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-cog mr-2"></i> {{ __('public.search.advanced_search') }}
            </a>
            <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.products') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-list mr-2"></i> {{ __('public.map_search.list_view') }}
            </a>
        </div>
    </div>
</div>

<!-- Map Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
// Map data from Laravel
const mapData = @json($mapData);
let map;
let markers = [];

// Initialize map
function initMap() {
    // Default center (Riyadh, Saudi Arabia)
    const defaultCenter = [24.7136, 46.6753];
    
    map = L.map('map').setView(defaultCenter, 10);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers
    addMarkersToMap();
}

// Add markers to map
function addMarkersToMap() {
    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    if (mapData.length === 0) {
        // Show message if no properties found
        const noDataDiv = L.divIcon({
            html: '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">' +
                  '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                  '{{ __("public.map_search.no_properties_area") }}' +
                  '</div>',
            className: 'custom-div-icon',
            iconSize: [300, 50],
            iconAnchor: [150, 25]
        });
        
        L.marker(defaultCenter, { icon: noDataDiv }).addTo(map);
        return;
    }
    
    // Add markers for each property
    mapData.forEach(property => {
        const markerColor = getMarkerColor(property);
        
        const marker = L.circleMarker([property.latitude, property.longitude], {
            radius: 8,
            fillColor: markerColor,
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);
        
        // Create popup content
        const popupContent = `
            <div class="p-2">
                <h4 class="font-semibold text-gray-800 mb-2">${property.name}</h4>
                <p class="text-sm text-gray-600 mb-2">${property.address}</p>
                <p class="text-lg font-bold text-blue-600 mb-2">${formatPrice(property.price)}</p>
                <p class="text-sm text-gray-500 mb-2">
                    <i class="fas fa-tag mr-1"></i> ${property.category}
                </p>
                <p class="text-sm text-gray-500 mb-3">
                    <i class="fas fa-building mr-1"></i> ${property.facility}
                </p>
                <a href="${property.url}" class="inline-block bg-blue-600 !text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                    {{ __('public.search.view_details') }}
                </a>
            </div>
        `;
        
        marker.bindPopup(popupContent);
        markers.push(marker);
    });
    
    // Fit map to show all markers
    if (mapData.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Get marker color based on property type
function getMarkerColor(property) {
    // This would need to be determined based on your property data structure
    // For now, using a default blue color
    return '#3B82F6';
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 0
    }).format(price);
}

// Update form action based on search type
function updateMapForm() {
    const form = document.getElementById('mapSearchForm');
    const searchType = document.querySelector('input[name="search_type"]:checked').value;
    const priceFilters = document.getElementById('priceFilters');
    const maxPriceFilter = document.getElementById('maxPriceFilter');
    
    if (searchType === 'products') {
        form.action = '{{ route("public.search.map") }}';
        priceFilters.style.display = 'block';
        maxPriceFilter.style.display = 'block';
    } else {
        form.action = '{{ route("public.search.map") }}';
        priceFilters.style.display = 'none';
        maxPriceFilter.style.display = 'none';
    }
}

// Filter map based on form inputs
function filterMap() {
    const form = document.getElementById('mapSearchForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Reload page with new parameters
    window.location.href = form.action + '?' + params.toString();
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateMapForm();
    initMap();
});

// Handle map resize
window.addEventListener('resize', function() {
    if (map) {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
});
</script>

<style>
.custom-div-icon {
    background: transparent;
    border: none;
}

.leaflet-popup-content {
    margin: 0;
}

.leaflet-popup-content-wrapper {
    border-radius: 8px;
}
</style>
@endsection
