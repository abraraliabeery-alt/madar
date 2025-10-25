@props([
    'product',
    'compact' => false,
    'showAttributes' => true,
    'showPrice' => true
])

<div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
    <div class="flex">
        <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
            <img src="{{ $product->image_url ?? $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                 alt="{{ $product->title }}" class="w-full h-full object-cover">
            @if($product->is_featured)
                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                    {{ __('products.property_card.featured') }}
                </div>
            @endif
            @if($product->is_verified)
                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                    <i class="fas fa-check ml-1"></i>{{ __('products.property_card.verified') }}
                </div>
            @elseif($product->hasActiveOffers())
                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                    {{ __('products.property_card.has_offers') }}
                </div>
            @elseif($product->category)
                <div class="absolute top-2 left-2 bg-white text-gray-800 px-2 py-1 rounded text-xs font-medium">
                    {{ $product->category->getTranslatedName() }}
                </div>
            @endif
        </div>
        <div class="flex-1 p-6">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-xl font-semibold text-gray-900">
                    <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                        {{ $product->title }}
                    </a>
                </h3>
                @if($showPrice)
                    <div class="text-lg font-bold text-primary-600 flex items-center">
                        @if($product->hasActiveOffers())
                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-5 h-5 mr-1">
                            {{ $product->getFormattedPrice() }}
                        @else
                            {{ __('products.actions.price_on_request') }}
                        @endif
                    </div>
                @endif
            </div>
            <p class="text-gray-600 text-sm mb-3">{{ $product->address ?? __('products.property_card.location_unknown') }}</p>

            @if($showAttributes && $product->card_attributes && $product->card_attributes->count() > 0)
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    @foreach($product->card_attributes as $attribute)
                        <span>
                            @if($attribute->icon)
                                <i class="{{ $attribute->icon }} ml-1"></i>
                            @else
                                <i class="fas fa-info-circle ml-1"></i>
                            @endif
                            {{ $attribute->pivot->value }}
                            @if($attribute->Symbol)
                                {{ $attribute->Symbol }}
                            @else
                                {{ $attribute->getTranslatedName() ?? $attribute->type }}
                            @endif
                        </span>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <!-- Comments Count -->
                    <div class="flex items-center">
                        <i class="fas fa-comment-alt mr-1"></i>
                        <span>{{ $product->comments_count ?? $product->comments()->count() }}</span>
                    </div>
                    
                    <!-- Likes/Favorites Count -->
                    <div class="flex items-center">
                        <i class="fas fa-heart mr-1"></i>
                        <span>{{ $product->favorites_count ?? $product->favoredByUsers()->count() }}</span>
                    </div>
                    
                    <!-- Views Count (if available) -->
                    @if($product->views_count)
                        <div class="flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            <span>{{ number_format($product->views_count) }}</span>
                        </div>
                    @endif
                </div>
                
                <a href="{{ route('public.products.show', $product) }}"
                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    {{ __('products.property_card.view_details') }}
                </a>
            </div>
        </div>
    </div>
</div>
