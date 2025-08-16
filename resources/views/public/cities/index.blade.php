@extends('layouts.app')

@section('title', __('cities.title'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-4">
                <x-language-switcher :showFlags="true" :showNames="true" class="mb-4" />
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('cities.title') }}
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('cities.subtitle') }}
            </p>
        </div>

        <!-- Cities Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($cities as $city)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    @if($city->image)
                        <div class="h-48 bg-gray-200">
                            <img src="{{ asset('storage/' . $city->image) }}" 
                                 alt="{{ __('cities.city_image') }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-city text-white text-6xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ app()->getLocale() === 'en' && $city->name_en ? $city->name_en : $city->name }}
                        </h3>
                        
                        @if($city->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $city->description }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>
                                <i class="fas fa-building ml-1"></i>
                                {{ $city->products_count ?? 0 }} 
                                {{ __('cities.properties') }}
                            </span>
                            <span>
                                <i class="fas fa-store ml-1"></i>
                                {{ $city->facilities_count ?? 0 }}
                                {{ __('cities.facilities') }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ route('public.products.index', ['city' => $city->id]) }}" 
                               class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors duration-200 text-sm">
                                {{ __('cities.view_properties') }}
                            </a>
                            <a href="{{ route('public.facilities.index', ['city' => $city->id]) }}" 
                               class="flex-1 bg-gray-600 text-white text-center py-2 px-4 rounded-md hover:bg-gray-700 transition-colors duration-200 text-sm">
                                {{ __('cities.view_facilities') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-city text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        {{ __('cities.no_cities_available') }}
                    </h3>
                    <p class="text-gray-500">
                        {{ __('cities.check_back_later') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Load More Button -->
        @if($cities->count() >= 6)
            <div class="text-center mt-12">
                <button class="bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors duration-200 font-semibold">
                    {{ __('cities.load_more_cities') }}
                </button>
            </div>
        @endif
    </div>
</div>
@endsection
