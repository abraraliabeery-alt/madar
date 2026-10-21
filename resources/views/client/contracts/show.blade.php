@extends('client.layouts.app')

@section('title', __('client.contracts.contract_number') . ' #' . $contract->id)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.contracts.contract_number') }} #{{ $contract->contract_number ?? $contract->id }}</h1>
            <a href="{{ route('client.contracts.index') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div><p class="text-gray-500">{{ __('client.contracts.property') }}</p><p class="font-semibold">{{ optional($contract->product)->getTranslatedTitle() ?: '-' }}</p></div>
                <div><p class="text-gray-500">{{ __('client.facility') }}</p><p class="font-semibold">{{ optional($contract->facility)->name ?? '-' }}</p></div>
                <div><p class="text-gray-500">{{ __('client.contracts.status') }}</p><p class="font-semibold">{{ $contract->status }}</p></div>
                <div><p class="text-gray-500">{{ __('client.contracts.total_amount') }}</p><p class="font-semibold">{{ number_format($contract->total_amount ?? 0, 2) }}</p></div>
                <div><p class="text-gray-500">{{ __('client.contracts.paid_amount') }}</p><p class="font-semibold text-green-600">{{ number_format($contract->getTotalPaidAmount(), 2) }}</p></div>
                <div><p class="text-gray-500">{{ __('client.contracts.remaining_amount') }}</p><p class="font-semibold text-red-600">{{ number_format(max(0, ($contract->total_amount ?? 0) - $contract->getTotalPaidAmount()), 2) }}</p></div>
                <div><p class="text-gray-500">{{ __('client.contracts.start_date') }}</p><p class="font-semibold">{{ $contract->start_date }}</p></div>
                <div><p class="text-gray-500">{{ __('client.contracts.end_date') }}</p><p class="font-semibold">{{ $contract->end_date }}</p></div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('client.contracts.invoices', $contract) }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition font-semibold text-gray-900">{{ __('client.invoices.title') }} ({{ $contract->invoices->count() }})</a>
            <a href="{{ route('client.contracts.payments', $contract) }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition font-semibold text-gray-900">{{ __('client.payments.title') }} ({{ $contract->payments->count() }})</a>
            <a href="{{ route('client.contracts.financial-report', $contract) }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition font-semibold text-gray-900">{{ __('client.financial_report') }}</a>
        </div>
    </div>
</div>
@endsection
