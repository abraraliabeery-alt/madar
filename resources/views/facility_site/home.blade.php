@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $facility->name }} - الموقع</title>
    <style>
        :root{
            @foreach($cssVars as $k => $v)
            {{ $k }}: {{ $v }};
            @endforeach
        }
        body{font-family: 'Cairo', system-ui, -apple-system, Segoe UI, Roboto, Arial; margin:0; color: var(--text-color); background: var(--background-color)}
        .hero{padding:64px 24px; color:#fff; position:relative}
        .overlay{position:absolute; inset:0; background: rgba(0,0,0,0.{{ (int)($facility->customization['hero']['overlay_opacity'] ?? 20) }});}
        .hero-inner{position:relative; z-index:1; max-width:1100px; margin:auto}
        .container{max-width:1100px; margin:auto; padding:24px}
        .btn{display:inline-block; padding:10px 16px; background: var(--primary-color); color:#fff; text-decoration:none; border-radius:8px}
        header{display:flex; align-items:center; justify-content:space-between; padding:16px 24px}
        nav a{margin-inline:8px; color: var(--text-color); text-decoration:none}
        .logo{display:flex; align-items:center; gap:10px}
        .logo img{height:40px}
        .card{background:#fff; border:1px solid #eee; border-radius:12px; padding:16px}
        footer{padding:24px; text-align:center; color:#666}
    </style>
</head>
<body>
<header>
    <div class="logo">
        @if($facility->logo_url)
            <img src="{{ $facility->logo_url }}" alt="{{ $facility->name }}">
        @endif
        <strong>{{ $facility->name }}</strong>
    </div>
    <nav>
        <a href="{{ route('home') }}">الرئيسية</a>
        <a href="#about">عن المنشأة</a>
        <a href="#contact">تواصل</a>
    </nav>
</header>
<section class="hero" style="{{ $facility->hero_background_style }}">
    <div class="overlay"></div>
    <div class="hero-inner">
        <h1 style="margin:0 0 12px">مرحبًا بكم في {{ $facility->name }}</h1>
        <p style="margin:0 0 16px">{{ $facility->meta_description ?? 'وصف مختصر للمنشأة' }}</p>
        <a class="btn" href="#contact">تواصل الآن</a>
    </div>
</section>
<main class="container">
    <div class="card" id="about">
        <h2>عن المنشأة</h2>
        <p>{{ $facility->description ?? 'لم تتم إضافة وصف بعد.' }}</p>
    </div>
    <div class="card" id="contact" style="margin-top:16px">
        <h2>بيانات التواصل</h2>
        <p>الهاتف: {{ $facility->phone ?? '-' }}</p>
        <p>البريد: {{ $facility->email ?? '-' }}</p>
        @if($facility->website)
        <p>الموقع: <a href="{{ $facility->website }}" target="_blank">{{ $facility->website }}</a></p>
        @endif
    </div>
</main>
<footer>
    جميع الحقوق محفوظة &copy; {{ date('Y') }} - {{ $facility->name }}
</footer>
</body>
</html>
