@extends('layouts.app')

@section('title', __('facilities.search_results.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('facilities.search_results.title') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('facilities.search_results.subtitle', ['search' => $searchTerm]) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-primary-100 p-3 rounded-full">
                        <i class="fas fa-search text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('facilities.search_results.title') }}</h2>
                        <p class="text-gray-600">{{ $facilities->total() }} {{ __('facilities.search_results.facilities_found') }}</p>
                    </div>
                </div>
                <a href="{{ route('public.facilities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-arrow-right ml-2"></i>{{ __('facilities.search_results.view_all_facilities') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('public.facilities.search') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="flex">
                    <input type="text" name="q" value="{{ $searchTerm }}"
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                           placeholder="{{ __('facilities.search.placeholder') }}">
                    <button type="submit" class="btn-primary text-white px-6 py-3 rounded-l-lg font-medium">
                        <i class="fas fa-search ml-2"></i>{{ __('facilities.search.button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Facilities Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($facilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $facility)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                        <div class="relative">
                            <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                 alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                            @if($facility->is_featured)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    {{ __('facilities.facility_card.featured') }}
                                </div>
                            @endif
                            @if($facility->is_verified)
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}" class="hover:text-primary-600 transition-colors">
                                        {{ $facility->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-sm text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-4">{{ $facility->description ?? __('facilities.facility_card.no_description') }}</p>

                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                <span>{{ $facility->address ?? __('facilities.facility_card.location_unknown') }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                <span><i class="fas fa-calendar ml-1"></i>{{ $facility->created_at ? $facility->created_at->diffForHumans() : __('facilities.show.not_specified') }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}"
                                   class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    {{ __('facilities.facility_card.view_facility') }}
                                </a>
                                <a href="{{ route('public.products.by-facility', $facility) }}"
                                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ __('facilities.facility_card.view_properties') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($facilities->hasPages())
                <div class="mt-12">
                    {{ $facilities->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.results.no_results') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('facilities.search_results.no_results_message', ['search' => $searchTerm]) }}</p>
                    <div class="space-y-3">
                        <p class="text-sm text-gray-500">{{ __('facilities.search_results.try_suggestions') }}</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>{{ __('facilities.search_results.check_keywords') }}</li>
                            <li>{{ __('facilities.search_results.use_different_words') }}</li>
                            <li>{{ __('facilities.search_results.search_single_word') }}</li>
                        </ul>
                    </div>
                    <div class="mt-6 space-x-4 space-x-reverse">
                        <a href="{{ route('public.facilities.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('facilities.results.view_all_facilities') }}
                        </a>
                        <a href="{{ route('public.facilities.search') }}" class="border border-primary-600 text-primary-600 px-6 py-2 rounded-lg font-medium hover:bg-primary-50 transition-colors">
                            {{ __('facilities.search_results.new_search') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Search Tips -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('facilities.search_results.search_tips') }}</h2>
                <p class="text-lg text-gray-600">{{ __('facilities.search_results.search_tips_subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-keyboard text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.search_results.use_keywords') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.search_results.use_keywords_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-filter text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.search_results.use_filters') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.search_results.use_filters_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('facilities.search_results.search_featured') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.search_results.search_featured_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
