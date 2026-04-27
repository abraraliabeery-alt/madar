@php
    $cssVars = $facility->css_variables;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الأسئلة الشائعة - {{ $facility->name }}</title>
    <style>
        :root{ @foreach($cssVars as $k => $v) {{ $k }}: {{ $v }}; @endforeach }
        body{font-family:'Cairo',system-ui; margin:0; color:var(--text-color); background:var(--background-color)}
        .container{max-width:900px; margin:auto; padding:24px}
        header{padding:16px 24px; display:flex; justify-content:space-between; align-items:center}
        details{background:#fff; border:1px solid #eee; border-radius:12px; padding:16px; margin:10px 0}
        summary{cursor:pointer; font-weight:600}
    </style>
</head>
<body>
<header>
    <strong>{{ $facility->name }}</strong>
    <a href="{{ route('facility.site.home', $facility->slug ?? $facility->id) }}">العودة للموقع</a>
</header>
<main class="container">
    <h1 style="margin:0 0 16px">الأسئلة الشائعة</h1>
    @forelse($faqs as $faq)
        <details>
            <summary>{{ $faq->question }}</summary>
            <div style="margin-top:8px">{!! nl2br(e($faq->answer)) !!}</div>
        </details>
    @empty
        <p>لا توجد أسئلة شائعة بعد.</p>
    @endforelse
</main>
</body>
</html>
