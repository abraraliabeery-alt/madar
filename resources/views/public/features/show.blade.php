@extends('layouts.app')
@section('title', $feature->name ?? __('public.features'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ $feature->name ?? '' }}</h1>
    <p>{{ $feature->description ?? '' }}</p>
</div>
@endsection
