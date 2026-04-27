@extends('facility.layouts.app')

@section('title', __('facility.bookings'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">{{ __('facility.bookings') }}</h1>

    <div class="bg-white shadow rounded-lg p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2 px-3">#</th>
                        <th class="py-2 px-3">{{ __('Name') }}</th>
                        <th class="py-2 px-3">{{ __('Product') }}</th>
                        <th class="py-2 px-3">{{ __('Status') }}</th>
                        <th class="py-2 px-3">{{ __('Total') }}</th>
                        <th class="py-2 px-3">{{ __('Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="border-b">
                            <td class="py-2 px-3">{{ $booking->id }}</td>
                            <td class="py-2 px-3">{{ optional($booking->user)->name }}</td>
                            <td class="py-2 px-3">{{ optional($booking->product)->name }}</td>
                            <td class="py-2 px-3">{{ $booking->status }}</td>
                            <td class="py-2 px-3">{{ number_format($booking->total_amount, 2) }}</td>
                            <td class="py-2 px-3">{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">{{ __('No bookings found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
