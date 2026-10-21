{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0">
<channel>
    <title>{{ __('public.facilities_title') ?? config('app.name') }}</title>
    <link>{{ url('/facilities') }}</link>
    <description>{{ config('app.name') }}</description>
    @foreach($facilities as $facility)
    <item>
        <title>{{ $facility->name }}</title>
        <link>{{ route('public.facilities.show', $facility->id) }}</link>
        <pubDate>{{ $facility->created_at->toRfc2822String() }}</pubDate>
        <guid>{{ route('public.facilities.show', $facility->id) }}</guid>
    </item>
    @endforeach
</channel>
</rss>
