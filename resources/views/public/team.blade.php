@extends('layouts.app')
@section('title', __('public.team'))
@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center">{{ __('public.team') }}</h1>
    <div class="row g-4">
        @foreach($team as $member)
        <div class="col-md-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">{{ $member['name'] ?? '' }}</h5>
                    <p class="text-muted">{{ $member['position'] ?? '' }}</p>
                    <p class="card-text">{{ $member['bio'] ?? '' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
