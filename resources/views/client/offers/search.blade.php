@extends('client.layouts.app')

@section('title', __('client.search'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.search_offers') }}</h1>
            <form method="GET" action="{{ route('client.offers.search') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">{{ __('client.all_types') }}</option>
                    <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>{{ __('client.offers.types.sale') }}</option>
                    <option value="rent_monthly" {{ request('type') == 'rent_monthly' ? 'selected' : '' }}>{{ __('client.offers.types.rent_monthly') }}</option>
                    <option value="rent_yearly" {{ request('type') == 'rent_yearly' ? 'selected' : '' }}>{{ __('client.offers.types.rent_yearly') }}</option>
                    <option value="rent_daily" {{ request('type') == 'rent_daily' ? 'selected' : '' }}>{{ __('client.offers.types.rent_daily') }}</option>
                </select>
                <input type="number" name="min_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="{{ __('client.min_price') }}" value="{{ request('min_price') }}">
                <input type="number" name="max_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="{{ __('client.max_price') }}" value="{{ request('max_price') }}">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">{{ __('client.search') }}</button>
            </form>
        </div>

        <p class="text-sm text-gray-600 mb-4">{{ $offers->count() }} {{ __('client.results_found') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($offers as $offer)
                <a href="{{ route('client.offers.show', $offer) }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition block">
                    <p class="font-semibold text-gray-900">{{ optional($offer->product)->getTranslatedTitle() ?: '-' }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($offer->facility)->name ?? '-' }}</p>
                    <p class="text-lg font-bold text-blue-600 mt-3">{{ number_format($offer->price ?? 0, 2) }}</p>
                </a>
            @empty
                <p class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">{{ __('client.no_results') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
