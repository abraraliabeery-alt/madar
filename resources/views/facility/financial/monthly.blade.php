@extends('facility.layouts.app')

@section('title', __('financial.monthly_report'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('financial.monthly_report') }}</h1>
            <p class="text-gray-600">{{ __('financial.monthly_report_desc') }}</p>
        </div>
        <a href="{{ route('facility.financial.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-arrow-right ml-1"></i> {{ __('financial.reports') }}
        </a>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.year') }}</label>
            <select name="year" class="border border-gray-300 rounded-lg px-3 py-2">
                @for($y = date('Y') + 1; $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ (request('year', $year ?? date('Y')) == $y) ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.month') }}</label>
            <select name="month" class="border border-gray-300 rounded-lg px-3 py-2">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ (request('month', $month ?? date('n')) == $m) ? 'selected' : '' }}>{{ __('financial.month_' . $m) }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition-colors">{{ __('financial.view') }}</button>
    </form>

    @if($report)
        <p class="text-sm text-gray-500 mb-4">{{ __('financial.period') }}: {{ $report['period']['start_date'] }} → {{ $report['period']['end_date'] }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-5 shadow">
                <h4 class="text-2xl font-bold">{{ number_format($report['summary']['total_revenue'], 2) }}</h4>
                <p class="text-sm text-blue-100">{{ __('financial.total_revenue') }}</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-5 shadow">
                <h4 class="text-2xl font-bold">{{ number_format($report['summary']['total_payments'], 2) }}</h4>
                <p class="text-sm text-green-100">{{ __('financial.total_payments') }}</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-5 shadow">
                <h4 class="text-2xl font-bold">{{ number_format($report['summary']['total_commissions'], 2) }}</h4>
                <p class="text-sm text-purple-100">{{ __('financial.total_commissions') }}</p>
            </div>
            <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-lg p-5 shadow">
                <h4 class="text-2xl font-bold">{{ number_format($report['summary']['net_income'], 2) }}</h4>
                <p class="text-sm text-gray-300">{{ __('financial.net_income') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">{{ __('financial.collection_rate') }}</h3>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-green-500 h-4 rounded-full" style="width: {{ min(100, $report['summary']['collection_rate']) }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">{{ number_format($report['summary']['collection_rate'], 1) }}% {{ __('financial.collection_rate_note') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.invoices') }}</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">{{ __('financial.count') }}</span><span class="font-semibold">{{ $report['invoices']['total_invoices'] }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">{{ __('financial.total') }}</span><span class="font-semibold">{{ number_format($report['invoices']['total_amount'], 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">{{ __('financial.paid') }}</span><span class="font-semibold text-green-600">{{ number_format($report['invoices']['paid_amount'], 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">{{ __('financial.remaining_amount') }}</span><span class="font-semibold text-red-600">{{ number_format($report['invoices']['remaining_amount'], 2) }}</span></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.payments_by_method') }}</h3>
                <div class="space-y-2 text-sm">
                    @forelse($report['payments']['by_method'] as $method => $amount)
                        <div class="flex justify-between"><span class="text-gray-600">{{ $method }}</span><span class="font-semibold">{{ number_format($amount, 2) }}</span></div>
                    @empty
                        <p class="text-gray-400">{{ __('financial.no_payments') }}</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.revenue_by_type') }}</h3>
                <div class="space-y-2 text-sm">
                    @forelse($report['revenue']['by_type'] as $type => $amount)
                        <div class="flex justify-between"><span class="text-gray-600">{{ $type ?: __('financial.uncategorized') }}</span><span class="font-semibold">{{ number_format($amount, 2) }}</span></div>
                    @empty
                        <p class="text-gray-400">{{ __('financial.no_revenue') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
