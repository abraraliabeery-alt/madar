{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($facilities as $facility)
    <url>
        <loc>{{ route('public.facilities.show', $facility->id) }}</loc>
        <lastmod>{{ $facility->updated_at->format('Y-m-d') }}</lastmod>
    </url>
    @endforeach
</urlset>
