@props([
    'product',
    'compact' => false,
    'showAttributes' => true,
    'showPrice' => true
])

<div class="bg-white dark:bg-secondary-900 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden h-full flex flex-col border border-gray-100 dark:border-secondary-800">
    <div class="relative">
        @if($product->image_url || $product->image)
            <img src="{{ $product->image_url ?? $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                 alt="{{ $product->title }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-100 dark:bg-secondary-800 flex items-center justify-center">
                @if($product->category && $product->category->icon)
                    @if(Str::startsWith($product->category->icon, 'fas ') || Str::startsWith($product->category->icon, 'fa ') || Str::startsWith($product->category->icon, 'fab '))
                        <i class="{{ $product->category->icon }} fa-4x text-gray-400 dark:text-gray-300"></i>
                    @else
                        <img src="{{ asset($product->category->icon) }}" alt="{{ $product->category->name ?? 'Category' }}" class="w-16 h-16 object-contain">
                    @endif
                @else
                    <i class="fas fa-home fa-4x text-gray-400 dark:text-gray-300"></i>
                @endif
            </div>
        @endif
        
        @if($product->is_featured)
            <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs">
                {{ __('products.property_card.featured') }}
            </div>
        @endif
        
        @if($product->hasActiveOffers())
            <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs">
                {{ __('products.property_card.has_offers') }}
            </div>
        @endif
    </div>
    
    <div class="p-4 flex-1 flex flex-col">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
            <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600">
                {{ $product->title }}
            </a>
        </h3>
        
        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">{{ $product->address ?? __('products.property_card.location_unknown') }}</p>

        @if($showAttributes && $product->card_attributes && $product->card_attributes->count() > 0)
            <div class="flex flex-wrap gap-3 mb-4 text-sm text-gray-700 dark:text-gray-200">
                @foreach($product->card_attributes->take(3) as $attribute)
                    <div class="inline-flex items-center gap-2 rtl:flex-row-reverse bg-gray-50 dark:bg-secondary-800 border border-gray-100 dark:border-secondary-700 px-2.5 py-1.5 rounded-lg">
                        @if($attribute->icon)
                            <i class="{{ $attribute->icon }} text-primary-700"></i>
                        @else
                            <i class="fas fa-info-circle text-primary-700"></i>
                        @endif
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $attribute->pivot->value }}</span>
                        @if($attribute->Symbol)
                            <span class="text-gray-500 dark:text-gray-300">{{ $attribute->Symbol }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center justify-between mt-auto">
            @if($showPrice)
                <div class="font-bold text-primary-600 flex items-center">
                    @if($product->hasActiveOffers())
                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                        {{ $product->getFormattedPrice() }}
                    @else
                        {{ __('products.actions.price_on_request') }}
                    @endif
                </div>
            @endif
            <a href="{{ route('public.products.show', $product) }}" class="text-primary-600 hover:text-primary-700 text-sm">
                {{ __('products.property_card.view_details') }}
            </a>
        </div>

        <!-- Comments and Likes Count -->
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-secondary-800">
            <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-300">
                <!-- Comments Count -->
                <div class="flex items-center gap-1">
                    <i class="fas fa-comment-alt"></i>
                    <span>{{ $product->comments_count ?? $product->comments()->count() }}</span>
                </div>
                
                <!-- Likes/Favorites Count -->
                <div class="flex items-center gap-1">
                    <i class="fas fa-heart"></i>
                    <span>{{ $product->favorites_count ?? $product->favoredByUsers()->count() }}</span>
                </div>
            </div>
            
            <!-- Views Count (if available) -->
            @if($product->views_count)
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-300">
                    <i class="fas fa-eye me-1"></i>
                    <span>{{ number_format($product->views_count) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
