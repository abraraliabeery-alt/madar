@extends('facility.layouts.app')

@section('panel_title')
    @yield('title', __('facility_management.financial_system'))
@endsection

@push('styles')
    <link href="{{ asset('financial-bundle.css') }}" rel="stylesheet">
@endpush

@if(View::hasSection('styles'))
    @push('styles')
        @yield('styles')
    @endpush
@endif

@section('content')
    @yield('content')
@endsection

@push('scripts')
    <script src="{{ asset('js/facility-financial.js') }}"></script>
@endpush

@if(View::hasSection('scripts'))
    @push('scripts')
        @yield('scripts')
    @endpush
@endif
