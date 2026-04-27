@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->title ?? 'صورة' }} - {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:1000px; margin:auto; padding:24px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        .content{background:#fff; border:1px solid #eee; border-radius:12px; padding:20px}
        .img{width:100%; max-height:70vh; object-fit:contain; display:block; border-radius:8px}
        a{text-decoration:none}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <div>
        <a href="{{ route('facility.site.gallery.index', $facility->slug ?? $facility->id) }}">المعرض</a>
        ·
        <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">الرئيسية</a>
    </div>
</header>
<main class="container">
    <div class="content">
        @if($item->title)
            <h1 style="margin-top:0">{{ $item->title }}</h1>
        @endif
        <img class="img" src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}">
    </div>
</main>
</body>
</html>
