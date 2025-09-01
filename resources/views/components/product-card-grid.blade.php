@props([
    'product',
    'compact' => false,
    'showAttributes' => true,
    'showPrice' => true
])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden h-full flex flex-col">
    <div class="relative">
        @if($product->image_url || $product->image)
            <img src="{{ $product->image_url ?? $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                 alt="{{ $product->title }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                @if($product->category && $product->category->icon)
                    @if(Str::startsWith($product->category->icon, 'fas ') || Str::startsWith($product->category->icon, 'fa ') || Str::startsWith($product->category->icon, 'fab '))
                        <i class="{{ $product->category->icon }} fa-4x text-gray-400"></i>
                    @else
                        <img src="{{ asset($product->category->icon) }}" alt="{{ $product->category->name ?? 'Category' }}" class="w-16 h-16 object-contain">
                    @endif
                @else
                    <i class="fas fa-home fa-4x text-gray-400"></i>
                @endif
            </div>
        @endif
        
        @if($product->is_featured)
            <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs">
                {{ __('products.property_card.featured') }}
            </div>
        @endif
    </div>
    
    <div class="p-4 flex-1 flex flex-col">
        <h3 class="font-semibold text-gray-900 mb-2">
            <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600">
                {{ $product->title }}
            </a>
        </h3>
        
        <p class="text-gray-600 text-sm mb-3">{{ $product->address ?? __('products.property_card.location_unknown') }}</p>

        @if($showAttributes && $product->card_attributes && $product->card_attributes->count() > 0)
            <div class="flex gap-4 mb-4 text-sm text-gray-600">
                @foreach($product->card_attributes->take(3) as $attribute)
                    <div>
                        <span class="font-medium text-primary-600">{{ $attribute->pivot->value }}</span>
                        @if($attribute->Symbol)
                            <span class="text-primary-600">{{ $attribute->Symbol }}</span>
                        @endif
                        <div class="text-xs text-gray-500">{{ $attribute->getTranslatedName() ?? $attribute->type }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center justify-between mt-auto">
            @if($showPrice)
                <div class="font-bold text-primary-600">
                                            {{ number_format($product->price) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}
                </div>
            @endif
            <a href="{{ route('public.products.show', $product) }}" class="text-primary-600 hover:text-primary-700 text-sm">
                {{ __('products.property_card.view_details') }}
            </a>
        </div>
    </div>
</div>
