@extends('facility.layouts.app')

@section('title', __('financial.owner_report'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('financial.owner_report') }}</h1>
            <p class="text-gray-600">{{ __('financial.owner_report_desc') }}</p>
        </div>
        <a href="{{ route('facility.financial.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-arrow-right ml-1"></i> {{ __('financial.reports') }}
        </a>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6 flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[250px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.select_owner') }}</label>
            <select name="owner_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="">{{ __('financial.select') }}</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                        {{ $owner->name }} ({{ $owner->email ?? $owner->phone }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition-colors">{{ __('financial.view_report') }}</button>
    </form>

    @if($report)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($report['total_contracts']) }}</h4>
                <p class="text-sm text-gray-600">{{ __('financial.contracts') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
                <h4 class="text-2xl font-bold text-blue-600">{{ number_format($report['total_value'], 2) }}</h4>
                <p class="text-sm text-gray-600">{{ __('financial.total_contracts_value') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
                <h4 class="text-2xl font-bold text-purple-600">{{ number_format($report['total_commission'], 2) }}</h4>
                <p class="text-sm text-gray-600">{{ __('financial.total_commissions') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
                <h4 class="text-2xl font-bold text-green-600">{{ number_format($report['net_amount'], 2) }}</h4>
                <p class="text-sm text-gray-600">{{ __('financial.net_owner_dues') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">{{ __('financial.commission_rate') }}</h3>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-purple-500 h-4 rounded-full" style="width: {{ min(100, $report['commission_rate']) }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">{{ number_format($report['commission_rate'], 1) }}% {{ __('financial.of_contracts_value') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __('financial.owner_contracts') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.contract_number') }}</th>
                            <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.product') }}</th>
                            <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.customer') }}</th>
                            <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.amount') }}</th>
                            <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.commission') }}</th>
                            <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.net') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($report['contracts'] as $contract)
                            <tr>
                                <td class="px-6 py-3 font-semibold">{{ $contract->contract_number ?? '#'.$contract->id }}</td>
                                <td class="px-6 py-3">{{ $contract->product?->name ?? '—' }}</td>
                                <td class="px-6 py-3">{{ $contract->user?->name ?? '—' }}</td>
                                <td class="px-6 py-3">{{ number_format($contract->total_amount, 2) }}</td>
                                <td class="px-6 py-3 text-purple-600">{{ number_format($contract->commission_amount, 2) }}</td>
                                <td class="px-6 py-3 text-green-600">{{ number_format($contract->total_amount - $contract->commission_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">{{ __('financial.no_owner_contracts') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($owners->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center text-yellow-800">
            {{ __('financial.no_owners') }}
        </div>
    @endif
</div>
@endsection
