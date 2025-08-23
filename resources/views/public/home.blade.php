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
            <button id="grid-view" 
                    class="view-toggle-btn bg-primary-600 text-white p-2 rounded-lg transition-colors"
                    onclick="switchView('grid')"
                    title="{{ __('general.view_toggle.grid') }}">
                <i class="fas fa-th-large"></i>
            </button>
            <button id="row-view" 
                    class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                    onclick="switchView('row')"
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

        <!-- Grid View -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProducts->take(6) as $product)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <!-- Product Image -->
                <div class="relative h-48 bg-gray-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-home text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badges -->
                    @if($product->is_featured)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">{{ __('general.status.featured') }}</span>
                    @endif
                    @if($product->is_verified)
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">{{ __('general.status.verified') }}</span>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-3 line-clamp-2">{{ $product->title }}</h3>
                    
                    <!-- Key Details -->
                    @if($product->card_attributes && $product->card_attributes->count() > 0)
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mb-4">
                            @foreach($product->card_attributes as $attribute)
                                <span class="inline-flex items-center bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-lg">
                                    @if($attribute->icon)
                                        <i class="{{ $attribute->icon }} ml-1 text-primary-600 text-xs"></i>
                                    @else
                                        <i class="fas fa-info-circle ml-1 text-primary-600 text-xs"></i>
                                    @endif
                                    <span class="font-medium text-gray-700">{{ $attribute->pivot->value }}</span>
                                    @if($attribute->Symbol)
                                        <span class="text-primary-600 font-semibold">{{ $attribute->Symbol }}</span>
                                    @else
                                        <span class="text-gray-500 text-xs">{{ $attribute->translations->first()->name ?? $attribute->type }}</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Location -->
                    @if($product->city)
                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-map-marker-alt ml-1 text-primary-500"></i>
                            <span class="font-medium">@cityName($product->city)</span>
                        </div>
                    @endif
                    
                    <!-- Price and Action -->
                    <div class="flex justify-between items-center">
                        @if($product->price)
                            <span class="text-xl font-bold text-primary-600">{{ number_format($product->price) }} {{ __('general.currency.sar') }}</span>
                        @else
                            <span class="text-gray-500">{{ __('general.status.price_on_request') }}</span>
                        @endif
                        <a href="{{ route('public.products.show', $product->id) }}" 
                           class="text-primary-600 hover:text-primary-700 font-medium">
                            {{ __('general.actions.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Row View (Hidden by default) -->
        <div id="products-row" class="hidden space-y-4">
            @foreach($featuredProducts->take(6) as $product)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex">
                    <!-- Product Image -->
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
                        
                        <!-- Status Badges -->
                        @if($product->is_featured)
                            <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">{{ __('general.status.featured') }}</span>
                        @endif
                        @if($product->is_verified)
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">{{ __('general.status.verified') }}</span>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-gray-900 text-lg">{{ $product->title }}</h3>
                            @if($product->price)
                                <span class="text-xl font-bold text-primary-600">{{ number_format($product->price) }} {{ __('general.currency.sar') }}</span>
                            @else
                                <span class="text-gray-500">{{ __('general.status.price_on_request') }}</span>
                            @endif
                        </div>
                        
                        <!-- Key Details -->
                        @if($product->card_attributes && $product->card_attributes->count() > 0)
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-4">
                                @foreach($product->card_attributes as $attribute)
                                    <span class="inline-flex items-center bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-lg">
                                        @if($attribute->icon)
                                            <i class="{{ $attribute->icon }} ml-1 text-primary-600 text-xs"></i>
                                        @else
                                            <i class="fas fa-info-circle ml-1 text-primary-600 text-xs"></i>
                                        @endif
                                        <span class="font-medium text-gray-700">{{ $attribute->pivot->value }}</span>
                                        @if($attribute->Symbol)
                                            <span class="text-primary-600 font-semibold">{{ $attribute->Symbol }}</span>
                                        @else
                                            <span class="text-gray-500 text-xs">{{ $attribute->translations->first()->name ?? $attribute->type }}</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Location and Action -->
                        <div class="flex justify-between items-center">
                            @if($product->city)
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt ml-1 text-primary-500"></i>
                                    <span class="font-medium">@cityName($product->city)</span>
                                </div>
                            @endif
                            <a href="{{ route('public.products.show', $product->id) }}" 
                               class="text-primary-600 hover:text-primary-700 font-medium">
                                {{ __('general.actions.view_details') }}
                            </a>
                        </div>
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
        
        <!-- Grid View -->
        <div id="cities-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

        <!-- Row View (Hidden by default) -->
        <div id="cities-row" class="hidden space-y-4">
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
        
        <!-- Grid View -->
        <div id="categories-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories->take(4) as $category)
            <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                @if($category->icon)
                    <i class="{{ $category->icon }} text-4xl text-primary-600 mb-4"></i>
                @else
                    <i class="fas fa-building text-4xl text-primary-600 mb-4"></i>
                @endif
                <h3 class="font-medium text-gray-900 mb-2">{{ $category->display_name ?? categoryName($category) }}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">@categoryDescription($category)</p>
                <div class="text-sm text-gray-500 mb-4">{{ $category->products_count }} {{ __('general.status.property') }}</div>
                <a href="{{ route('public.products.by-category', $category->id) }}" 
                   class="text-primary-600 hover:text-primary-700 font-medium">
                    {{ __('general.actions.browse_category') }}
                </a>
            </div>
            @endforeach
        </div>

        <!-- Row View (Hidden by default) -->
        <div id="categories-row" class="hidden space-y-4">
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
                        <h3 class="font-medium text-gray-900 text-lg mb-2">{{ $category->display_name ?? categoryName($category) }}</h3>
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
    const productsGridView = document.getElementById('products-grid');
    const productsRowView = document.getElementById('products-row');
    
    // Featured Cities
    const citiesGridView = document.getElementById('cities-grid');
    const citiesRowView = document.getElementById('cities-row');
    
    // Featured Categories
    const categoriesGridView = document.getElementById('categories-grid');
    const categoriesRowView = document.getElementById('categories-row');
    
    // Toggle buttons
    const gridBtn = document.getElementById('grid-view');
    const rowBtn = document.getElementById('row-view');
    
    if (viewType === 'grid') {
        // Show grid views, hide row views
        if (productsGridView) productsGridView.classList.remove('hidden');
        if (productsRowView) productsRowView.classList.add('hidden');
        if (citiesGridView) citiesGridView.classList.remove('hidden');
        if (citiesRowView) citiesRowView.classList.add('hidden');
        if (categoriesGridView) categoriesGridView.classList.remove('hidden');
        if (categoriesRowView) categoriesRowView.classList.add('hidden');
        
        // Update button styles
        gridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        gridBtn.classList.add('bg-primary-600', 'text-white');
        rowBtn.classList.remove('bg-primary-600', 'text-white');
        rowBtn.classList.add('bg-gray-200', 'text-gray-600');
    } else {
        // Show row views, hide grid views
        if (productsRowView) productsRowView.classList.remove('hidden');
        if (productsGridView) productsGridView.classList.add('hidden');
        if (citiesRowView) citiesRowView.classList.remove('hidden');
        if (citiesGridView) citiesGridView.classList.add('hidden');
        if (categoriesRowView) categoriesRowView.classList.remove('hidden');
        if (categoriesGridView) categoriesGridView.classList.add('hidden');
        
        // Update button styles
        rowBtn.classList.remove('bg-gray-200', 'text-gray-600');
        rowBtn.classList.add('bg-primary-600', 'text-white');
        gridBtn.classList.remove('bg-primary-600', 'text-white');
        gridBtn.classList.add('bg-gray-200', 'text-gray-600');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('preferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('preferredView') || 'grid';
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
