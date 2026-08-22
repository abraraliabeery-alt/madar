@props([
    'product',
    'compact' => false,
    'showAttributes' => true,
    'showPrice' => true
])

@php
    $daysRemaining = null;

    $activityValue = null;
    $deliveryValue = null;
    if ($product->card_attributes && $product->card_attributes->count() > 0) {
        $activityValue = optional($product->card_attributes->firstWhere('type', 'activity'))->pivot->value ?? null;
        $deliveryValue = optional($product->card_attributes->firstWhere('type', 'delivery_date'))->pivot->value ?? null;
    }

    $publishDate = $product->created_at ? $product->created_at->format('d-m-Y') : null;

    $deadlineDate = null;
    if (!empty($product->available_to) && !is_numeric($product->available_to)) {
        $deadlineDate = $product->available_to;
    } elseif (!empty($deliveryValue)) {
        try {
            $deadlineDate = \Carbon\Carbon::createFromFormat('d-m-Y', $deliveryValue);
        } catch (\Throwable $e) {
            $deadlineDate = null;
        }
    }

    if (!empty($deadlineDate)) {
        try {
            $daysRemaining = now()->diffInDays($deadlineDate, false);
            if (!is_int($daysRemaining) || abs($daysRemaining) > 999) {
                $daysRemaining = null;
            }
        } catch (\Throwable $e) {
            $daysRemaining = null;
        }
    }

    $displayAttributes = collect();
    if ($showAttributes && $product->card_attributes && $product->card_attributes->count() > 0) {
        $displayAttributes = $product->card_attributes
            ->reject(function ($a) {
                return in_array($a->type, ['activity', 'delivery_date']);
            })
            ->take(3);
    }
@endphp

<div class="bg-white dark:bg-secondary-900 rounded-xl shadow-md overflow-hidden card-hover border border-gray-100 dark:border-secondary-800">
    <div class="flex flex-col sm:flex-row">
        <div class="p-3 sm:p-5 flex items-center justify-center sm:w-40 md:w-52 flex-shrink-0 bg-white dark:bg-secondary-900 border-b sm:border-b-0 sm:border-e border-gray-100 dark:border-secondary-800">
            <div class="w-16 h-16 md:w-24 md:h-24 rounded-full border-4 border-primary-600 flex items-center justify-center text-center flex-shrink-0">
                <div>
                    <div class="text-lg md:text-2xl font-extrabold text-primary-700 dark:text-primary-300 leading-none tracking-tight tabular-nums">
                        <span class="block w-[6ch] overflow-hidden text-ellipsis whitespace-nowrap">
                            {{ $product->area ? number_format($product->area) : '--' }}
                        </span>
                    </div>
                    <div class="text-[10px] md:text-xs text-gray-500 dark:text-gray-300">م²</div>
                </div>
            </div>
        </div>

        <div class="flex-1 p-4 sm:p-5 min-w-0">
            <div class="flex items-start justify-between gap-3 sm:gap-4">
                <div class="min-w-0">
                    <h3 class="text-base md:text-xl font-bold text-gray-900 dark:text-white leading-snug">
                        <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                            {{ $product->title }}
                        </a>
                    </h3>
                    <div class="mt-1 flex flex-wrap gap-1.5 text-xs">
                        @if($product->category && $product->category->parent)
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">
                                {{ $product->category->parent->name }}
                            </span>
                        @endif
                        @if($product->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-info-100 text-info-700 dark:bg-info-900/30 dark:text-info-300">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 dark:bg-secondary-800 flex items-center justify-center">
                    @if($product->image_url || $product->image)
                        <img src="{{ $product->image_url ?? $product->image }}" alt="{{ $product->title }}" loading="lazy" class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-product.svg') }}'; this.classList.replace('object-cover','object-contain');">
                    @else
                        <i class="fas fa-home text-gray-400 dark:text-gray-300 text-xl"></i>
                    @endif
                </div>
            </div>

            <div class="mt-3 text-sm text-gray-700 dark:text-gray-200">
                <div class="flex items-start gap-2">
                    <i class="fas fa-map-marker-alt text-primary-700 mt-1 flex-shrink-0"></i>
                    <span class="font-semibold min-w-0">
                        {{ $product->address ?? __('products.property_card.location_unknown') }}
                        @if(!empty($product->city))
                            - @cityName($product->city)
                        @endif
                    </span>
                </div>
            </div>

            @if($displayAttributes->count() > 0)
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($displayAttributes as $attribute)
                        <div class="inline-flex items-center gap-2 rtl:flex-row-reverse bg-gray-50 dark:bg-secondary-800 border border-gray-100 dark:border-secondary-700 px-2.5 py-1.5 rounded-lg text-sm">
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

            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('public.products.show', $product) }}"
                   class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                    {{ __('products.property_card.view_details') }}
                </a>
                @if($product->is_featured)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                        {{ __('products.property_card.featured') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
