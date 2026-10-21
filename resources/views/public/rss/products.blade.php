{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0">
<channel>
    <title>{{ __('public.latest_products') }}</title>
    <link>{{ url('/products') }}</link>
    <description>{{ config('app.name') }}</description>
    @foreach($products as $product)
    <item>
        <title>{{ $product->getTranslatedTitle() ?? $product->id }}</title>
        <link>{{ route('public.products.show', $product->id) }}</link>
        <pubDate>{{ $product->created_at->toRfc2822String() }}</pubDate>
        <guid>{{ route('public.products.show', $product->id) }}</guid>
    </item>
    @endforeach
</channel>
</rss>
