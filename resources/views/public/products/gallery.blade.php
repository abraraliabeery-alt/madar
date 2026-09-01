<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('products.gallery.title') }} {{ $product->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #0b1220;
            font-family: 'Cairo', 'Tajawal', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #fff;
        }

        .gallery-wrap {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .gallery-top {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(6px);
        }

        .gallery-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            opacity: 0.95;
        }

        .gallery-close,
        .gallery-arrow {
            background: rgba(255,255,255,0.12);
            border: 0;
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 22px;
            line-height: 1;
            transition: background 0.15s ease;
        }

        .gallery-close:hover,
        .gallery-arrow:hover {
            background: rgba(255,255,255,0.25);
        }

        .gallery-main {
            flex: 1 1 auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .gallery-stage {
            max-width: 100%;
            max-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-stage img {
            max-width: 100%;
            max-height: calc(100vh - 210px);
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.45);
        }

        .gallery-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .gallery-arrow.prev { right: 20px; }
        .gallery-arrow.next { left: 20px; }

        .gallery-counter {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.45);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 14px;
            direction: ltr;
        }

        .gallery-thumbs {
            flex: 0 0 auto;
            padding: 14px 20px;
            background: rgba(0,0,0,0.35);
            display: flex;
            gap: 10px;
            overflow-x: auto;
            justify-content: center;
        }

        .gallery-thumb {
            flex: 0 0 auto;
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            opacity: 0.7;
            transition: all 0.15s ease;
        }

        .gallery-thumb.active,
        .gallery-thumb:hover {
            border-color: #fff;
            opacity: 1;
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .empty-state {
            text-align: center;
            color: #aaa;
        }

        @media (max-width: 768px) {
            .gallery-arrow { width: 38px; height: 38px; font-size: 18px; }
            .gallery-arrow.prev { right: 8px; }
            .gallery-arrow.next { left: 8px; }
            .gallery-thumbs { justify-content: flex-start; }
        }
    </style>
</head>
<body>
    @php
        $images = $images ?? [];
        $count = count($images);
    @endphp

    <div class="gallery-wrap" data-count="{{ $count }}">
        <div class="gallery-top">
            <h1 class="gallery-title">{{ $product->title }}</h1>
            <button class="gallery-close" onclick="window.close(); window.history.back();" title="{{ __('products.gallery.close') }}">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="gallery-main">
            @if($count > 0)
                <button class="gallery-arrow prev" onclick="gallery.prev()" title="{{ __('products.gallery.previous') }}">&#8250;</button>
                <button class="gallery-arrow next" onclick="gallery.next()" title="{{ __('products.gallery.next') }}">&#8249;</button>

                <div class="gallery-stage" id="galleryStage">
                    <img src="{{ $images[0] }}" alt="{{ $product->title }}" id="galleryImage">
                </div>

                <div class="gallery-counter" id="galleryCounter">1 / {{ $count }}</div>
            @else
                <div class="empty-state">{{ __('products.gallery.no_images') }}</div>
            @endif
        </div>

        @if($count > 1)
            <div class="gallery-thumbs">
                @foreach($images as $i => $src)
                    <div class="gallery-thumb {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" onclick="gallery.go({{ $i }})">
                        <img src="{{ $src }}" alt="">
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        const gallery = {
            images: @json($images),
            index: 0,
            count: {{ $count }},
            img: document.getElementById('galleryImage'),
            counter: document.getElementById('galleryCounter'),
            thumbs: document.querySelectorAll('.gallery-thumb'),

            render() {
                if (this.img) this.img.src = this.images[this.index];
                if (this.counter) this.counter.textContent = (this.index + 1) + ' / ' + this.count;
                this.thumbs.forEach((t, i) => t.classList.toggle('active', i === this.index));
            },

            next() {
                this.index = (this.index + 1) % this.count;
                this.render();
            },

            prev() {
                this.index = (this.index - 1 + this.count) % this.count;
                this.render();
            },

            go(i) {
                this.index = i;
                this.render();
            }
        };

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') gallery.prev();
            if (e.key === 'ArrowLeft') gallery.next();
            if (e.key === 'Escape') window.close();
        });
    </script>
</body>
</html>
