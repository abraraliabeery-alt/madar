@if(!$pdfOnlySlideKey || $pdfOnlySlideKey === 'offers')
    @if($product->activeOffers && $product->activeOffers->count())
        <div class="slide">
            <h2>{{ $slideTitle ?? __('pdf.slides.offers.title') }}</h2>
            <div class="section-lead">{{ __('pdf.slides.offers.lead') }}</div>

            <table class="items">
                <thead>
                    <tr>
                        <th>{{ __('pdf.slides.offers.offer') }}</th>
                        <th>{{ __('pdf.slides.offers.offer_type') }}</th>
                        <th>{{ __('pdf.slides.offers.price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->activeOffers as $offer)
                        <tr>
                            <td>{{ $offer->title ?? $offer->offer_type }}</td>
                            <td>
                                {{ __('pdf.offer_types.'.$offer->offer_type) === 'pdf.offer_types.'.$offer->offer_type
                                    ? $offer->offer_type
                                    : __('pdf.offer_types.'.$offer->offer_type) }}
                            </td>
                            <td style="white-space:nowrap;text-align:end">{{ number_format($offer->price, 0) }} {{ __('pdf.common.currency') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif
