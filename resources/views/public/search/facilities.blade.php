@extends('layouts.app')

@section('title', __('public.facilities.search'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('public.facilities.search') }}</h1>
        
        <!-- Search Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('public.search.facilities') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.search_term') }}</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('public.search.enter_search_term') }}">
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.common.category') }}</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                id="category_id" name="category_id">
                            <option value="">{{ __('public.search.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->getTranslatedName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.common.status') }}</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                id="status_id" name="status_id">
                            <option value="">{{ __('public.common.all') }} {{ __('public.common.status') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" 
                                        {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-search mr-2"></i> {{ __('public.search.title') }}
                        </button>
                    </div>
                </div>
                
                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-between">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.advanced') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-cog mr-2"></i> {{ __('public.search.advanced_search') }}
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="sort" class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.sort_by') }}:</label>
                        <select class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                id="sort" name="sort" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('public.advanced_search.latest') }}</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('public.common.name') }}: {{ __('public.search.a_to_z') }}</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('public.common.name') }}: {{ __('public.search.z_to_a') }}</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('public.common.rating') }}</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('public.search.results') }}
                    @if($facilities->total() > 0)
                        <span class="text-sm font-normal text-gray-600">
                            ({{ $facilities->total() }} {{ __('public.search.facilities_found') }})
                        </span>
                    @endif
                </h2>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.map') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-map mr-2"></i> {{ __('public.search.map_view') }}
                </a>
            </div>
        </div>

        <!-- Results -->
        @if($facilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($facilities as $facility)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Facility Logo/Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($facility->logo)
                            <img src="{{ asset('storage/' . $facility->logo) }}" 
                                 alt="{{ $facility->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <i class="fas fa-building text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Verification Badge -->
                        @if($facility->is_verified)
                            <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">
                                <i class="fas fa-check mr-1"></i> {{ __('public.search.verified') }}
                            </span>
                        @endif
                        
                        <!-- Featured Badge -->
                        @if($facility->is_featured)
                            <span class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-medium">
                                <i class="fas fa-star mr-1"></i> {{ __('public.search.featured') }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Facility Details -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">
                            {{ $facility->name }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ $facility->address }}
                        </p>
                        
                        <!-- Category -->
                        <div class="mb-3">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $facility->facilityCategory->name ?? __('public.search.n_a') }}
                            </span>
                        </div>
                        
                        <!-- Rating -->
                        @if($facility->rating > 0)
                        <div class="flex items-center mb-3">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $facility->rating)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">
                                {{ number_format($facility->rating, 1) }} ({{ $facility->reviews_count ?? 0 }} {{ __('public.search.reviews') }})
                            </span>
                        </div>
                        @endif
                        
                        <!-- Contact Info -->
                        <div class="mb-4 text-sm text-gray-600">
                            @if($facility->phone)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span>{{ $facility->phone }}</span>
                            </div>
                            @endif
                            
                            @if($facility->email)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span>{{ $facility->email }}</span>
                            </div>
                            @endif
                            
                            @if($facility->website)
                            <div class="flex items-center">
                                <i class="fas fa-globe mr-2 text-gray-400"></i>
                                <a href="{{ $facility->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ __('public.search.website') }}
                                </a>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Description -->
                        @if($facility->description)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            {{ Str::limit($facility->description, 100) }}
                        </p>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('public.facilities.show', $facility->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                {{ __('public.search.view_details') }}
                            </a>
                            <a href="{{ route('public.facilities.appointment.form', $facility->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-calendar mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $facilities->appends(request()->query())->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ __('public.search.no_facilities_found') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('public.search.try_adjusting_facilities') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ \App\Helpers\SearchHelper::buildSearchRoute('public.search.advanced') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-cog mr-2"></i> {{ __('public.search.advanced_search') }}
                    </a>
                    <a href="{{ route('public.facilities.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-list mr-2"></i> {{ __('public.search.browse_all_facilities') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
