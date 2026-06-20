<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <div class="text-sm font-semibold">تصفية القطع</div>
                        <div class="mt-3">
                            <label class="block text-xs mb-1" style="color: var(--muted);">بحث برقم القطعة</label>
                            <input id="lotsSearch" class="w-full px-4 py-3 text-sm rounded-2xl border" style="border-color: var(--border); background: color-mix(in oklab, var(--card) 92%, transparent); color: var(--fg);" placeholder="مثال: 101">
                        </div>
                        <div class="mt-3">
                            <label class="block text-xs mb-1" style="color: var(--muted);">الحالة</label>
                            <select id="lotsStatus" class="w-full px-4 py-3 text-sm rounded-2xl border" style="border-color: var(--border); background: color-mix(in oklab, var(--card) 92%, transparent); color: var(--fg);">
                                <option value="">الكل</option>
                                <option value="available">متاح</option>
                                <option value="reserved">محجوز</option>
                                <option value="sold">مباع</option>
                            </select>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-xs" style="color: var(--muted);">عدد النتائج: <span id="lotsCount">0</span></div>
                            <button type="button" onclick="resetLotsFilters()" class="chip" style="height: 2.25rem; display:inline-flex; align-items:center; gap:.4rem;">
                                <i data-lucide="rotate-ccw" style="width:16px; height:16px;"></i>
                                <span>إعادة ضبط</span>
                            </button>
                        </div>
                    </div>

                    <div id="lotsList" class="px-4 pb-4" style="max-height: 560px; overflow:auto;"></div>
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
                            <button type="button" onclick="fitToLots()" class="chip" style="height: 2.25rem; display:inline-flex; align-items:center; gap:.4rem;">
                                <i data-lucide="maximize-2" style="width:16px; height:16px;"></i>
                                <span>عرض الكل</span>
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

let map;
let lotsLayer;
let lotsPointsLayer;
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
let planOverlayClipCleanup = null;

const planImageUrl = @json(asset('assets/assets/ajlan_plan_true_vector.svg') . '?v=' . @filemtime(public_path('assets/assets/ajlan_plan_true_vector.svg')));

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

    pointLotRadiusMeters = Number(planShadeRadiusMeters) > 0
        ? Math.max(25, Math.min(70, Number(planShadeRadiusMeters) / 140))
        : 45;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

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
                    console.log('Ajlan plan outer ring (lat,lng):', coords);
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

                planOverlay = null;
                planOverlayEnabled = false;
            }

            if (bounds && bounds.isValid()) {
                map.fitBounds(bounds.pad(0.15));
            }

            if (bounds && bounds.isValid()) {
                loadOsmRoads(bounds);
            }
        })
        .catch(() => {});

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

    const filteredPoints = [];

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

    document.getElementById('lotsCount').textContent = String(filtered.length);
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
