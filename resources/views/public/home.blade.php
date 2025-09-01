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
    <section class="mb-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('general.home.latest_properties') }}</h2>
        </div>

        <!-- Small Grid View -->
        <div id="products-small-grid">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($featuredProducts->take(6) as $product)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="relative h-32 bg-gray-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-home text-2xl text-gray-400"></i>
                                </div>
                            @endif
                            @if($product->is_featured)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs">
                                    {{ __('general.status.featured') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium text-gray-900 text-sm mb-1 line-clamp-1">{{ $product->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $product->description }}</p>
                            <div class="text-sm font-semibold text-primary-600 mb-2">
                                {{ number_format($product->price) }} {{ __('general.currency.sar') }}
                            </div>
                            <a href="{{ route('public.products.show', $product) }}" 
                               class="text-primary-600 hover:text-primary-700 text-xs font-medium">
                                {{ __('general.actions.view_details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Large Grid View (Hidden by default) -->
        <div id="products-large-grid" class="hidden">
            <x-product-grid :products="$featuredProducts->take(6)" :columns="3" />
        </div>

        <!-- List View (Hidden by default) -->
        <div id="products-list" class="hidden space-y-4">
            @foreach($featuredProducts->take(6) as $product)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex">
                    <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-home text-2xl text-gray-400"></i>
                            </div>
                        @endif
                        @if($product->is_featured)
                            <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs">
                                {{ __('general.status.featured') }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 p-4">
                        <h3 class="font-medium text-gray-900 text-lg mb-2">{{ $product->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $product->description }}</p>
                        <div class="text-lg font-semibold text-primary-600 mb-3">
                            {{ number_format($product->price) }} {{ __('general.currency.sar') }}
                        </div>
                        <a href="{{ route('public.products.show', $product) }}" 
                           class="text-primary-600 hover:text-primary-700 font-medium">
                            {{ __('general.actions.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('public.products.index') }}" 
               class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                {{ __('general.home.view_all_properties') }}
            </a>
        </div>
    </section>
    @endif

    <!-- Featured Cities -->
    @if(isset($featuredCities) && $featuredCities->count() > 0)
    <section class="mb-16">
        <h2 class="text-2xl font-semibold text-gray-900 mb-8">{{ __('general.home.featured_cities') }}</h2>
        
        <!-- Small Grid View -->
        <div id="cities-small-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($featuredCities as $city)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="relative h-32 bg-gray-100">
                    @if($city->image)
                        <img src="{{ asset('storage/' . $city->image) }}" 
                             alt="{{ $city->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-city text-2xl text-gray-400"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2 bg-white text-primary-600 px-2 py-1 rounded text-xs">
                        {{ $city->products_count }} {{ __('general.status.property') }}
                    </div>
                </div>
                <div class="p-3">
                    <h3 class="font-medium text-gray-900 text-sm mb-1 line-clamp-1">@cityName($city)</h3>
                    <p class="text-xs text-gray-600 mb-2 line-clamp-2">@cityDescription($city)</p>
                    <a href="{{ route('public.products.index', ['city' => $city->id]) }}" 
                       class="text-primary-600 hover:text-primary-700 text-xs font-medium">
                        {{ __('general.actions.browse_properties') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Large Grid View (Hidden by default) -->
        <div id="cities-large-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredCities as $city)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="relative h-40 bg-gray-100">
                    @if($city->image)
                        <img src="{{ asset('storage/' . $city->image) }}" 
                             alt="{{ $city->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-city text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2 bg-white text-primary-600 px-2 py-1 rounded text-sm">
                        {{ $city->products_count }} {{ __('general.status.property') }}
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-2">@cityName($city)</h3>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">@cityDescription($city)</p>
                    <a href="{{ route('public.products.index', ['city' => $city->id]) }}" 
                       class="text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('general.actions.browse_properties') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- List View (Hidden by default) -->
        <div id="cities-list" class="hidden space-y-4">
            @foreach($featuredCities as $city)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex">
                    <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
                        @if($city->image)
                            <img src="{{ asset('storage/' . $city->image) }}" 
                                 alt="{{ $city->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-city text-2xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 bg-white text-primary-600 px-2 py-1 rounded text-sm">
                            {{ $city->products_count }} {{ __('general.status.property') }}
                        </div>
                    </div>
                    <div class="flex-1 p-4">
                        <h3 class="font-medium text-gray-900 text-lg mb-2">@cityName($city)</h3>
                        <p class="text-sm text-gray-600 mb-3">@cityDescription($city)</p>
                        <a href="{{ route('public.products.index', ['city' => $city->id]) }}" 
                           class="text-primary-600 hover:text-primary-700 font-medium">
                            {{ __('general.actions.browse_properties') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('public.cities.index') }}" 
               class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                {{ __('general.home.view_all_cities') }}
            </a>
        </div>
    </section>
    @endif

    <!-- Featured Categories -->
    @if(isset($categories) && $categories->count() > 0)
    <section class="mb-16">
        <h2 class="text-2xl font-semibold text-gray-900 mb-8">{{ __('general.home.featured_categories') }}</h2>
        
        <!-- Small Grid View -->
        <div id="categories-small-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($categories->take(6) as $category)
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow">
                @if($category->icon)
                    <i class="{{ $category->icon }} text-2xl text-primary-600 mb-3"></i>
                @else
                    <i class="fas fa-building text-2xl text-primary-600 mb-3"></i>
                @endif
                <h3 class="font-medium text-gray-900 text-sm mb-1 line-clamp-1">{{ $category->display_name ?? App\Helpers\LanguageHelper::getCategoryName($category) }}</h3>
                <p class="text-xs text-gray-600 mb-2 line-clamp-2">@categoryDescription($category)</p>
                <div class="text-xs text-gray-500 mb-3">{{ $category->products_count }} {{ __('general.status.property') }}</div>
                <a href="{{ route('public.products.by-category', $category->id) }}" 
                   class="text-primary-600 hover:text-primary-700 text-xs font-medium">
                    {{ __('general.actions.browse_category') }}
                </a>
            </div>
            @endforeach
        </div>

        <!-- Large Grid View (Hidden by default) -->
        <div id="categories-large-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories->take(4) as $category)
            <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                @if($category->icon)
                    <i class="{{ $category->icon }} text-4xl text-primary-600 mb-4"></i>
                @else
                    <i class="fas fa-building text-4xl text-primary-600 mb-4"></i>
                @endif
                <h3 class="font-medium text-gray-900 mb-2">{{ $category->display_name ?? App\Helpers\LanguageHelper::getCategoryName($category) }}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">@categoryDescription($category)</p>
                <div class="text-sm text-gray-500 mb-4">{{ $category->products_count }} {{ __('general.status.property') }}</div>
                <a href="{{ route('public.products.by-category', $category->id) }}" 
                   class="text-primary-600 hover:text-primary-700 font-medium">
                    {{ __('general.actions.browse_category') }}
                </a>
            </div>
            @endforeach
        </div>

        <!-- List View (Hidden by default) -->
        <div id="categories-list" class="hidden space-y-4">
            @foreach($categories->take(4) as $category)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex">
                    <div class="w-48 h-32 bg-gray-100 flex-shrink-0 flex items-center justify-center">
                        @if($category->icon)
                            <i class="{{ $category->icon }} text-4xl text-primary-600"></i>
                        @else
                            <i class="fas fa-building text-4xl text-primary-600"></i>
                        @endif
                    </div>
                    <div class="flex-1 p-4">
                        <h3 class="font-medium text-gray-900 text-lg mb-2">{{ $category->display_name ?? App\Helpers\LanguageHelper::getCategoryName($category) }}</h3>
                        <p class="text-sm text-gray-600 mb-3">@categoryDescription($category)</p>
                        <div class="text-sm text-gray-500 mb-4">{{ $category->products_count }} {{ __('general.status.property') }}</div>
                        <a href="{{ route('public.products.by-category', $category->id) }}" 
                           class="text-primary-600 hover:text-primary-700 font-medium">
                            {{ __('general.actions.browse_category') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
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

function switchView(viewType) {
    // Featured Products
    const productsSmallGridView = document.getElementById('products-small-grid');
    const productsLargeGridView = document.getElementById('products-large-grid');
    const productsListView = document.getElementById('products-list');
    
    // Featured Cities
    const citiesSmallGridView = document.getElementById('cities-small-grid');
    const citiesLargeGridView = document.getElementById('cities-large-grid');
    const citiesListView = document.getElementById('cities-list');
    
    // Featured Categories
    const categoriesSmallGridView = document.getElementById('categories-small-grid');
    const categoriesLargeGridView = document.getElementById('categories-large-grid');
    const categoriesListView = document.getElementById('categories-list');
    
    // Toggle buttons
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');
    
    // Hide all views first
    if (productsSmallGridView) productsSmallGridView.classList.add('hidden');
    if (productsLargeGridView) productsLargeGridView.classList.add('hidden');
    if (productsListView) productsListView.classList.add('hidden');
    if (citiesSmallGridView) citiesSmallGridView.classList.add('hidden');
    if (citiesLargeGridView) citiesLargeGridView.classList.add('hidden');
    if (citiesListView) citiesListView.classList.add('hidden');
    if (categoriesSmallGridView) categoriesSmallGridView.classList.add('hidden');
    if (categoriesLargeGridView) categoriesLargeGridView.classList.add('hidden');
    if (categoriesListView) categoriesListView.classList.add('hidden');
    
    // Reset all button styles
    smallGridBtn.classList.remove('bg-primary-600', 'text-white');
    smallGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    largeGridBtn.classList.remove('bg-primary-600', 'text-white');
    largeGridBtn.classList.add('bg-gray-200', 'text-gray-600');
    listBtn.classList.remove('bg-primary-600', 'text-white');
    listBtn.classList.add('bg-gray-200', 'text-gray-600');
    
    if (viewType === 'small-grid') {
        // Show small grid views
        if (productsSmallGridView) productsSmallGridView.classList.remove('hidden');
        if (citiesSmallGridView) citiesSmallGridView.classList.remove('hidden');
        if (categoriesSmallGridView) categoriesSmallGridView.classList.remove('hidden');
        
        // Update button styles
        smallGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        smallGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'large-grid') {
        // Show large grid views
        if (productsLargeGridView) productsLargeGridView.classList.remove('hidden');
        if (citiesLargeGridView) citiesLargeGridView.classList.remove('hidden');
        if (categoriesLargeGridView) categoriesLargeGridView.classList.remove('hidden');
        
        // Update button styles
        largeGridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        largeGridBtn.classList.add('bg-primary-600', 'text-white');
    } else if (viewType === 'list') {
        // Show list views
        if (productsListView) productsListView.classList.remove('hidden');
        if (citiesListView) citiesListView.classList.remove('hidden');
        if (categoriesListView) categoriesListView.classList.remove('hidden');
        
        // Update button styles
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
