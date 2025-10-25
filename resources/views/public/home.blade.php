@extends('layouts.app')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="text-center py-16 mb-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-4 hero-title">{{ __('general.home.title') }}</h1>
        <p class="text-lg text-gray-600 mb-8">{{ __('general.home.subtitle') }}</p>
        
        <!-- Search Form -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('public.search') }}" method="GET" class="flex search-form" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                <input type="text" 
                       class="flex-1 px-4 py-3 text-gray-900 border border-gray-300 search-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                       name="q" 
                       placeholder="{{ __('general.home.search_placeholder') }}" 
                       required>
                <button class="bg-primary-600 text-white px-6 py-3 search-button hover:bg-primary-700 transition-colors" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
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

    <!-- Featured Products -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
        <x-multi-view-grid 
            :items="$featuredProducts" 
            type="products" 
            :title="__('general.home.latest_properties')"
            :viewAllRoute="route('public.products.index')"
            :viewAllText="__('general.home.view_all_properties')"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="products"
        />
    @endif

    <!-- Featured Cities -->
    @if(isset($featuredCities) && $featuredCities->count() > 0)
        <x-multi-view-grid 
            :items="$featuredCities" 
            type="cities" 
            :title="__('general.home.featured_cities')"
            :viewAllRoute="route('public.cities.index')"
            :viewAllText="__('general.home.view_all_cities')"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="cities"
        />
    @endif

    <!-- Featured Categories -->
    @if(isset($categories) && $categories->count() > 0)
        <x-multi-view-grid 
            :items="$categories" 
            type="categories" 
            :title="__('general.home.featured_categories')"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="categories"
        />
    @endif

    <!-- Call to Action -->
    <section class="bg-gray-50 rounded-lg p-8 text-center">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('general.home.cta_title') }}</h2>
        <p class="text-gray-600 mb-6">{{ __('general.home.cta_subtitle') }}</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                {{ __('general.home.register_now') }}
            </a>
            <a href="{{ route('public.contact') }}" 
               class="border border-primary-600 text-primary-600 px-6 py-3 rounded-lg hover:bg-primary-50 transition-colors">
                {{ __('general.home.contact_us') }}
            </a>
        </div>
    </section>
</div>
@endsection

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

/* Language-specific styling */
[dir="rtl"] .search-form {
    flex-direction: row-reverse;
}

[dir="rtl"] .search-input {
    border-radius: 0 0.5rem 0.5rem 0;
}

[dir="rtl"] .search-button {
    border-radius: 0.5rem 0 0 0.5rem;
}

[dir="ltr"] .search-form {
    flex-direction: row;
}

[dir="ltr"] .search-input {
    border-radius: 0 0.5rem 0.5rem 0;
}

[dir="ltr"] .search-button {
    border-radius: 0.5rem 0 0 0.5rem;
}

/* Responsive language support */
@media (max-width: 768px) {
    [dir="rtl"] .hero-title {
        font-size: 2rem;
    }
    
    [dir="ltr"] .hero-title {
        font-size: 2rem;
    }
}

/* Language-specific text alignment */
[dir="rtl"] .text-left {
    text-align: right;
}

[dir="rtl"] .text-right {
    text-align: left;
}

[dir="ltr"] .text-left {
    text-align: left;
}

[dir="ltr"] .text-right {
    text-align: right;
}

/* Language-specific margins and padding */
[dir="rtl"] .ml-1 {
    margin-left: 0;
    margin-right: 0.25rem;
}

[dir="rtl"] .mr-1 {
    margin-right: 0;
    margin-left: 0.25rem;
}

[dir="ltr"] .ml-1 {
    margin-left: 0.25rem;
    margin-right: 0;
}

[dir="ltr"] .mr-1 {
    margin-right: 0.25rem;
    margin-left: 0;
}
</style>
@endpush

@push('scripts')
<script>
// Helper function for locale-aware number formatting
function formatNumber(number, locale = '{{ app()->getLocale() }}') {
    if (locale === 'ar') {
        return new Intl.NumberFormat('ar-SA').format(number);
    } else {
        return new Intl.NumberFormat('en-US').format(number);
    }
}

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
    
    // Add language-specific functionality
    const currentLocale = '{{ app()->getLocale() }}';
    const isRTL = currentLocale === 'ar';
    
    // Set document direction based on language
    if (isRTL) {
        document.documentElement.setAttribute('dir', 'rtl');
        document.documentElement.setAttribute('lang', 'ar');
    } else {
        document.documentElement.setAttribute('dir', 'ltr');
        document.documentElement.setAttribute('lang', 'en');
    }
    
    // Add language-specific event listeners
    if (isRTL) {
        // RTL specific functionality
        console.log('RTL mode activated');
        
        // Add RTL-specific keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                // In RTL, left arrow should go to next item
                e.preventDefault();
                // Add your RTL navigation logic here
            }
        });
    } else {
        // LTR specific functionality
        console.log('LTR mode activated');
        
        // Add LTR-specific keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight') {
                // In LTR, right arrow should go to next item
                e.preventDefault();
                // Add your LTR navigation logic here
            }
        });
    }
    
    // Add accessibility improvements
    const viewToggleButtons = document.querySelectorAll('.view-toggle-btn');
    viewToggleButtons.forEach(button => {
        button.setAttribute('aria-label', button.title);
        button.setAttribute('role', 'button');
        button.setAttribute('tabindex', '0');
    });
});
</script>
@endpush
