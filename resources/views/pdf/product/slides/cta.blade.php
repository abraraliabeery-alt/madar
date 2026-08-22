@if(!$pdfOnlySlideKey || $pdfOnlySlideKey === 'cta')
    <div class="slide cta-slide" style="justify-content:center; align-items:center; text-align:center;">
        @php
            $hasAny = $product->contact_phone
                || $product->contact_email
                || $product->facility?->facebook_url
                || $product->facility?->twitter_url
                || $product->facility?->instagram_url
                || $product->facility?->linkedin_url;

            $socials = array_filter([
                __('pdf.socials.facebook') => $product->facility?->facebook_url,
                __('pdf.socials.twitter') => $product->facility?->twitter_url,
                __('pdf.socials.instagram') => $product->facility?->instagram_url,
                __('pdf.socials.linkedin') => $product->facility?->linkedin_url,
            ], fn ($v) => !empty($v));
        @endphp

        @if(!$hasAny)
            <div class="cta-row">{{ __('pdf.slides.cta.contact_us') }}</div>
        @endif

        @if($product->contact_phone)
            <div class="cta-row">{{ $product->contact_phone }}</div>
        @endif

        @if($product->contact_email)
            <div class="cta-row">{{ $product->contact_email }}</div>
        @endif

        @foreach($socials as $label => $url)
            <div class="cta-row">{{ $label }}: {{ $url }}</div>
        @endforeach
    </div>
@endif