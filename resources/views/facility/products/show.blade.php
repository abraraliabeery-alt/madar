@extends('facility.layouts.app')

@section('title', $product->getTranslatedTitle() ?: $product->address ?? __('facility.products.product_details'))

@section('content')
<div class="w-full px-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $product->getTranslatedTitle() ?: $product->address ?? __('facility.products.product_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('facility.products.product_details_subtitle') }}</p>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse mt-4 sm:mt-0">
            <a href="{{ route('facility.products.edit', $product) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center">
                <i class="fas fa-edit ml-2"></i>
                {{ __('facility.products.edit_button') }}
            </a>
            <a href="{{ route('facility.products.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('facility.products.back_to_list') }}
            </a>
        </div>
    </div>

    <!-- Product Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Product Images & Basic Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.product_image') }}</h3>
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                         alt="{{ $product->getTranslatedTitle() ?: $product->address }}" 
                         class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.product_information') }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.name') }}</label>
                        <p class="text-gray-900">{{ $product->getTranslatedTitle() ?: $product->address }}</p>
                    </div>
                    
                    @if($product->additional_info)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.description') }}</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $product->additional_info }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.category') }}</label>
                            <p class="text-gray-900">
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->getTranslatedName('ar') }}
                                    </span>
                                @else
                                    <span class="text-gray-500">{{ __('facility.products.not_set') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Offers Card (Amazon Style) -->
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
                                        {{ __('facility.products.best_seller') }}
                                    </span>
                                    @if($saleOffers->where('valid_to')->count() > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock ml-1"></i>
                                            {{ __('facility.products.limited_time') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        @elseif($rentOffers->count() > 0)
                            @php
                                $lowestRentPrice = $rentOffers->min('price');
                                $rentType = $rentOffers->first()->offer_type;
                                $rentLabels = [
                                    'rent_daily' => __('facility.products.daily_rent'),
                                    'rent_monthly' => __('facility.products.monthly_rent'),
                                    'rent_yearly' => __('facility.products.yearly_rent')
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
                        
                        <!-- Quick Action Buttons -->
                        <div class="flex space-x-3 space-x-reverse mb-6">
                            <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-calendar-check ml-2"></i>
                                {{ __('products.show.book_now') }}
                            </button>
                            <button class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-handshake ml-2"></i>
                                {{ __('products.show.request_quote') }}
                            </button>
                        </div>
                    </div>
                    <!-- Detailed Offers Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-list-alt text-blue-600 ml-2"></i>
                            {{ __('facility.products.available_options') }}
                        </h4>
                        
                        <!-- Sale Offers -->
                        @if($saleOffers->count() > 0)
                            <div class="mb-6">
                                <h5 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-tag text-green-600 ml-2"></i>
                                    {{ __('facility.products.purchase_options') }}
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($saleOffers as $offer)
                                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer {{ $offer->is_featured ? 'border-blue-300 bg-blue-50' : '' }}" 
                                             onclick="openOfferModal({{ $offer->id }})">
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                                        <span class="text-xl font-bold {{ $offer->is_featured ? 'text-blue-600' : 'text-green-600' }}">
                                                            {{ number_format($offer->price, 0) }} ريال
                                                        </span>
                                                        @if($offer->is_featured)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                <i class="fas fa-crown ml-1"></i>
                                                                {{ __('facility.products.recommended') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($offer->deposit_amount)
                                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                                            <i class="fas fa-hand-holding-usd ml-1"></i>
                                                            {{ __('facility.products.deposit') }}: {{ number_format($offer->deposit_amount, 0) }} ريال
                                                        </div>
                                                    @endif
                                                    
                                                    @if($offer->getTranslatedTitle('ar'))
                                                        <p class="text-sm text-gray-700 mb-2">{{ $offer->getTranslatedTitle('ar') }}</p>
                                                    @endif
                                                    
                                                    <div class="flex space-x-2 space-x-reverse">
                                                        <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded transition duration-200">
                                                            {{ __('facility.products.select_option') }}
                                                        </button>
                                                        <button class="px-3 py-2 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition duration-200">
                                                            <i class="fas fa-info-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                @if($offer->valid_to)
                                                    <div class="text-right">
                                                        <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                            {{ __('facility.products.valid_until') }}<br>
                                                            {{ $offer->valid_to->format('Y/m/d') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Rent Offers -->
                        @if($rentOffers->count() > 0)
                            <div>
                                <h5 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-calendar-alt text-blue-600 ml-2"></i>
                                    {{ __('facility.products.rental_options') }}
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @php
                                        $rentTypes = [
                                            'rent_daily' => ['label' => __('facility.products.daily_rent'), 'icon' => 'fas fa-sun', 'color' => 'yellow'],
                                            'rent_monthly' => ['label' => __('facility.products.monthly_rent'), 'icon' => 'fas fa-calendar', 'color' => 'blue'],
                                            'rent_yearly' => ['label' => __('facility.products.yearly_rent'), 'icon' => 'fas fa-calendar-alt', 'color' => 'green']
                                        ];
                                    @endphp
                                    
                                    @foreach($rentTypes as $type => $config)
                                        @php
                                            $typeOffers = $rentOffers->where('offer_type', $type);
                                        @endphp
                                        
                                        @if($typeOffers->count() > 0)
                                            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-{{ $config['color'] }}-300 hover:shadow-md transition-all duration-200 cursor-pointer" 
                                                 onclick="openOfferModal('{{ $type }}')">
                                                <div class="text-center">
                                                    <div class="flex items-center justify-center mb-3">
                                                        <i class="{{ $config['icon'] }} text-{{ $config['color'] }}-600 text-xl ml-2"></i>
                                                        <h6 class="text-sm font-medium text-gray-700">{{ $config['label'] }}</h6>
                                                    </div>
                                                    
                                                    @php
                                                        $minPrice = $typeOffers->min('price');
                                                        $maxPrice = $typeOffers->max('price');
                                                    @endphp
                                                    
                                                    @if($minPrice == $maxPrice)
                                                        <div class="text-xl font-bold text-{{ $config['color'] }}-600 mb-2">
                                                            {{ number_format($minPrice, 0) }} ريال
                                                        </div>
                                                    @else
                                                        <div class="text-xl font-bold text-{{ $config['color'] }}-600 mb-2">
                                                            {{ __('facility.products.price_from') }} {{ number_format($minPrice, 0) }}
                                                            <br>
                                                            {{ __('facility.products.price_to') }} {{ number_format($maxPrice, 0) }} ريال
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="space-y-2">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                                            {{ $typeOffers->count() }} {{ $typeOffers->count() == 1 ? 'عرض' : 'عروض' }}
                                                        </span>
                                                        
                                                        @if($typeOffers->where('is_featured', true)->count() > 0)
                                                            <div class="block">
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    <i class="fas fa-crown ml-1"></i>
                                                                    {{ __('facility.products.recommended') }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        
                                                        <button class="w-full bg-{{ $config['color'] }}-600 hover:bg-{{ $config['color'] }}-700 text-white text-sm font-medium py-2 px-3 rounded transition duration-200 mt-2">
                                                            {{ __('facility.products.select_rental') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    <!-- View All Offers Link -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('facility.offers.index', ['product_id' => $product->id]) }}" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-eye ml-2"></i>
                            {{ __('facility.products.view_all_offers') }}
                        </a>
                    </div>
                @else
                    <!-- No Offers Available - Amazon Style -->
                    <div class="text-center py-12">
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                <i class="fas fa-tag text-gray-400 text-3xl"></i>
                            </div>
                            <h4 class="text-2xl font-semibold text-gray-800 mb-2">{{ __('facility.products.price_on_request') }}</h4>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ __('facility.products.contact_for_pricing_description') }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-center space-x-4 space-x-reverse text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-green-600 ml-2"></i>
                                    {{ __('facility.products.call_for_price') }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-blue-600 ml-2"></i>
                                    {{ __('facility.products.email_for_price') }}
                                </div>
                            </div>
                            
                            <div class="flex space-x-4 space-x-reverse justify-center">
                                <a href="{{ route('facility.offers.create', ['product_id' => $product->id]) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
                                    <i class="fas fa-plus ml-2"></i>
                                    {{ __('facility.offers.create_offer') }}
                                </a>
                                <button class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                                    <i class="fas fa-phone ml-2"></i>
                                    {{ __('facility.products.contact_now') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Features -->
            @if($product->features && $product->features->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.features') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($product->features as $feature)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                                @if($feature->icon)
                                    @if(Str::startsWith($feature->icon, 'fas ') || Str::startsWith($feature->icon, 'fa ') || Str::startsWith($feature->icon, 'fab '))
                                        <i class="{{ $feature->icon }} text-blue-600 ml-2"></i>
                                    @else
                                        <img src="{{ Storage::url($feature->icon) }}" alt="icon" width="20" class="ml-2">
                                    @endif
                                @endif
                                <span class="text-gray-900">{{ $feature->getTranslatedName('ar') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Product Attributes -->
            @if($product->attributes && $product->attributes->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.attributes') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($product->attributes as $attribute)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">{{ $attribute->getTranslatedName('ar') }}</span>
                                <span class="font-medium text-gray-900">{{ $attribute->pivot->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Location Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.location') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.address') }}</label>
                        <p class="text-gray-900">{{ $product->address }}</p>
                    </div>
                    
                    @if($product->latitude && $product->longitude)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.latitude') }}</label>
                                <p class="text-gray-900">{{ $product->latitude }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.longitude') }}</label>
                                <p class="text-gray-900">{{ $product->longitude }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($product->google_maps_url)
                        <div>
                            <a href="{{ $product->google_maps_url }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                {{ __('facility.products.view_on_google_maps') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Status & Actions -->
        <div class="space-y-6">
            
            <!-- Product Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.status') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ __('facility.products.active_status') }}</span>
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check ml-1"></i>
                                {{ __('facility.products.active') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times ml-1"></i>
                                {{ __('facility.products.inactive') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ __('facility.products.featured_status') }}</span>
                        @if($product->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star ml-1"></i>
                                {{ __('facility.products.featured') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ __('facility.products.not_featured') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ __('facility.products.verified_status') }}</span>
                        @if($product->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-shield-alt ml-1"></i>
                                {{ __('facility.products.verified') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ __('facility.products.pending_verification') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('facility.products.edit', $product) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-edit ml-2"></i>
                        {{ __('facility.products.edit_product') }}
                    </a>
                    
                    <form action="{{ route('facility.products.toggle-status', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $product->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                            <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }} ml-2"></i>
                            {{ $product->is_active ? __('facility.products.deactivate') : __('facility.products.activate') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('facility.products.toggle-featured', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $product->is_featured ? 'bg-gray-600 hover:bg-gray-700' : 'bg-yellow-600 hover:bg-yellow-700' }}">
                            <i class="fas fa-star ml-2"></i>
                            {{ $product->is_featured ? __('facility.products.remove_from_featured') : __('facility.products.add_to_featured') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('facility.products.destroy', $product) }}" method="POST" class="w-full" onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-trash ml-2"></i>
                            {{ __('facility.products.delete_product') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.statistics') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.views') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->views_count ?? 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.rating') }}</span>
                        <div class="flex items-center">
                            <span class="font-medium text-gray-900 ml-2">{{ $product->rating ?? 0 }}</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($product->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.rating_count') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->rating_count ?? 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.created_at') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->created_at->format('Y/m/d') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.updated_at') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->updated_at->format('Y/m/d') }}</span>
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
        window.location.href = '{{ route("facility.bookings.create") }}?offer_id=' + offerId;
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
    fetch('{{ route("public.contact.quote") }}', {
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
