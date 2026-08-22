@if(!$pdfOnlySlideKey || $pdfOnlySlideKey === 'gallery')
    <div class="slide gallery-slide" style="justify-content:center; align-items:center; text-align:center;">
        <h2>{{ $slideTitle ?? __('pdf.slides.gallery.title') }}</h2>
        <p class="section-lead" style="max-width: 70%;">{{ __('pdf.slides.gallery.lead_link') }}</p>

        <a href="{{ route('public.products.gallery', $product) }}" target="_blank" class="pdf-gallery-button" style="
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 6mm;
            padding: 5mm 12mm;
            border-radius: var(--card-radius, 4mm);
            background: var(--brand);
            color: #fff;
            font-size: var(--fs-value);
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.18);
        ">
            <span>{{ __('pdf.slides.gallery.open') }}</span>
        </a>
    </div>
@endif
