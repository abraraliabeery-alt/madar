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

        <!-- Search and Filter Section -->
        <div class="bg-white shadow-md rounded-lg mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <form action="{{ route('public.cities.index') }}" method="GET" class="space-y-6" id="cities-filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="q" class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.search.title') }}</label>
                            <input type="text" name="q" id="q" value="{{ request('q') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('cities.search.placeholder') }}">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.search.category') }}</label>
                            <select name="category" id="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">{{ __('cities.search.all_categories') }}</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->getTranslatedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="facility" class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.search.facility') }}</label>
                            <select name="facility" id="facility"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">{{ __('cities.search.all_facilities') }}</option>
                                @foreach($facilities ?? [] as $facility)
                                    <option value="{{ $facility->id }}" {{ request('facility') == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">{{ __('cities.search.sort_by') }}</label>
                            <select name="sort_by" id="sort_by"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="name" {{ request('sort_by', 'name') == 'name' ? 'selected' : '' }}>{{ __('cities.search.sort_name') }}</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('cities.search.sort_latest') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            <i class="fas fa-search ml-2"></i>{{ __('cities.search.button') }}
                        </button>
                        <a href="{{ route('public.cities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                            {{ __('cities.search.clear_filters') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cities Grid using Global Multi-View Component -->
        <x-multi-view-grid 
            :items="$cities" 
            type="cities" 
            :showViewToggle="true"
            :showPagination="true"
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
