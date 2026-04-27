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
    <div class="flex flex-col md:flex-row">
        <div class="p-4 md:p-5 flex items-center justify-center md:w-52 flex-shrink-0 bg-white dark:bg-secondary-900 border-b md:border-b-0 md:border-r border-gray-100 dark:border-secondary-800">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-primary-600 flex items-center justify-center text-center">
                <div>
                    <div class="text-2xl font-extrabold text-primary-700 dark:text-primary-300 leading-none tracking-tight tabular-nums">
                        <span class="block w-[6ch] overflow-hidden text-ellipsis whitespace-nowrap">
                            {{ is_null($daysRemaining) ? '--' : number_format($daysRemaining) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-300">يوم متبقي</div>
                </div>
            </div>
        </div>

        <div class="flex-1 p-5">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white leading-snug">
                    <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                        {{ $product->title }}
                    </a>
                </h3>

                <div class="flex items-start gap-3">
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-secondary-800 flex items-center justify-center">
                        @if($product->image_url || $product->image)
                            <img src="{{ $product->image_url ?? $product->image }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-briefcase text-gray-400 dark:text-gray-300 text-xl"></i>
                        @endif
                    </div>
                    <div class="w-16 h-16 rounded-lg bg-gray-50 dark:bg-secondary-800 border border-gray-200 dark:border-secondary-700 flex items-center justify-center">
                        <div class="text-[10px] text-gray-500 dark:text-gray-300 text-center leading-tight">
                            QR
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700 dark:text-gray-200">
                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-calendar text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">تاريخ النشر</span>
                    <span class="font-semibold">{{ $publishDate ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-eye text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">المشاهدات</span>
                    <span class="font-semibold">{{ $product->views_count ? number_format($product->views_count) : '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-briefcase text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">النشاط</span>
                    <span class="font-semibold">{{ $activityValue ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse">
                    <i class="fas fa-clock text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">موعد التسليم</span>
                    <span class="font-semibold">{{ $deliveryValue ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-2 rtl:flex-row-reverse md:col-span-2">
                    <i class="fas fa-map-marker-alt text-primary-700"></i>
                    <span class="text-gray-500 dark:text-gray-300">المكان</span>
                    <span class="font-semibold">
                        {{ $product->address ?? __('products.property_card.location_unknown') }}
                        @if(!empty($product->city))
                            - @cityName($product->city)
                        @endif
                    </span>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-gray-100 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-800/60 p-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-gray-600 dark:text-gray-200">
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span>
                        <span class="text-gray-500 dark:text-gray-300">نُشر</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $publishDate ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <span class="w-2.5 h-2.5 rounded-full bg-secondary-600"></span>
                        <span class="text-gray-500 dark:text-gray-300">نهاية التقديم</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $deadlineDate ? $deadlineDate->format('d-m-Y') : '—' }}</span>
                    </div>
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                        <span class="text-gray-500 dark:text-gray-300">التسليم</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $deliveryValue ?? '—' }}</span>
                    </div>
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
