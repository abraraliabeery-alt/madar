@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $partner->name }} - شركاء {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:900px; margin:auto; padding:24px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        .content{background:#fff; border:1px solid #eee; border-radius:12px; padding:20px; text-align:center}
        img{max-width:220px; max-height:120px; object-fit:contain; display:block; margin:0 auto 12px}
        a{text-decoration:none}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <div>
        <a href="{{ route('facility.site.partners.index', $facility->slug ?? $facility->id) }}">الشركاء</a>
        ·
        <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">الرئيسية</a>
    </div>
</header>
<main class="container">
    <div class="content">
        @if($partner->logo_path)
            <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="{{ $partner->name }}">
        @endif
        <h1 style="margin-top:0">{{ $partner->name }}</h1>
        @if($partner->website)
            <p><a href="{{ $partner->website }}" target="_blank">زيارة الموقع</a></p>
        @endif
    </div>
</main>
</body>
</html>
