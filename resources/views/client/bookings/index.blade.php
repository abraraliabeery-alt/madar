@extends('layouts.app')

@section('title', __('client.bookings.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('client.bookings.title') }}</h1>
                    <p class="text-gray-600">{{ __('client.bookings.subtitle') }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('client.bookings.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center">
                        <i class="fas fa-plus ml-2"></i>
                        {{ __('client.bookings.create_new') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('client.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('client.bookings.filter_by_status') }}</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">{{ __('client.bookings.all_statuses') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">{{ __('client.bookings.from_date') }}</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">{{ __('client.bookings.to_date') }}</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('client.bookings.search') }}</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="{{ __('client.bookings.search_placeholder') }}"
                               class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="lg:col-span-4 flex flex-col sm:flex-row gap-2">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors">
                        <i class="fas fa-filter ml-2"></i>{{ __('client.bookings.apply_filters') }}
                    </button>
                    <a href="{{ route('client.bookings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors text-center">
                        <i class="fas fa-redo ml-2"></i>{{ __('client.bookings.reset_filters') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.bookings.total_bookings') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.bookings.confirmed_bookings') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->where('is_confirmed', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.bookings.pending_bookings') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->where('is_confirmed', false)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-money-bill text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.bookings.total_amount') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($bookings->sum('total_amount'), 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('client.bookings.my_bookings') }}</h2>
            </div>

            @if($bookings->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <!-- Booking Info -->
                                <div class="flex-1">
                                    <div class="flex items-start space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            @if($booking->product && $booking->product->gallery->count() > 0)
                                                <img src="{{ asset('storage/' . $booking->product->gallery->first()) }}" 
                                                     alt="{{ $booking->product->name }}" 
                                                     class="w-16 h-16 rounded-lg object-cover">
                                            @elseif($booking->product && $booking->product->main_image)
                                                <img src="{{ asset('storage/' . $booking->product->main_image) }}" 
                                                     alt="{{ $booking->product->name }}" 
                                                     class="w-16 h-16 rounded-lg object-cover">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-home text-gray-400 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Booking Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ $booking->product->title ?? $booking->product->name ?? __('client.bookings.unknown_product') }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($booking->status == 'reserved') bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $booking->status ?? __('client.bookings.pending') }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 mb-1">
                                                <i class="fas fa-building ml-1"></i>
                                                {{ $booking->facility->name ?? __('client.bookings.unknown_facility') }}
                                            </p>
                                            
                                            <p class="text-sm text-gray-600 mb-1">
                                                <i class="fas fa-calendar ml-1"></i>
                                                {{ $booking->created_at->format('M d, Y') }}
                                            </p>
                                            
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-money-bill ml-1"></i>
                                                {{ number_format($booking->total_amount, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Indicators -->
                                <div class="mt-4 lg:mt-0 lg:ml-6">
                                    <div class="flex flex-col space-y-2">
                                        <!-- Payment Status -->
                                        <div class="flex items-center space-x-2">
                                            @if($booking->is_paid)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle ml-1"></i>
                                                    {{ __('client.bookings.paid') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle ml-1"></i>
                                                    {{ __('client.bookings.unpaid') }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Confirmation Status -->
                                        <div class="flex items-center space-x-2">
                                            @if($booking->is_confirmed)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle ml-1"></i>
                                                    {{ __('client.bookings.confirmed') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock ml-1"></i>
                                                    {{ __('client.bookings.pending_confirmation') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 lg:mt-0 lg:ml-6">
                                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                        <a href="{{ route('client.bookings.show', $booking) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-eye ml-1"></i>
                                            {{ __('client.bookings.view_details') }}
                                        </a>

                                        @if(!$booking->is_confirmed && $booking->status != 'cancelled')
                                            <form action="{{ route('client.bookings.cancel', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                        onclick="return confirm('{{ __('client.bookings.cancel_confirmation') }}')">
                                                    <i class="fas fa-times ml-1"></i>
                                                    {{ __('client.bookings.cancel') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('client.bookings.no_bookings') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('client.bookings.no_bookings_description') }}</p>
                    <a href="{{ route('client.bookings.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center">
                        <i class="fas fa-plus ml-2"></i>
                        {{ __('client.bookings.create_first_booking') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterForm = document.querySelector('form[method="GET"]');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"], input[type="text"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Add a small delay to allow for multiple rapid changes
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    });
});
</script>
@endpush
