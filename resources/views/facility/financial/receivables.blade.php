@extends('facility.layouts.app')

@section('title', __('financial.receivables_report'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('financial.receivables_report') }}</h1>
            <p class="text-gray-600">{{ __('financial.receivables_desc') }}</p>
        </div>
        <a href="{{ route('facility.financial.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-arrow-right ml-1"></i> {{ __('financial.reports') }}
        </a>
    </div>

    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg mb-6">
        <p class="text-yellow-100 mb-1">{{ __('financial.total_receivables') }}</p>
        <h2 class="text-4xl font-bold">{{ number_format($report['total_receivables'], 2) }}</h2>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __('financial.receivables_by_contract') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.contract_number') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.product') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.amount_due') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($report['by_contract'] as $row)
                        <tr>
                            <td class="px-6 py-3 font-semibold">{{ $row['contract']->contract_number ?? '#'.$row['contract_id'] }}</td>
                            <td class="px-6 py-3">{{ $row['contract']?->product?->name ?? '—' }}</td>
                            <td class="px-6 py-3 font-semibold text-yellow-600">{{ number_format($row['amount'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">{{ __('financial.no_receivables') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
