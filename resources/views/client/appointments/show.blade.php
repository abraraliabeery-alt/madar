@extends('layouts.app')

@section('title', __('client.appointments.appointment_details'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold">{{ __('client.appointments.appointment_details') }}</h1>
            <p class="text-primary-100 mt-2">{{ __('client.appointments.appointment_with') }} {{ $appointment->facility->name }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Appointment Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">{{ __('client.appointments.appointment_info') }}</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                            @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                            @elseif($appointment->status === 'rescheduled') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ __('client.appointments.status_' . $appointment->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.date') }}</p>
                            <p class="text-lg text-gray-900">{{ $appointment->appointment_time->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.time') }}</p>
                            <p class="text-lg text-gray-900">{{ $appointment->appointment_time->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.created_at') }}</p>
                            <p class="text-lg text-gray-900">{{ $appointment->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.updated_at') }}</p>
                            <p class="text-lg text-gray-900">{{ $appointment->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    @if($appointment->subject)
                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-500 mb-2">{{ __('client.appointments.subject') }}</p>
                            <p class="text-gray-900">{{ $appointment->subject }}</p>
                        </div>
                    @endif

                    @if($appointment->notes)
                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-500 mb-2">{{ __('client.appointments.notes') }}</p>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $appointment->notes }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-8 flex flex-wrap gap-3">
                        @if($appointment->status === 'scheduled')
                            <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                        onclick="return confirm('{{ __('client.appointments.confirm_cancel') }}')">
                                    <i class="fas fa-times ml-1"></i>
                                    {{ __('client.appointments.cancel') }}
                                </button>
                            </form>
                            
                            <button onclick="openRescheduleModal()" 
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-calendar-alt ml-1"></i>
                                {{ __('client.appointments.reschedule') }}
                            </button>
                        @endif
                        
                        <a href="{{ route('client.appointments') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-right ml-1"></i>
                            {{ __('client.appointments.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Facility Information -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('client.appointments.facility_info') }}</h3>
                    
                    <div class="flex items-center space-x-3 space-x-reverse mb-4">
                        <img src="{{ $appointment->facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=100&q=80' }}" 
                             class="w-16 h-16 rounded object-cover" 
                             alt="{{ $appointment->facility->name }}">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $appointment->facility->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $appointment->facility->category->name ?? '' }}</p>
                        </div>
                    </div>

                    @if($appointment->facility->address)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.address') }}</p>
                            <p class="text-sm text-gray-900">{{ $appointment->facility->address }}</p>
                        </div>
                    @endif

                    @if($appointment->facility->phone)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.phone') }}</p>
                            <a href="tel:{{ $appointment->facility->phone }}" class="text-sm text-primary-600 hover:text-primary-700">
                                {{ $appointment->facility->phone }}
                            </a>
                        </div>
                    @endif

                    @if($appointment->facility->email)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('client.appointments.email') }}</p>
                            <a href="mailto:{{ $appointment->facility->email }}" class="text-sm text-primary-600 hover:text-primary-700">
                                {{ $appointment->facility->email }}
                            </a>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('public.facilities.show', $appointment->facility) }}" 
                           class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                            <i class="fas fa-external-link-alt ml-1"></i>
                            {{ __('client.appointments.view_facility') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('client.appointments.reschedule_appointment') }}</h3>
            
            <form action="{{ route('client.appointments.reschedule', $appointment) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="new_appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('client.appointments.new_appointment_time') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="appointment_time" id="new_appointment_time" required 
                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <div class="mb-4">
                    <label for="reschedule_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('client.appointments.reschedule_notes') }}
                    </label>
                    <textarea name="notes" id="reschedule_notes" rows="3" 
                              placeholder="{{ __('client.appointments.reschedule_notes_placeholder') }}"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeRescheduleModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        {{ __('client.appointments.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md">
                        {{ __('client.appointments.reschedule') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRescheduleModal() {
    document.getElementById('rescheduleModal').classList.remove('hidden');
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().slice(0, 16);
    document.getElementById('new_appointment_time').min = minDate;
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.add('hidden');
}
</script>
@endsection




