@extends('layouts.app')
@section('title', __('public.features'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">{{ __('public.features') }}</h1>
    <div class="row g-4">
        @foreach($features as $feature)
        <div class="col-md-4"><div class="card h-100"><div class="card-body">
            <h5>{{ $feature->name ?? '' }}</h5>
            <p class="text-muted">{{ $feature->description ?? '' }}</p>
        </div></div></div>
        @endforeach
    </div>
</div>
@endsection
