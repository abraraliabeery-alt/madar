{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0">
<channel>
    <title>{{ __('public.news') }}</title>
    <link>{{ url('/news') }}</link>
    <description>{{ config('app.name') }}</description>
    @foreach($news as $item)
    <item>
        <title>{{ $item->title }}</title>
        <link>{{ $item->url ?? url('/news/' . $item->slug) }}</link>
        <pubDate>{{ $item->created_at->toRfc2822String() }}</pubDate>
        <guid>{{ $item->url ?? url('/news/' . $item->slug) }}</guid>
    </item>
    @endforeach
</channel>
</rss>
