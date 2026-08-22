@if(!$pdfOnlySlideKey || $pdfOnlySlideKey === 'features')
    @if($product->features && $product->features->count())
        @foreach($product->features->chunk(4) as $featureChunk)
        <div class="slide">
            <h2>{{ $slideTitle ?? __('pdf.slides.features.title') }}</h2>
            @if($loop->first)
                <div class="section-lead">{{ __('pdf.slides.features.lead') }}</div>
            @endif

            <div class="chips" style="margin: auto 0;">
                @foreach($featureChunk as $feature)
                    @php
                        $name = $feature->getTranslatedName() ?: '-';
                        $iconKey = $featureIconByName[$name] ?? null;
                        $icon = $iconKey ? ($svgIcons[$iconKey] ?? null) : null;
                    @endphp
                    <span class="chip">
                        <span class="chip-icon"><span>{!! $icon ?? $svgIcons['check'] !!}</span></span>
                        <span class="chip-text">{{ $name }}</span>
                    </span>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
@endif
