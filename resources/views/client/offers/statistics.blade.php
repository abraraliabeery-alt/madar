@extends('client.layouts.app')

@section('title', __('client.offer_statistics'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.offer_statistics') }}</h1>
            <a href="{{ route('client.offers.index') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_offers') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_offers'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.active_offers') }}</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active_offers'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.expired_offers') }}</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['expired_offers'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.sale_offers') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['sale_offers'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.rent_offers') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['rent_offers'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_value') }}</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_value'], 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
