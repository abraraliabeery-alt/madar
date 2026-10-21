@extends('layouts.app')
@section('title', __('public.sitemap'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('public.sitemap') }}</h1>
    <div class="row">
        @foreach($sitemap as $section => $data)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5><a href="{{ $data['url'] }}">{{ $section }}</a></h5>
                    <ul class="list-unstyled">
                        @foreach($data['pages'] as $page => $url)
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
