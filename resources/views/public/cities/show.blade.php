@extends('layouts.app')

@section('title', __('cities.show.title', ['city' => $city->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $city->name }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ $city->description ?? __('cities.show.default_description') }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-building text-xl mr-2"></i>
                            <span class="text-white">{{ $city->products_count ?? 0 }} {{ __('cities.show.properties') }}</span>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-store text-xl mr-2"></i>
                            <span class="text-white">{{ $city->facilities_count ?? 0 }} {{ __('cities.show.facilities') }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    @if($city->image)
                        <img src="{{ asset('storage/' . $city->image) }}" 
                             alt="{{ $city->name }}" class="rounded-lg shadow-xl w-full">
                    @else
                        <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg shadow-xl w-full h-64 flex items-center justify-center">
                            <i class="fas fa-city text-white text-8xl"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- City Stats -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-home text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $city->products_count ?? 0 }}</h3>
                    <p class="text-gray-600">{{ __('cities.show.properties_available') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-primary-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-building text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $city->facilities_count ?? 0 }}</h3>
                    <p class="text-gray-600">{{ __('cities.show.facilities_available') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-primary-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-star text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('cities.show.featured') }}</h3>
                    <p class="text-gray-600">{{ $city->is_featured ? __('cities.show.yes') : __('cities.show.no') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('cities.show.explore_city', ['city' => $city->name]) }}</h2>
            <p class="text-lg text-gray-600 mb-8">{{ __('cities.show.explore_description') }}</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.cities.products', $city) }}" 
                   class="bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors text-lg font-medium">
                    <i class="fas fa-home ml-2"></i>{{ __('cities.show.view_properties') }}
                </a>
                <a href="{{ route('public.cities.facilities', $city) }}" 
                   class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors text-lg font-medium">
                    <i class="fas fa-building ml-2"></i>{{ __('cities.show.view_facilities') }}
                </a>
            </div>
        </div>
    </div>

    <!-- City Description -->
    @if($city->description)
        <div class="bg-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('cities.show.about_city', ['city' => $city->name]) }}</h2>
                </div>
                <div class="prose prose-lg mx-auto text-gray-600">
                    <p class="text-lg leading-relaxed">{{ $city->description }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Links -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('cities.show.quick_links') }}</h2>
                <p class="text-lg text-gray-600">{{ __('cities.show.quick_links_description') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('public.products.index') }}" 
                   class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                    <div class="bg-primary-100 p-3 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-home text-primary-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('cities.show.all_properties') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('cities.show.all_properties_description') }}</p>
                </a>
                
                <a href="{{ route('public.facilities.index') }}" 
                   class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                    <div class="bg-primary-100 p-3 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-building text-primary-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('cities.show.all_facilities') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('cities.show.all_facilities_description') }}</p>
                </a>
                
                <a href="{{ route('public.categories.index') }}" 
                   class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                    <div class="bg-primary-100 p-3 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-th-large text-primary-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('cities.show.categories') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('cities.show.categories_description') }}</p>
                </a>
                
                <a href="{{ route('public.contact') }}" 
                   class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                    <div class="bg-primary-100 p-3 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-envelope text-primary-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('cities.show.contact') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('cities.show.contact_description') }}</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
