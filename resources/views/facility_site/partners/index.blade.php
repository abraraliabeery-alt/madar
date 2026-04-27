@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>شركاؤنا - {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:1100px; margin:auto; padding:24px}
        .grid{display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:16px}
        .card{background:#fff; border:1px solid #eee; border-radius:12px; padding:16px; text-align:center}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        img{max-width:100%; max-height:80px; object-fit:contain; margin-bottom:10px}
        a{text-decoration:none; color:inherit}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">العودة للموقع</a>
</header>
<main class="container">
    <h1 style="margin:0 0 16px">شركاؤنا</h1>
    <div class="grid">
        @forelse($partners as $partner)
            <a class="card" href="{{ route('facility.site.partners.show', [$facility->slug ?? $facility->id, $partner->slug]) }}">
                @if($partner->logo_path)
                    <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="{{ $partner->name }}">
                @endif
                <div>{{ $partner->name }}</div>
            </a>
        @empty
            <p>لم تتم إضافة شركاء بعد.</p>
        @endforelse
    </div>
</main>
</body>
</html>
