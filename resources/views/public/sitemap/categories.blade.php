{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($categories as $category)
    <url>
        <loc>{{ route('public.categories.show', $category->id) }}</loc>
        <lastmod>{{ $category->updated_at->format('Y-m-d') }}</lastmod>
    </url>
    @endforeach
</urlset>
