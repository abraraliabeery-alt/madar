<!doctype html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل القطعة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|cairo:400,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['class', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'Segoe UI', 'Tahoma', 'Arial', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <script>
        (function () {
            try {
                const stored = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const shouldUseDark = stored ? stored === 'dark' : prefersDark;
                const initial = shouldUseDark ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', initial);
                document.documentElement.classList.toggle('dark', initial === 'dark');
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleTheme() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const next = isDark ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            document.documentElement.classList.toggle('dark', next === 'dark');
            try {
                localStorage.setItem('theme', next);
            } catch (e) {}
            const buttons = document.querySelectorAll('[data-theme-toggle]');
            buttons.forEach((btn) => {
                btn.setAttribute('aria-pressed', next === 'dark' ? 'true' : 'false');
                const sun = btn.querySelector('[data-icon="sun"]');
                const moon = btn.querySelector('[data-icon="moon"]');
                if (sun && moon) {
                    sun.classList.toggle('hidden', next !== 'dark');
                    moon.classList.toggle('hidden', next === 'dark');
                }
            });
        }
    </script>
    <style>
        :root {
            --bg: #f8fafc;
            --fg: #0f172a;
            --muted: rgba(15, 23, 42, 0.62);
            --border: rgba(15, 23, 42, 0.14);
            --card: rgba(255, 255, 255, 0.82);
            --accent: #b8892f;
            --accent2: #111a3a;
            --ring: rgba(184, 137, 47, 0.30);
            --shadowSoft: 0 14px 40px rgba(2, 6, 23, 0.12);
        }

        [data-theme='dark'] {
            --bg: #070b16;
            --fg: #f8fafc;
            --muted: rgba(226,232,240,0.76);
            --border: rgba(148,163,184,0.20);
            --card: rgba(15,23,42,0.78);
            --accent: #d2ae63;
            --accent2: #2c355f;
            --ring: rgba(184, 137, 47, 0.26);
            --shadowSoft: 0 14px 40px rgba(2, 6, 23, 0.55);
        }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif;
            background:
                radial-gradient(1100px 520px at 12% 0%, color-mix(in oklab, var(--accent) 18%, transparent), transparent 60%),
                radial-gradient(900px 480px at 92% 8%, color-mix(in oklab, var(--accent2) 10%, transparent), transparent 62%),
                var(--bg);
            color: var(--fg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        [dir='rtl'] body {
            font-family: 'Cairo', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Tahoma, Arial, sans-serif;
        }

        :focus-visible {
            outline: none;
            box-shadow: 0 0 0 4px var(--ring);
            border-radius: 12px;
        }

        .nav-link {
            border-radius: 12px;
            padding: 0.5rem 0.75rem;
            color: var(--muted);
            transition: none;
        }

        .nav-link:hover {
            background: transparent;
            color: var(--fg);
        }

        .icon-btn {
            display: inline-flex;
            height: 2.5rem;
            width: 2.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            background: var(--card);
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.04);
            transition: none;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--muted);
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            transition: none;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 1.25rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: white;
            box-shadow: var(--shadowSoft);
            transition: none;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 1.25rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--fg);
            transition: none;
        }

        .card {
            border-radius: 1.5rem;
            border: 1px solid var(--border);
            background: var(--card);
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.04);
        }

        .hero-bg {
            position: relative;
            overflow: clip;
            isolation: isolate;
        }

        .hero-bg.hero-bg--photo {
            background-image:
                linear-gradient(180deg, color-mix(in oklab, var(--bg) 50%, transparent) 0%, color-mix(in oklab, var(--bg) 82%, transparent) 55%, var(--bg) 100%),
                radial-gradient(1100px 520px at 20% 0%, color-mix(in oklab, var(--accent) 26%, transparent), transparent 62%),
                radial-gradient(900px 420px at 92% 12%, color-mix(in oklab, var(--accent2) 14%, transparent), transparent 65%),
                url('/images/طريق الياقوت عرض 60م (1).pdf.jpg');
            background-repeat: no-repeat, no-repeat, no-repeat, no-repeat;
            background-position: center, center, center, top center;
            background-size: cover;
        }

        .hero-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                radial-gradient(1100px 520px at 20% 0%, color-mix(in oklab, var(--accent) 32%, transparent), transparent 60%),
                radial-gradient(900px 420px at 92% 12%, color-mix(in oklab, var(--accent2) 18%, transparent), transparent 62%),
                linear-gradient(180deg, color-mix(in oklab, var(--bg) 62%, transparent) 0%, color-mix(in oklab, var(--bg) 92%, transparent) 60%, var(--bg) 100%),
                repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.14) 0 1px, transparent 1px 44px);
            opacity: 0.35;
        }

        .media-grid { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 0.75rem; }
        .media-tile { border: 1px solid var(--border); border-radius: 1.25rem; overflow: hidden; background: color-mix(in oklab, var(--card) 92%, transparent); }
        .media-tile img { width: 100%; height: 88px; object-fit: cover; display: block; }

        @media (max-width: 900px) {
            .media-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
    </style>
</head>
<body>
@php
    $st = (string) ($lot->status ?? 'available');
    $statusText = $st === 'available' ? 'متاح' : ($st === 'reserved' ? 'محجوز' : 'مباع');
    $statusColor = $st === 'available' ? '#22C55E' : ($st === 'reserved' ? '#F59E0B' : '#EF4444');
    $area = $lot->area_m2;
    $price = $lot->price;
    $coordText = null;
    if (is_array($centroid) && isset($centroid['lat'], $centroid['lng'])) {
        $coordText = number_format((float)$centroid['lat'], 6, '.', '') . ', ' . number_format((float)$centroid['lng'], 6, '.', '');
    }

    $tplImg = function (string $label) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">'
            . '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0" stop-color="#0b1220"/><stop offset="1" stop-color="#0f1b2d"/>'
            . '</linearGradient></defs>'
            . '<rect width="800" height="500" fill="url(#g)"/>'
            . '<rect x="24" y="24" width="752" height="452" rx="22" fill="rgba(255,255,255,0.03)" stroke="rgba(148,163,184,0.28)" stroke-width="2"/>'
            . '<path d="M90 250 H710" stroke="rgba(148,163,184,0.35)" stroke-width="2" stroke-dasharray="10 10"/>'
            . '<path d="M400 90 V410" stroke="rgba(148,163,184,0.35)" stroke-width="2" stroke-dasharray="10 10"/>'
            . '<text x="50%" y="44%" text-anchor="middle" font-size="34" font-family="ui-sans-serif,system-ui,-apple-system,Segoe UI,Arial" fill="#E5E7EB" font-weight="800">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</text>'
            . '<text x="50%" y="55%" text-anchor="middle" font-size="18" font-family="ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace" fill="rgba(229,231,235,0.72)">800 × 500</text>'
            . '</svg>';
        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    };
@endphp

<header class="site-header sticky top-0 z-50 is-top" data-site-header>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('public.plans.ajlan') }}" class="flex items-center gap-2">
                    <span class="h-9 w-9 rounded-xl" style="background: linear-gradient(135deg, var(--accent), var(--accent2));"></span>
                    <div class="leading-tight">
                        <div class="font-semibold">مخطط عجلان</div>
                        <div class="text-xs" style="color: var(--muted);">تفاصيل القطعة</div>
                    </div>
                </a>
            </div>
            <div class="flex items-center gap-2">
                <a class="nav-link hidden sm:inline-flex" href="{{ route('public.plans.ajlan') }}">المخطط</a>
                <button type="button" onclick="toggleTheme()" data-theme-toggle class="icon-btn" aria-pressed="false" aria-label="Toggle theme">
                    <svg data-icon="moon" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                    <svg data-icon="sun" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728L7.05 7.05m9.9 9.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

<main>
    <section class="hero-bg hero-bg--photo" style="min-height: 100svh;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-14">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7">
                    <div class="card overflow-hidden shadow-soft">
                        <div class="p-6" style="border-bottom: 1px solid var(--border);">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-xs" style="color: var(--muted);">{{ $plan->name ?? 'مخطط عجلان' }} • {{ $plan->plan_number ?? '-' }}</div>
                                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">قطعة رقم {{ $lot->lot_number }}</h1>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="chip" style="display:inline-flex; align-items:center; gap:.4rem;">
                                            <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $statusColor }}"></span>
                                            <span>{{ $statusText }}</span>
                                        </span>
                                        <span class="chip">المساحة: {{ $area ? number_format($area, 0) : '-' }} م²</span>
                                        <span class="chip">السعر: {{ $price ? number_format($price, 0) : '-' }} ر.س</span>
                                    </div>
                                </div>
                                <div class="hidden sm:flex flex-col items-end gap-2">
                                    <a class="btn-secondary" href="{{ route('public.plans.ajlan') }}">رجوع للمخطط</a>
                                    <a class="btn-primary" href="{{ route('public.plans.ajlan') }}?lot={{ urlencode((string)$lot->lot_number) }}">عرض على الخريطة</a>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="card p-4" style="background: color-mix(in oklab, var(--card) 92%, transparent);">
                                    <div class="text-xs" style="color: var(--muted);">الاستخدام</div>
                                    <div class="mt-1 font-semibold">{{ $lot->usage ?: '—' }}</div>
                                </div>
                                <div class="card p-4" style="background: color-mix(in oklab, var(--card) 92%, transparent);">
                                    <div class="text-xs" style="color: var(--muted);">رقم المرجع</div>
                                    <div class="mt-1 font-semibold">{{ $lot->excel_lot_number ?? $lot->lot_number }}</div>
                                </div>
                                <div class="card p-4" style="background: color-mix(in oklab, var(--card) 92%, transparent);">
                                    <div class="text-xs" style="color: var(--muted);">الإحداثيات</div>
                                    <div class="mt-1 font-semibold" style="direction:ltr; text-align:right;">{{ $coordText ?: '—' }}</div>
                                </div>
                                <div class="card p-4" style="background: color-mix(in oklab, var(--card) 92%, transparent);">
                                    <div class="text-xs" style="color: var(--muted);">الخدمات</div>
                                    <div class="mt-1" style="color: var(--muted); font-size: .9rem;">ماء • كهرباء • طرق</div>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col sm:flex-row gap-3">
                                <a class="btn-primary" href="https://wa.me/{{ preg_replace('/\D+/', '', (string)$whatsappNumber) }}?text={{ urlencode('استفسار عن قطعة رقم ' . $lot->lot_number . ' في مخطط ' . ($plan->name ?? 'عجلان')) }}" target="_blank" rel="noopener">استفسار واتساب</a>
                                <a class="btn-secondary" href="#media">الصور والفيديو</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="card overflow-hidden shadow-soft" id="media">
                        <div class="p-5" style="border-bottom: 1px solid var(--border);">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold">الصور والفيديو</div>
                                    <div class="text-xs mt-1" style="color: var(--muted);">قالب ستاتيك للعرض — سيتم ربطه بالمرفقات لاحقًا</div>
                                </div>
                                <span class="chip">6 صور</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="card overflow-hidden" style="background: color-mix(in oklab, var(--card) 92%, transparent);">
                                <img alt="" src="{{ $tplImg('مخطط القطعة') }}" style="width:100%; height: 280px; object-fit: cover; display:block;" loading="lazy">
                            </div>
                            <div class="mt-4 media-grid">
                                <div class="media-tile"><img alt="" src="{{ $tplImg('واجهة') }}" loading="lazy"></div>
                                <div class="media-tile"><img alt="" src="{{ $tplImg('جانبي') }}" loading="lazy"></div>
                                <div class="media-tile"><img alt="" src="{{ $tplImg('أبعاد') }}" loading="lazy"></div>
                                <div class="media-tile"><img alt="" src="{{ $tplImg('مساحة') }}" loading="lazy"></div>
                                <div class="media-tile"><img alt="" src="{{ $tplImg('مرفقات') }}" loading="lazy"></div>
                                <div class="media-tile"><img alt="" src="{{ $tplImg('فيديو') }}" loading="lazy"></div>
                            </div>

                            <div class="mt-4 card overflow-hidden" style="background: color-mix(in oklab, var(--card) 92%, transparent);">
                                <iframe
                                    src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                    title="Video"
                                    loading="lazy"
                                    referrerpolicy="no-referrer"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                    style="width:100%; height: 280px; border:0; display:block;"
                                ></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="card overflow-hidden shadow-soft mt-6">
                        <div class="p-5" style="border-bottom: 1px solid var(--border);">
                            <div class="text-sm font-semibold">ملخص سريع</div>
                            <div class="text-xs mt-1" style="color: var(--muted);">نص جاهز للعرض (مؤقت)</div>
                        </div>
                        <div class="p-5 text-sm" style="color: var(--muted); line-height: 1.9;">
                            قطعة مميزة داخل مخطط {{ $plan->name ?? 'عجلان' }}، مناسبة للاستخدام {{ $lot->usage ?: 'العام' }}. يمكن التواصل مباشرة للاستفسار عن التفاصيل وخيارات الدفع.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 sm:hidden card p-4" style="position: sticky; bottom: 0; z-index: 20; backdrop-filter: blur(10px);">
                <div class="flex gap-3">
                    <a class="btn-primary" style="flex:1;" href="https://wa.me/{{ preg_replace('/\D+/', '', (string)$whatsappNumber) }}?text={{ urlencode('استفسار عن قطعة رقم ' . $lot->lot_number . ' في مخطط ' . ($plan->name ?? 'عجلان')) }}" target="_blank" rel="noopener">واتساب</a>
                    <a class="btn-secondary" style="flex:1;" href="{{ route('public.plans.ajlan') }}?lot={{ urlencode((string)$lot->lot_number) }}">المخطط</a>
                </div>
            </div>
        </div>
    </section>
</main>

<footer style="border-top: 1px solid var(--border);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="font-semibold">مخطط عجلان واخوانه</div>
                <div class="mt-2 text-sm" style="color: var(--muted);">صفحة مستقلة لعرض المخطط والقطع بشكل تفاعلي.</div>
            </div>
            <div>
                <div class="font-semibold">روابط سريعة</div>
                <div class="mt-3 flex flex-col gap-2 text-sm">
                    <a style="color: var(--muted);" class="hover:underline" href="{{ route('public.plans.ajlan') }}">المخطط</a>
                    <a style="color: var(--muted);" class="hover:underline" href="#media">الصور والفيديو</a>
                    <a style="color: var(--muted);" class="hover:underline" href="/">الرئيسية</a>
                </div>
            </div>
            <div>
                <div class="font-semibold">الوضع الليلي</div>
                <div class="mt-3 text-sm" style="color: var(--muted);">بدّل بين الداكن والفاتح من زر أعلى الصفحة.</div>
            </div>
        </div>
        <div class="mt-8 text-xs" style="color: var(--muted);">© {{ date('Y') }} جميع الحقوق محفوظة</div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const buttons = document.querySelectorAll('[data-theme-toggle]');
        buttons.forEach((btn) => {
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            const sun = btn.querySelector('[data-icon="sun"]');
            const moon = btn.querySelector('[data-icon="moon"]');
            if (sun && moon) {
                sun.classList.toggle('hidden', !isDark);
                moon.classList.toggle('hidden', isDark);
            }
        });
    });
</script>
</body>
</html>
