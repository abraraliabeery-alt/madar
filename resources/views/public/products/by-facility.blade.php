@extends('layouts.app')

@section('title', __('products.by_facility.title', ['facility' => $facility->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('products.by_facility.title', ['facility' => $facility->name]) }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ __('products.by_facility.subtitle', ['facility' => $facility->name]) }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <div class="flex items-center text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                            @endfor
                            <span class="text-white mr-2">({{ $facility->rating ?? 0 }})</span>
                        </div>
                        @if($facility->is_verified)
                            <div class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-check ml-1"></i>{{ __('products.property_card.verified') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $facility->name }}" class="rounded-lg shadow-xl w-full">
                </div>
            </div>
        </div>
    </div>

    <!-- Facility Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-primary-100 p-3 rounded-full">
                        <i class="fas fa-building text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $facility->name }}</h2>
                        <p class="text-gray-600">{{ $products->total() }} {{ __('products.by_facility.properties_available') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('public.facilities.show', $facility) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-building ml-2"></i>{{ __('products.by_facility.view_facility') }}
                    </a>
                    <a href="{{ route('public.products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-arrow-right ml-2"></i>{{ __('products.by_facility.view_all_properties') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
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

        @if($products->count() > 0)
            <!-- Grid View -->
            <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                        <div class="relative">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                 alt="{{ $product->title }}" class="w-full h-48 object-cover">
                            @if($product->is_featured)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    {{ __('products.property_card.featured') }}
                                </div>
                            @endif
                            @if($product->is_verified)
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check ml-1"></i>{{ __('products.property_card.verified') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                                    {{ $product->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $product->address ?? __('products.property_card.location_unknown') }}</p>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                @foreach($product->card_attributes as $attribute)
                                    <span>
                                        @if($attribute->icon)
                                            <i class="{{ $attribute->icon }} ml-1"></i>
                                        @else
                                            <i class="fas fa-info-circle ml-1"></i>
                                        @endif
                                        {{ $attribute->pivot->value }}
                                        @if($attribute->Symbol)
                                            {{ $attribute->Symbol }}
                                        @else
                                            {{ $attribute->translations->first()->name ?? $attribute->type }}
                                        @endif
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-lg font-bold text-primary-600">
                                    {{ number_format($product->price) }} ريال
                                </div>
                                <a href="{{ route('public.products.show', $product) }}"
                                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ __('products.property_card.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Row View (Hidden by default) -->
            <div id="products-row" class="hidden space-y-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                        <div class="flex">
                            <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
                                <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                     alt="{{ $product->title }}" class="w-full h-full object-cover">
                                @if($product->is_featured)
                                    <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                        {{ __('products.property_card.featured') }}
                                    </div>
                                @endif
                                @if($product->is_verified)
                                    <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-check ml-1"></i>{{ __('products.property_card.verified') }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                                            {{ $product->title }}
                                        </a>
                                    </h3>
                                    <div class="text-lg font-bold text-primary-600">
                                        {{ number_format($product->price) }} ريال
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mb-3">{{ $product->address ?? __('products.property_card.location_unknown') }}</p>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    @foreach($product->card_attributes as $attribute)
                                        <span>
                                            @if($attribute->icon)
                                                <i class="{{ $attribute->icon }} ml-1"></i>
                                            @else
                                                <i class="fas fa-info-circle ml-1"></i>
                                            @endif
                                            {{ $attribute->pivot->value }}
                                            @if($attribute->Symbol)
                                                {{ $attribute->Symbol }}
                                            @else
                                                {{ $attribute->translations->first()->name ?? $attribute->type }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>

                                <div class="flex justify-end">
                                    <a href="{{ route('public.products.show', $product) }}"
                                       class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                        {{ __('products.property_card.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('products.by_facility.no_properties') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('products.by_facility.no_properties_message', ['facility' => $facility->name]) }}</p>
                    <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('products.by_facility.view_all_properties') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Facility Details -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('products.by_facility.about_facility', ['facility' => $facility->name]) }}</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">
                        {{ $facility->description ?? __('products.by_facility.facility_description') }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-map-marker-alt text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('products.show.contact_info') }}</h3>
                                <p class="text-gray-600">{{ $facility->address ?? __('products.show.not_specified') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-phone text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('products.show.contact_info') }}</h3>
                                <p class="text-gray-600">{{ $facility->phone ?? __('products.show.not_specified') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-envelope text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('products.show.contact_info') }}</h3>
                                <p class="text-gray-600">{{ $facility->email ?? __('products.show.not_specified') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-home text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('products.categories.properties_count') }}</h3>
                                <p class="text-gray-600">{{ $facility->products_count ?? 0 }} {{ __('products.categories.properties_count') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $facility->name }}" class="rounded-lg shadow-xl w-full">
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
    const productsGridView = document.getElementById('products-grid');
    const productsRowView = document.getElementById('products-row');
    const gridBtn = document.getElementById('grid-view');
    const rowBtn = document.getElementById('row-view');
    
    if (viewType === 'grid') {
        productsGridView.classList.remove('hidden');
        productsRowView.classList.add('hidden');
        gridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        gridBtn.classList.add('bg-primary-600', 'text-white');
        rowBtn.classList.remove('bg-primary-600', 'text-white');
        rowBtn.classList.add('bg-gray-200', 'text-gray-600');
    } else {
        productsRowView.classList.remove('hidden');
        productsGridView.classList.add('hidden');
        rowBtn.classList.remove('bg-gray-200', 'text-gray-600');
        rowBtn.classList.add('bg-primary-600', 'text-white');
        gridBtn.classList.remove('bg-primary-600', 'text-white');
        gridBtn.classList.add('bg-gray-200', 'text-gray-600');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('productsByFacilityPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('productsByFacilityPreferredView') || 'grid';
    switchView(preferredView);
});
</script>
@endpush
@endsection
