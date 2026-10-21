{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($products as $product)
    <url>
        <loc>{{ route('public.products.show', $product->id) }}</loc>
        <lastmod>{{ $product->updated_at->format('Y-m-d') }}</lastmod>
    </url>
    @endforeach
</urlset>
