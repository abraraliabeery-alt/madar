@extends('layouts.app')
@section('title', __('public.branches'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">{{ __('public.branches') }}</h1>
    <div class="row g-4">
        @foreach($branches as $branch)
        <div class="col-md-4"><div class="card h-100"><div class="card-body">
            <h5>{{ $branch['name'] ?? '' }}</h5>
            <p class="text-muted">{{ $branch['address'] ?? '' }}</p>
            <p>{{ $branch['working_hours'] ?? '' }}</p>
        </div></div></div>
        @endforeach
    </div>
</div>
@endsection
