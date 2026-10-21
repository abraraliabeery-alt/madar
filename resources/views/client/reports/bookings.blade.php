@extends('client.layouts.app')

@section('title', __('client.booking_reports'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.booking_reports') }}</h1>
            <a href="{{ route('client.reports') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back_to_reports') }}</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-right">#</th>
                        <th class="p-3 text-right">{{ __('client.product') }}</th>
                        <th class="p-3 text-right">{{ __('client.facility') }}</th>
                        <th class="p-3 text-right">{{ __('client.amount') }}</th>
                        <th class="p-3 text-right">{{ __('client.status') }}</th>
                        <th class="p-3 text-right">{{ __('client.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="p-3">{{ $booking->id }}</td>
                            <td class="p-3">{{ optional($booking->product)->getTranslatedTitle() ?: '-' }}</td>
                            <td class="p-3">{{ optional($booking->facility)->name ?? '-' }}</td>
                            <td class="p-3">{{ number_format($booking->total_amount ?? 0, 2) }}</td>
                            <td class="p-3">{{ $booking->status }}</td>
                            <td class="p-3">{{ $booking->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-gray-500">{{ __('client.no_results') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
