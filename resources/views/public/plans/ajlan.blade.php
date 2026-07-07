<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/V0SjfGQ7_400x400.jpg') }}">
    <title>مخطط عجلان واخوانه</title>
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

            const header = document.querySelector('[data-site-header]');
            if (header) {
                const sync = () => {
                    const scrolled = window.scrollY > 8;
                    header.classList.toggle('is-scrolled', scrolled);
                    header.classList.toggle('is-top', !scrolled);
                };
                sync();
                window.addEventListener('scroll', sync, { passive: true });
            }
        });
    </script>
    <style>
        :root {
            --bg: #ffffff;
            --fg: #000000;
            --muted: #666666;
            --card: #ffffff;
            --border: rgba(0, 0, 0, 0.12);
            --ring: rgba(184, 137, 47, 0.35);
            --accent: #b8892f;
            --accent2: #111a3a;
            --shadow: 0 18px 50px rgba(0, 0, 0, 0.10);
            --shadowSoft: 0 10px 30px rgba(0, 0, 0, 0.10);
        }

        html[data-theme='dark'] {
            --bg: #000000;
            --fg: #ffffff;
            --muted: rgba(255, 255, 255, 0.70);
            --card: rgba(255, 255, 255, 0.06);
            --border: rgba(255, 255, 255, 0.12);
            --ring: rgba(184, 137, 47, 0.26);
            --accent: #d2ae63;
            --accent2: #2c355f;
            --shadow: 0 22px 70px rgba(0, 0, 0, 0.60);
            --shadowSoft: 0 12px 40px rgba(0, 0, 0, 0.45);
        }

        html {
            scroll-behavior: smooth;
            color-scheme: light;
        }

        html[data-theme='dark'] {
            color-scheme: dark;
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

        .shadow-soft { box-shadow: var(--shadowSoft); }

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

        .cad-label {
            background: color-mix(in oklab, var(--card) 88%, transparent);
            border: 1px solid var(--border);
            color: var(--fg);
            border-radius: 10px;
            padding: 2px 6px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            font-size: 12px;
            font-weight: 600;
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
            min-height: 100svh;
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
                repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.14) 0 1px, transparent 1px 44px),
                repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.10) 0 1px, transparent 1px 44px);
            background-repeat: repeat;
            background-position: center;
            filter: saturate(0.95);
            z-index: -1;
        }

        .hero-bg.hero-bg--photo::before {
            background-image:
                linear-gradient(180deg, color-mix(in oklab, var(--bg) 52%, transparent) 0%, color-mix(in oklab, var(--bg) 78%, transparent) 55%, var(--bg) 100%),
                radial-gradient(1100px 520px at 20% 0%, color-mix(in oklab, var(--accent) 26%, transparent), transparent 62%),
                radial-gradient(900px 420px at 92% 12%, color-mix(in oklab, var(--accent2) 14%, transparent), transparent 65%),
                url('/images/طريق الياقوت عرض 60م (1).pdf.jpg');
            background-repeat: no-repeat, no-repeat, no-repeat, no-repeat;
            background-position: center, center, center, top center;
            background-size: auto, auto, auto, cover;
            background-color: var(--bg);
            filter: saturate(1) contrast(1);
        }

        html[data-theme="dark"] .hero-bg::before {
            filter: saturate(0.9) brightness(0.75);
            background-image:
                radial-gradient(1100px 520px at 20% 0%, color-mix(in oklab, var(--accent) 26%, transparent), transparent 62%),
                radial-gradient(900px 420px at 92% 12%, color-mix(in oklab, var(--accent2) 22%, transparent), transparent 65%),
                linear-gradient(180deg, color-mix(in oklab, var(--bg) 68%, transparent) 0%, color-mix(in oklab, var(--bg) 92%, transparent) 60%, var(--bg) 100%),
                repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.08) 0 1px, transparent 1px 44px),
                repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.06) 0 1px, transparent 1px 44px);
        }

        html[data-theme="dark"] .hero-bg.hero-bg--photo::before {
            filter: brightness(0.82) saturate(0.95) contrast(1.02);
            background-image:
                linear-gradient(180deg, color-mix(in oklab, var(--bg) 48%, transparent) 0%, color-mix(in oklab, var(--bg) 76%, transparent) 55%, var(--bg) 100%),
                radial-gradient(1100px 520px at 20% 0%, color-mix(in oklab, var(--accent) 22%, transparent), transparent 65%),
                radial-gradient(900px 420px at 92% 12%, color-mix(in oklab, var(--accent2) 18%, transparent), transparent 68%),
                url('/images/طريق الياقوت عرض 60م (1).pdf.jpg');
            background-repeat: no-repeat, no-repeat, no-repeat, no-repeat;
            background-position: center, center, center, top center;
            background-size: auto, auto, auto, cover;
            background-color: var(--bg);
        }

        .hero-eyebrow {
            margin-bottom: 1.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            background: color-mix(in oklab, var(--card) 88%, transparent);
            color: var(--muted);
        }

        .hero-eyebrow-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
        }

        .hero-divider {
            height: 1px;
            width: 140px;
            margin-top: 18px;
            margin-inline: auto;
            background: linear-gradient(90deg, transparent, color-mix(in oklab, var(--accent) 45%, var(--border)), transparent);
            opacity: 0.9;
        }

        .site-header {
            transition: background-color 220ms ease, border-color 220ms ease, box-shadow 220ms ease;
        }

        .site-header.is-top {
            background: transparent;
            border-bottom: 1px solid transparent;
            backdrop-filter: none;
        }

        .site-header.is-scrolled {
            border-bottom: 1px solid var(--border);
            background: color-mix(in oklab, var(--bg) 86%, transparent);
            backdrop-filter: blur(14px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }


        /* صورة تقسيم الأراضي فوق المخطط */
        .plan-overlay-image {
            opacity: 1 !important;
            mix-blend-mode: normal !important;
            filter: contrast(1.35) brightness(1.08);
            image-rendering: auto;
        }
    </style>
</head>
<body>

<header class="site-header sticky top-0 z-50 is-top" data-site-header>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="inline-flex items-center gap-2">
                    <img src="{{ asset('images/sm-logo-ar.png') }}" alt="مخطط عجلان واخوانه" class="w-20 h-10 rounded-xl shadow-soft object-contain">
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a class="nav-link hidden sm:inline-flex" href="#map">المخطط</a>
                <button type="button" onclick="toggleTheme()" data-theme-toggle class="icon-btn" aria-pressed="false" aria-label="Toggle theme">
                    <svg data-icon="moon" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                    <svg data-icon="sun" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-12.728L7.05 7.05m10.9 10.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

<main>
    <section class="hero-bg hero-bg--photo" aria-label="Hero">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 min-h-[calc(100svh-4.5rem)] flex items-center py-12 sm:py-14 lg:py-16">
            <div class="mx-auto max-w-3xl lg:text-center">
                <div class="hero-eyebrow mx-auto w-fit">
                    <span class="hero-eyebrow-dot" aria-hidden="true"></span>
                    <span>واجهة تفاعلية لعرض المخطط</span>
                </div>

                <h1 class="mx-auto text-balance max-w-2xl text-2xl font-semibold leading-[1.25] tracking-tight sm:text-3xl lg:text-4xl">
                    <span class="block">مخطط عجلان واخوانه</span>
                    <span class="block mt-2" style="color: var(--muted); font-weight: 600;">عرض المخطط والقطع على خريطة تفاعلية</span>
                </h1>

                <p class="mx-auto mt-3 max-w-2xl text-pretty text-[13px] leading-7 sm:text-sm" style="color: var(--muted);">
                    استعرض المخطط على خريطة حقيقية، وابحث عن القطع بسرعة، واطّلع على تفاصيل المساحة والحالة والسعر.
                </p>

                <div class="hero-divider" aria-hidden="true"></div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center lg:justify-center">
                    <a href="#map" class="btn-primary">استعراض المخطط</a>
                </div>

                <div class="mt-8 grid gap-3 sm:grid-cols-2 max-w-2xl mx-auto">
                    <div class="card p-4">
                        <div class="text-xs" style="color: var(--muted);">رقم المخطط</div>
                        <div class="mt-1 text-lg font-semibold">{{ $planNumber ?? '—' }}</div>
                    </div>
                    <div class="card p-4">
                        <div class="text-xs" style="color: var(--muted);">المساحة</div>
                        <div class="mt-1 text-lg font-semibold">{{ $planAreaKm2 ?? '—' }} <span class="text-sm" style="color: var(--muted);">كم²</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="map" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10" style="scroll-margin-top: 5rem;">
        <div class="card overflow-hidden shadow-soft">
            <div class="p-5" style="border-bottom: 1px solid var(--border);">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">الخريطة والقطع</h2>
                        <p class="text-sm mt-1" style="color: var(--muted);">اضغط على القطعة لمعرفة التفاصيل</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full ml-2" style="background:#22C55E"></div>
                            <span class="text-sm" style="color: var(--muted);">متاح</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full ml-2" style="background:#F59E0B"></div>
                            <span class="text-sm" style="color: var(--muted);">محجوز</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full ml-2" style="background:#EF4444"></div>
                            <span class="text-sm" style="color: var(--muted);">مباع</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12" style="min-height: 660px;">
                <aside id="lots" class="lg:col-span-4 border-b lg:border-b-0 lg:border-l" style="border-color: var(--border); background: var(--card);">
                    <div class="p-4">
                    </div>

                    <div id="lotsList" class="px-4 pb-4" style="max-height: 560px; overflow:auto; display:none;"></div>
                </aside>

                <section class="lg:col-span-8" style="background: var(--card);">
                    <div class="p-3 flex items-center justify-between" style="border-bottom: 1px solid var(--border);">
                        <div class="text-xs" style="color: var(--muted);">خريطة OSM - اسحب للتنقل وعجلة الماوس للتكبير</div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="zoomInMap()" class="chip" style="height: 2.25rem; display:inline-flex; align-items:center; gap:.4rem;">
                                <i data-lucide="zoom-in" style="width:16px; height:16px;"></i>
                                <span>تكبير</span>
                            </button>
                            <button type="button" onclick="zoomOutMap()" class="chip" style="height: 2.25rem; display:inline-flex; align-items:center; gap:.4rem;">
                                <i data-lucide="zoom-out" style="width:16px; height:16px;"></i>
                                <span>تصغير</span>
                            </button>
                        </div>
                    </div>
                    <div id="ajlanPlanMap" class="w-full" style="height: 610px;"></div>
                </section>
            </div>
        </div>

    </section>

    <!-- Contact Form Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="card p-8 shadow-soft">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold">تواصل معنا</h2>
                <p class="text-sm mt-2" style="color: var(--muted);">نحن هنا لمساعدتك. أرسل لنا استفسارك وسنرد عليك في أقرب وقت.</p>
            </div>
            <form action="{{ route('contact.home.store') }}" method="POST" class="max-w-2xl mx-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">الاسم</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 text-sm rounded-2xl border" style="border-color: var(--border); background: color-mix(in oklab, var(--card) 92%, transparent); color: var(--fg);" placeholder="أدخل اسمك">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">رقم الهاتف</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-3 text-sm rounded-2xl border" style="border-color: var(--border); background: color-mix(in oklab, var(--card) 92%, transparent); color: var(--fg);" placeholder="05xxxxxxxx">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 text-sm rounded-2xl border" style="border-color: var(--border); background: color-mix(in oklab, var(--card) 92%, transparent); color: var(--fg);" placeholder="example@email.com">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-2">الرسالة</label>
                    <textarea name="message" required rows="4" class="w-full px-4 py-3 text-sm rounded-2xl border" style="border-color: var(--border); background: color-mix(in oklab, var(--card) 92%, transparent); color: var(--fg);" placeholder="اكتب رسالتك هنا..."></textarea>
                </div>
                <div class="mt-6 text-center">
                    <button type="submit" class="btn-primary">إرسال الرسالة</button>
                </div>
            </form>
        </div>
    </section>
</main>

<footer style="border-top: 1px solid var(--border);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-sm font-semibold">مخطط عجلان واخوانه</div>
            <div class="text-xs" style="color: var(--muted);">© {{ date('Y') }} جميع الحقوق محفوظة</div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet-imageoverlay-rotated@0.2.1/Leaflet.ImageOverlay.Rotated.js"></script>
<script src="https://unpkg.com/proj4@2.9.2/dist/proj4.js"></script>
<script src="https://unpkg.com/lucide@0.453.0/dist/umd/lucide.js"></script>

<script>
const centerLat = @json($centerLat);
const centerLng = @json($centerLng);
const geoJsonData = @json($geoJson);
const whatsappNumber = @json($whatsappNumber);
const planShadeRadiusMeters = @json($planShadeRadiusMeters ?? null);
const legacyOverlaysEnabled = false;

let map;
let lotsLayer;
let lotsPointsLayer;
let lotsLabelsLayer;
let roadsLayer;
let lotsIndex = {};
let lotsPointsIndex = {};
let lotsSource = [];
let lotsPointsSource = [];
let selectedLot = null;
let pointLotRadiusMeters = 45;
let initialLotToSelect = null;
let planOverlay = null;
let planOverlayEnabled = false;

const cadDxfFileName = 'A';
let planOverlayClipCleanup = null;

let cadTextLayer;
let cadPointLayer;
let cadPolylineLayer;
let cadLineLayer;

let phase1BoundariesLayer;
let phase1LabelsLayer;
let phase1Loaded = false;
let phase1LabelsFeatures = null;
let phase1LabelsBuilt = false;
let phase1LabelsBuildInProgress = false;
let phase1LabelsCanvasLayer = null;

let utmLandBoundariesLayer;
let utmLandLabelsCanvasLayer;
let utmLandLabelsFeatures = null;
let utmLandLastSig = null;
let cadCalibration = null;
let cadPlanLocalBbox = null;
let cadTextReqId = 0;
let cadPointReqId = 0;
let cadPolylineReqId = 0;
let cadLineReqId = 0;
let cadLastRefreshSig = null;

const planImageUrl = @json(asset('assets/assets/ajlan_plan_true_vector.svg') . '?v=' . @filemtime(public_path('assets/assets/ajlan_plan_true_vector.svg')));

const UTM38_DEF = '+proj=utm +zone=38 +datum=WGS84 +units=m +no_defs';

function trySelectInitialLot() {
    if (!initialLotToSelect) return;
    const key = String(initialLotToSelect);
    const hasPolygon = lotsSource.some(f => String(f?.properties?.lot_number) === key);
    const hasPoint = lotsPointsSource.some(p => String(p?.lot_number) === key);
    if (!hasPolygon && !hasPoint) return;
    const lot = initialLotToSelect;
    initialLotToSelect = null;
    selectLot(lot, true);
}

function getLotStyle(status) {
    return { color: '#111A3A', fillColor: '#111A3A' };
}

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>'"]/g, (c) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;',
    }[c]));
}

function formatPrice(price) {
    const n = Number(price);
    if (!Number.isFinite(n)) return '';
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 0
    }).format(n);
}

function statusText(status) {
    if (status === 'available') return 'متاح';
    if (status === 'reserved') return 'محجوز';
    if (status === 'sold') return 'مباع';
    return status || '';
}

function buildWhatsAppUrl(lotProps) {
    const to = String(whatsappNumber || '').replace(/\D/g, '');
    const msg = `استفسار عن قطعة رقم ${lotProps.lot_number} - الحالة: ${statusText(lotProps.status)} - المساحة: ${lotProps.area}م²`;
    const encoded = encodeURIComponent(msg);

    if (!to) {
        return `https://wa.me/?text=${encoded}`;
    }

    return `https://wa.me/${to}?text=${encoded}`;
}

function lotStyle(feature) {
    const s = feature?.properties?.status;
    const base = getLotStyle(s);
    const isActive = selectedLot && selectedLot.lot_number === feature?.properties?.lot_number;
    const active = { color: '#B8892F', fillColor: '#B8892F' };
    return {
        color: isActive ? active.color : base.color,
        fillColor: isActive ? active.fillColor : base.fillColor,
        weight: isActive ? 3 : 2,
        fillOpacity: isActive ? 0.55 : 0.30,
    };
}

function initMap() {
    const center = [centerLat, centerLng];

    map = L.map('ajlanPlanMap', {
        zoomControl: true,
        attributionControl: false,
        maxZoom: 22,
    }).setView(center, 17);

    map.createPane('boundaryPane');
    map.getPane('boundaryPane').style.zIndex = 250;

    map.createPane('roadsPane');
    map.getPane('roadsPane').style.zIndex = 390;

    map.createPane('planImagePane');
    map.getPane('planImagePane').style.zIndex = 410;
    map.getPane('planImagePane').style.pointerEvents = 'none';

    map.createPane('lotsPane');
    map.getPane('lotsPane').style.zIndex = 420;

    map.createPane('cadPane');
    map.getPane('cadPane').style.zIndex = 430;

    pointLotRadiusMeters = Number(planShadeRadiusMeters) > 0
        ? Math.max(25, Math.min(70, Number(planShadeRadiusMeters) / 140))
        : 45;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxNativeZoom: 19,
        maxZoom: 22,
    }).addTo(map);

    map.on('moveend zoomend', debounce(refreshUtmLandLayers, 220));
    refreshUtmLandLayers();

    fetch('/geojson/plan-boundary.geojson', { cache: 'no-store' })
        .then((res) => {
            if (!res.ok) throw new Error('GeoJSON not found');
            return res.json();
        })
        .then((boundary) => {
            if (!boundary || boundary.type !== 'FeatureCollection') return;

            const reprojected = geoJsonLooksLikeUtm(boundary)
                ? reprojectGeoJsonUtmToWgs84(boundary)
                : boundary;

            try {
                const ringLatLngs = getOuterPolygonRingLatLngs(reprojected);
                if (Array.isArray(ringLatLngs) && ringLatLngs.length) {
                    const coords = ringLatLngs.map(ll => [Number(ll.lat.toFixed(8)), Number(ll.lng.toFixed(8))]);
                    window.__ajlan_plan_outer_ring_latlng = coords;
                }
            } catch (e) {}

            const layer = L.geoJSON(reprojected, {
                pane: 'boundaryPane',
                style: {
                    color: '#1D4ED8',
                    weight: 3,
                    fillColor: 'transparent',
                    fillOpacity: 0,
                }
            }).addTo(map);

            const bounds = L.latLngBounds(
                [24.543627000069844, 46.81368100000787],
                [24.56727800007028, 46.84426200000871]
            );
            if (bounds && bounds.isValid()) {
                map.fitBounds(bounds.pad(0.15));

                if (legacyOverlaysEnabled) {
                    if (planOverlay) {
                        planOverlay.remove();
                    }

                    try {
                        const sw = bounds.getSouthWest();
                        const ne = bounds.getNorthEast();
                        console.log('Plan bounds (use these numbers for overlay):', [
                            [Number(sw.lat.toFixed(8)), Number(sw.lng.toFixed(8))],
                            [Number(ne.lat.toFixed(8)), Number(ne.lng.toFixed(8))]
                        ]);
                    } catch (e) {}

                    planOverlay = L.imageOverlay(planImageUrl, bounds, {
                        pane: 'planImagePane',
                        opacity: 1,
                        interactive: false,
                        className: 'plan-overlay-image'
                    }).addTo(map);

                    planOverlay.bringToFront();
                }
            }

            if (bounds && bounds.isValid()) {
                if (legacyOverlaysEnabled) {
                    loadOsmRoads(bounds);
                }
            }
        })
        .catch(() => {});

    if (legacyOverlaysEnabled) {
        fetch('/gis/ajlan-lots-points.json', { cache: 'no-store' })
            .then((res) => {
                if (!res.ok) throw new Error('points not found');
                return res.json();
            })
            .then((points) => {
                lotsPointsSource = Array.isArray(points) ? points : [];
                renderLots();
                trySelectInitialLot();
            })
            .catch(() => {
                lotsPointsSource = [];
                renderLots();
            });

        lotsSource = Array.isArray(geoJsonData?.features) ? geoJsonData.features : [];
        renderLots();
        trySelectInitialLot();
    }

}

function latLngBoundsToUtm38Bbox(bounds) {
    try {
        if (!bounds || !bounds.isValid?.()) return null;
        if (typeof proj4 !== 'function') return null;

        const sw = bounds.getSouthWest();
        const ne = bounds.getNorthEast();
        const p1 = proj4('WGS84', UTM38_DEF, [Number(sw.lng), Number(sw.lat)]);
        const p2 = proj4('WGS84', UTM38_DEF, [Number(ne.lng), Number(ne.lat)]);

        const minX = Math.min(Number(p1?.[0]), Number(p2?.[0]));
        const maxX = Math.max(Number(p1?.[0]), Number(p2?.[0]));
        const minY = Math.min(Number(p1?.[1]), Number(p2?.[1]));
        const maxY = Math.max(Number(p1?.[1]), Number(p2?.[1]));
        if (![minX, minY, maxX, maxY].every(Number.isFinite)) return null;

        const padX = (maxX - minX) * 0.08;
        const padY = (maxY - minY) * 0.08;

        return {
            minX: minX - padX,
            minY: minY - padY,
            maxX: maxX + padX,
            maxY: maxY + padY,
        };
    } catch (e) {
        return null;
    }
}

function refreshUtmLandLayers() {
    try {
        if (!map) return;
        const z = Math.round(map.getZoom());
        if (z < 14) {
            if (utmLandBoundariesLayer && map.hasLayer(utmLandBoundariesLayer)) utmLandBoundariesLayer.remove();
            if (utmLandLabelsCanvasLayer && map.hasLayer(utmLandLabelsCanvasLayer)) utmLandLabelsCanvasLayer.remove();
            return;
        }

        const bbox = latLngBoundsToUtm38Bbox(map.getBounds());
        if (!bbox) return;
        const sig = `${z}|${bbox.minX.toFixed(1)},${bbox.minY.toFixed(1)},${bbox.maxX.toFixed(1)},${bbox.maxY.toFixed(1)}`;
        if (utmLandLastSig === sig) return;
        utmLandLastSig = sig;

        loadUtmLandBoundaries(z, bbox);
        loadUtmLandLabels(z, bbox);
    } catch (e) {}
}

function loadUtmLandBoundaries(zoom, bbox) {
    const url = new URL(`/plans/ajlan/cad/file/polylines`, window.location.origin);
    url.searchParams.set('utmOnly', '1');
    url.searchParams.set('minX', String(bbox.minX));
    url.searchParams.set('minY', String(bbox.minY));
    url.searchParams.set('maxX', String(bbox.maxX));
    url.searchParams.set('maxY', String(bbox.maxY));

    const limit = zoom >= 17 ? 4500 : (zoom >= 16 ? 2500 : 1300);
    url.searchParams.set('limit', String(limit));

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) throw new Error(`HTTP ${r.status} ${r.statusText}`);
            return r.json();
        })
        .then((fc) => {
            if (!fc || fc.type !== 'FeatureCollection') return;
            const reprojected = reprojectGeoJsonUtmToWgs84(fc);

            if (utmLandBoundariesLayer) {
                utmLandBoundariesLayer.remove();
                utmLandBoundariesLayer = null;
            }

            utmLandBoundariesLayer = L.geoJSON(reprojected, {
                pane: 'cadPane',
                style: {
                    color: '#16A34A',
                    weight: 2,
                    opacity: 0.9,
                },
            }).addTo(map);
        })
        .catch(() => {});
}

function loadUtmLandLabels(zoom, bbox) {
    const url = new URL(`/plans/ajlan/cad/file/texts`, window.location.origin);
    url.searchParams.set('utmOnly', '1');
    url.searchParams.set('minX', String(bbox.minX));
    url.searchParams.set('minY', String(bbox.minY));
    url.searchParams.set('maxX', String(bbox.maxX));
    url.searchParams.set('maxY', String(bbox.maxY));

    const limit = zoom >= 18 ? 4000 : (zoom >= 17 ? 2200 : (zoom >= 16 ? 1400 : 800));
    url.searchParams.set('limit', String(limit));

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) throw new Error(`HTTP ${r.status} ${r.statusText}`);
            return r.json();
        })
        .then((fc) => {
            if (!fc || fc.type !== 'FeatureCollection') return;

            const feats = Array.isArray(fc?.features) ? fc.features : [];
            const utmPts = feats
                .map((f) => {
                    const c = f?.geometry?.coordinates;
                    const x = Number(c?.[0]);
                    const y = Number(c?.[1]);
                    if (!Number.isFinite(x) || !Number.isFinite(y)) return null;
                    const text = String(f?.properties?.text ?? '').trim();
                    if (!text) return null;
                    return { x, y, text };
                })
                .filter(Boolean);

            utmLandLabelsFeatures = utmPts;
            ensureUtmLandLabelsCanvas();
            if (!map.hasLayer(utmLandLabelsCanvasLayer)) {
                utmLandLabelsCanvasLayer.addTo(map);
            }
        })
        .catch(() => {});
}

function ensureUtmLandLabelsCanvas() {
    try {
        if (utmLandLabelsCanvasLayer) return;

        const CanvasLabels = L.Layer.extend({
            onAdd: function (m) {
                this._map = m;
                this._canvas = L.DomUtil.create('canvas', 'leaflet-utm-land-labels');
                this._canvas.style.position = 'absolute';
                this._canvas.style.pointerEvents = 'none';

                const pane = m.getPane('cadPane') || m.getPane('overlayPane');
                pane.appendChild(this._canvas);

                this._pending = false;
                this._redraw = this._redraw.bind(this);
                this._schedule = this._schedule.bind(this);

                m.on('move zoom resize', this._schedule, this);
                this._schedule();
            },
            onRemove: function (m) {
                m.off('move zoom resize', this._schedule, this);
                if (this._canvas && this._canvas.parentNode) {
                    this._canvas.parentNode.removeChild(this._canvas);
                }
                this._canvas = null;
                this._map = null;
            },
            _schedule: function () {
                if (this._pending) return;
                this._pending = true;
                requestAnimationFrame(this._redraw);
            },
            _redraw: function () {
                this._pending = false;
                const m = this._map;
                const c = this._canvas;
                if (!m || !c) return;

                const size = m.getSize();
                if (!size?.x || !size?.y) return;
                const dpr = window.devicePixelRatio || 1;

                c.width = Math.round(size.x * dpr);
                c.height = Math.round(size.y * dpr);
                c.style.width = `${size.x}px`;
                c.style.height = `${size.y}px`;

                const ctx = c.getContext('2d');
                if (!ctx) return;
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                ctx.clearRect(0, 0, size.x, size.y);

                const z = Math.round(m.getZoom());
                if (z < 15) return;
                const feats = Array.isArray(utmLandLabelsFeatures) ? utmLandLabelsFeatures : [];
                if (!feats.length) return;
                if (typeof proj4 !== 'function') return;

                let maxLabels = 500;
                if (z >= 18) maxLabels = 2000;
                else if (z >= 17) maxLabels = 1300;
                else if (z >= 16) maxLabels = 850;

                let fontSize = 11;
                if (z >= 18) fontSize = 14;
                else if (z >= 17) fontSize = 13;
                else if (z >= 16) fontSize = 12;

                ctx.font = `700 ${fontSize}px system-ui, -apple-system, Segoe UI, Arial`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                const bounds = m.getBounds();
                if (!bounds || !bounds.isValid()) return;

                const drawn = [];
                const pad = 3;
                let shown = 0;

                for (let i = 0; i < feats.length; i++) {
                    if (shown >= maxLabels) break;
                    const f = feats[i];

                    const ll = proj4(UTM38_DEF, 'WGS84', [Number(f.x), Number(f.y)]);
                    const lng = Number(ll?.[0]);
                    const lat = Number(ll?.[1]);
                    if (!Number.isFinite(lat) || !Number.isFinite(lng)) continue;

                    const latlng = L.latLng(lat, lng);
                    if (!bounds.contains(latlng)) continue;

                    const p = m.latLngToContainerPoint(latlng);
                    if (!p) continue;

                    const text = f.text;
                    const w = ctx.measureText(text).width;
                    const h = fontSize;
                    const x0 = p.x - w / 2 - pad;
                    const y0 = p.y - h / 2 - pad;
                    const x1 = p.x + w / 2 + pad;
                    const y1 = p.y + h / 2 + pad;

                    let collide = false;
                    for (let j = 0; j < drawn.length; j++) {
                        const b = drawn[j];
                        if (!(x1 < b.x0 || x0 > b.x1 || y1 < b.y0 || y0 > b.y1)) {
                            collide = true;
                            break;
                        }
                    }
                    if (collide) continue;
                    drawn.push({ x0, y0, x1, y1 });

                    ctx.lineWidth = 3;
                    ctx.strokeStyle = 'rgba(255,255,255,0.85)';
                    ctx.strokeText(text, p.x, p.y);
                    ctx.fillStyle = 'rgba(17, 24, 39, 0.92)';
                    ctx.fillText(text, p.x, p.y);
                    shown++;
                }
            },
        });

        utmLandLabelsCanvasLayer = new CanvasLabels();
    } catch (e) {}
}

function updatePhase1LabelsVisibility() {
    try {
        if (!map) return;
        const z = Math.round(map.getZoom());
        if (z >= 15) {
            if (!phase1LabelsCanvasLayer) {
                buildPhase1LabelsLayer();
            }
            if (!phase1LabelsCanvasLayer) return;
            if (!map.hasLayer(phase1LabelsCanvasLayer)) {
                phase1LabelsCanvasLayer.addTo(map);
            }
        } else {
            if (phase1LabelsCanvasLayer && map.hasLayer(phase1LabelsCanvasLayer)) {
                phase1LabelsCanvasLayer.remove();
            }
        }
    } catch (e) {}
}

function buildPhase1LabelsLayer() {
    try {
        if (!Array.isArray(phase1LabelsFeatures)) return;
        if (phase1LabelsCanvasLayer) return;

        const feats = phase1LabelsFeatures
            .map((f) => {
                const c = f?.geometry?.coordinates;
                const lon = Number(c?.[0]);
                const lat = Number(c?.[1]);
                if (!Number.isFinite(lat) || !Number.isFinite(lon)) return null;
                const label = String(f?.properties?.text ?? '').trim();
                if (!label) return null;
                return { lat, lon, label };
            })
            .filter(Boolean);

        const CanvasLabels = L.Layer.extend({
            onAdd: function (m) {
                this._map = m;
                this._canvas = L.DomUtil.create('canvas', 'leaflet-phase1-labels');
                this._canvas.style.position = 'absolute';
                this._canvas.style.pointerEvents = 'none';

                const pane = m.getPane('cadPane') || m.getPane('overlayPane');
                pane.appendChild(this._canvas);

                this._pending = false;
                this._redraw = this._redraw.bind(this);
                this._schedule = this._schedule.bind(this);

                m.on('move zoom resize', this._schedule, this);
                this._schedule();
            },
            onRemove: function (m) {
                m.off('move zoom resize', this._schedule, this);
                if (this._canvas && this._canvas.parentNode) {
                    this._canvas.parentNode.removeChild(this._canvas);
                }
                this._canvas = null;
                this._map = null;
            },
            _schedule: function () {
                if (this._pending) return;
                this._pending = true;
                requestAnimationFrame(this._redraw);
            },
            _redraw: function () {
                this._pending = false;
                const m = this._map;
                const c = this._canvas;
                if (!m || !c) return;

                const size = m.getSize();
                if (!size?.x || !size?.y) return;
                const dpr = window.devicePixelRatio || 1;

                c.width = Math.round(size.x * dpr);
                c.height = Math.round(size.y * dpr);
                c.style.width = `${size.x}px`;
                c.style.height = `${size.y}px`;

                const ctx = c.getContext('2d');
                if (!ctx) return;
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                ctx.clearRect(0, 0, size.x, size.y);

                const z = Math.round(m.getZoom());
                if (z < 15) return;

                const bounds = m.getBounds();
                if (!bounds || !bounds.isValid()) return;

                let maxLabels = 600;
                if (z >= 18) maxLabels = 2500;
                else if (z >= 17) maxLabels = 1600;
                else if (z >= 16) maxLabels = 1000;

                let fontSize = 11;
                if (z >= 18) fontSize = 14;
                else if (z >= 17) fontSize = 13;
                else if (z >= 16) fontSize = 12;

                ctx.font = `600 ${fontSize}px system-ui, -apple-system, Segoe UI, Arial`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                const drawn = [];
                const pad = 3;

                let shown = 0;
                for (let i = 0; i < feats.length; i++) {
                    if (shown >= maxLabels) break;
                    const f = feats[i];
                    const ll = L.latLng(f.lat, f.lon);
                    if (!bounds.contains(ll)) continue;
                    const p = m.latLngToContainerPoint(ll);
                    if (!p) continue;

                    const text = f.label;
                    const w = ctx.measureText(text).width;
                    const h = fontSize;
                    const x0 = p.x - w / 2 - pad;
                    const y0 = p.y - h / 2 - pad;
                    const x1 = p.x + w / 2 + pad;
                    const y1 = p.y + h / 2 + pad;

                    let collide = false;
                    for (let j = 0; j < drawn.length; j++) {
                        const b = drawn[j];
                        if (!(x1 < b.x0 || x0 > b.x1 || y1 < b.y0 || y0 > b.y1)) {
                            collide = true;
                            break;
                        }
                    }
                    if (collide) continue;
                    drawn.push({ x0, y0, x1, y1 });

                    ctx.lineWidth = 3;
                    ctx.strokeStyle = 'rgba(255,255,255,0.85)';
                    ctx.strokeText(text, p.x, p.y);
                    ctx.fillStyle = 'rgba(17, 24, 39, 0.92)';
                    ctx.fillText(text, p.x, p.y);
                    shown++;
                }
            },
        });

        phase1LabelsCanvasLayer = new CanvasLabels();
        phase1LabelsBuilt = true;
        updatePhase1LabelsVisibility();
    } catch (e) {
    }
}

function loadPhase1FixedLayers() {
    if (phase1Loaded) return;
    phase1Loaded = true;

    const labelsUrl = new URL('/plans/ajlan/phase1/labels', window.location.origin);
    const boundariesUrl = new URL('/plans/ajlan/phase1/boundaries', window.location.origin);

    fetch(boundariesUrl.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) throw new Error(`HTTP ${r.status} ${r.statusText}`);
            return r.json();
        })
        .then((fc) => {
            const feats = Array.isArray(fc?.features) ? fc.features : [];
            phase1BoundariesLayer = L.geoJSON(fc, {
                pane: 'cadPane',
                style: {
                    color: '#2563EB',
                    weight: 2,
                    opacity: 0.85,
                },
            }).addTo(map);
        })
        .catch((e) => {
            console.warn('Phase1 boundaries failed', boundariesUrl.toString(), e);
        });

    fetch(labelsUrl.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) throw new Error(`HTTP ${r.status} ${r.statusText}`);
            return r.json();
        })
        .then((fc) => {
            phase1LabelsFeatures = Array.isArray(fc?.features) ? fc.features : [];
            updatePhase1LabelsVisibility();
        })
        .catch((e) => {
            console.warn('Phase1 labels failed', labelsUrl.toString(), e);
        });
}

function initCadControls() {
    const saved = localStorage.getItem('ajlanCadCalibration');
    if (saved) {
        try {
            cadCalibration = JSON.parse(saved);
        } catch (e) {
            cadCalibration = null;
        }
    }

    if (!cadCalibration) {
        cadCalibration = { type: 'utm38' };
        localStorage.setItem('ajlanCadCalibration', JSON.stringify(cadCalibration));
    }

    setCadStatus(cadCalibration ? 'المعايرة مفعلة' : 'المعايرة غير مفعلة');

    const applyBtn = document.getElementById('applyCadCalibration');
    const clearBtn = document.getElementById('clearCadCalibration');
    const toggleTexts = document.getElementById('cadToggleTexts');
    const togglePoints = document.getElementById('cadTogglePoints');
    const togglePolylines = document.getElementById('cadTogglePolylines');
    const toggleLines = document.getElementById('cadToggleLines');
    const textSearch = document.getElementById('cadTextSearch');

    if (toggleTexts) toggleTexts.checked = true;
    if (togglePoints) togglePoints.checked = true;
    if (togglePolylines) togglePolylines.checked = true;
    if (toggleLines) toggleLines.checked = true;

    applyBtn?.addEventListener('click', function () {
        const p = readCalibrationInputs();
        if (!p) return setCadStatus('بيانات المعايرة غير مكتملة');
        cadCalibration = buildCalibration(p);
        if (!cadCalibration) return setCadStatus('تعذر بناء المعايرة');
        localStorage.setItem('ajlanCadCalibration', JSON.stringify(cadCalibration));
        setCadStatus('تم تفعيل المعايرة');
        updateCadPlanLocalBbox();
        refreshCadLayers();
    });

    clearBtn?.addEventListener('click', function () {
        cadCalibration = null;
        localStorage.removeItem('ajlanCadCalibration');
        setCadStatus('تم مسح المعايرة');
        clearCadLayers();
        tryAutoCadCalibration();
    });

    toggleTexts?.addEventListener('change', refreshCadLayers);
    togglePoints?.addEventListener('change', refreshCadLayers);
    togglePolylines?.addEventListener('change', refreshCadLayers);
    toggleLines?.addEventListener('change', refreshCadLayers);
    textSearch?.addEventListener('input', debounce(refreshCadLayers, 350));

    map.on('moveend zoomend', debounce(refreshCadLayers, 250));
    map.on('click', function (e) {
        const lat = Number(e?.latlng?.lat);
        const lng = Number(e?.latlng?.lng);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

        const lat1 = document.getElementById('calLat1');
        const lng1 = document.getElementById('calLng1');
        const lat2 = document.getElementById('calLat2');
        const lng2 = document.getElementById('calLng2');

        if (lat1 && lng1 && (!lat1.value || !lng1.value)) {
            lat1.value = String(lat);
            lng1.value = String(lng);
            return;
        }
        if (lat2 && lng2 && (!lat2.value || !lng2.value)) {
            lat2.value = String(lat);
            lng2.value = String(lng);
        }
    });

    if (cadCalibration) {
        if (cadCalibration?.type === 'bbox' && cadCalibration?.localBbox) {
            cadPlanLocalBbox = cadCalibration.localBbox;
        } else {
            updateCadPlanLocalBbox();
        }
    }

    refreshCadLayers();
    loadCadMetadata();
}

function isLikelyUtm38(x, y) {
    if (!Number.isFinite(x) || !Number.isFinite(y)) return false;
    return x >= 400000 && x <= 800000 && y >= 2500000 && y <= 3100000;
}

function cadCoordIsDrawable(x, y) {
    if (!Number.isFinite(x) || !Number.isFinite(y)) return false;
    if (Math.abs(x) < 2 && Math.abs(y) < 2) return false;

    if (cadCalibration?.type === 'utm38') {
        return isLikelyUtm38(x, y);
    }

    return true;
}

function planLatLngBbox() {
    try {
        const ring = window.__ajlan_plan_outer_ring_latlng;
        if (Array.isArray(ring) && ring.length) {
            let minLat = Infinity, minLng = Infinity, maxLat = -Infinity, maxLng = -Infinity;
            ring.forEach((p) => {
                const lat = Number(p?.[0]);
                const lng = Number(p?.[1]);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
                minLat = Math.min(minLat, lat);
                minLng = Math.min(minLng, lng);
                maxLat = Math.max(maxLat, lat);
                maxLng = Math.max(maxLng, lng);
            });
            if ([minLat, minLng, maxLat, maxLng].every(Number.isFinite)) {
                return { minLat, minLng, maxLat, maxLng };
            }
        }
    } catch (e) {}

    try {
        const b = map.getBounds();
        if (b && b.isValid()) {
            const sw = b.getSouthWest();
            const ne = b.getNorthEast();
            return { minLat: sw.lat, minLng: sw.lng, maxLat: ne.lat, maxLng: ne.lng };
        }
    } catch (e) {}

    return {
        minLat: 24.543627000069844,
        minLng: 46.81368100000787,
        maxLat: 24.56727800007028,
        maxLng: 46.84426200000871,
    };
}

function localBboxFromFeatures(fc) {
    const feats = Array.isArray(fc?.features) ? fc.features : [];
    let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
    let n = 0;

    const push = (x, y) => {
        if (!cadCoordIsDrawable(x, y)) return;
        minX = Math.min(minX, x);
        minY = Math.min(minY, y);
        maxX = Math.max(maxX, x);
        maxY = Math.max(maxY, y);
        n++;
    };

    feats.forEach((f) => {
        const g = f?.geometry;
        const t = String(g?.type || '');
        const c = g?.coordinates;
        if (t === 'Point') {
            push(Number(c?.[0]), Number(c?.[1]));
        } else if (t === 'LineString') {
            if (Array.isArray(c)) {
                for (let i = 0; i < c.length; i++) {
                    const p = c[i];
                    push(Number(p?.[0]), Number(p?.[1]));
                }
            }
        }
    });

    if (!Number.isFinite(minX) || !Number.isFinite(minY) || !Number.isFinite(maxX) || !Number.isFinite(maxY) || n < 20) {
        return null;
    }
    return { minX, minY, maxX, maxY };
}

function buildBboxCalibration(localBbox, planBbox) {
    const dx = localBbox.maxX - localBbox.minX;
    const dy = localBbox.maxY - localBbox.minY;
    const dLng = planBbox.maxLng - planBbox.minLng;
    const dLat = planBbox.maxLat - planBbox.minLat;
    if (![dx, dy, dLng, dLat].every(Number.isFinite)) return null;
    if (dx === 0 || dy === 0) return null;

    const scaleX = dLng / dx;
    const scaleY = dLat / dy;
    const tLng = planBbox.minLng - scaleX * localBbox.minX;
    const tLat = planBbox.minLat - scaleY * localBbox.minY;

    if (![scaleX, scaleY, tLng, tLat].every(Number.isFinite)) return null;

    return {
        type: 'bbox',
        scaleX,
        scaleY,
        tLng,
        tLat,
        localBbox,
        planBbox,
    };
}

function tryAutoCadCalibration() {
    const planBbox = planLatLngBbox();

    const url = new URL('/plans/ajlan/cad/file/points', window.location.origin);
    url.searchParams.set('limit', '6000');
    url.searchParams.set('utmOnly', '1');

    setCadStatus('جاري معايرة CAD تلقائياً...');

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => r.json())
        .then((fc) => {
            const feats = Array.isArray(fc?.features) ? fc.features : [];
            const sampleUtm = feats.find((f) => {
                const c = f?.geometry?.coordinates;
                const x = Number(c?.[0]);
                const y = Number(c?.[1]);
                return isLikelyUtm38(x, y);
            });

            if (sampleUtm) {
                cadCalibration = { type: 'utm38' };
                localStorage.setItem('ajlanCadCalibration', JSON.stringify(cadCalibration));
                setCadStatus('تم تفعيل تحويل UTM 38N تلقائياً');
                try {
                    const sw = cadLatLngToLocal(planBbox.minLat, planBbox.minLng);
                    const ne = cadLatLngToLocal(planBbox.maxLat, planBbox.maxLng);
                    if (sw && ne) {
                        cadPlanLocalBbox = {
                            minX: Math.min(sw[0], ne[0]),
                            minY: Math.min(sw[1], ne[1]),
                            maxX: Math.max(sw[0], ne[0]),
                            maxY: Math.max(sw[1], ne[1]),
                        };
                    } else {
                        cadPlanLocalBbox = null;
                    }
                } catch (e) {
                    cadPlanLocalBbox = null;
                }
                refreshCadLayers();
                return;
            }

            const localBbox = localBboxFromFeatures(fc);
            if (!localBbox) {
                setCadStatus('تعذر بناء معايرة تلقائية (نقاط غير كافية)');
                return;
            }

            const cal = buildBboxCalibration(localBbox, planBbox);
            if (!cal) {
                setCadStatus('تعذر بناء معايرة تلقائية (BBox غير صالح)');
                return;
            }

            cadCalibration = cal;
            cadPlanLocalBbox = localBbox;
            localStorage.setItem('ajlanCadCalibration', JSON.stringify(cadCalibration));
            setCadStatus('تم تفعيل معايرة تلقائية (BBox)');
            refreshCadLayers();
        })
        .catch(() => {
            setCadStatus('فشل تحميل بيانات CAD للمعايرة');
        });
}

function loadCadMetadata() {
    const box = document.getElementById('cadMetaBox');
    const list = document.getElementById('cadMetaList');
    if (!box || !list) return;

    fetch('/plans/ajlan/cad/file/inserts?limit=120', { cache: 'no-store' })
        .then(r => r.json())
        .then((fc) => {
            const feats = Array.isArray(fc?.features) ? fc.features : [];
            const names = feats
                .map(f => String(f?.properties?.name || '').trim())
                .filter(Boolean)
                .slice(0, 40);
            if (!names.length) return;
            box.style.display = '';
            list.innerHTML = names.map(n => `<div>${escapeHtml(n)}</div>`).join('');
        })
        .catch(() => {});
}

function setCadStatus(msg) {
    const el = document.getElementById('cadCalStatus');
    if (el) el.textContent = msg;
}

function readCalibrationInputs() {
    const lat1 = Number(document.getElementById('calLat1')?.value);
    const lng1 = Number(document.getElementById('calLng1')?.value);
    const x1 = Number(document.getElementById('calX1')?.value);
    const y1 = Number(document.getElementById('calY1')?.value);
    const lat2 = Number(document.getElementById('calLat2')?.value);
    const lng2 = Number(document.getElementById('calLng2')?.value);
    const x2 = Number(document.getElementById('calX2')?.value);
    const y2 = Number(document.getElementById('calY2')?.value);
    const nums = [lat1, lng1, x1, y1, lat2, lng2, x2, y2];
    if (nums.some((n) => !Number.isFinite(n))) return null;
    return { lat1, lng1, x1, y1, lat2, lng2, x2, y2 };
}

function buildCalibration(p) {
    const dx = p.x2 - p.x1;
    const dy = p.y2 - p.y1;
    const dLon = p.lng2 - p.lng1;
    const dLat = p.lat2 - p.lat1;
    const denom = dx * dx + dy * dy;
    if (!Number.isFinite(denom) || denom === 0) return null;

    const a = (dLon * dx + dLat * dy) / denom;
    const b = (dLat * dx - dLon * dy) / denom;
    const tLon = p.lng1 - (a * p.x1 - b * p.y1);
    const tLat = p.lat1 - (b * p.x1 + a * p.y1);
    const invDen = a * a + b * b;
    if (!Number.isFinite(invDen) || invDen === 0) return null;

    return { a, b, tLon, tLat, invDen };
}

function cadLocalToLatLng(x, y) {
    if (!cadCalibration) return null;
    if (cadCalibration?.type === 'utm38') {
        try {
            if (!isLikelyUtm38(x, y)) return null;
            const utm38 = '+proj=utm +zone=38 +datum=WGS84 +units=m +no_defs';
            const out = proj4(utm38, 'WGS84', [x, y]);
            const lon = Number(out?.[0]);
            const lat = Number(out?.[1]);
            if (!Number.isFinite(lat) || !Number.isFinite(lon)) return null;
            return [lat, lon];
        } catch (e) {
            return null;
        }
    }

    if (cadCalibration?.type === 'bbox') {
        const lon = cadCalibration.tLng + cadCalibration.scaleX * x;
        const lat = cadCalibration.tLat + cadCalibration.scaleY * y;
        if (!Number.isFinite(lat) || !Number.isFinite(lon)) return null;
        return [lat, lon];
    }

    const lon = cadCalibration.tLon + cadCalibration.a * x - cadCalibration.b * y;
    const lat = cadCalibration.tLat + cadCalibration.b * x + cadCalibration.a * y;
    if (!Number.isFinite(lat) || !Number.isFinite(lon)) return null;
    return [lat, lon];
}

function cadLatLngToLocal(lat, lon) {
    if (!cadCalibration) return null;
    if (cadCalibration?.type === 'utm38') {
        try {
            const utm38 = '+proj=utm +zone=38 +datum=WGS84 +units=m +no_defs';
            const out = proj4('WGS84', utm38, [lon, lat]);
            const x = Number(out?.[0]);
            const y = Number(out?.[1]);
            if (!Number.isFinite(x) || !Number.isFinite(y)) return null;
            return [x, y];
        } catch (e) {
            return null;
        }
    }

    if (cadCalibration?.type === 'bbox') {
        const x = (lon - cadCalibration.tLng) / cadCalibration.scaleX;
        const y = (lat - cadCalibration.tLat) / cadCalibration.scaleY;
        if (!Number.isFinite(x) || !Number.isFinite(y)) return null;
        return [x, y];
    }

    const dLon = lon - cadCalibration.tLon;
    const dLat = lat - cadCalibration.tLat;
    const x = (cadCalibration.a * dLon + cadCalibration.b * dLat) / cadCalibration.invDen;
    const y = (-cadCalibration.b * dLon + cadCalibration.a * dLat) / cadCalibration.invDen;
    if (!Number.isFinite(x) || !Number.isFinite(y)) return null;
    return [x, y];
}

function updateCadPlanLocalBbox() {
    try {
        if (!cadCalibration) return;
        const ring = window.__ajlan_plan_outer_ring_latlng;
        if (!Array.isArray(ring) || !ring.length) return;
        const pts = ring
            .map((p) => Array.isArray(p) ? cadLatLngToLocal(Number(p[0]), Number(p[1])) : null)
            .filter(Boolean);
        if (!pts.length) return;
        const xs = pts.map(p => p[0]);
        const ys = pts.map(p => p[1]);
        const minX = Math.min(...xs);
        const maxX = Math.max(...xs);
        const minY = Math.min(...ys);
        const maxY = Math.max(...ys);
        if (![minX, minY, maxX, maxY].every(Number.isFinite)) return;
        cadPlanLocalBbox = { minX, minY, maxX, maxY };
    } catch (e) {}
}

function intersectBbox(a, b) {
    if (!a || !b) return a || b || null;
    const minX = Math.max(a.minX, b.minX);
    const minY = Math.max(a.minY, b.minY);
    const maxX = Math.min(a.maxX, b.maxX);
    const maxY = Math.min(a.maxY, b.maxY);
    if (![minX, minY, maxX, maxY].every(Number.isFinite)) return null;
    if (minX > maxX || minY > maxY) return null;
    return { minX, minY, maxX, maxY };
}

function cadLocalBboxFromMapBounds() {
    try {
        const b = map.getBounds();
        if (!b || !b.isValid()) return null;
        const sw = b.getSouthWest();
        const ne = b.getNorthEast();
        const nw = L.latLng(ne.lat, sw.lng);
        const se = L.latLng(sw.lat, ne.lng);

        const pts = [sw, ne, nw, se]
            .map((p) => cadLatLngToLocal(Number(p.lat), Number(p.lng)))
            .filter(Boolean);
        if (pts.length < 4) return null;

        const xs = pts.map(p => p[0]);
        const ys = pts.map(p => p[1]);
        const minX = Math.min(...xs);
        const maxX = Math.max(...xs);
        const minY = Math.min(...ys);
        const maxY = Math.max(...ys);
        if (![minX, minY, maxX, maxY].every(Number.isFinite)) return null;
        return { minX, minY, maxX, maxY };
    } catch (e) {
        return null;
    }
}

function clearCadLayers() {
    if (cadTextLayer) {
        cadTextLayer.remove();
        cadTextLayer = null;
    }
    if (cadPointLayer) {
        cadPointLayer.remove();
        cadPointLayer = null;
    }
    if (cadPolylineLayer) {
        cadPolylineLayer.remove();
        cadPolylineLayer = null;
    }
    if (cadLineLayer) {
        cadLineLayer.remove();
        cadLineLayer = null;
    }
}

function refreshCadLayers() {
    if (!cadCalibration) return;

    const showTexts = !!document.getElementById('cadToggleTexts')?.checked;
    const showPoints = !!document.getElementById('cadTogglePoints')?.checked;
    const showPolylines = !!document.getElementById('cadTogglePolylines')?.checked;
    const showLines = !!document.getElementById('cadToggleLines')?.checked;
    const q = String(document.getElementById('cadTextSearch')?.value || '').trim();

    const zoomRaw = map.getZoom();
    const zoom = Math.round(zoomRaw);
    if (zoom < 13) return;

    let bbox = null;
    let bboxSource = 'map';
    if (cadPlanLocalBbox) {
        bbox = cadPlanLocalBbox;
        bboxSource = 'plan';
    } else {
        bbox = cadLocalBboxFromMapBounds();
        if (!bbox) {
            console.log('CAD: bbox is null', { zoom, zoomRaw, cadCalibration });
            return;
        }
    }

    const bboxSig = bbox
        ? `${bboxSource}:${Number(bbox.minX).toFixed(1)},${Number(bbox.minY).toFixed(1)},${Number(bbox.maxX).toFixed(1)},${Number(bbox.maxY).toFixed(1)}`
        : `${bboxSource}:null`;

    const sig = JSON.stringify({
        zoom,
        showTexts,
        showPoints,
        showPolylines,
        showLines,
        q,
        calType: cadCalibration?.type || null,
        bboxSig,
    });
    if (sig === cadLastRefreshSig) {
        return;
    }
    cadLastRefreshSig = sig;

    if (bboxSource === 'plan') {
        console.log('CAD: using plan bbox', bbox);
    } else {
        console.log('CAD: using map bbox', bbox);
    }
    console.log('CAD: loading', { zoom, showTexts, showPoints, showPolylines, showLines, q: q ? q : null });

    if (showTexts && zoom >= 13) loadCadTexts(q, bbox);
    if (showPoints && zoom >= 13) loadCadPoints(bbox);
    if (showPolylines && zoom >= 13) loadCadPolylines(bbox);
    if (showLines && zoom >= 14) loadCadLines(bbox);
}

function loadCadTexts(q, bbox) {
    const reqId = ++cadTextReqId;
    if (cadTextLayer) {
        cadTextLayer.remove();
        cadTextLayer = null;
    }
    const url = new URL('/plans/ajlan/cad/dxf/' + encodeURIComponent(cadDxfFileName), window.location.origin);
    url.searchParams.set('kind', 'texts');
    url.searchParams.set('minX', String(bbox.minX));
    url.searchParams.set('minY', String(bbox.minY));
    url.searchParams.set('maxX', String(bbox.maxX));
    url.searchParams.set('maxY', String(bbox.maxY));
    const z = map.getZoom();
    url.searchParams.set('limit', z >= 18 ? '6000' : (z >= 17 ? '3500' : (z >= 15 ? '2000' : '900')));
    if (q) url.searchParams.set('q', q);
    

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) {
                throw new Error(`HTTP ${r.status} ${r.statusText}`);
            }
            return r.json();
        })
        .then((fc) => {
            if (reqId !== cadTextReqId) return;
            const feats = Array.isArray(fc?.features) ? fc.features : [];
            console.log('CAD: texts feats', feats.length);

            cadTextLayer = L.layerGroup([], { pane: 'cadPane' });
            feats.forEach((f) => {
                const c = f?.geometry?.coordinates;
                const x = Number(c?.[0]);
                const y = Number(c?.[1]);
                if (!cadCoordIsDrawable(x, y)) return;
                const latlng = cadLocalToLatLng(x, y);
                const label = String(f?.properties?.text ?? '').trim();
                if (!latlng || !label) return;

                const m = L.marker(latlng, { opacity: 0, interactive: false, pane: 'cadPane' });
                m.bindTooltip(label, {
                    permanent: true,
                    direction: 'center',
                    className: 'cad-label',
                    opacity: 0.95,
                });
                cadTextLayer.addLayer(m);
            });
            cadTextLayer.addTo(map);
        })
        .catch((e) => {
            if (reqId !== cadTextReqId) return;
            console.warn('CAD: texts fetch failed', url.toString(), e);
        });
}

function loadCadPoints(bbox) {
    const reqId = ++cadPointReqId;
    if (cadPointLayer) {
        cadPointLayer.remove();
        cadPointLayer = null;
    }
    const url = new URL('/plans/ajlan/cad/file/points', window.location.origin);
    url.searchParams.set('minX', String(bbox.minX));
    url.searchParams.set('minY', String(bbox.minY));
    url.searchParams.set('maxX', String(bbox.maxX));
    url.searchParams.set('maxY', String(bbox.maxY));
    url.searchParams.set('limit', map.getZoom() >= 18 ? '3000' : '1600');
    if (cadCalibration?.type === 'utm38') url.searchParams.set('utmOnly', '1');

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) {
                throw new Error(`HTTP ${r.status} ${r.statusText}`);
            }
            return r.json();
        })
        .then((fc) => {
            if (reqId !== cadPointReqId) return;
            const feats = Array.isArray(fc?.features) ? fc.features : [];

            cadPointLayer = L.layerGroup([], { pane: 'cadPane' });
            feats.forEach((f) => {
                const c = f?.geometry?.coordinates;
                const x = Number(c?.[0]);
                const y = Number(c?.[1]);
                if (!cadCoordIsDrawable(x, y)) return;
                const latlng = cadLocalToLatLng(x, y);
                if (!latlng) return;

                const layerName = String(f?.properties?.layer || '');
                const m = L.circleMarker(latlng, {
                    pane: 'cadPane',
                    radius: 3,
                    color: '#2563EB',
                    weight: 1,
                    fillColor: '#60A5FA',
                    fillOpacity: 0.85,
                });
                if (layerName) {
                    m.bindPopup(`<div style="font-size:12px"><div><strong>Layer</strong>: ${escapeHtml(layerName)}</div></div>`);
                }
                cadPointLayer.addLayer(m);
            });
            cadPointLayer.addTo(map);
        })
        .catch((e) => {
            if (reqId !== cadPointReqId) return;
            console.warn('CAD: points fetch failed', url.toString(), e);
        });
}

function cadStrokeForLayer(layerName) {
    const n = String(layerName || '').toLowerCase();
    if (n.includes('road') || n.includes('street') || n.includes('st')) return { color: '#F97316', weight: 3, opacity: 0.95 };
    if (n.includes('bound') || n.includes('parcel') || n.includes('lot')) return { color: '#22C55E', weight: 2, opacity: 0.85 };
    if (n.includes('water') || n.includes('drain')) return { color: '#38BDF8', weight: 2, opacity: 0.9 };
    if (n.includes('elec') || n.includes('power')) return { color: '#A855F7', weight: 2, opacity: 0.9 };
    return { color: '#111A3A', weight: 2, opacity: 0.8 };
}

function loadCadPolylines(bbox) {
    const reqId = ++cadPolylineReqId;
    if (cadPolylineLayer) {
        cadPolylineLayer.remove();
        cadPolylineLayer = null;
    }
    const url = new URL('/plans/ajlan/cad/dxf/' + encodeURIComponent(cadDxfFileName), window.location.origin);
    url.searchParams.set('kind', 'polylines');
    url.searchParams.set('minX', String(bbox.minX));
    url.searchParams.set('minY', String(bbox.minY));
    url.searchParams.set('maxX', String(bbox.maxX));
    url.searchParams.set('maxY', String(bbox.maxY));
    const z = map.getZoom();
    url.searchParams.set('limit', z >= 18 ? '12000' : (z >= 17 ? '8000' : (z >= 15 ? '4500' : '1800')));
    

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) {
                throw new Error(`HTTP ${r.status} ${r.statusText}`);
            }
            return r.json();
        })
        .then((fc) => {
            if (reqId !== cadPolylineReqId) return;
            const feats = Array.isArray(fc?.features) ? fc.features : [];
            console.log('CAD: polylines feats', feats.length);
            cadPolylineLayer = L.featureGroup([], { pane: 'cadPane' }).addTo(map);

            feats.forEach((f) => {
                const coords = f?.geometry?.coordinates;
                if (!Array.isArray(coords)) return;
                const layerName = String(f?.properties?.layer || '');
                const style = cadStrokeForLayer(layerName);

                const latlngs = coords
                    .map((c) => {
                        const x = Number(c?.[0]);
                        const y = Number(c?.[1]);
                        if (!cadCoordIsDrawable(x, y)) return null;
                        const ll = cadLocalToLatLng(x, y);
                        return ll ? ll : null;
                    })
                    .filter(Boolean);

                if (latlngs.length < 2) return;
                const line = L.polyline(latlngs, { ...style, pane: 'cadPane' });
                if (layerName) {
                    line.bindPopup(`<div style="font-size:12px"><div><strong>Layer</strong>: ${escapeHtml(layerName)}</div></div>`);
                }
                cadPolylineLayer.addLayer(line);
            });
        })
        .catch((e) => {
            if (reqId !== cadPolylineReqId) return;
            console.warn('CAD: polylines fetch failed', url.toString(), e);
        });
}

function loadCadLines(bbox) {
    const reqId = ++cadLineReqId;
    if (cadLineLayer) {
        cadLineLayer.remove();
        cadLineLayer = null;
    }
    const url = new URL('/plans/ajlan/cad/dxf/' + encodeURIComponent(cadDxfFileName), window.location.origin);
    url.searchParams.set('kind', 'lines');
    url.searchParams.set('minX', String(bbox.minX));
    url.searchParams.set('minY', String(bbox.minY));
    url.searchParams.set('maxX', String(bbox.maxX));
    url.searchParams.set('maxY', String(bbox.maxY));
    const z = map.getZoom();
    url.searchParams.set('limit', z >= 18 ? '12000' : (z >= 17 ? '8000' : (z >= 15 ? '4500' : '1800')));
    

    fetch(url.toString(), { cache: 'no-store' })
        .then((r) => {
            if (!r.ok) {
                throw new Error(`HTTP ${r.status} ${r.statusText}`);
            }
            return r.json();
        })
        .then((fc) => {
            if (reqId !== cadLineReqId) return;
            const feats = Array.isArray(fc?.features) ? fc.features : [];
            console.log('CAD: lines feats', feats.length);
            cadLineLayer = L.featureGroup([], { pane: 'cadPane' }).addTo(map);

            feats.forEach((f) => {
                const coords = f?.geometry?.coordinates;
                if (!Array.isArray(coords)) return;
                const layerName = String(f?.properties?.layer || '');
                const style = cadStrokeForLayer(layerName);

                const latlngs = coords
                    .map((c) => {
                        const x = Number(c?.[0]);
                        const y = Number(c?.[1]);
                        if (!cadCoordIsDrawable(x, y)) return null;
                        const ll = cadLocalToLatLng(x, y);
                        return ll ? ll : null;
                    })
                    .filter(Boolean);

                if (latlngs.length < 2) return;
                const line = L.polyline(latlngs, { ...style, pane: 'cadPane', weight: Math.max(1, style.weight - 1), opacity: Math.min(0.95, style.opacity) });
                if (layerName) {
                    line.bindPopup(`<div style="font-size:12px"><div><strong>Layer</strong>: ${escapeHtml(layerName)}</div></div>`);
                }
                cadLineLayer.addLayer(line);
            });
        })
        .catch((e) => {
            if (reqId !== cadLineReqId) return;
            console.warn('CAD: lines fetch failed', url.toString(), e);
        });
}

function debounce(fn, wait) {
    let t;
    return function (...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), wait);
    };
}

function loadOsmRoads(bounds) {
    try {
        if (!bounds || !bounds.isValid()) return;

        if (roadsLayer) {
            roadsLayer.remove();
            roadsLayer = null;
        }

        const sw = bounds.getSouthWest();
        const ne = bounds.getNorthEast();
        const south = sw.lat;
        const west = sw.lng;
        const north = ne.lat;
        const east = ne.lng;

        fetch('/plans/ajlan/osm-roads', { cache: 'no-store' })
            .then((r) => r.json())
            .then((data) => {
                if (data && data.ok === false) return;
                const ways = Array.isArray(data?.elements) ? data.elements.filter(e => e.type === 'way' && Array.isArray(e.geometry)) : [];
                roadsLayer = L.featureGroup([], { pane: 'roadsPane' }).addTo(map);

                ways.forEach((w) => {
                    const latlngs = w.geometry
                        .map((p) => [Number(p.lat), Number(p.lon)])
                        .filter((p) => Number.isFinite(p[0]) && Number.isFinite(p[1]));
                    if (latlngs.length < 2) return;
                    L.polyline(latlngs, {
                        color: '#EF4444',
                        weight: 3,
                        opacity: 0.9,
                    }).addTo(roadsLayer);
                });
            })
            .catch(() => {});
    } catch (e) {}
}

function reprojectGeoJsonUtmToWgs84(geojson) {
    const source = '+proj=utm +zone=38 +datum=WGS84 +units=m +no_defs';
    const dest = 'EPSG:4326';

    function mapCoords(coords, depth) {
        if (depth === 0) {
            const x = Number(coords[0]);
            const y = Number(coords[1]);
            if (!Number.isFinite(x) || !Number.isFinite(y)) return coords;
            const out = proj4(source, dest, [x, y]);
            return [out[0], out[1]];
        }
        return coords.map((c) => mapCoords(c, depth - 1));
    }

    function depthForType(type) {
        if (type === 'Point') return 0;
        if (type === 'MultiPoint' || type === 'LineString') return 1;
        if (type === 'MultiLineString' || type === 'Polygon') return 2;
        if (type === 'MultiPolygon') return 3;
        return null;
    }

    const out = JSON.parse(JSON.stringify(geojson));

    if (out.type === 'FeatureCollection' && Array.isArray(out.features)) {
        out.features = out.features.map((f) => {
            const g = f?.geometry;
            const d = g?.type ? depthForType(g.type) : null;
            if (d === null || !Array.isArray(g?.coordinates)) return f;
            f.geometry.coordinates = mapCoords(g.coordinates, d);
            return f;
        });
    }

    return out;
}

function geoJsonLooksLikeUtm(geojson) {
    function firstCoord(coords, depth) {
        if (!Array.isArray(coords)) return null;
        if (depth === 0) return coords;
        for (const c of coords) {
            const out = firstCoord(c, depth - 1);
            if (out) return out;
        }
        return null;
    }

    function depthForType(type) {
        if (type === 'Point') return 0;
        if (type === 'MultiPoint' || type === 'LineString') return 1;
        if (type === 'MultiLineString' || type === 'Polygon') return 2;
        if (type === 'MultiPolygon') return 3;
        return null;
    }

    const f = geojson?.type === 'FeatureCollection' ? geojson.features?.[0] : geojson;
    const g = f?.type === 'Feature' ? f.geometry : f?.geometry;
    const d = g?.type ? depthForType(g.type) : null;
    const c = d === null ? null : firstCoord(g?.coordinates, d);
    if (!c || c.length < 2) return false;

    const x = Number(c[0]);
    const y = Number(c[1]);
    if (!Number.isFinite(x) || !Number.isFinite(y)) return false;

    return Math.abs(x) > 180 || Math.abs(y) > 90;
}

function getOuterPolygonCornerLatLngs(featureCollection) {
    if (featureCollection?.type !== 'FeatureCollection' || !Array.isArray(featureCollection.features)) {
        return null;
    }

    function polygonArea(ring) {
        if (!Array.isArray(ring) || ring.length < 3) return 0;
        let sum = 0;
        for (let i = 0; i < ring.length - 1; i++) {
            const x1 = Number(ring[i][0]);
            const y1 = Number(ring[i][1]);
            const x2 = Number(ring[i + 1][0]);
            const y2 = Number(ring[i + 1][1]);
            if (!Number.isFinite(x1) || !Number.isFinite(y1) || !Number.isFinite(x2) || !Number.isFinite(y2)) continue;
            sum += (x1 * y2 - x2 * y1);
        }
        return Math.abs(sum) / 2;
    }

    let bestRing = null;
    let bestArea = 0;

    featureCollection.features.forEach((f) => {
        const g = f?.geometry;
        if (!g || !g.type || !Array.isArray(g.coordinates)) return;

        if (g.type === 'Polygon') {
            const ring = g.coordinates?.[0];
            const a = polygonArea(ring);
            if (a > bestArea) {
                bestArea = a;
                bestRing = ring;
            }
        }

        if (g.type === 'MultiPolygon') {
            (g.coordinates || []).forEach((poly) => {
                const ring = poly?.[0];
                const a = polygonArea(ring);
                if (a > bestArea) {
                    bestArea = a;
                    bestRing = ring;
                }
            });
        }
    });

    if (!bestRing || bestRing.length < 3) return null;

    let nw = null;
    let ne = null;
    let sw = null;

    bestRing.forEach((pt) => {
        const lng = Number(pt?.[0]);
        const lat = Number(pt?.[1]);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

        if (!nw || lat > nw.lat || (lat === nw.lat && lng < nw.lng)) nw = { lat, lng };
        if (!ne || lat > ne.lat || (lat === ne.lat && lng > ne.lng)) ne = { lat, lng };
        if (!sw || lat < sw.lat || (lat === sw.lat && lng < sw.lng)) sw = { lat, lng };
    });

    if (!nw || !ne || !sw) return null;
    return {
        topleft: L.latLng(nw.lat, nw.lng),
        topright: L.latLng(ne.lat, ne.lng),
        bottomleft: L.latLng(sw.lat, sw.lng),
    };
}

function getOuterPolygonRingLatLngs(featureCollection) {
    if (featureCollection?.type !== 'FeatureCollection' || !Array.isArray(featureCollection.features)) {
        return null;
    }

    function polygonArea(ring) {
        if (!Array.isArray(ring) || ring.length < 3) return 0;
        let sum = 0;
        for (let i = 0; i < ring.length - 1; i++) {
            const x1 = Number(ring[i][0]);
            const y1 = Number(ring[i][1]);
            const x2 = Number(ring[i + 1][0]);
            const y2 = Number(ring[i + 1][1]);
            if (!Number.isFinite(x1) || !Number.isFinite(y1) || !Number.isFinite(x2) || !Number.isFinite(y2)) continue;
            sum += (x1 * y2 - x2 * y1);
        }
        return Math.abs(sum) / 2;
    }

    let bestRing = null;
    let bestArea = 0;

    featureCollection.features.forEach((f) => {
        const g = f?.geometry;
        if (!g || !g.type || !Array.isArray(g.coordinates)) return;

        if (g.type === 'Polygon') {
            const ring = g.coordinates?.[0];
            const a = polygonArea(ring);
            if (a > bestArea) {
                bestArea = a;
                bestRing = ring;
            }
        }

        if (g.type === 'MultiPolygon') {
            (g.coordinates || []).forEach((poly) => {
                const ring = poly?.[0];
                const a = polygonArea(ring);
                if (a > bestArea) {
                    bestArea = a;
                    bestRing = ring;
                }
            });
        }
    });

    if (!bestRing || bestRing.length < 3) return null;
    const latLngs = [];
    bestRing.forEach((pt) => {
        const lng = Number(pt?.[0]);
        const lat = Number(pt?.[1]);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
        latLngs.push(L.latLng(lat, lng));
    });
    return latLngs.length >= 3 ? latLngs : null;
}

function setupOverlayClipToRing(overlay, ringLatLngs) {
    if (!overlay || !Array.isArray(ringLatLngs) || ringLatLngs.length < 3 || !map) return;

    if (typeof planOverlayClipCleanup === 'function') {
        try { planOverlayClipCleanup(); } catch (e) {}
        planOverlayClipCleanup = null;
    }

    const applyClip = () => {
        const el = overlay?.getElement?.();
        if (!el) return;

        const elPos = L.DomUtil.getPosition(el);
        if (!elPos) return;

        const pts = ringLatLngs.map((ll) => {
            const p = map.latLngToLayerPoint(ll);
            const x = p.x - elPos.x;
            const y = p.y - elPos.y;
            return `${x.toFixed(2)}px ${y.toFixed(2)}px`;
        });

        const poly = `polygon(${pts.join(',')})`;
        el.style.clipPath = poly;
        el.style.webkitClipPath = poly;
    };

    const handler = () => applyClip();
    map.on('zoomend moveend', handler);
    applyClip();

    planOverlayClipCleanup = () => {
        map.off('zoomend moveend', handler);
        const el = overlay?.getElement?.();
        if (el) {
            el.style.clipPath = '';
            el.style.webkitClipPath = '';
        }
    };
}

function renderLots() {
    if (lotsLayer) {
        lotsLayer.remove();
    }
    if (lotsPointsLayer) {
        lotsPointsLayer.remove();
    }
    if (lotsLabelsLayer) {
        lotsLabelsLayer.remove();
    }
    lotsIndex = {};
    lotsPointsIndex = {};

    const q = String(document.getElementById('lotsSearch')?.value || '').trim().toLowerCase();
    const status = String(document.getElementById('lotsStatus')?.value || '').trim();

    const filtered = lotsSource.filter((f) => {
        const p = f?.properties || {};
        const id = String(p.lot_number || '').toLowerCase();
        const matchesId = !q || id.includes(q);
        const matchesStatus = !status || p.status === status;
        return matchesId && matchesStatus;
    });

    const filteredPoints = lotsPointsSource.filter((p) => {
        const id = String(p.lot_number || '').toLowerCase();
        const matchesId = !q || id.includes(q);
        const matchesStatus = !status || p.status === status;
        return matchesId && matchesStatus;
    });

    lotsLayer = L.geoJSON(filtered, {
        pane: 'lotsPane',
        style: lotStyle,
        onEachFeature: function (feature, layer) {
            const p = feature.properties || {};
            lotsIndex[String(p.lot_number)] = layer;

            layer.on('click', function () {
                selectLot(p.lot_number, true);
            });

            layer.on('mouseover', function () {
                this.setStyle({ fillOpacity: 0.55 });
            });

            layer.on('mouseout', function () {
                if (selectedLot && String(selectedLot.lot_number) === String(p.lot_number)) {
                    return;
                }
                this.setStyle({ fillOpacity: 0.35 });
            });
        }
    }).addTo(map);

    lotsPointsLayer = L.featureGroup([], { pane: 'lotsPane' }).addTo(map);

    lotsLabelsLayer = L.layerGroup([], { pane: 'lotsPane' }).addTo(map);

    const zoom = map.getZoom();
    const showLabels = zoom >= 15;

    if (showLabels) {
        lotsLayer.eachLayer((layer) => {
            try {
                const p = layer?.feature?.properties || {};
                const lotNumber = String(p.lot_number || '').trim();
                if (!lotNumber) return;
                const center = layer.getBounds?.().getCenter?.();
                if (!center) return;
                const m = L.marker(center, { opacity: 0, interactive: false });
                m.bindTooltip(lotNumber, {
                    permanent: true,
                    direction: 'center',
                    className: 'cad-label',
                    opacity: 0.95,
                });
                lotsLabelsLayer.addLayer(m);
            } catch (e) {}
        });
    }

    filteredPoints.forEach((p) => {
        const lat = Number(p.lat);
        const lng = Number(p.lng);

        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

        const circle = L.circle([lat, lng], {
            pane: 'lotsPane',
            radius: pointLotRadiusMeters,
            color: '#B8892F',
            fillColor: '#B8892F',
            fillOpacity: 0.45,
            weight: 2,
        }).addTo(lotsPointsLayer);

        lotsPointsIndex[String(p.lot_number)] = circle;

        circle.on('click', function () {
            selectLot(p.lot_number, true);
        });

        if (showLabels) {
            const lotNumber = String(p.lot_number || '').trim();
            if (lotNumber) {
                const m = L.marker([lat, lng], { opacity: 0, interactive: false });
                m.bindTooltip(lotNumber, {
                    permanent: true,
                    direction: 'center',
                    className: 'cad-label',
                    opacity: 0.95,
                });
                lotsLabelsLayer.addLayer(m);
            }
        }
    });

    document.getElementById('lotsCount').textContent = String(filtered.length + filteredPoints.length);
    renderLotsList(filtered, filteredPoints);

    if (!selectedLot && (filtered.length > 0 || filteredPoints.length > 0)) {
        fitToLots();
    }
}

function renderLotsList(features, points) {
    const container = document.getElementById('lotsList');
    if (!container) return;

    container.innerHTML = '';

    features.forEach((f) => {
        const p = f.properties || {};
        const lotId = p.db_id || p.id || '';
        const st = String(p.status || '');
        const isActive = selectedLot && String(selectedLot.lot_number) === String(p.lot_number);

        const pill = st === 'available'
            ? 'bg-green-50 text-green-700 border-green-200'
            : (st === 'reserved' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200');

        const itemWrap = document.createElement('div');
        itemWrap.className = `w-full text-right mb-3 p-3 rounded-lg border transition-colors ${isActive ? 'border-amber-300 bg-amber-50 ring-2 ring-amber-500' : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50'}`;
        itemWrap.setAttribute('data-lot', String(p.lot_number));
        itemWrap.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <button type="button" class="text-right" style="flex:1;" data-action="select">
                    <div class="font-semibold text-gray-900">قطعة ${escapeHtml(p.lot_number)}</div>
                    <div class="text-xs text-gray-600 mt-1">${escapeHtml(p.usage || '')} • ${escapeHtml(p.area || '')} م²</div>
                    <div class="text-xs text-gray-500 mt-1">${escapeHtml(formatPrice(p.price) || '')}</div>
                </button>
                <div class="shrink-0 flex flex-col gap-2 items-end">
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded-full border ${pill}">${escapeHtml(statusText(p.status))}</span>
                    ${lotId ? `<a class="chip" style="height:2.1rem; display:inline-flex; align-items:center; gap:.35rem;" href="/plans/ajlan/lots/${encodeURIComponent(lotId)}" target="_blank" rel="noopener"><i data-lucide="file-text" style="width:16px; height:16px;"></i><span>تفاصيل</span></a>` : ``}
                </div>
            </div>
        `;

        itemWrap.querySelector('[data-action="select"]')?.addEventListener('click', function () {
            selectLot(p.lot_number, false);
        });

        container.appendChild(itemWrap);
    });

    (points || []).forEach((p) => {
        const st = String(p?.status || 'available');
        const isActive = selectedLot && String(selectedLot.lot_number) === String(p.lot_number);

        const pill = st === 'available'
            ? 'bg-green-50 text-green-700 border-green-200'
            : (st === 'reserved' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200');

        const item = document.createElement('button');
        item.type = 'button';
        item.className = `lot-item w-full text-right mb-3 p-3 rounded-lg border transition-colors ${isActive ? 'border-blue-300 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50'}`;
        item.setAttribute('data-lot', String(p.lot_number));
        item.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="font-semibold text-gray-900">قطعة ${escapeHtml(p.lot_number)}</div>
                    <div class="text-xs text-gray-600 mt-1">نقطة موقع</div>
                </div>
                <div class="shrink-0">
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded-full border ${pill}">${escapeHtml(statusText(st))}</span>
                </div>
            </div>
        `;

        item.addEventListener('click', function () {
            selectLot(p.lot_number, false);
        });

        container.appendChild(item);
    });
}

function selectLot(lotNumber, fromMap) {
    const key = String(lotNumber);
    const feature = lotsSource.find(f => String(f?.properties?.lot_number) === key);

    if (feature) {
        selectedLot = feature.properties;

        if (lotsLayer) {
            lotsLayer.setStyle(lotStyle);
        }

        const layer = lotsIndex[key];
        if (layer) {
            const p = feature.properties || {};
            const popup = `
                <div class="p-2" style="min-width: 260px">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-semibold text-gray-900">قطعة رقم ${escapeHtml(p.lot_number)}</div>
                            <div class="text-xs text-gray-500 mt-1">${escapeHtml(p.usage || '')} • ${escapeHtml(p.area || '')} م²</div>
                        </div>
                        <span class="text-[11px] px-2 py-1 rounded-full border">${escapeHtml(statusText(p.status))}</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                        <div class="text-gray-500">السعر</div>
                        <div class="font-semibold text-gray-900 text-left">${escapeHtml(formatPrice(p.price) || '')}</div>
                    </div>
                    <a href="${escapeHtml(buildWhatsAppUrl(p))}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center justify-center w-full bg-green-600 !text-white px-3 py-2 rounded-md text-sm hover:bg-green-700 transition-colors">استفسار واتساب</a>
                </div>
            `;
            layer.bindPopup(popup, { maxWidth: 320 }).openPopup();
            map.fitBounds(layer.getBounds().pad(0.25));
        }
    } else {
        const point = lotsPointsSource.find(p => String(p?.lot_number) === key);
        if (!point) return;

        selectedLot = {
            lot_number: point.lot_number,
            status: point.status || 'available',
        };

        const marker = lotsPointsIndex[key];
        if (marker) {
            const st = String(point?.status || 'available');
            const popup = `
                <div class="p-2" style="min-width: 220px">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-semibold text-gray-900">قطعة رقم ${escapeHtml(point.lot_number)}</div>
                            <div class="text-xs text-gray-500 mt-1">نقطة موقع</div>
                        </div>
                        <span class="text-[11px] px-2 py-1 rounded-full border">${escapeHtml(statusText(st))}</span>
                    </div>
                </div>
            `;
            marker.bindPopup(popup, { maxWidth: 280 }).openPopup();
            const b = marker.getBounds?.();
            if (b && b.isValid && b.isValid()) {
                map.fitBounds(b.pad(0.45));
            } else {
                map.setView(marker.getLatLng(), Math.max(map.getZoom(), 18), { animate: true });
            }
        }
    }

    renderLots();

    if (fromMap) {
        const container = document.getElementById('lotsList');
        const active = container?.querySelector(`[data-lot="${CSS.escape(key)}"]`);
        active?.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }
}

function resetLotsFilters() {
    const s = document.getElementById('lotsSearch');
    const st = document.getElementById('lotsStatus');
    if (s) s.value = '';
    if (st) st.value = '';
    selectedLot = null;
    renderLots();
}

function fitToLots() {
    const group = L.featureGroup([]);
    if (lotsLayer) {
        group.addLayer(lotsLayer);
    }
    if (lotsPointsLayer) {
        group.addLayer(lotsPointsLayer);
    }
    const bounds = group.getBounds();
    if (bounds && bounds.isValid()) {
        map.fitBounds(bounds.pad(0.15));
        return;
    }
}

function zoomInMap() {
    map.zoomIn();
}

function zoomOutMap() {
    map.zoomOut();
}

document.addEventListener('DOMContentLoaded', function () {
    try {
        const q = new URLSearchParams(window.location.search);
        const lot = q.get('lot');
        if (lot) {
            initialLotToSelect = String(lot).trim();
        }
    } catch (e) {}

    initMap();

    const search = document.getElementById('lotsSearch');
    if (search) {
        search.addEventListener('input', function () {
            renderLots();
            try {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            } catch (e) {}
        });
    }

    const status = document.getElementById('lotsStatus');
    if (status) {
        status.addEventListener('change', function () {
            renderLots();
            try {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            } catch (e) {}
        });
    }

    try {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    } catch (e) {}
});
</script>

<a
    href="{{ !empty($whatsappNumber) ? ('https://wa.me/' . preg_replace('/\D+/', '', (string)$whatsappNumber)) : 'https://wa.me/?text=' . urlencode('استفسار عن مخطط عجلان') }}"
    target="_blank"
    rel="noopener"
    aria-label="WhatsApp"
    style="position: fixed; right: 18px; bottom: 18px; z-index: 60; width: 56px; height: 56px; border-radius: 999px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, var(--accent), var(--accent2)); box-shadow: var(--shadowSoft); border: 1px solid color-mix(in oklab, var(--accent) 35%, transparent);"
>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="26" height="26" fill="white" aria-hidden="true">
        <path d="M19.11 17.79c-.27-.13-1.59-.78-1.83-.87-.24-.09-.42-.13-.6.13-.18.27-.69.87-.84 1.05-.16.18-.31.2-.58.07-.27-.13-1.13-.42-2.16-1.33-.8-.72-1.34-1.6-1.5-1.87-.16-.27-.02-.41.12-.55.12-.12.27-.31.4-.47.13-.16.18-.27.27-.44.09-.18.04-.33-.02-.47-.07-.13-.6-1.45-.82-1.99-.22-.53-.44-.46-.6-.47h-.51c-.18 0-.47.07-.71.33-.24.27-.94.92-.94 2.24 0 1.32.96 2.6 1.1 2.78.13.18 1.89 2.89 4.58 4.05.64.28 1.14.45 1.53.57.65.2 1.24.17 1.71.1.52-.08 1.59-.65 1.81-1.28.22-.62.22-1.16.16-1.28-.07-.11-.24-.18-.51-.31ZM16.02 3C8.84 3 3 8.77 3 15.87c0 2.25.6 4.45 1.74 6.39L3 29l6.93-1.79a13.2 13.2 0 0 0 6.09 1.48h.01c7.18 0 13.02-5.77 13.02-12.87C29.05 8.77 23.2 3 16.02 3Zm0 23.45h-.01c-1.93 0-3.82-.51-5.47-1.47l-.39-.23-4.11 1.06 1.1-3.98-.25-.4a11.39 11.39 0 0 1-1.76-6.06c0-6.29 5.18-11.4 11.55-11.4 6.37 0 11.55 5.11 11.55 11.4 0 6.29-5.18 11.4-11.55 11.4Z"/>
    </svg>
</a>

</body>
</html>
