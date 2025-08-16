@extends('layouts.app')

@section('title', __('client.dashboard.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('client.dashboard.welcome', ['name' => auth()->user()->name]) }}</h1>
            <p class="text-gray-600">{{ __('client.dashboard.subtitle', ['default' => 'Here\'s what\'s happening with your account.']) }}</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Bookings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.dashboard.total_bookings') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Contracts -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-file-contract text-green-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.dashboard.total_contracts') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_contracts'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Appointments -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.dashboard.total_appointments') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Favorite Products -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-heart text-red-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('client.dashboard.favorite_products') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['favorite_products'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('client.dashboard.quick_actions') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('client.favorites') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-heart text-red-500 ml-3"></i>
                    <span class="text-gray-700">{{ __('client.navigation.favorites') }}</span>
                </a>
                
                <a href="{{ route('client.bookings.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-calendar-check text-blue-500 ml-3"></i>
                    <span class="text-gray-700">{{ __('client.navigation.bookings') }}</span>
                </a>
                
                <a href="{{ route('client.appointments') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-clock text-purple-500 ml-3"></i>
                    <span class="text-gray-700">{{ __('client.navigation.appointments') }}</span>
                </a>
                
                <a href="{{ route('client.notifications') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bell text-yellow-500 ml-3"></i>
                    <span class="text-gray-700">{{ __('client.navigation.notifications') }}</span>
                </a>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('client.dashboard.my_bookings') }}</h2>
                    <a href="{{ route('client.bookings.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ __('client.actions.view') }} {{ __('client.actions.all') }}
                    </a>
                </div>
                <div class="p-6">
                    @if($stats['recent_bookings']->count() > 0)
                        <div class="space-y-4">
                            @foreach($stats['recent_bookings'] as $booking)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center ml-3">
                                            <i class="fas fa-home text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $booking->product->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-600">{{ $booking->facility->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">{{ $booking->created_at->format('M d') }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   @if($booking->status_id == 1) bg-yellow-100 text-yellow-800
                                                   @elseif($booking->status_id == 2) bg-green-100 text-green-800
                                                   @else bg-gray-100 text-gray-800 @endif">
                                            {{ $booking->status->name ?? 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-600">{{ __('client.bookings.no_bookings') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('client.dashboard.my_appointments') }}</h2>
                    <a href="{{ route('client.appointments') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ __('client.actions.view') }} {{ __('client.actions.all') }}
                    </a>
                </div>
                <div class="p-6">
                    @if($stats['recent_appointments']->count() > 0)
                        <div class="space-y-4">
                            @foreach($stats['recent_appointments'] as $appointment)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center ml-3">
                                            <i class="fas fa-clock text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $appointment->facility->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">{{ $appointment->created_at->format('M d') }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ __('client.status.pending') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-clock text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-600">{{ __('client.appointments.no_appointments') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Actions -->
        @if($stats['pending_bookings'] > 0 || $stats['active_contracts'] > 0)
            <div class="mt-8 bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('client.dashboard.pending_actions', ['default' => 'Pending Actions']) }}</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($stats['pending_bookings'] > 0)
                            <div class="flex items-center p-4 bg-yellow-50 rounded-lg">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center ml-3">
                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $stats['pending_bookings'] }} {{ __('client.dashboard.pending_bookings_text', ['default' => 'pending bookings']) }}</p>
                                    <p class="text-sm text-gray-600">{{ __('client.dashboard.pending_bookings_description', ['default' => 'Review and confirm your pending bookings']) }}</p>
                                </div>
                                <a href="{{ route('client.bookings.index') }}" class="mr-auto text-yellow-600 hover:text-yellow-700">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        @endif

                        @if($stats['active_contracts'] > 0)
                            <div class="flex items-center p-4 bg-green-50 rounded-lg">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center ml-3">
                                    <i class="fas fa-file-contract text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $stats['active_contracts'] }} {{ __('client.dashboard.active_contracts_text', ['default' => 'active contracts']) }}</p>
                                    <p class="text-sm text-gray-600">{{ __('client.dashboard.active_contracts_description', ['default' => 'Manage your active contracts']) }}</p>
                                </div>
                                <a href="{{ route('client.contracts') }}" class="mr-auto text-green-600 hover:text-green-700">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
