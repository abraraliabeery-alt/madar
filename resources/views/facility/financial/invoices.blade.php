@extends('facility.layouts.app')

@section('title', __('financial.invoices_report'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('financial.invoices_report') }}</h1>
            <p class="text-gray-600">{{ __('financial.invoices_report_desc') }}</p>
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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
            <h4 class="text-2xl font-bold text-gray-900">{{ number_format($report['total_invoices']) }}</h4>
            <p class="text-sm text-gray-600">{{ __('financial.invoices_count') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
            <h4 class="text-2xl font-bold text-blue-600">{{ number_format($report['total_amount'], 2) }}</h4>
            <p class="text-sm text-gray-600">{{ __('financial.total_invoices') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
            <h4 class="text-2xl font-bold text-green-600">{{ number_format($report['paid_amount'], 2) }}</h4>
            <p class="text-sm text-gray-600">{{ __('financial.paid_amount') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-200 p-5 text-center">
            <h4 class="text-2xl font-bold text-red-600">{{ number_format($report['remaining_amount'], 2) }}</h4>
            <p class="text-sm text-gray-600">{{ __('financial.remaining_amount') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.invoices_by_status') }}</h3>
            <div class="space-y-3">
                @forelse($report['by_status'] as $status => $row)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $status }} ({{ $row['count'] }})</span>
                        <span class="font-semibold">{{ number_format($row['amount'], 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center">{{ __('financial.no_data') }}</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('financial.invoices_by_type') }}</h3>
            <div class="space-y-3">
                @forelse($report['by_type'] as $type => $row)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $type ?: __('financial.uncategorized') }} ({{ $row['count'] }})</span>
                        <span class="font-semibold">{{ number_format($row['amount'], 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center">{{ __('financial.no_data') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    @if($report['overdue_invoices']->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-red-800 mb-2">
            <i class="fas fa-exclamation-triangle ml-1"></i>
            {{ __('financial.overdue_invoices') }} ({{ $report['overdue_invoices']->count() }})
        </h3>
        <div class="space-y-1 text-sm">
            @foreach($report['overdue_invoices'] as $invoice)
                <div class="flex justify-between">
                    <span>{{ $invoice->invoice_number ?? '#'.$invoice->id }}</span>
                    <span class="font-semibold text-red-700">{{ number_format($invoice->remaining_amount, 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __('financial.invoices') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.invoice_number') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.contract') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.due_date') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.amount') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.paid_amount') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.remaining_amount') }}</th>
                        <th class="px-6 py-3 text-right text-gray-600">{{ __('financial.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($report['invoices']->sortByDesc('created_at') as $invoice)
                        <tr>
                            <td class="px-6 py-3 font-semibold">{{ $invoice->invoice_number ?? '#'.$invoice->id }}</td>
                            <td class="px-6 py-3">{{ $invoice->contract?->contract_number ?? '—' }}</td>
                            <td class="px-6 py-3">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '—' }}</td>
                            <td class="px-6 py-3">{{ number_format($invoice->amount, 2) }}</td>
                            <td class="px-6 py-3 text-green-600">{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="px-6 py-3 text-red-600">{{ number_format($invoice->remaining_amount, 2) }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : ($invoice->isOverdue() ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">{{ __('financial.no_invoices_period') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
