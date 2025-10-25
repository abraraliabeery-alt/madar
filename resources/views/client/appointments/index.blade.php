@extends('layouts.app')

@section('title', __('client.appointments.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold">{{ __('client.appointments.title') }}</h1>
            <p class="text-primary-100 mt-2">{{ __('client.appointments.subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('client.appointments.my_appointments') }}</h2>
            <a href="{{ route('client.appointments.create') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                <i class="fas fa-plus ml-2"></i>
                {{ __('client.appointments.book_new') }}
            </a>
        </div>

        @if($appointments->count() > 0)
            <div class="grid gap-6">
                @foreach($appointments as $appointment)
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 
                        @if($appointment->status === 'scheduled') border-blue-500
                        @elseif($appointment->status === 'completed') border-green-500
                        @elseif($appointment->status === 'cancelled') border-red-500
                        @elseif($appointment->status === 'rescheduled') border-yellow-500
                        @else border-gray-500 @endif">
                        
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 space-x-reverse mb-2">
                                    <img src="{{ $appointment->facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=50&q=80' }}" 
                                         class="w-12 h-12 rounded object-cover" 
                                         alt="{{ $appointment->facility->name }}">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $appointment->facility->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $appointment->facility->category->name ?? '' }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('client.appointments.date') }}</p>
                                        <p class="text-sm text-gray-900">{{ $appointment->appointment_time->format('Y-m-d') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('client.appointments.time') }}</p>
                                        <p class="text-sm text-gray-900">{{ $appointment->appointment_time->format('H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('client.appointments.status') }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                            @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($appointment->status === 'rescheduled') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ __('client.appointments.status_' . $appointment->status) }}
                                        </span>
                                    </div>
                                </div>

                                @if($appointment->notes)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('client.appointments.notes') }}</p>
                                        <p class="text-sm text-gray-900">{{ $appointment->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col space-y-2 space-y-reverse">
                                <a href="{{ route('client.appointments.show', $appointment) }}" 
                                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ __('client.appointments.view_details') }}
                                </a>
                                
                                @if($appointment->status === 'scheduled')
                                    <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-700 text-sm font-medium"
                                                onclick="return confirm('{{ __('client.appointments.confirm_cancel') }}')">
                                            {{ __('client.appointments.cancel') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <i class="fas fa-calendar-alt text-6xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('client.appointments.no_appointments') }}</h3>
                <p class="mt-2 text-gray-500">{{ __('client.appointments.no_appointments_description') }}</p>
                <div class="mt-6">
                    <a href="{{ route('client.appointments.create') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus ml-2"></i>
                        {{ __('client.appointments.book_first') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection




