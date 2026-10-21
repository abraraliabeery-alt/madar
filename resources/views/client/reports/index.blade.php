@extends('client.layouts.app')

@section('title', __('client.reports'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.reports') }}</h1>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_bookings') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.confirmed_bookings') }}</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_contracts') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_contracts'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_spent') }}</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_spent'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_appointments') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('client.reports.bookings') }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition font-semibold text-gray-900">{{ __('client.booking_reports') }}</a>
            <a href="{{ route('client.reports.spending') }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition font-semibold text-gray-900">{{ __('client.spending_reports') }}</a>
            <a href="{{ route('client.reports.activity') }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition font-semibold text-gray-900">{{ __('client.activity_reports') }}</a>
        </div>
    </div>
</div>
@endsection
