@extends('layouts.app')

@section('title', __('public.products.search'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('public.products.search') }}</h1>
        
        <!-- Search Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('public.search.products') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
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
                
                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-between">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-search mr-2"></i> {{ __('public.search.title') }}
                        </button>
                        <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.advanced') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-cog mr-2"></i> {{ __('public.search.advanced_search') }}
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="sort" class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.sort_by') }}:</label>
                        <select class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                id="sort" name="sort" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('public.advanced_search.latest') }}</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('public.advanced_search.price_low_high') }}</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('public.advanced_search.price_high_low') }}</option>
                            <option value="area_low" {{ request('sort') == 'area_low' ? 'selected' : '' }}>{{ __('public.advanced_search.area_small_large') }}</option>
                            <option value="area_high" {{ request('sort') == 'area_high' ? 'selected' : '' }}>{{ __('public.advanced_search.area_large_small') }}</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('public.search.results') }}
                    @if($products->total() > 0)
                        <span class="text-sm font-normal text-gray-600">
                            ({{ $products->total() }} {{ __('public.search.properties_found') }})
                        </span>
                    @endif
                </h2>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.map') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-map mr-2"></i> {{ __('public.search.map_view') }}
                </a>
            </div>
        </div>

        <!-- Results -->
        @if($products->count() > 0)
            <x-multi-view-grid
                :items="$products"
                type="products"
                :showPagination="true"
                :showViewToggle="true"
                idPrefix="search-products"
            />
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ __('public.search.no_properties_found') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('public.search.try_adjusting_criteria') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.advanced') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-cog mr-2"></i> {{ __('public.search.advanced_search') }}
                    </a>
                    <a href="{{ route('public.products.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-list mr-2"></i> {{ __('public.search.browse_all_properties') }}
                    </a>
                </div>
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

.card-hover {
    transition: all 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
/**
 * Switch between different view modes (small-grid, large-grid, list)
 * Includes validation to prevent invalid view types from hiding content
 * @param {string} viewType - The view type to switch to
 */
function switchView(viewType) {
    // Get all grid containers dynamically
    const gridContainers = document.querySelectorAll('[id$="-small-grid"], [id$="-large-grid"], [id$="-list"]');
    
    // Toggle buttons
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');
    
    // Validate view type - only allow valid options
    const validViewTypes = ['small-grid', 'large-grid', 'list'];
    if (!validViewTypes.includes(viewType)) {
        console.warn(`Invalid view type "${viewType}" detected. Falling back to list view.`);
        viewType = 'list';
        // Clear invalid preference from localStorage
        localStorage.removeItem('preferredView');
    }
    
    // Hide all views first
    gridContainers.forEach(container => {
        container.classList.add('hidden');
    });
    
    // Reset all button styles
    [smallGridBtn, largeGridBtn, listBtn].forEach(btn => {
        if (btn) {
            btn.classList.remove('bg-primary-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-600');
        }
    });
    
    // Show the selected view type
    const targetSuffix = viewType === 'small-grid' ? '-small-grid' : 
                        viewType === 'large-grid' ? '-large-grid' : '-list';
    
    const targetContainers = document.querySelectorAll(`[id$="${targetSuffix}"]`);
    targetContainers.forEach(container => {
        container.classList.remove('hidden');
    });
    
    // Update button styles
    let activeBtn = null;
    if (viewType === 'small-grid' && smallGridBtn) {
        activeBtn = smallGridBtn;
    } else if (viewType === 'large-grid' && largeGridBtn) {
        activeBtn = largeGridBtn;
    } else if (viewType === 'list' && listBtn) {
        activeBtn = listBtn;
    }
    
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
        activeBtn.classList.add('bg-primary-600', 'text-white');
    }
    
    // Store user preference in localStorage only if it's valid
    if (validViewTypes.includes(viewType)) {
        localStorage.setItem('preferredView', viewType);
    }
    
    // Safety check: if no view containers exist, show small grid as fallback
    if (gridContainers.length === 0) {
        console.warn('No view containers found. This might indicate a rendering issue.');
    }
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('preferredView');
    const validViewTypes = ['small-grid', 'large-grid', 'list'];
    
    // Use preferred view if valid, otherwise default to list view
    const initialView = validViewTypes.includes(preferredView) ? preferredView : 'list';
    
    // If no valid preference exists, set list as default
    if (!preferredView || !validViewTypes.includes(preferredView)) {
        localStorage.setItem('preferredView', 'list');
    }
    
    switchView(initialView);
});
</script>
@endpush
@endsection
