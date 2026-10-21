@extends('client.layouts.app')

@section('panel_title')
    @yield('title', 'النظام المالي')
@endsection

@if(View::hasSection('styles'))
    @push('styles')
        @yield('styles')
    @endpush
@endif

@section('content')
    @yield('content')
@endsection

@if(View::hasSection('scripts'))
    @push('scripts')
        @yield('scripts')
    @endpush
@endif
