@extends('facility.layouts.app')

@section('title', __('financial.accounting_setup'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">{{ __('financial.accounting_setup') }}</h3>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif

        <div class="space-y-4 mb-6">
            <div class="flex items-center justify-between border rounded-lg p-4">
                <span class="font-semibold">{{ __('financial.chart_of_accounts') }}</span>
                <span class="px-3 py-1 rounded-full text-sm {{ $hasAccounts ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $hasAccounts ? __('financial.configured') : __('financial.not_configured') }}
                </span>
            </div>
            <div class="flex items-center justify-between border rounded-lg p-4">
                <span class="font-semibold">{{ __('financial.accounting_periods') }}</span>
                <span class="px-3 py-1 rounded-full text-sm {{ $hasPeriods ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $hasPeriods ? __('financial.configured') : __('financial.not_configured') }}
                </span>
            </div>
            <div class="flex items-center justify-between border rounded-lg p-4">
                <span class="font-semibold">{{ __('financial.tax_rates') }}</span>
                <span class="px-3 py-1 rounded-full text-sm {{ $hasTaxRates ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $hasTaxRates ? __('financial.configured') : __('financial.not_configured') }}
                </span>
            </div>
        </div>

        @if(!$hasAccounts || !$hasPeriods || !$hasTaxRates)
            <form method="POST" action="{{ route('facility.accounting.setup.create') }}">
                @csrf
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                    {{ __('financial.create_default_setup') }}
                </button>
            </form>
        @else
            <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-center">
                {{ __('financial.setup_complete') }}
            </div>
        @endif
    </div>
</div>
@endsection
