@extends('client.layouts.app')

@section('title', __('client.payments.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.payments.title') }} — {{ __('client.contracts.contract_number') }} #{{ $contract->contract_number ?? $contract->id }}</h1>
            <a href="{{ route('client.contracts.show', $contract) }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-right">#</th>
                        <th class="p-3 text-right">{{ __('client.payments.amount') }}</th>
                        <th class="p-3 text-right">{{ __('client.payments.payment_method') }}</th>
                        <th class="p-3 text-right">{{ __('client.payments.payment_date') }}</th>
                        <th class="p-3 text-right">{{ __('client.payments.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="p-3">{{ $payment->id }}</td>
                            <td class="p-3 font-semibold">{{ number_format($payment->amount ?? 0, 2) }}</td>
                            <td class="p-3">{{ __('client.payments.methods.' . $payment->payment_method) }}</td>
                            <td class="p-3">{{ $payment->payment_date ?? $payment->created_at->format('Y-m-d') }}</td>
                            <td class="p-3">{{ __('client.payments.statuses.' . $payment->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">{{ __('client.payments.no_payments') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
