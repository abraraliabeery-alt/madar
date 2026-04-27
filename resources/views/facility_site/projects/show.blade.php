@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} - مشاريع {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:900px; margin:auto; padding:24px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        .content{background:#fff; border:1px solid #eee; border-radius:12px; padding:20px}
        .gallery{display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:10px; margin-top:12px}
        .gallery img{width:100%; height:120px; object-fit:cover; border-radius:8px}
        a{text-decoration:none}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <div>
        <a href="{{ route('facility.site.projects.index', $facility->slug ?? $facility->id) }}">المشاريع</a>
        ·
        <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">الرئيسية</a>
    </div>
</header>
<main class="container">
    <div class="content">
        <h1 style="margin-top:0">{{ $project->title }}</h1>
        @if($project->cover_image)
            <img src="{{ asset('storage/'.$project->cover_image) }}" style="max-width:100%; border-radius:8px; margin:0 0 12px" alt="{{ $project->title }}">
        @endif
        @if($project->excerpt)
            <p style="color:#666">{{ $project->excerpt }}</p>
        @endif
        {!! $project->content !!}
        @if(!empty($project->gallery))
            <div class="gallery">
                @foreach($project->gallery as $img)
                    <img src="{{ asset('storage/'.$img) }}" alt="">
                @endforeach
            </div>
        @endif
    </div>
</main>
</body>
</html>
