<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('brand.name', $facility->name ?? config('app.name')) }}</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('site.css') }}">
  @php($site = config('brand.customization', []))
  <style>
    :root{
      --primary: {{ $site['primary_color'] ?? config('brand.color', '#2563eb') }};
      --primary-700: color-mix(in oklab, var(--primary), #000 20%);
      --bg: {{ $site['background_color'] ?? '#ffffff' }};
      --fg: {{ $site['text_color'] ?? '#111827' }};
      --card: #ffffff;
      --border: #e5e7eb;
      --shadow-sm: 0 1px 2px rgba(0,0,0,.06);
      --accent: {{ $site['accent_color'] ?? 'var(--primary)' }};
      --hero-bg-type: {{ $site['hero_background_type'] ?? 'gradient' }};
      --hero-bg-value: {{ $site['hero_background_value'] ?? '' }};
      --hero-overlay: {{ isset($site['hero_overlay_opacity']) ? ((int)$site['hero_overlay_opacity'] / 100) : 0.0 }};
    }
    body{ font-family: {{ ($site['font_family'] ?? null) ? ("'".($site['font_family'])."'") : "'Cairo'" }}, system-ui, -apple-system, Segoe UI, Roboto, Arial; background:var(--bg); color:var(--fg); margin:0 }
    .site-header{ position:sticky; top:0; z-index:60; background:var(--bg); border-bottom:1px solid var(--border) }
    .site-header .inner{ width:min(1160px,92vw); margin-inline:auto; display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:.6rem 0 }
    .brand{ display:flex; align-items:center; gap:.6rem; font-weight:900 }
    .brand img{ height:28px; width:auto }
    nav.site-nav{ display:flex; gap:.8rem }
    nav.site-nav a{ color:inherit; text-decoration:none; padding:.35rem .6rem; border-radius:999px; border:1px solid transparent }
    nav.site-nav a:hover{ border-color: color-mix(in oklab, var(--fg), transparent 85%) }
    .site-footer{ background: color-mix(in oklab, var(--fg), transparent 94%); border-top:1px solid var(--border); padding:18px 0; margin-top:28px }
    .site-footer .inner{ width:min(1160px,92vw); margin-inline:auto; display:flex; flex-wrap:wrap; justify-content:space-between; gap:.8rem; font-size:.95rem; color: color-mix(in oklab, var(--fg), transparent 35%) }
  </style>
  @if(!empty($site['custom_css']))
    <style>{!! $site['custom_css'] !!}</style>
  @endif
  @yield('head_extra')
</head>
<body>
  <header class="site-header" role="banner">
    <div class="inner">
      <a class="brand" href="{{ url('/') }}">
        @if(config('brand.logo_path'))
          <img src="{{ \Illuminate\Support\Str::startsWith(config('brand.logo_path'), ['http://', 'https://', '/']) ? config('brand.logo_path') : asset(config('brand.logo_path')) }}" alt="{{ config('brand.name', config('app.name')) }}">
        @endif
        <span>{{ config('brand.name', config('app.name')) }}</span>
      </a>
      <nav class="site-nav" aria-label="روابط الموقع">
        <a href="#services">الخدمات</a>
        <a href="#products-carousel">المنتجات</a>
        <a href="#contact">تواصل</a>
      </nav>
    </div>
  </header>

  @yield('content')

  <footer class="site-footer" role="contentinfo">
    <div class="inner">
      <div>
        <strong>{{ config('brand.name', config('app.name')) }}</strong>
        @if(config('brand.website'))
          · <a href="{{ config('brand.website') }}" target="_blank" rel="noopener">الموقع</a>
        @endif
      </div>
      <div>
        @if(config('brand.phone'))
          <a href="tel:{{ config('brand.phone') }}">{{ config('brand.phone') }}</a>
        @endif
        @if(config('brand.email'))
          · <a href="mailto:{{ config('brand.email') }}">{{ config('brand.email') }}</a>
        @endif
      </div>
    </div>
  </footer>
  <script src="{{ asset('theme.js') }}" defer></script>
  <script src="{{ asset('script.js') }}" defer></script>
</body>
</html>
