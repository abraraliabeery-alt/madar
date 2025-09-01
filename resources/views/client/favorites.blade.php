@extends('layouts.app')

@section('title', __('client.favorites.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('client.favorites.title') }}</h1>
            <p class="text-gray-600">{{ __('client.favorites.subtitle', ['default' => 'Manage your favorite products and facilities']) }}</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 space-x-reverse" aria-label="Tabs">
                    <a href="#products" 
                       class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active"
                       data-tab="products">
                        {{ __('client.favorites.products') }}
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs font-medium">
                            {{ $favoriteProducts->total() }}
                        </span>
                    </a>
                    <a href="#facilities" 
                       class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                       data-tab="facilities">
                        {{ __('client.favorites.facilities') }}
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs font-medium">
                            {{ $favoriteFacilities->total() }}
                        </span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Products Tab Content -->
        <div id="products-tab" class="tab-content active">
            @if($favoriteProducts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($favoriteProducts as $product)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <!-- Product Image -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-home text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                    <form method="POST" action="{{ route('client.favorites.remove-product', $product) }}" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 transition-colors"
                                                title="{{ __('client.favorites.remove_from_favorites') }}">
                                            <i class="fas fa-heart-broken"></i>
                                        </button>
                                    </form>
                                </div>

                                @if($product->facility)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-building text-gray-400 ml-1"></i>
                                        {{ $product->facility->name }}
                                    </p>
                                @endif

                                @if($product->category)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-tag text-gray-400 ml-1"></i>
                                        {{ $product->category->name }}
                                    </p>
                                @endif

                                @if($product->price)
                                    <p class="text-lg font-bold text-primary-600 mb-3">
                                        {{ number_format($product->price) }} ريال
                                    </p>
                                @endif

                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('public.products.show', $product) }}" 
                                       class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors text-sm">
                                        {{ __('client.favorites.view_product') }}
                                    </a>
                                    <a href="{{ route('public.products.show', $product) }}?book=1" 
                                       class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm">
                                        {{ __('client.favorites.book_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($favoriteProducts->hasPages())
                    <div class="mt-8">
                        {{ $favoriteProducts->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-heart text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('client.favorites.no_products') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('client.favorites.no_products_description', ['default' => 'Start adding products to your favorites to see them here.']) }}</p>
                    <a href="{{ route('public.products.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        {{ __('client.favorites.browse_products', ['default' => 'Browse Products']) }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Facilities Tab Content -->
        <div id="facilities-tab" class="tab-content hidden">
            @if($favoriteFacilities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favoriteFacilities as $facility)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <!-- Facility Image -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                @if($facility->logo)
                                    <img src="{{ asset('storage/' . $facility->logo) }}" 
                                         alt="{{ $facility->name }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-building text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Facility Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                        {{ $facility->name }}
                                    </h3>
                                    <form method="POST" action="{{ route('client.favorites.remove-facility', $facility) }}" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 transition-colors"
                                                title="{{ __('client.favorites.remove_from_favorites') }}">
                                            <i class="fas fa-heart-broken"></i>
                                        </button>
                                    </form>
                                </div>

                                @if($facility->facilityCategory)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-tag text-gray-400 ml-1"></i>
                                        {{ $facility->facilityCategory->name }}
                                    </p>
                                @endif

                                @if($facility->address)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 ml-1"></i>
                                        {{ Str::limit($facility->address, 50) }}
                                    </p>
                                @endif

                                @if($facility->phone)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-phone text-gray-400 ml-1"></i>
                                        {{ $facility->phone }}
                                    </p>
                                @endif

                                @if($facility->rating)
                                    <div class="flex items-center mb-3">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $facility->rating)
                                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600 mr-2">({{ $facility->rating_count ?? 0 }})</span>
                                    </div>
                                @endif

                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('public.facilities.show', $facility) }}" 
                                       class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors text-sm">
                                        {{ __('client.favorites.view_facility') }}
                                    </a>
                                    <a href="{{ route('public.facilities.appointment', $facility) }}" 
                                       class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm">
                                        {{ __('client.favorites.book_appointment', ['default' => 'Book Appointment']) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($favoriteFacilities->hasPages())
                    <div class="mt-8">
                        {{ $favoriteFacilities->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-building text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('client.favorites.no_facilities') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('client.favorites.no_facilities_description', ['default' => 'Start adding facilities to your favorites to see them here.']) }}</p>
                    <a href="{{ route('public.facilities.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        {{ __('client.favorites.browse_facilities', ['default' => 'Browse Facilities']) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.add('hidden'));
            
            // Add active class to current tab and show content
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.tab-link.active {
    border-color: #3b82f6;
    color: #3b82f6;
}

.tab-link:not(.active) {
    border-color: transparent;
    color: #6b7280;
}

.tab-link:hover:not(.active) {
    color: #374151;
    border-color: #d1d5db;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection
