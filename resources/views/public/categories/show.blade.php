@extends('layouts.app')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $category->display_name ?? categoryName($category) }} - {{ config('app.name') }}</title>
<meta name="description" content="@categoryDescription($category)">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Category Header -->
    <div class="text-center mb-12">
        @if($category->icon)
            <i class="{{ $category->icon }} text-6xl text-primary-600 mb-6"></i>
        @else
            <i class="fas fa-building text-6xl text-primary-600 mb-6"></i>
        @endif
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $category->display_name ?? categoryName($category) }}</h1>
        <p class="text-lg text-gray-600 mb-6">@categoryDescription($category)</p>
        
        <div class="text-sm text-gray-500">
            {{ $category->products_count ?? 0 }} {{ __('general.status.property') }}
        </div>
    </div>

    <!-- Subcategories -->
    @if($category->children && $category->children->count() > 0)
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('general.categories.subcategories') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($category->children as $subcategory)
            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow">
                @if($subcategory->icon)
                    <i class="{{ $subcategory->icon }} text-3xl text-primary-600 mb-3"></i>
                @else
                    <i class="fas fa-building text-3xl text-primary-600 mb-3"></i>
                @endif
                
                <h3 class="font-medium text-gray-900 mb-2">{{ $subcategory->display_name ?? categoryName($subcategory) }}</h3>
                <p class="text-sm text-gray-600 mb-3">@categoryDescription($subcategory)</p>
                
                <div class="text-sm text-gray-500 mb-3">
                    {{ $subcategory->products_count ?? 0 }} {{ __('general.status.property') }}
                </div>
                
                <a href="{{ route('public.categories.show', $subcategory->id) }}" 
                   class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                    {{ __('general.actions.browse_category') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Products in this category -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('general.categories.products_in_category') }}</h2>
        
        @if($category->products && $category->products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($category->products->take(9) as $product)
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
        
        @if($category->products->count() > 9)
        <div class="text-center mt-8">
            <a href="{{ route('public.products.by-category', $category->id) }}" 
               class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                {{ __('general.categories.view_all_products') }}
            </a>
        </div>
        @endif
        
        @else
        <div class="text-center py-12">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">{{ __('general.categories.no_products') }}</h3>
            <p class="text-gray-600">{{ __('general.categories.no_products_description') }}</p>
        </div>
        @endif
    </div>

    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ route('public.home') }}" class="hover:text-primary-600">{{ __('general.navigation.home') }}</a>
        <span class="mx-2">/</span>
        <a href="{{ route('public.categories.index') }}" class="hover:text-primary-600">{{ __('general.categories.title') }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">{{ $category->display_name ?? categoryName($category) }}</span>
    </div>
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
</style>
@endpush
