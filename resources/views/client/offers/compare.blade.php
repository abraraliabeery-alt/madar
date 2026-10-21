@extends('client.layouts.app')

@section('title', __('client.compare_offers'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.compare_offers') }}</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-800 rounded-lg p-4 mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-right">{{ __('client.product') }}</th>
                        <th class="p-3 text-right">{{ __('client.facility') }}</th>
                        <th class="p-3 text-right">{{ __('client.offers.type') }}</th>
                        <th class="p-3 text-right">{{ __('client.offers.price') }}</th>
                        <th class="p-3 text-right">{{ __('client.offers.deposit') }}</th>
                        <th class="p-3 text-right">{{ __('client.offers.valid_to') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($offers as $offer)
                        <tr>
                            <td class="p-3">{{ optional($offer->product)->getTranslatedTitle() ?: '-' }}</td>
                            <td class="p-3">{{ optional($offer->facility)->name ?? '-' }}</td>
                            <td class="p-3">{{ __('client.offers.types.' . $offer->offer_type) }}</td>
                            <td class="p-3 font-semibold">{{ number_format($offer->price ?? 0, 2) }}</td>
                            <td class="p-3">{{ number_format($offer->deposit ?? 0, 2) }}</td>
                            <td class="p-3">{{ $offer->valid_to }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-gray-500">{{ __('client.no_results') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
