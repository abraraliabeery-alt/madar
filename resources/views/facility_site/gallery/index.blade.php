@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>المعرض - {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:1100px; margin:auto; padding:24px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        .filters a{margin:0 6px; text-decoration:none}
        .grid{display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; margin-top:12px}
        .card{background:#fff; border:1px solid #eee; border-radius:12px; overflow:hidden}
        .card img{width:100%; height:180px; object-fit:cover; display:block}
        .card .title{padding:10px}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">العودة للموقع</a>
</header>
<main class="container">
    <h1 style="margin:0 0 8px">المعرض</h1>
    <div class="filters">
        <a href="{{ route('facility.site.gallery.index', $facility->slug ?? $facility->id) }}">الكل</a>
        @foreach($categories as $cat)
            <a href="{{ route('facility.site.gallery.index', [$facility->slug ?? $facility->id, 'category' => $cat]) }}">{{ $cat }}</a>
        @endforeach
    </div>
    <div class="grid">
        @forelse($items as $item)
            <a class="card" href="{{ route('facility.site.gallery.show', [$facility->slug ?? $facility->id, $item->slug ?: $item->id]) }}">
                <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}">
                @if($item->title)
                    <div class="title">{{ $item->title }}</div>
                @endif
            </a>
        @empty
            <p>لا توجد صور في المعرض بعد.</p>
        @endforelse
    </div>
    <div style="margin-top:16px">{{ $items->withQueryString()->links() }}</div>
</main>
</body>
</html>
