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

        <x-multi-view-grid 
            :items="$cities" 
            type="cities" 
            :showViewToggle="false"
            idPrefix="cities"
        />

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
