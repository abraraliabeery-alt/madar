@extends('layouts.app')
@section('title', __('errors.500_title'))
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1">500</h1>
    <p class="lead">{{ __('errors.500_message') }}</p>
    <a href="{{ url('/') }}" class="btn btn-primary">{{ __('errors.back_home') }}</a>
</div>
@endsection
