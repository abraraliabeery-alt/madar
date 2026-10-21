@extends('client.layouts.app')

@section('title', __('client.booking_statistics'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.booking_statistics') }}</h1>
            <a href="{{ route('client.bookings.index') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_bookings') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.confirmed_bookings') }}</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.pending_bookings') }}</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.cancelled_bookings') }}</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <p class="text-sm text-gray-500">{{ __('client.total_spent') }}</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_spent'], 2) }}</p>
            </div>
        </div>

        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('client.recent_bookings') }}</h2>
        <div class="bg-white rounded-lg shadow-sm divide-y">
            @forelse($stats['recent_bookings'] as $booking)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ optional($booking->product)->getTranslatedTitle() ?: '-' }}</p>
                        <p class="text-sm text-gray-500">{{ optional($booking->facility)->name ?? '-' }} · {{ $booking->created_at->format('Y-m-d') }}</p>
                    </div>
                    <span class="text-sm font-semibold">{{ number_format($booking->total_amount ?? 0, 2) }}</span>
                </div>
            @empty
                <p class="p-8 text-center text-gray-500">{{ __('client.no_results') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
