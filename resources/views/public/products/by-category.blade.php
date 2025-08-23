@extends('layouts.app')

@section('title', __('products.by_category.title', ['category' => $category->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('products.by_category.title', ['category' => $category->name]) }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('products.by_category.subtitle', ['category' => $category->name]) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Category Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-primary-100 p-3 rounded-full">
                        <i class="fas fa-home text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                        <p class="text-gray-600">{{ $products->total() }} {{ __('products.by_category.properties_available') }}</p>
                    </div>
                </div>
                <a href="{{ route('public.products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-arrow-right ml-2"></i>{{ __('products.by_category.view_all_properties') }}
                </a>
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
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('products.by_category.no_properties') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('products.by_category.no_properties_message', ['category' => $category->name]) }}</p>
                    <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('products.by_category.view_all_properties') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Category Description -->
    @if($category->description)
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('products.by_category.category_description', ['category' => $category->name]) }}</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        {{ $category->description }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Related Categories -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('products.by_category.other_categories') }}</h2>
                <p class="text-lg text-gray-600">{{ __('products.by_category.browse_other_categories') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories ?? [] as $relatedCategory)
                    @if($relatedCategory->id !== $category->id)
                        <a href="{{ route('public.products.by-category', $relatedCategory) }}"
                           class="bg-white rounded-lg p-6 text-center hover:bg-primary-50 transition-colors shadow-md">
                            <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-home text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedCategory->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $relatedCategory->products_count ?? 0 }} {{ __('products.categories.properties_count') }}</p>
                        </a>
                    @endif
                @endforeach
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
    localStorage.setItem('productsByCategoryPreferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('productsByCategoryPreferredView') || 'grid';
    switchView(preferredView);
});
</script>
@endpush
@endsection
