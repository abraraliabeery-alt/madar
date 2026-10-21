@extends('client.layouts.app')

@section('title', __('client.financial_report'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.financial_report') }} — #{{ $contract->contract_number ?? $contract->id }}</h1>
            <a href="{{ route('client.contracts.show', $contract) }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.contracts.total_amount') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($contract->total_amount ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.contracts.paid_amount') }}</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.contracts.remaining_amount') }}</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($remaining, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.payment_status') }}</p>
                <p class="text-2xl font-bold {{ $isFullyPaid ? 'text-green-600' : 'text-yellow-600' }}">{{ $isFullyPaid ? __('client.fully_paid') : __('client.partially_paid') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
