@extends('layouts.app')
@section('title', __('public.lot_details'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('public.lot_details') }}</h1>
    <div class="card p-4">
        <p><strong>{{ __('public.plan') }}:</strong> {{ $plan->name ?? $plan->plan_number ?? '' }}</p>
        <p><strong>{{ __('public.lot') }}:</strong> {{ $lot->lot_number ?? $lot->id ?? '' }}</p>
        @if($centroid)
        <p><strong>{{ __('public.location') }}:</strong> {{ $centroid['lat'] }}, {{ $centroid['lng'] }}</p>
        @endif
        @if($whatsappNumber)
        <a href="https://wa.me/{{ $whatsappNumber }}" class="btn btn-success mt-3">{{ __('public.contact_whatsapp') }}</a>
        @endif
    </div>
</div>
@endsection
