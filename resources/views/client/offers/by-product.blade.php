@extends('client.layouts.app')

@section('title', optional($product)->getTranslatedTitle() ?: __('client.offers'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.offers_for') }}: {{ optional($product)->getTranslatedTitle() }}</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($offers as $offer)
                <a href="{{ route('client.offers.show', $offer) }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition block">
                    <p class="text-sm text-gray-500">{{ optional($offer->facility)->name ?? '-' }}</p>
                    <p class="text-lg font-bold text-blue-600 mt-3">{{ number_format($offer->price ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ __('client.offers.types.' . $offer->offer_type) }}</p>
                </a>
            @empty
                <p class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">{{ __('client.no_results') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
