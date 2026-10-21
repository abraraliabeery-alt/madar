@extends('facility.layouts.app')

@section('title', __('financial.accounting_entries'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">{{ __('financial.accounting_entries') }}</h3>
            <a href="{{ route('facility.accounting.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>{{ __('financial.back_to_accounting') }}</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ number_format($stats['total_entries'] ?? 0) }}</div>
                <div class="text-sm text-gray-600">{{ __('financial.total_entries') }}</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-700">{{ number_format($stats['total_debits'] ?? 0, 2) }}</div>
                <div class="text-sm text-gray-600">{{ __('financial.total_debits') }}</div>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-700">{{ number_format($stats['total_credits'] ?? 0, 2) }}</div>
                <div class="text-sm text-gray-600">{{ __('financial.total_credits') }}</div>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
            <select name="entry_type" class="border rounded-lg px-3 py-2">
                <option value="">{{ __('financial.entry_type') }}</option>
                <option value="debit" @selected(request('entry_type') === 'debit')>{{ __('financial.debit') }}</option>
                <option value="credit" @selected(request('entry_type') === 'credit')>{{ __('financial.credit') }}</option>
            </select>
            <select name="account_type" class="border rounded-lg px-3 py-2">
                <option value="">{{ __('financial.account_type') }}</option>
                @foreach(['revenue', 'receivable', 'commission', 'liability', 'expense', 'asset'] as $type)
                    <option value="{{ $type }}" @selected(request('account_type') === $type)>{{ __('financial.account_types.' . $type) }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded-lg px-3 py-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded-lg px-3 py-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('financial.filter') }}
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right">{{ __('financial.entry_date') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('financial.description') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('financial.contract_number') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('financial.entry_type') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('financial.account_type') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('financial.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ optional($entry->entry_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ $entry->description ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $entry->contract?->contract_number ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs {{ $entry->entry_type === 'debit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ __('financial.' . $entry->entry_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ __("financial.account_types.{$entry->account_type}") !== "financial.account_types.{$entry->account_type}" ? __("financial.account_types.{$entry->account_type}") : $entry->account_type }}</td>
                            <td class="px-4 py-3 font-semibold">{{ number_format($entry->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">{{ __('financial.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $entries->links() }}</div>
    </div>
</div>
@endsection
