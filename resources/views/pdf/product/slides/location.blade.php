@php
    $mapUrl = $product->google_maps_url ?: 'https://www.google.com/maps?q='.urlencode(($product->latitude ?: 0).','.($product->longitude ?: 0));
@endphp
@if((!$pdfOnlySlideKey || $pdfOnlySlideKey === 'location') && $mapImageDataUri)
    <div class="slide map-slide" style="padding:0;">
        <a class="map-canvas" href="{{ $mapUrl }}" target="_blank" style="display:block;height:100%;border:0;background:transparent;">
            <img class="map-hero" style="height:100%;" src="{{ $mapImageDataUri }}" alt="Map" />
        </a>
    </div>
@endif
