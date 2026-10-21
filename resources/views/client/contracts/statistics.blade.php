@extends('client.layouts.app')

@section('title', __('client.contract_statistics'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.contract_statistics') }}</h1>
            <a href="{{ route('client.contracts.index') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_contracts') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_contracts'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.active_contracts') }}</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active_contracts'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.completed_contracts') }}</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['completed_contracts'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.contracts.total_amount') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_value'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.contracts.paid_amount') }}</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_paid'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.contracts.remaining_amount') }}</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($stats['remaining_amount'], 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
