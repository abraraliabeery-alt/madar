@extends('client.layouts.app')

@section('title', __('client.invoices.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.invoices.title') }} — {{ __('client.contracts.contract_number') }} #{{ $contract->contract_number ?? $contract->id }}</h1>
            <a href="{{ route('client.contracts.show', $contract) }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-right">{{ __('client.invoices.invoice_number') }}</th>
                        <th class="p-3 text-right">{{ __('client.invoices.amount') }}</th>
                        <th class="p-3 text-right">{{ __('client.invoices.due_date') }}</th>
                        <th class="p-3 text-right">{{ __('client.invoices.status') }}</th>
                        <th class="p-3 text-right">{{ __('client.invoices.type') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="p-3">{{ $invoice->invoice_number ?? $invoice->id }}</td>
                            <td class="p-3 font-semibold">{{ number_format($invoice->net_amount ?? $invoice->amount ?? 0, 2) }}</td>
                            <td class="p-3">{{ $invoice->due_date }}</td>
                            <td class="p-3">{{ __('client.invoices.statuses.' . $invoice->status) }}</td>
                            <td class="p-3">{{ __('client.invoices.types.' . $invoice->type) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">{{ __('client.invoices.no_invoices') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
