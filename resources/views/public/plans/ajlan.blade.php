@extends('layouts.app')
@section('title', __('public.ajlan_plan'))
@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('public.ajlan_plan') }} {{ $planNumber }}</h1>
    <div id="ajlan-map" style="height: 70vh; border-radius: 8px;"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const geoJson = @json($geoJson);
    const center = [{{ $centerLat }}, {{ $centerLng }}];
    const whatsapp = "{{ $whatsappNumber }}";
    if (typeof L !== 'undefined') {
        const map = L.map('ajlan-map').setView(center, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        if (geoJson) {
            L.geoJSON(geoJson).addTo(map);
        }
    }
});
</script>
@endsection
