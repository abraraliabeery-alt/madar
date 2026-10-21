@extends('facility.layouts.app')

@section('title', __('financial.payments_report'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('financial.payments_report') }}</h1>
            <p class="text-gray-600">{{ __('financial.payments_report_desc') }}</p>
        </div>
        <a href="{{ route('facility.financial.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-arrow-right ml-1"></i> {{ __('financial.reports') }}
        </a>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.start_date') }}</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.end_date') }}</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition-colors">{{ __('financial.apply') }}</button>
    </form>

    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg mb-6">
        <p class="text-green-100 mb-1">{{ __('financial.total_payments') }}</p>
        <h2 class="text-4xl font-bold">{{ number_format($report['total_payments'], 2) }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.payments_by_method') }}</h3>
            <div class="space-y-3">
                @forelse($report['by_method'] as $method => $amount)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ __('financial.' . $method) !== 'financial.' . $method ? __('financial.' . $method) : $method }}</span>
                        <span class="font-semibold">{{ number_format($amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center">{{ __('financial.no_data') }}</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.monthly_payments') }}</h3>
            <div class="space-y-3">
                @forelse($report['by_month']->sortKeys() as $month => $amount)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $month }}</span>
                        <span class="font-semibold">{{ number_format($amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center">{{ __('financial.no_data') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __('financial.payments') }} ({{ $report['payments']->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.date') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.reference_number') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.contract') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.payment_method') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($report['payments']->sortByDesc('payment_date') as $payment)
                        <tr>
                            <td class="px-6 py-3">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-3">{{ $payment->reference_number ?? '—' }}</td>
                            <td class="px-6 py-3">{{ $payment->contract?->contract_number ?? '—' }}</td>
                            <td class="px-6 py-3">{{ $payment->payment_method }}</td>
                            <td class="px-6 py-3 font-semibold text-green-600">{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">{{ __('financial.no_payments_period') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
