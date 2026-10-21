@extends('layouts.app')
@section('title', __('public.location'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">{{ __('public.location') }}</h1>
    <div class="card p-4">
        <h5>{{ $location['name'] ?? '' }}</h5>
        <p>{{ $location['address'] ?? '' }}</p>
        @if(isset($location['latitude']) && isset($location['longitude']))
        <p class="text-muted">{{ $location['latitude'] }}, {{ $location['longitude'] }}</p>
        @endif
    </div>
</div>
@endsection
