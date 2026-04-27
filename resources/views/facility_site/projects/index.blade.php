@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>مشاريع {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:1100px; margin:auto; padding:24px}
        .grid{display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px}
        .card{background:#fff; border:1px solid #eee; border-radius:12px; padding:16px}
        .title{margin:0 0 16px}
        a{text-decoration:none; color:inherit}
        .badge{display:inline-block; background:var(--primary-color); color:#fff; padding:4px 10px; border-radius:999px; font-size:12px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        img{max-width:100%; height:160px; object-fit:cover; border-radius:8px; display:block; margin:0 0 10px}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">العودة للموقع</a>
</header>
<main class="container">
    <h1 class="title">المشاريع</h1>
    <div class="grid">
        @forelse($projects as $project)
            <a class="card" href="{{ route('facility.site.projects.show', [$facility->slug ?? $facility->id, $project->slug]) }}">
                @if($project->cover_image)
                    <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}">
                @endif
                <h3 style="margin:0 0 8px">{{ $project->title }}</h3>
                @if($project->excerpt)
                    <p style="margin:0 0 12px; color:#666">{{ \Illuminate\Support\Str::limit($project->excerpt, 120) }}</p>
                @endif
                <span class="badge">تفاصيل</span>
            </a>
        @empty
            <p>لا توجد مشاريع متاحة حاليًا.</p>
        @endforelse
    </div>
    <div style="margin-top:16px">{{ $projects->links() }}</div>
</main>
</body>
</html>
