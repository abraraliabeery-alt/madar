@props([
    'items' => [],
    'type' => 'products', // 'products', 'execution_requests', 'cities', 'categories', 'facilities'
    'title' => '',
    'viewAllRoute' => '',
    'viewAllText' => '',
    'maxItems' => 6,
    'idPrefix' => 'items',
    'showPagination' => false,
    'showViewToggle' => false,
    'showPrice' => true,
    'loadMore' => false,
])

@php
    $gridId = $idPrefix;
    // When loadMore is enabled, keep all items to reveal later; otherwise apply maxItems
    if (!$showPagination && !$loadMore) {
        $items = $items->take($maxItems);
    }
@endphp

@if($items->count() > 0)
<section class="mb-12 sm:mb-16">
    @if($title)
    <div class="flex justify-between items-center gap-3 mb-5 sm:mb-8">
        <h2 class="text-xl sm:text-2xl font-bold">{{ $title }}</h2>
        @if($type === 'cities' || $type === 'categories')
            <div class="flex gap-2 flex-shrink-0">
                <button type="button" onclick="document.getElementById('{{ $gridId }}-small-grid').scrollBy({ left: -200, behavior: 'smooth' })" class="bg-primary-600 text-white w-8 h-8 rounded-full hover:bg-primary-700 flex items-center justify-center">
                    ‹
                </button>
                <button type="button" onclick="document.getElementById('{{ $gridId }}-small-grid').scrollBy({ left: 200, behavior: 'smooth' })" class="bg-primary-600 text-white w-8 h-8 rounded-full hover:bg-primary-700 flex items-center justify-center">
                    ›
                </button>
            </div>
        @endif
    </div>
    @endif

    @if($showViewToggle)
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
    @endif

    <!-- Small Grid View -->
    <div id="{{ $gridId }}-small-grid" class="{{ ($type === 'cities' || $type === 'categories') ? 'flex flex-nowrap overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar gap-2 sm:gap-3 pb-2' : 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4' }}">
        @foreach($items as $item)
            @if($type === 'products')
                <div class="bg-white dark:bg-secondary-900 border border-gray-200 dark:border-secondary-800 rounded-lg overflow-hidden hover:shadow-md transition-shadow {{ ($loadMore && $loop->index >= $maxItems) ? 'extra-' . $gridId . ' hidden' : '' }}">
                    <div class="relative h-32 bg-gray-100 dark:bg-secondary-800">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 alt="{{ $item->title }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-product.svg') }}'; this.classList.replace('object-cover','object-contain');">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-home text-2xl text-gray-400 dark:text-gray-300"></i>
                            </div>
                        @endif
                        @if($item->is_featured)
                            <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs">
                                {{ __('general.status.featured') }}
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm mb-1 line-clamp-1">{{ $item->title }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mb-2 line-clamp-2">{{ $item->description }}</p>

                        @if($item->card_attributes && $item->card_attributes->count() > 0)
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach($item->card_attributes->take(2) as $attribute)
                                    <span class="inline-flex items-center gap-1 rtl:flex-row-reverse bg-gray-50 dark:bg-secondary-800 border border-gray-100 dark:border-secondary-700 px-2 py-1 rounded text-[11px] text-gray-700 dark:text-gray-200">
                                        @if($attribute->icon)
                                            <i class="{{ $attribute->icon }} text-primary-700"></i>
                                        @else
                                            <i class="fas fa-info-circle text-primary-700"></i>
                                        @endif
                                        <span class="font-semibold">{{ $attribute->pivot->value }}</span>
                                        @if($attribute->Symbol)
                                            <span class="text-gray-500 dark:text-gray-300">{{ $attribute->Symbol }}</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if(!$showPrice)
                            <div class="text-xs text-gray-500 mb-2">
                                <div class="flex items-center gap-2 rtl:flex-row-reverse flex-wrap">
                                    @if(!empty($item->area))
                                        <span class="inline-flex items-center gap-1 rtl:flex-row-reverse">
                                            <i class="fas fa-ruler-combined"></i>
                                            <span>{{ number_format($item->area) }} م²</span>
                                        </span>
                                    @endif
                                    @if(!empty($item->facility) && !empty($item->facility->name))
                                        <span class="inline-flex items-center gap-1 rtl:flex-row-reverse">
                                            <i class="fas fa-building"></i>
                                            <span class="line-clamp-1">{{ $item->facility->name }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if($showPrice)
                            <div class="text-sm font-semibold text-primary-600 mb-2">
                                @if($item->hasActiveOffers())
                                    <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 inline mr-1">
                                    {{ $item->getFormattedPrice() }}
                                @else
                                    {{ __('products.actions.price_on_request') }}
                                @endif
                            </div>
                        @endif
                        <a href="{{ route('public.products.show', $item) }}" 
                           class="text-primary-600 hover:text-primary-700 text-xs font-medium">
                            {{ __('general.actions.view_details') }}
                        </a>
                    </div>
                </div>
            @elseif($type === 'execution_requests')
                @php
                    $translation = $item->translations->firstWhere('locale', app()->getLocale());
                @endphp
                <div class="bg-white dark:bg-secondary-900 border border-gray-200 dark:border-secondary-800 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <div class="relative h-32 bg-gray-100 dark:bg-secondary-800 flex items-center justify-center">
                        <i class="fas fa-gavel text-2xl text-gray-400 dark:text-gray-300"></i>
                    </div>
                    <div class="p-3">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm mb-1 line-clamp-1">
                            {{ $translation->title ?? ('طلب #' . $item->id) }}
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mb-2 line-clamp-2">
                            {{ $translation->description ?? '' }}
                        </p>
                        <a href="{{ route('public.execution.show', $item) }}" class="text-primary-600 hover:text-primary-700 text-xs font-medium">
                            {{ __('general.actions.view_details') }}
                        </a>
                    </div>
                </div>
            @elseif($type === 'cities')
                <a href="{{ route('public.products.index', ['city' => $item->id]) }}" class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow block w-28 sm:w-36 flex-shrink-0 snap-start">
                    <div class="relative h-20 sm:h-24 bg-gray-100">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        <div class="absolute top-1 right-1 bg-white text-primary-600 px-1 py-0.5 rounded text-[10px]">
                            {{ $item->products_count }}
                        </div>
                    </div>
                    <div class="p-2 text-center">
                        <h3 class="font-medium text-gray-900 text-[11px] line-clamp-1">@cityName($item)</h3>
                    </div>
                </a>
                         @elseif($type === 'categories')
                <a href="{{ route('public.products.by-category', $item->id) }}" class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow block w-28 sm:w-36 flex-shrink-0 snap-start">
                    <div class="relative h-20 sm:h-24 bg-gray-100 flex items-center justify-center">
                        @if($item->icon)
                            <i class="{{ $item->icon }} text-2xl text-primary-600"></i>
                        @else
                            <i class="fas fa-building text-2xl text-primary-600"></i>
                        @endif
                        <div class="absolute top-1 right-1 bg-white text-primary-600 px-1 py-0.5 rounded text-[10px]">
                            {{ $item->products_count }}
                        </div>
                    </div>
                    <div class="p-2 text-center">
                        <h3 class="font-medium text-gray-900 text-[11px] line-clamp-1">{{ $item->display_name ?? App\Helpers\LanguageHelper::getCategoryName($item) }}</h3>
                    </div>
                </a>
             @elseif($type === 'facilities')
                 <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                     <div class="relative">
                         <img src="{{ $item->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                              alt="{{ $item->name }}" class="w-full h-32 object-cover">
                         @if($item->is_featured)
                             <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                 {{ __('facilities.facility_card.featured') }}
                             </div>
                         @endif
                         @if($item->is_verified)
                             <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                 <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                             </div>
                         @endif
                     </div>
                     <div class="p-3">
                         <div class="flex items-start justify-between mb-2">
                             <h3 class="text-sm font-semibold text-gray-900 line-clamp-1">
                                 <a href="{{ route('facility.site.home', $item->slug ?? $item->id) }}" class="hover:text-primary-600 transition-colors">
                                     {{ $item->name }}
                                 </a>
                             </h3>
                             <div class="flex items-center text-yellow-400 text-xs">
                                 @for($i = 1; $i <= 5; $i++)
                                     <i class="fas fa-star {{ $i <= ($item->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                 @endfor
                             </div>
                         </div>

                         <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $item->description ?? __('facilities.facility_card.no_description') }}</p>

                         <div class="flex items-center text-xs text-gray-500 mb-2">
                             <i class="fas fa-map-marker-alt ml-1"></i>
                             <span class="line-clamp-1">{{ $item->location ?? __('facilities.facility_card.location_unknown') }}</span>
                         </div>

                         <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                             <span><i class="fas fa-home ml-1"></i>{{ $item->products_count ?? 0 }}</span>
                             <span><i class="fas fa-calendar ml-1"></i>{{ $item->created_at ? $item->created_at->diffForHumans() : __('facilities.show.not_specified') }}</span>
                         </div>

                         <div class="flex items-center justify-between">
                             <a href="{{ route('facility.site.home', $item->slug ?? $item->id) }}"
                               class="btn btn-primary text-white px-3 py-1 rounded text-xs font-medium">
                                 {{ __('facilities.facility_card.view_facility') }}
                             </a>
                             <a href="{{ route('public.products.by-facility', $item) }}"
                                class="text-primary-600 hover:text-primary-700 text-xs font-medium">
                                 {{ __('facilities.facility_card.view_properties') }}
                             </a>
                         </div>
                     </div>
                 </div>
             @endif
        @endforeach
    </div>

    <!-- Large Grid View (Hidden by default) -->
    <div id="{{ $gridId }}-large-grid" class="hidden">
        @if($type === 'execution_requests')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    @php
                        $translation = $item->translations->firstWhere('locale', app()->getLocale());
                    @endphp
                    <div class="bg-white dark:bg-secondary-900 border border-gray-200 dark:border-secondary-800 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="relative h-40 bg-gray-100 dark:bg-secondary-800 flex items-center justify-center">
                            <i class="fas fa-gavel text-4xl text-gray-400 dark:text-gray-300"></i>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ $translation->title ?? ('طلب #' . $item->id) }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                {{ $translation->description ?? '' }}
                            </p>
                            <a href="{{ route('public.execution.show', $item) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                {{ __('general.actions.view_details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($type === 'products')
            <x-product-grid :products="$items" :columns="3" :showPrice="$showPrice" />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $type === 'categories' ? '4' : '3' }} gap-6">
                @foreach($items as $item)
                    @if($type === 'cities')
                        <a href="{{ route('public.products.index', ['city' => $item->id]) }}" class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow block">
                            <div class="relative h-40 bg-gray-100">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 bg-white text-primary-600 px-2 py-1 rounded text-sm">
                                    {{ $item->products_count }} {{ __('general.status.property') }}
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-2">@cityName($item)</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">@cityDescription($item)</p>
                            </div>
                        </a>
                                         @elseif($type === 'categories')
                         <a href="{{ route('public.products.by-category', $item->id) }}" class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow block">
                             @if($item->icon)
                                 <i class="{{ $item->icon }} text-4xl text-primary-600 mb-4"></i>
                             @else
                                 <i class="fas fa-building text-4xl text-primary-600 mb-4"></i>
                             @endif
                             <h3 class="font-medium text-gray-900 mb-2">{{ $item->display_name ?? App\Helpers\LanguageHelper::getCategoryName($item) }}</h3>
                             <p class="text-sm text-gray-600 mb-3 line-clamp-2">@categoryDescription($item)</p>
                             <div class="text-sm text-gray-500 mb-4">{{ $item->products_count }} {{ __('general.status.property') }}</div>
                         </a>
                     @elseif($type === 'facilities')
                         <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                             <div class="relative">
                                 <img src="{{ $item->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                      alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                 @if($item->is_featured)
                                     <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                         {{ __('facilities.facility_card.featured') }}
                                     </div>
                                 @endif
                                 @if($item->is_verified)
                                     <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                         <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                                     </div>
                                 @endif
                             </div>
                             <div class="p-6">
                                 <div class="flex items-start justify-between mb-4">
                                     <h3 class="text-xl font-semibold text-gray-900">
                                         <a href="{{ route('facility.site.home', $item->slug ?? $item->id) }}" class="hover:text-primary-600 transition-colors">
                                             {{ $item->name }}
                                         </a>
                                     </h3>
                                     <div class="flex items-center text-yellow-400">
                                         @for($i = 1; $i <= 5; $i++)
                                             <i class="fas fa-star {{ $i <= ($item->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                         @endfor
                                         <span class="text-sm text-gray-600 mr-2">({{ $item->rating ?? 0 }})</span>
                                     </div>
                                 </div>

                                 <p class="text-gray-600 text-sm mb-4">{{ $item->description ?? __('facilities.facility_card.no_description') }}</p>

                                 <div class="flex items-center text-sm text-gray-500 mb-4">
                                     <i class="fas fa-map-marker-alt ml-2"></i>
                                     <span>{{ $item->location ?? __('facilities.facility_card.location_unknown') }}</span>
                                 </div>

                                 <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                     <span><i class="fas fa-home ml-1"></i>{{ $item->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                     <span><i class="fas fa-calendar ml-1"></i>{{ $item->created_at ? $item->created_at->diffForHumans() : __('facilities.show.not_specified') }}</span>
                                 </div>

                                 <div class="flex items-center justify-between">
                                     <a href="{{ route('facility.site.home', $item->slug ?? $item->id) }}"
                                       class="btn btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                         {{ __('facilities.facility_card.view_facility') }}
                                     </a>
                                     <a href="{{ route('public.products.by-facility', $item) }}"
                                        class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                         {{ __('facilities.facility_card.view_properties') }}
                                     </a>
                                 </div>
                             </div>
                         </div>
                     @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- List View (Hidden by default) -->
    <div id="{{ $gridId }}-list" class="hidden space-y-4">
        @foreach($items as $item)
            @if($type === 'products')
                <x-product-card-row :product="$item" :showPrice="$showPrice" />
            @elseif($type === 'execution_requests')
                <x-execution-request-card-row :request="$item" />
            @else
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row {{ $type === 'cities' || $type === 'categories' ? 'cursor-pointer' : '' }}"
                         @if($type === 'cities' || $type === 'categories')
                         onclick="window.location.href='{{ $type === 'cities' ? route('public.products.index', ['city' => $item->id]) : route('public.products.by-category', $item->id) }}'"
                         @endif>
                        @if($type === 'cities')
                        <div class="relative w-full sm:w-48 h-40 sm:h-32 bg-gray-100 flex-shrink-0">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2 bg-white text-primary-600 px-2 py-1 rounded text-sm">
                                {{ $item->products_count }} {{ __('general.status.property') }}
                            </div>
                        </div>
                        <div class="flex-1 p-4">
                            <h3 class="font-medium text-gray-900 text-lg mb-2">@cityName($item)</h3>
                            <p class="text-sm text-gray-600 mb-3">@cityDescription($item)</p>
                        </div>
                                         @elseif($type === 'categories')
                         <div class="w-full sm:w-48 h-32 bg-gray-100 flex-shrink-0 flex items-center justify-center">
                             @if($item->icon)
                                 <i class="{{ $item->icon }} text-4xl text-primary-600"></i>
                             @else
                                 <i class="fas fa-building text-4xl text-primary-600"></i>
                             @endif
                         </div>
                         <div class="flex-1 p-4">
                             <h3 class="font-medium text-gray-900 text-lg mb-2">{{ $item->display_name ?? App\Helpers\LanguageHelper::getCategoryName($item) }}</h3>
                             <p class="text-sm text-gray-600 mb-3">@categoryDescription($item)</p>
                             <div class="text-sm text-gray-500 mb-4">{{ $item->products_count }} {{ __('general.status.property') }}</div>
                         </div>
                     @elseif($type === 'facilities')
                         <div class="relative w-full sm:w-48 h-40 sm:h-32 bg-gray-100 flex-shrink-0">
                             <img src="{{ $item->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                  alt="{{ $item->name }}" class="w-full h-full object-cover">
                             @if($item->is_featured)
                                 <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                     {{ __('facilities.facility_card.featured') }}
                                 </div>
                             @endif
                             @if($item->is_verified)
                                 <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                     <i class="fas fa-check ml-1"></i>{{ __('facilities.facility_card.verified') }}
                                 </div>
                             @endif
                         </div>
                         <div class="flex-1 p-6">
                             <div class="flex justify-between items-start mb-3">
                                 <h3 class="text-xl font-semibold text-gray-900">
                                     <a href="{{ route('facility.site.home', $item->slug ?? $item->id) }}" class="hover:text-primary-600 transition-colors">
                                         {{ $item->name }}
                                     </a>
                                 </h3>
                                 <div class="flex items-center text-yellow-400">
                                     @for($i = 1; $i <= 5; $i++)
                                         <i class="fas fa-star {{ $i <= ($item->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                     @endfor
                                     <span class="text-sm text-gray-600 mr-2">({{ $item->rating ?? 0 }})</span>
                                 </div>
                             </div>

                             <p class="text-gray-600 text-sm mb-4">{{ $item->description ?? __('facilities.facility_card.no_description') }}</p>

                             <div class="flex items-center text-sm text-gray-500 mb-4">
                                 <i class="fas fa-map-marker-alt ml-2"></i>
                                 <span>{{ $item->location ?? __('facilities.facility_card.location_unknown') }}</span>
                             </div>

                             <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                 <span><i class="fas fa-home ml-1"></i>{{ $item->products_count ?? 0 }} {{ __('facilities.facility_card.properties') }}</span>
                                 <span><i class="fas fa-calendar ml-1"></i>{{ $item->created_at ? $item->created_at->diffForHumans() : __('facilities.show.not_specified') }}</span>
                             </div>

                             <div class="flex items-center justify-between">
                                 <a href="{{ route('facility.site.home', $item->slug ?? $item->id) }}"
                                   class="btn btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                     {{ __('facilities.facility_card.view_facility') }}
                                 </a>
                                 <a href="{{ route('public.products.by-facility', $item) }}"
                                    class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                     {{ __('facilities.facility_card.view_properties') }}
                                 </a>
                             </div>
                         </div>
                     @endif
                </div>
            </div>
            @endif
        @endforeach
    </div>
    
    @if($viewAllText)
        @if($loadMore && $items->count() > $maxItems)
            <div class="text-center mt-8">
                <button type="button" onclick="document.querySelectorAll('.extra-{{ $gridId }}').forEach(el => el.classList.remove('hidden')); this.parentElement.style.display='none';" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                    {{ $viewAllText }}
                </button>
            </div>
        @elseif($viewAllRoute)
            <div class="text-center mt-8">
                <a href="{{ $viewAllRoute }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                    {{ $viewAllText }}
                </a>
            </div>
        @endif
    @endif
     
     @if($showPagination && method_exists($items, 'hasPages') && $items->hasPages())
     <div class="mt-12">
         {{ $items->links() }}
     </div>
     @endif
 </section>
 @endif

 @push('styles')
 <style>
 .line-clamp-1 {
     display: -webkit-box;
     -webkit-line-clamp: 1;
     -webkit-box-orient: vertical;
     overflow: hidden;
 }

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

 .view-toggle-btn {
     transition: all 0.2s ease-in-out;
 }

 .view-toggle-btn:hover {
     transform: scale(1.05);
 }

 .card-hover {
     transition: all 0.2s ease-in-out;
 }

 .card-hover:hover {
     transform: translateY(-2px);
     box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
 }
 </style>
 @endpush

 @push('scripts')
 <script>
 /**
  * Switch between different view modes (small-grid, large-grid, list)
  * Includes validation to prevent invalid view types from hiding content
  * @param {string} viewType - The view type to switch to
  */
 function switchView(viewType) {
     // Get all grid containers dynamically (skip city/category sliders which are always small)
     const gridContainers = document.querySelectorAll('[id$="-small-grid"]:not([id^="cities-"]):not([id^="categories-"]), [id$="-large-grid"]:not([id^="cities-"]):not([id^="categories-"]), [id$="-list"]:not([id^="cities-"]):not([id^="categories-"])');
     
     // Toggle buttons
     const smallGridBtn = document.getElementById('small-grid-view');
     const largeGridBtn = document.getElementById('large-grid-view');
     const listBtn = document.getElementById('list-view');
     
     // Validate view type - only allow valid options
     const validViewTypes = ['small-grid', 'large-grid', 'list'];
     if (!validViewTypes.includes(viewType)) {
         console.warn(`Invalid view type "${viewType}" detected. Falling back to list view.`);
         viewType = 'list';
         // Clear invalid preference from localStorage
         localStorage.removeItem('preferredView');
     }
     
     // Hide all views first
     gridContainers.forEach(container => {
         container.classList.add('hidden');
     });
     
     // Reset all button styles
     [smallGridBtn, largeGridBtn, listBtn].forEach(btn => {
         if (btn) {
             btn.classList.remove('bg-primary-600', 'text-white');
             btn.classList.add('bg-gray-200', 'text-gray-600');
         }
     });
     
     // Show the selected view type
     const targetSuffix = viewType === 'small-grid' ? '-small-grid' : 
                         viewType === 'large-grid' ? '-large-grid' : '-list';
     
     const targetContainers = document.querySelectorAll(`[id$="${targetSuffix}"]:not([id^="cities-"]):not([id^="categories-"])`);
     targetContainers.forEach(container => {
         container.classList.remove('hidden');
     });
     
     // Update button styles
     let activeBtn = null;
     if (viewType === 'small-grid' && smallGridBtn) {
         activeBtn = smallGridBtn;
     } else if (viewType === 'large-grid' && largeGridBtn) {
         activeBtn = largeGridBtn;
     } else if (viewType === 'list' && listBtn) {
         activeBtn = listBtn;
     }
     
     if (activeBtn) {
         activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
         activeBtn.classList.add('bg-primary-600', 'text-white');
     }
     
     // Store user preference in localStorage only if it's valid
     if (validViewTypes.includes(viewType)) {
         localStorage.setItem('preferredView', viewType);
     }
     
     // Safety check: if no view containers exist, show small grid as fallback
     if (gridContainers.length === 0) {
         console.warn('No view containers found. This might indicate a rendering issue.');
     }
 }

 // Set initial view based on user preference
 document.addEventListener('DOMContentLoaded', function() {
     const preferredView = localStorage.getItem('preferredView');
     const validViewTypes = ['small-grid', 'large-grid', 'list'];
     
     // Use preferred view if valid, otherwise default to list view
     const initialView = validViewTypes.includes(preferredView) ? preferredView : 'list';
     
     // If no valid preference exists, set list as default
     if (!preferredView || !validViewTypes.includes(preferredView)) {
         localStorage.setItem('preferredView', 'list');
     }
     
     switchView(initialView);
 });
 </script>
 @endpush
