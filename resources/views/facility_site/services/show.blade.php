@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $service->title }} - خدمات {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:900px; margin:auto; padding:24px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        .content{background:#fff; border:1px solid #eee; border-radius:12px; padding:20px}
        a{text-decoration:none}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <div>
        <a href="{{ route('facility.site.services.index', $facility->slug ?? $facility->id) }}">الخدمات</a>
        ·
        <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">الرئيسية</a>
    </div>
</header>
<main class="container">
    <div class="content">
        <h1 style="margin-top:0">{{ $service->title }}</h1>
        @if($service->image_path)
            <img src="{{ asset('storage/'.$service->image_path) }}" style="max-width:100%; border-radius:8px; margin:0 0 12px" alt="{{ $service->title }}">
        @endif
        @if($service->excerpt)
            <p style="color:#666">{{ $service->excerpt }}</p>
        @endif
        {!! $service->content !!}
    </div>
</main>
</body>
</html>
