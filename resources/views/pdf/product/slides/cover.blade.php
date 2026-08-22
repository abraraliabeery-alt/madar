@if(!$pdfOnlySlideKey || $pdfOnlySlideKey === 'cover')
    @php
        $localCity = \App\Helpers\LanguageHelper::getCityName($product->city) ?: $product->city?->name;
        $localCategory = \App\Helpers\LanguageHelper::getCategoryName($product->category) ?: $product->category?->name;
    @endphp
    <div class="cover" style="background-color: {{ $pdfSettings['style']['brand_color'] ?? '#0D2B3B' }};">
        <div class="cover-row">
            <div class="cover-cell cover-image-cell">
                @if($productImageDataUri)
                    <img class="cover-square-image" src="{{ $productImageDataUri }}" alt="{{ $product->title }}">
                @endif
            </div>
            <div class="cover-cell">
                <div class="cover-title">{{ $product->title }}</div>
                <div class="cover-sub">
                    {{ $product->reference_number ? 'Ref: '.$product->reference_number.' · ' : '' }}
                    {{ $localCity ?? '' }}{{ $product->neighborhood?->name ? ' - '.$product->neighborhood->name : '' }}
                </div>
                @if($product->price)
                    <div class="cover-price">{{ $product->formattedPrice() }}</div>
                @endif
                <div class="cover-badges">
                    @if($localCategory)
                        <span class="badge">{{ $localCategory }}</span>
                    @endif
                    @if($product->facility?->name)
                        <span class="badge">{{ $product->facility->name }}</span>
                    @endif
                    @if($localCity)
                        <span class="badge">{{ $localCity }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
