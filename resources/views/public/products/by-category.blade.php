@extends('layouts.app')

@section('title', __('products.by_category.title', ['category' => $category->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('products.by_category.title', ['category' => App\Helpers\LanguageHelper::getCategoryName($category)]) }}</h1>
                    <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                        {{ __('products.by_category.subtitle', ['category' => App\Helpers\LanguageHelper::getCategoryName($category)]) }}
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
                        <h2 class="text-2xl font-bold text-gray-900">{{ App\Helpers\LanguageHelper::getCategoryName($category) }}</h2>
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
        @if($products->count() > 0)
            <x-multi-view-grid 
                :items="$products" 
                type="products" 
                :showPagination="true"
                :showViewToggle="true"
                idPrefix="products"
            />
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('products.by_category.no_properties') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('products.by_category.no_properties_message', ['category' => App\Helpers\LanguageHelper::getCategoryName($category)]) }}</p>
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
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('products.by_category.category_description', ['category' => App\Helpers\LanguageHelper::getCategoryName($category)]) }}</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        @categoryDescription($category)
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">@categoryName($relatedCategory)</h3>
                            <p class="text-gray-600 text-sm">{{ $relatedCategory->products_count ?? 0 }} {{ __('products.categories.properties_count') }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
