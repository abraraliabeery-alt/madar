@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $product->title }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ $product->address ?? __('products.property_card.location_unknown') }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        @if($product->is_featured)
                            <div class="bg-yellow-600 text-white px-3 px-2 py-1 rounded-full text-sm">
                                <i class="fas fa-star ml-1"></i>{{ __('products.property_card.featured') }}
                            </div>
                        @endif
                        @if($product->is_verified)
                            <div class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-check ml-1"></i>{{ __('products.property_card.verified') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->title }}" class="rounded-lg shadow-xl w-full">
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Description Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('products.show.description') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ $product->description ?? __('products.show.no_description') }}
                    </p>

                    @if($product->statuses && $product->statuses->count())
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($product->statuses as $status)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $status->color_class }}-100 text-{{ $status->color_class }}-800">
                                    <i class="{{ $status->icon_class }} ml-1"></i>{{ $status->getTranslatedName() }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($product->card_attributes && $product->card_attributes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($product->card_attributes as $attribute)
                            <div class="text-center">
                                <div class="bg-primary-100 p-4 rounded-lg mb-3">
                                    @if($attribute->icon)
                                        <i class="{{ $attribute->icon }} text-2xl text-primary-600"></i>
                                    @else
                                        <i class="fas fa-info-circle text-2xl text-primary-600"></i>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900">{{ $attribute->pivot->value }}</h3>
                                <p class="text-gray-600 text-sm">
                                    @if($attribute->Symbol)
                                        {{ $attribute->Symbol }}
                                    @else
                                        {{ $attribute->getTranslatedName() ?? ucfirst($attribute->type) }}
                                    @endif
                                </p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">{{ __('products.show.no_attributes') }}</p>
                    @endif
                </div>

                <!-- Gallery Section -->
                @if($product->gallery && $product->gallery->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('products.show.gallery') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($product->gallery as $image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $image }}" alt="{{ $product->title }}"
                                         class="w-full h-48 object-cover rounded-lg hover:opacity-75 transition-opacity cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- All Attributes Section -->
                @if($product->attributes && $product->attributes->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('products.show.all_attributes') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->attributes as $attribute)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-600 font-medium">
                                        @if($attribute->getTranslatedName())
                                            {{ $attribute->getTranslatedName() }}
                                        @else
                                            {{ ucfirst($attribute->type) }}
                                        @endif
                                    </span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $attribute->pivot->value ?? '-' }}
                                        @if($attribute->Symbol)
                                            {{ $attribute->Symbol }}
                                        @elseif($attribute->translations->first() && $attribute->translations->first()->symbol)
                                            {{ $attribute->translations->first()->symbol }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Features Section -->
                @if($product->features && $product->features->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('products.show.features') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->features as $feature)
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 ml-3"></i>
                                    <span class="text-gray-700">{{ $feature->getTranslatedName() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('products.show.comments') }}</h2>
                    @auth
                        <form action="{{ route('public.products.comment', $product) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                                <div class="md:col-span-3">
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.show.add_comment') }}</label>
                                    <textarea name="comment" id="comment" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                              placeholder="{{ __('products.show.comment_placeholder') }}" required></textarea>
                                </div>
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.show.rating') }}</label>
                                    <select id="rating" name="rating" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                                        <option value="">{{ __('products.show.select_rating') }}</option>
                                        @for($i=5;$i>=1;$i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn-primary text-white px-4 py-2 rounded-lg font-medium">
                                    {{ __('products.show.submit_comment') }}
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <p class="text-gray-600">{{ __('products.show.login_to_comment') }} <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700">{{ __('products.show.login') }}</a> {{ __('products.show.to_add_comment') }}</p>
                        </div>
                    @endauth

                    @php
                        $comments = $product->comments()->latest()->take(10)->get();
                    @endphp
                    @if($comments->count())
                        <div class="space-y-4">
                            @foreach($comments as $comment)
                                <div class="border border-gray-100 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" class="w-8 h-8 rounded-full" alt="{{ $comment->user->name }}">
                                            <span class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</span>
                                        </div>
                                        @if($comment->rating)
                                            <div class="text-yellow-500 text-sm">
                                                @for($i=1;$i<=5;$i++)
                                                    <i class="fa{{ $i <= $comment->rating ? 's' : 'r' }} fa-star"></i>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ $comment->comment }}</p>
                                    <p class="text-xs text-gray-400 mt-2">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ __('products.show.no_comments') }}</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Price Card (Amazon Style) -->
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                    @php
                        $activeOffers = $product->activeOffers;
                        $saleOffers = $activeOffers->where('offer_type', 'sale');
                        $rentOffers = $activeOffers->filter(function($offer) {
                            return $offer->offer_type && str_starts_with($offer->offer_type, 'rent_');
                        });
                    @endphp

                    @if($activeOffers->count() > 0)
                        <!-- Main Price Display (Amazon Style) -->
                        <div class="mb-6">
                            @if($saleOffers->count() > 0)
                                @php
                                    $lowestSalePrice = $saleOffers->min('price');
                                    $highestSalePrice = $saleOffers->max('price');
                                @endphp
                                
                                <div class="flex items-baseline space-x-2 space-x-reverse mb-2">
                                    @if($lowestSalePrice == $highestSalePrice)
                                        <span class="text-3xl font-bold text-gray-900">{{ number_format($lowestSalePrice, 0) }}</span>
                                    @else
                                        <span class="text-3xl font-bold text-gray-900">{{ number_format($lowestSalePrice, 0) }} - {{ number_format($highestSalePrice, 0) }}</span>
                                    @endif
                                    <span class="text-lg text-gray-600">ريال</span>
                                </div>
                                
                                @if($saleOffers->where('is_featured', true)->count() > 0)
                                    <div class="flex items-center space-x-2 space-x-reverse mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-fire ml-1"></i>
                                            {{ __('products.property_card.best_seller') }}
                                        </span>
                                        @if($saleOffers->where('valid_to')->count() > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-clock ml-1"></i>
                                                {{ __('products.property_card.limited_time') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            @elseif($rentOffers->count() > 0)
                                @php
                                    $lowestRentPrice = $rentOffers->min('price');
                                    $rentType = $rentOffers->first()->offer_type;
                                    $rentLabels = [
                                        'rent_daily' => __('products.show.daily_rent'),
                                        'rent_monthly' => __('products.show.monthly_rent'),
                                        'rent_yearly' => __('products.show.yearly_rent')
                                    ];
                                @endphp
                                
                                <div class="flex items-baseline space-x-2 space-x-reverse mb-2">
                                    <span class="text-3xl font-bold text-gray-900">{{ number_format($lowestRentPrice, 0) }}</span>
                                    <span class="text-lg text-gray-600">ريال</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $rentLabels[$rentType] ?? $rentType }}</p>
                            @endif
                            
                            <!-- Property Status -->
                            <div class="flex items-center space-x-2 space-x-reverse mb-4">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-green-600 font-medium">{{ __('products.property_card.property_available') }}</span>
                                <span class="text-blue-600 hover:text-blue-800 cursor-pointer underline" onclick="openAllOffersModal()">- {{ $activeOffers->count() }} {{ $activeOffers->count() == 1 ? __('products.property_card.offers_available') : __('products.property_card.offers_available') }}</span>
                            </div>
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="space-y-3 mb-6">
                            @if($product->facility)
                                <a href="{{ route('public.facilities.appointment.form', $product->facility) }}" 
                                   class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-check ml-2"></i>
                                    {{ __('products.show.book_now') }}
                                </a>
                                <a href="{{ route('public.facilities.quote.form', $product->facility) }}" 
                                   class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-quote-left ml-2"></i>
                                    {{ __('products.show.request_quote') }}
                                </a>
                            @else
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-check ml-2"></i>
                                    {{ __('products.show.book_now') }}
                                </button>
                                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-handshake ml-2"></i>
                                    {{ __('products.show.request_quote') }}
                                </button>
                            @endif
                        </div>

                        <!-- Offer Details -->
                        @if($saleOffers->count() > 0)
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-tag text-green-600 ml-2"></i>
                                    {{ __('products.property_card.purchase_options') }}
                                </h4>
                                <div class="space-y-2">
                                    @foreach($saleOffers->take(3) as $offer)
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition-all duration-200 cursor-pointer {{ $offer->is_featured ? 'border-blue-300 bg-blue-50' : '' }}" 
                                             onclick="openOfferModal({{ $offer->id }})">
                                            <div class="flex justify-between items-center">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 space-x-reverse">
                                                        <span class="text-lg font-bold {{ $offer->is_featured ? 'text-blue-600' : 'text-green-600' }}">
                                                            {{ number_format($offer->price, 0) }} ريال
                                                        </span>
                                                        @if($offer->is_featured)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                <i class="fas fa-crown ml-1"></i>
                                                                {{ __('products.property_card.recommended') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($offer->deposit_amount)
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            {{ __('products.show.deposit') }}: {{ number_format($offer->deposit_amount, 0) }} ريال
                                                        </p>
                                                    @endif
                                                </div>
                                                @if($offer->valid_to)
                                                    <div class="text-right">
                                                        <p class="text-xs text-gray-500">
                                                            {{ __('products.show.valid_until') }}<br>
                                                            {{ $offer->valid_to->format('Y/m/d') }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($saleOffers->count() > 3)
                                        <div class="text-center">
                                            <a href="#offers" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                {{ __('products.show.view_all_offers') }} ({{ $saleOffers->count() }})
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- No Offers Available - Amazon Style -->
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                    <i class="fas fa-tag text-gray-400 text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ __('products.actions.price_on_request') }}</h4>
                                <p class="text-gray-600 mb-4 text-sm">{{ __('products.property_card.contact_for_pricing_description') }}</p>
                            </div>
                            
                            <div class="space-y-3">
                                @if($product->facility)
                                    <a href="{{ route('public.facilities.appointment.form', $product->facility) }}" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-calendar-check ml-2"></i>
                                        {{ __('products.show.book_now') }}
                                    </a>
                                    <a href="{{ route('public.facilities.quote.form', $product->facility) }}" 
                                       class="w-full border border-blue-600 text-blue-600 py-3 rounded-lg font-medium text-center block hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-quote-left ml-2"></i>
                                        {{ __('products.show.request_quote') }}
                                    </a>
                                @else
                                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-handshake ml-2"></i>
                                        {{ __('products.show.request_quote') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Favorites Section -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        @auth
                            @if($isFavorited ?? false)
                                <form action="{{ route('public.products.favorite.remove', $product) }}" method="POST" class="block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full border border-red-300 text-red-600 py-3 rounded-lg font-medium text-center block hover:bg-red-50 transition-colors">
                                        <i class="fas fa-heart-broken ml-2"></i>{{ __('products.show.remove_from_favorites') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('public.products.favorite.add', $product) }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-medium text-center block hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-heart ml-2"></i>{{ __('products.show.add_to_favorites') }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-medium text-center block hover:bg-gray-50 transition-colors">
                                <i class="fas fa-heart ml-2"></i>{{ __('products.show.add_to_favorites') }}
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.show.property_details') }}</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ __('products.show.type') }}</span>
                            <span class="font-semibold text-gray-900">{{ $product->category ? $product->category->getTranslatedName() : __('products.show.not_specified') }}</span>
                        </div>
                        
                        <!-- Dynamic Attributes -->
                        @foreach($product->attributes as $attribute)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">
                                    @if($attribute->getTranslatedName())
                                        {{ $attribute->getTranslatedName() }}
                                    @else
                                        {{ ucfirst($attribute->type) }}
                                    @endif
                                </span>
                                <span class="font-semibold text-gray-900">
                                    {{ $attribute->pivot->value ?? __('products.show.not_specified') }}
                                    @if($attribute->Symbol)
                                        {{ $attribute->Symbol }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ __('products.show.available_from') }}</span>
                            <span class="font-semibold text-gray-900">{{ $product->available_from ? $product->available_from->format('Y/m/d') : __('products.show.not_specified') }}</span>
                        </div>
                        @if($product->available_to)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('products.show.available_until') }}</span>
                                <span class="font-semibold text-gray-900">{{ $product->available_to->format('Y/m/d') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Facility Info -->
                @if($product->facility)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.show.facility') }}</h3>
                        <div class="flex items-center space-x-3 space-x-reverse mb-4">
                            <img src="{{ $product->facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}"
                                 alt="{{ $product->facility->name }}" class="w-12 h-12 rounded object-cover">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $product->facility->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->facility->category->name ?? '' }}</p>
                            </div>
                        </div>
                        @if($product->facility->address)
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-map-marker-alt ml-2"></i>{{ $product->facility->address }}</p>
                        @endif
                        <a href="{{ route('public.facilities.show', $product->facility) }}"
                           class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            {{ __('products.show.view_facility') }}
                        </a>
                    </div>
                @endif

                @if($product->latitude && $product->longitude)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.show.map_location') }}</h3>
                        <div class="w-full h-64 rounded" id="map" style="background:#eef2ff"></div>
                        @if($product->google_maps_url)
                            <a href="{{ $product->google_maps_url }}" target="_blank" class="inline-block mt-3 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                {{ __('products.show.open_in_google_maps') }}
                            </a>
                        @endif
                    </div>
                    @push('styles')
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <style>
                            #map { direction: ltr; }
                        </style>
                    @endpush
                    @push('scripts')
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            (function () {
                                const el = document.getElementById('map');
                                if (!el || typeof L === 'undefined') return;

                                const lat = @json($product->latitude);
                                const lng = @json($product->longitude);
                                const title = @json($product->title);
                                const address = @json($product->address ?? '');

                                const map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 15);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);

                                const marker = L.marker([lat, lng]).addTo(map);
                                const popupContent = `<div class="text-sm"><div class="font-semibold mb-1">${title}</div>${address ? `<div class=\"text-gray-600\">${address}</div>` : ''}</div>`;
                                marker.bindPopup(popupContent).openPopup();
                            })();
                        </script>
                    @endpush
                @endif

                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.show.contact_info') }}</h3>
                    <div class="space-y-3">
                        @if($product->contact_phone)
                            <a href="tel:{{ $product->contact_phone }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-phone ml-3"></i>
                                <span>{{ $product->contact_phone }}</span>
                            </a>
                        @endif
                        @if($product->contact_email)
                            <a href="mailto:{{ $product->contact_email }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-envelope ml-3"></i>
                                <span>{{ $product->contact_email }}</span>
                            </a>
                        @endif
                        @if($product->address)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt ml-3"></i>
                                <span>{{ $product->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offers Modal -->
<div id="offersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">
                    {{ __('products.property_card.available_options') }}
                </h3>
                <button onclick="closeOfferModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="mt-4" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200">
                <button onclick="closeOfferModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    {{ __('general.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openOfferModal(offerId) {
    const modal = document.getElementById('offersModal');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalTitle');
    
    // Show loading
    modalBody.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-600">جاري التحميل...</p></div>';
    modal.classList.remove('hidden');
    
    // Fetch offer details
    fetch(`/api/v1/offers/${offerId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalBody.innerHTML = renderOfferDetails(data.offer, data.relatedOffers);
                modalTitle.textContent = data.offer.title || 'تفاصيل العرض';
            } else {
                modalBody.innerHTML = '<div class="text-center py-8 text-red-600">حدث خطأ في تحميل تفاصيل العرض</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<div class="text-center py-8 text-red-600">حدث خطأ في تحميل تفاصيل العرض</div>';
        });
}

function openAllOffersModal() {
    const modal = document.getElementById('offersModal');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalTitle');
    
    // Show loading
    modalBody.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-600">جاري التحميل...</p></div>';
    modal.classList.remove('hidden');
    modalTitle.textContent = 'جميع العروض المتاحة';
    
    // Fetch all offers for this product
    const productId = {{ $product->id }};
    fetch(`/api/v1/products/${productId}/all-offers`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalBody.innerHTML = renderAllOffers(data.offers);
            } else {
                modalBody.innerHTML = '<div class="text-center py-8 text-red-600">حدث خطأ في تحميل العروض</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<div class="text-center py-8 text-red-600">حدث خطأ في تحميل العروض</div>';
        });
}

function closeOfferModal() {
    document.getElementById('offersModal').classList.add('hidden');
}

function renderOfferDetails(offer, relatedOffers = []) {
    // Determine offer type display
    let offerTypeDisplay = 'عرض';
    if (offer.offer_type === 'sale') {
        offerTypeDisplay = 'عرض للبيع';
    } else if (offer.offer_type && offer.offer_type.startsWith('rent_')) {
        const rentTypes = {
            'rent_daily': 'عرض إيجار يومي',
            'rent_monthly': 'عرض إيجار شهري',
            'rent_yearly': 'عرض إيجار سنوي'
        };
        offerTypeDisplay = rentTypes[offer.offer_type] || 'عرض إيجار';
    }
    
    let html = `
        <div class="space-y-6">
            <!-- Main Offer Details -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-semibold text-blue-900">${offer.title || offerTypeDisplay}</h4>
                    ${offer.is_featured ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-star ml-1"></i>موصى به</span>' : ''}
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-2xl font-bold text-blue-600 mb-2">
                            ${offer.price ? offer.price.toLocaleString() : 'غير محدد'} ريال
                        </div>
                        ${offer.deposit_amount ? `<div class="text-sm text-gray-600">عربون: ${offer.deposit_amount.toLocaleString()} ريال</div>` : ''}
                    </div>
                    <div class="text-right">
                        ${offer.valid_to ? `<div class="text-sm text-gray-600">صالح حتى: ${new Date(offer.valid_to).toLocaleDateString('en-GB')}</div>` : ''}
                        <div class="text-sm text-gray-600 mt-1">تاريخ الإنشاء: ${new Date(offer.created_at).toLocaleDateString('ar-SA')}</div>
                    </div>
                </div>
                
                ${offer.description ? `<div class="mt-3 text-gray-700">${offer.description}</div>` : ''}
            </div>
            
            <!-- Related Offers -->
            ${relatedOffers.length > 0 ? `
                <div>
                    <h5 class="text-md font-medium text-gray-800 mb-3">عروض أخرى متاحة</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        ${relatedOffers.map(relatedOffer => {
                            // Determine related offer type display
                            let relatedOfferTypeDisplay = 'عرض';
                            if (relatedOffer.offer_type === 'sale') {
                                relatedOfferTypeDisplay = 'عرض للبيع';
                            } else if (relatedOffer.offer_type && relatedOffer.offer_type.startsWith('rent_')) {
                                const rentTypes = {
                                    'rent_daily': 'عرض إيجار يومي',
                                    'rent_monthly': 'عرض إيجار شهري',
                                    'rent_yearly': 'عرض إيجار سنوي'
                                };
                                relatedOfferTypeDisplay = rentTypes[relatedOffer.offer_type] || 'عرض إيجار';
                            }
                            
                            return `
                                <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition-colors">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">${relatedOffer.title || relatedOfferTypeDisplay}</div>
                                            <div class="text-lg font-bold text-green-600">${relatedOffer.price ? relatedOffer.price.toLocaleString() : 'غير محدد'} ريال</div>
                                        </div>
                                        ${relatedOffer.is_featured ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-crown ml-1"></i>موصى به</span>' : ''}
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            ` : ''}
            
            <!-- Action Buttons -->
            <div class="flex space-x-3 space-x-reverse">
                <button onclick="bookAppointment(${offer.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-calendar-check ml-2"></i>
                    احجز موعد للمعاينة
                </button>
                <button onclick="requestQuote(${offer.id})" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-handshake ml-2"></i>
                    طلب عرض سعر
                </button>
            </div>
        </div>
    `;
    
    return html;
}

function renderAllOffers(offers) {
    if (!offers || offers.length === 0) {
        return '<div class="text-center py-8 text-gray-600">لا توجد عروض متاحة</div>';
    }
    
    // Group offers by type
    const saleOffers = offers.filter(offer => offer.offer_type === 'sale');
    const rentOffers = offers.filter(offer => offer.offer_type && offer.offer_type.startsWith('rent_'));
    
    let html = '<div class="space-y-6">';
    
    // Sale Offers
    if (saleOffers.length > 0) {
        html += `
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tag text-green-600 ml-2"></i>
                    عروض البيع
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${saleOffers.map(offer => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer" onclick="openOfferModal(${offer.id})">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                        <span class="text-xl font-bold ${offer.is_featured ? 'text-blue-600' : 'text-green-600'}">
                                            ${offer.price ? offer.price.toLocaleString() : 'غير محدد'} ريال
                                        </span>
                                        ${offer.is_featured ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-crown ml-1"></i>موصى به</span>' : ''}
                                    </div>
                                    ${offer.deposit_amount ? `<div class="text-sm text-gray-600 mb-2">عربون: ${offer.deposit_amount.toLocaleString()} ريال</div>` : ''}
                                    ${offer.title ? `<p class="text-sm text-gray-700">${offer.title}</p>` : ''}
                                </div>
                                ${offer.valid_to ? `<div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">صالح حتى<br>${new Date(offer.valid_to).toLocaleDateString('en-GB')}</div>` : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    // Rent Offers
    if (rentOffers.length > 0) {
        const rentTypes = {
            'rent_daily': 'إيجار يومي',
            'rent_monthly': 'إيجار شهري',
            'rent_yearly': 'إيجار سنوي'
        };
        
        html += `
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 ml-2"></i>
                    عروض الإيجار
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    ${rentOffers.map(offer => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer" onclick="openOfferModal(${offer.id})">
                            <div class="text-center">
                                <h6 class="text-sm font-medium text-gray-700 mb-2">${rentTypes[offer.offer_type] || offer.offer_type}</h6>
                                <div class="text-xl font-bold text-blue-600 mb-2">
                                    ${offer.price ? offer.price.toLocaleString() : 'غير محدد'} ريال
                                </div>
                                ${offer.is_featured ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-crown ml-1"></i>موصى به</span>' : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    html += '</div>';
    return html;
}

// Close modal when clicking outside
document.getElementById('offersModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOfferModal();
    }
});

// Function to handle booking appointment
function bookAppointment(offerId) {
    // Close the modal first
    closeOfferModal();
    
    // Check if user is authenticated
    @auth
        // Redirect to booking form with offer ID
        window.location.href = '{{ route("public.bookings.create") }}?offer_id=' + offerId;
    @else
        // Show login modal or redirect to login
        if (confirm('يجب تسجيل الدخول أولاً لحجز الموعد. هل تريد الانتقال إلى صفحة تسجيل الدخول؟')) {
            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
        }
    @endauth
}

// Function to handle quote request
function requestQuote(offerId) {
    // Close the modal first
    closeOfferModal();
    
    // Check if user is authenticated
    @auth
        // Show quote request form
        showQuoteRequestForm(offerId);
    @else
        // Show login modal or redirect to login
        if (confirm('يجب تسجيل الدخول أولاً لطلب عرض السعر. هل تريد الانتقال إلى صفحة تسجيل الدخول؟')) {
            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
        }
    @endauth
}

// Function to show quote request form
function showQuoteRequestForm(offerId) {
    // Create a modal for quote request
    const quoteModal = document.createElement('div');
    quoteModal.id = 'quoteModal';
    quoteModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    quoteModal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">طلب عرض سعر</h3>
                    <button onclick="closeQuoteModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="quoteForm" class="mt-4">
                    <input type="hidden" name="offer_id" value="${offerId}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                            <input type="text" name="name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                            <input type="tel" name="phone" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الرسالة</label>
                            <textarea name="message" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="اكتب رسالتك هنا..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 mt-6">
                        <button type="button" onclick="closeQuoteModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                            إلغاء
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                            إرسال الطلب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.body.appendChild(quoteModal);
    
    // Handle form submission
    document.getElementById('quoteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitQuoteRequest(this);
    });
}

// Function to submit quote request
function submitQuoteRequest(form) {
    const formData = new FormData(form);
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'جاري الإرسال...';
    submitBtn.disabled = true;
    
    // Submit the form
    fetch('{{ route("public.contact.quote.send") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال طلب عرض السعر بنجاح! سنتواصل معك قريباً.');
            closeQuoteModal();
        } else {
            alert('حدث خطأ في إرسال الطلب. يرجى المحاولة مرة أخرى.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في إرسال الطلب. يرجى المحاولة مرة أخرى.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Function to close quote modal
function closeQuoteModal() {
    const modal = document.getElementById('quoteModal');
    if (modal) {
        modal.remove();
    }
}
</script>
@endpush
@endsection
