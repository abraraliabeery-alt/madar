@extends('layouts.app')

@section('title', __('public.map_search.title'))

@section('content')
<div class="map-page min-h-screen" style="background-color:var(--brand-bg);color:var(--brand-fg);">
    <div class="map-header px-4 sm:px-6 py-5 border-b" style="background-color:var(--brand-bg);border-color:var(--brand-border);">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold" style="color:var(--brand-fg);">{{ __('public.map_search.title') }}</h1>
                    <p class="text-sm mt-1" style="color:var(--brand-muted);">{{ __('public.map_search.subtitle') }}</p>
                </div>
                <div class="hidden sm:flex gap-3">
                    @if($mode === 'real_estate' || $mode === 'lifecycle')
                        <a href="{{ route('public.search.advanced') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                            <i class="fas fa-cog {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('public.search.advanced_search') }}
                        </a>
                        <a href="{{ route('public.products.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition text-white" style="background-color:var(--brand-brown);">
                            <i class="fas fa-list {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('public.map_search.list_view') }}
                        </a>
                    @else
                        <a href="{{ route('public.execution.marketplace') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition text-white" style="background-color:var(--brand-brown);">
                            <i class="fas fa-list {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('public.map_search.list_view') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filters -->
            <form id="mapSearchForm" action="{{ route('public.search.map') }}" method="GET" class="hidden sm:block">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex rounded-lg p-1" style="background-color:rgba(var(--brand-brown-rgb),.08);">
                        <label class="cursor-pointer">
                            <input type="radio" name="search_type" value="projects" class="peer sr-only" {{ request('search_type','projects') === 'projects' ? 'checked' : '' }} onchange="filterMap()">
                            <span class="block px-4 py-2 rounded-md text-sm font-medium transition" style="color:var(--brand-muted);" data-active="background-color:var(--brand-brown);color:#fff;">
                                {{ $mode === 'contracting' ? __('public.map_search.projects') : __('public.map_search.properties') }}
                            </span>
                        </label>
                        @if($mode !== 'real_estate')
                        <label class="cursor-pointer">
                            <input type="radio" name="search_type" value="facilities" class="peer sr-only" {{ request('search_type') === 'facilities' ? 'checked' : '' }} onchange="filterMap()">
                            <span class="block px-4 py-2 rounded-md text-sm font-medium transition" style="color:var(--brand-muted);" data-active="background-color:var(--brand-brown);color:#fff;">{{ __('public.map_search.facilities') }}</span>
                        </label>
                        @endif
                    </div>

                    <div id="categoryFilterBlock" class="hidden flex-1 min-w-[140px] max-w-xs">
                        <select id="category_id" name="category_id" onchange="filterMap()" class="w-full px-3 py-2 rounded-lg text-sm outline-none" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                            <option value="">{{ __('public.search.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->getTranslatedName() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="priceFilters" class="flex-1 min-w-[120px] max-w-xs">
                        <input type="number" name="min_budget" value="{{ request('min_budget') }}" placeholder="{{ __('public.search.min_price') }}" onchange="filterMap()" class="w-full px-3 py-2 rounded-lg text-sm outline-none" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                    </div>
                    <div id="maxPriceFilter" class="flex-1 min-w-[120px] max-w-xs">
                        <input type="number" name="max_budget" value="{{ request('max_budget') }}" placeholder="{{ __('public.search.max_price') }}" onchange="filterMap()" class="w-full px-3 py-2 rounded-lg text-sm outline-none" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative w-full h-[calc(100dvh-180px)] sm:h-[calc(100dvh-230px)]" id="map-wrapper">
        <div id="map" class="w-full h-full z-0"></div>

        <!-- Map Controls -->
        <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} z-[1000] flex flex-col gap-2">
            <button type="button" id="map-fullscreen-btn" class="w-10 h-10 rounded-lg shadow flex items-center justify-center transition" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);" title="{{ __('public.map_search.fullscreen') }}">
                <i class="fas fa-expand"></i>
            </button>
            <button type="button" id="map-locate-btn" class="w-10 h-10 rounded-lg shadow flex items-center justify-center transition" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);" title="{{ __('public.map_search.my_location') }}">
                <i class="fas fa-crosshairs"></i>
            </button>
        </div>

        <!-- Results Count -->
        <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} z-[1000] px-3 py-1.5 rounded-full text-sm font-medium shadow" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
            <span id="results-count">{{ count($mapData) }}</span> {{ __('public.map_search.result') }}
        </div>
    </div>

    <!-- Mobile Filter Sheet Trigger -->
    <button type="button" id="mobile-filter-btn" class="sm:hidden fixed bottom-6 {{ app()->getLocale() === 'ar' ? 'left-6' : 'right-6' }} z-[1001] w-12 h-12 rounded-full shadow-lg flex items-center justify-center text-white" style="background-color:var(--brand-brown);">
        <i class="fas fa-sliders-h"></i>
    </button>

    <!-- Mobile Filter Sheet -->
    <div id="mobile-filter-sheet" class="sm:hidden fixed inset-0 z-[1002] hidden" style="background-color:rgba(0,0,0,.5);">
        <div class="absolute bottom-0 inset-x-0 rounded-t-2xl p-5 max-h-[80vh] overflow-y-auto" style="background-color:var(--brand-bg);color:var(--brand-fg);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg">{{ __('public.map_search.filter_title') }}</h3>
                <button id="close-mobile-filter" class="p-2" style="color:var(--brand-muted);"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="mobileMapSearchForm" action="{{ route('public.search.map') }}" method="GET" class="space-y-4">
                <div class="flex rounded-lg p-1" style="background-color:rgba(var(--brand-brown-rgb),.08);">
                    <label class="cursor-pointer flex-1 text-center">
                        <input type="radio" name="search_type" value="projects" class="peer sr-only" {{ request('search_type','projects') === 'projects' ? 'checked' : '' }} onchange="filterMap(true)">
                        <span class="block px-3 py-2 rounded-md text-sm font-medium" style="color:var(--brand-muted);">
                            {{ $mode === 'contracting' ? __('public.map_search.projects') : __('public.map_search.properties') }}
                        </span>
                    </label>
                    @if($mode !== 'real_estate')
                    <label class="cursor-pointer flex-1 text-center">
                        <input type="radio" name="search_type" value="facilities" class="peer sr-only" {{ request('search_type') === 'facilities' ? 'checked' : '' }} onchange="filterMap(true)">
                        <span class="block px-3 py-2 rounded-md text-sm font-medium" style="color:var(--brand-muted);">{{ __('public.map_search.facilities') }}</span>
                    </label>
                    @endif
                </div>
                <div id="mobileCategoryFilter" class="hidden">
                    <label class="block text-sm mb-1" style="color:var(--brand-muted);">{{ __('public.map_search.category') }}</label>
                    <select name="category_id" onchange="filterMap(true)" class="w-full px-3 py-2 rounded-lg outline-none" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                        <option value="">{{ __('public.search.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->getTranslatedName() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1" style="color:var(--brand-muted);">{{ __('public.map_search.min_price_label') }}</label>
                    <input type="number" name="min_budget" value="{{ request('min_budget') }}" placeholder="{{ __('public.search.minimum_price') }}" onchange="filterMap(true)" class="w-full px-3 py-2 rounded-lg outline-none" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                </div>
                <div>
                    <label class="block text-sm mb-1" style="color:var(--brand-muted);">{{ __('public.map_search.max_price_label') }}</label>
                    <input type="number" name="max_budget" value="{{ request('max_budget') }}" placeholder="{{ __('public.search.maximum_price') }}" onchange="filterMap(true)" class="w-full px-3 py-2 rounded-lg outline-none" style="background-color:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);">
                </div>
            </form>
        </div>
    </div>

    <!-- Legend -->
    <div class="px-4 sm:px-6 py-4 border-t" style="background-color:var(--brand-bg);border-color:var(--brand-border);">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-sm font-semibold mb-3" style="color:var(--brand-fg);">{{ __('public.map_search.map_legend') }}</h3>
            <div class="flex flex-wrap gap-4 sm:gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color:#3B82F6;"></span>
                    <span class="text-sm" style="color:var(--brand-muted);">
                        {{ $mode === 'contracting' ? __('public.map_search.projects') : __('public.map_search.properties') }}
                    </span>
                </div>
                @if($mode !== 'real_estate')
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color:#10B981;"></span>
                    <span class="text-sm" style="color:var(--brand-muted);">{{ __('public.map_search.facilities') }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full border" style="background-color:var(--brand-bg);border-color:var(--brand-brown);"></span>
                    <span class="text-sm" style="color:var(--brand-muted);">{{ __('public.map_search.my_location') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

<script>
const initialMapData = @json($mapData);
const categoriesList = @json($categories);
const t = {
    no_price: @json(__('public.map_search.no_price')),
    geolocation_not_supported: @json(__('public.map_search.geolocation_not_supported')),
    geolocation_error: @json(__('public.map_search.geolocation_error')),
    current_location: @json(__('public.map_search.current_location')),
    view_details: @json(__('public.search.view_details')),
};
let map;
let markersCluster;
let markers = [];
let currentLocationMarker = null;
let isMapFullscreen = false;

const brandBrown = getComputedStyle(document.documentElement).getPropertyValue('--brand-brown').trim() || '#126b61';

function initMap() {
    const defaultCenter = [24.7136, 46.6753];
    map = L.map('map').setView(defaultCenter, 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    markersCluster = L.markerClusterGroup({
        chunkedLoading: true,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        maxClusterRadius: 60,
        iconCreateFunction: function(cluster) {
            return L.divIcon({
                html: `<div class="flex items-center justify-center w-8 h-8 rounded-full text-white text-xs font-bold shadow-lg" style="background-color:${brandBrown};">${cluster.getChildCount()}</div>`,
                className: 'marker-cluster-custom',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });
        }
    });
    map.addLayer(markersCluster);

    addMarkersToMap(initialMapData);
}

function getMarkerColor(item) {
    return item.type === 'facility' ? '#10B981' : '#3B82F6';
}

function createCustomIcon(color) {
    return L.divIcon({
        html: `<div class="w-8 h-8 rounded-full border-2 border-white shadow-md flex items-center justify-center" style="background-color:${color};"><i class="fas fa-map-marker-alt text-white text-xs"></i></div>`,
        className: 'custom-map-marker',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -34]
    });
}

function formatPrice(price) {
    if (price === null || price === undefined || price === '') return t.no_price;
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 0
    }).format(price);
}

function addMarkersToMap(data) {
    markersCluster.clearLayers();
    markers = [];

    const countEl = document.getElementById('results-count');
    if (countEl) countEl.textContent = data.length;

    if (data.length === 0) {
        const noDataDiv = L.divIcon({
            html: `<div class="px-4 py-3 rounded-lg text-sm" style="background-color:rgba(var(--brand-brown-rgb),.12);color:var(--brand-fg);border:1px solid var(--brand-border);"><i class="fas fa-exclamation-triangle {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('public.map_search.no_properties_area') }}</div>`,
            className: 'custom-div-icon',
            iconSize: [260, 50],
            iconAnchor: [130, 25]
        });
        L.marker(map.getCenter(), { icon: noDataDiv }).addTo(markersCluster);
        return;
    }

    data.forEach(item => {
        const color = getMarkerColor(item);
        const marker = L.marker([item.latitude, item.longitude], { icon: createCustomIcon(color) });

        const imageHtml = item.image ? `<img src="${item.image}" alt="" class="w-full h-28 object-cover rounded-t-lg mb-3">` : '';
        const priceHtml = item.price !== null ? `<p class="text-lg font-bold" style="color:${brandBrown};">${formatPrice(item.price)}</p>` : '';

        const popupContent = `
            <div class="w-56 rounded-lg overflow-hidden shadow-lg" style="font-family:Tajawal,Cairo,sans-serif;background-color:var(--brand-bg);color:var(--brand-fg);">
                ${imageHtml}
                <div class="p-3">
                    <h4 class="font-bold text-sm mb-1 leading-tight" style="color:var(--brand-fg);">${item.name}</h4>
                    <p class="text-xs mb-2" style="color:var(--brand-muted);">${item.address || ''}</p>
                    ${priceHtml}
                    <div class="flex items-center gap-2 mb-3 text-xs" style="color:var(--brand-muted);">
                        <span class="px-2 py-0.5 rounded" style="background-color:rgba(var(--brand-brown-rgb),.1);color:var(--brand-brown);">${item.category}</span>
                        <span>${item.facility}</span>
                    </div>
                    <a href="${item.url}" class="block w-full text-center text-white text-xs font-medium px-3 py-2 rounded transition" style="background-color:var(--brand-brown);">
                        {{ __('public.search.view_details') }}
                    </a>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, { maxWidth: 240, className: 'map-popup-custom' });
        markers.push(marker);
    });

    markersCluster.addLayers(markers);

    if (data.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.15));
    }
}

function filterMap(mobile = false) {
    const form = document.getElementById(mobile ? 'mobileMapSearchForm' : 'mapSearchForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    const url = '{{ route('public.search.map') }}?format=json&' + params.toString();

    // Update UI for search type
    const searchType = formData.get('search_type') || 'projects';
    updateFormVisibility(searchType, mobile);

    fetch(url)
        .then(r => r.json())
        .then(data => {
            addMarkersToMap(data.items || []);
            // Update URL without reload
            window.history.replaceState({}, '', '{{ route('public.search.map') }}?' + params.toString());
        })
        .catch(() => {
            // Fallback to reload
            window.location.href = '{{ route('public.search.map') }}?' + params.toString();
        });
}

function updateFormVisibility(searchType, mobile = false) {
    const catBlock = document.getElementById(mobile ? 'mobileCategoryFilter' : 'categoryFilterBlock');
    const price = document.getElementById(mobile ? null : 'priceFilters');
    const maxPrice = document.getElementById(mobile ? null : 'maxPriceFilter');

    if (catBlock) catBlock.style.display = 'block';
    if (price) price.style.display = searchType === 'projects' ? 'block' : 'none';
    if (maxPrice) maxPrice.style.display = searchType === 'projects' ? 'block' : 'none';

    // Radio buttons active styling
    document.querySelectorAll('input[name="search_type"]').forEach(radio => {
        const label = radio.closest('label');
        const span = label?.querySelector('span');
        if (span) {
            if (radio.checked) {
                span.style.backgroundColor = 'var(--brand-brown)';
                span.style.color = '#fff';
            } else {
                span.style.backgroundColor = '';
                span.style.color = '';
            }
        }
    });
}

function setMapFullscreen(next) {
    isMapFullscreen = !!next;
    document.body.classList.toggle('map-fullscreen', isMapFullscreen);
    const btn = document.getElementById('map-fullscreen-btn');
    if (btn) btn.innerHTML = isMapFullscreen ? '<i class="fas fa-compress"></i>' : '<i class="fas fa-expand"></i>';
    if (map) setTimeout(() => map.invalidateSize(), 150);
}

function locateUser() {
    if (!navigator.geolocation) {
        alert(t.geolocation_not_supported);
        return;
    }
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const { latitude, longitude } = pos.coords;
            const latlng = [latitude, longitude];
            if (currentLocationMarker) map.removeLayer(currentLocationMarker);
            currentLocationMarker = L.circleMarker(latlng, {
                radius: 8,
                fillColor: brandBrown,
                color: '#fff',
                weight: 3,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map);
            currentLocationMarker.bindPopup(t.current_location).openPopup();
            map.setView(latlng, 14);
        },
        () => alert(t.geolocation_error)
    );
}

document.addEventListener('DOMContentLoaded', function() {
    initMap();
    updateFormVisibility(document.querySelector('input[name="search_type"]:checked')?.value || 'projects');

    document.getElementById('map-fullscreen-btn')?.addEventListener('click', () => setMapFullscreen(!isMapFullscreen));
    document.getElementById('map-locate-btn')?.addEventListener('click', locateUser);

    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const mobileFilterSheet = document.getElementById('mobile-filter-sheet');
    const closeMobileFilter = document.getElementById('close-mobile-filter');

    mobileFilterBtn?.addEventListener('click', () => mobileFilterSheet?.classList.remove('hidden'));
    closeMobileFilter?.addEventListener('click', () => mobileFilterSheet?.classList.add('hidden'));
    mobileFilterSheet?.addEventListener('click', (e) => {
        if (e.target === mobileFilterSheet) mobileFilterSheet.classList.add('hidden');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isMapFullscreen) setMapFullscreen(false);
    });
});

window.addEventListener('resize', () => {
    if (map) setTimeout(() => map.invalidateSize(), 100);
});
</script>

<style>
.map-fullscreen { overflow: hidden; }
.map-fullscreen #map-wrapper {
    position: fixed;
    inset: 0;
    z-index: 9999;
    height: 100vh;
    border-radius: 0;
}
.map-fullscreen #map { height: 100%; }
.marker-cluster-custom { background: transparent; border: none; }
.custom-map-marker { background: transparent; border: none; }
.custom-div-icon { background: transparent; border: none; }
.leaflet-popup-content-wrapper { border-radius: 12px; overflow: hidden; padding: 0; }
.leaflet-popup-content { margin: 0; }
.leaflet-container a.leaflet-popup-close-button { color: var(--brand-fg); top: 6px; {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 6px; }
</style>
@endsection
