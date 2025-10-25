@props([
    'products',
    'columns' => 4,
    'compact' => false,
    'showAttributes' => true,
    'showPrice' => true
])

@php
    $gridClasses = match($columns) {
        2 => 'grid-cols-1 sm:grid-cols-2',
        3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
        5 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5',
        6 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'
    };
@endphp

<div class="grid {{ $gridClasses }} gap-6">
    @foreach($products as $product)
        <x-product-card-grid 
            :product="$product" 
            :compact="$compact"
            :showAttributes="$showAttributes"
            :showPrice="$showPrice" />
    @endforeach
</div>
