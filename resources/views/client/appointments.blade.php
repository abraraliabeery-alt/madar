@extends('client.layouts.app')

@section('title', 'ظ…ظˆط§ط¹ظٹط¯ظٹ - ظ…ظ†ط·ظ‚ط© ط§ظ„ط¹ظ…ظٹظ„')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold">ظ…ظˆط§ط¹ظٹط¯ظٹ</h1>
                    <p class="text-primary-100 mt-2">ط¥ط¯ط§ط±ط© ظ…ظˆط§ط¹ظٹط¯ظƒ ظ…ط¹ ط§ظ„ظ…ط¤ط³ط³ط§طھ ط§ظ„ظ…ط´ط§ط±ظٹط¹ظٹط©</p>
                </div>
                <a href="{{ route('client.appointments.create') }}" 
                   class="bg-white text-primary-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-plus ml-2"></i>
                    ط­ط¬ط² ظ…ظˆط¹ط¯ ط¬ط¯ظٹط¯
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">ط¥ط¬ظ…ط§ظ„ظٹ ط§ظ„ظ…ظˆط§ط¹ظٹط¯</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $appointments->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">ظ…ظƒطھظ…ظ„ط©</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $appointments->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">ظ…ط¬ط¯ظˆظ„ط©</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $appointments->where('status', 'scheduled')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">ظ…ظ„ط؛ظٹط©</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $appointments->where('status', 'cancelled')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">ط¬ظ…ظٹط¹ ط§ظ„ط­ط§ظ„ط§طھ</option>
                        <option value="scheduled">ظ…ط¬ط¯ظˆظ„ط©</option>
                        <option value="completed">ظ…ظƒطھظ…ظ„ط©</option>
                        <option value="cancelled">ظ…ظ„ط؛ظٹط©</option>
                        <option value="rescheduled">ظ…ط¹ط¯ظ„ط©</option>
                    </select>
                    
                    <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">ط¬ظ…ظٹط¹ ط§ظ„ظ…ط¤ط³ط³ط§طھ</option>
                        <!-- Dynamic options will be loaded here -->
                    </select>
                </div>
                
                <div class="flex space-x-2 space-x-reverse">
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition duration-200">
                        <i class="fas fa-filter ml-2"></i>
                        ظپظ„طھط±
                    </button>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-200">
                        <i class="fas fa-download ml-2"></i>
                        طھطµط¯ظٹط±
                    </button>
                </div>
            </div>
        </div>

        <!-- Appointments List -->
        @if($appointments->count() > 0)
            <div class="space-y-6">
                @foreach($appointments as $appointment)
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 
                        @if($appointment->status === 'scheduled') border-blue-500
                        @elseif($appointment->status === 'completed') border-green-500
                        @elseif($appointment->status === 'cancelled') border-red-500
                        @elseif($appointment->status === 'rescheduled') border-yellow-500
                        @else border-gray-500 @endif">
                        
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex-1">
                                <!-- Facility Info -->
                                <div class="flex items-center space-x-4 space-x-reverse mb-4">
                                    <img src="{{ $appointment->facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=50&q=80' }}" 
                                         class="w-16 h-16 rounded-lg object-cover" 
                                         alt="{{ $appointment->facility->name }}">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $appointment->facility->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $appointment->facility->category->name ?? '' }}</p>
                                        @if($appointment->facility->address)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt ml-1"></i>
                                                {{ $appointment->facility->address }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Appointment Details -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <i class="fas fa-calendar text-primary-600"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">ط§ظ„طھط§ط±ظٹط®</p>
                                                <p class="text-lg font-semibold text-gray-900">
                                                    {{ $appointment->appointment_time->format('Y/m/d') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <i class="fas fa-clock text-primary-600"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">ط§ظ„ظˆظ‚طھ</p>
                                                <p class="text-lg font-semibold text-gray-900">
                                                    {{ $appointment->appointment_time->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <i class="fas fa-info-circle text-primary-600"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">ط§ظ„ط­ط§ظ„ط©</p>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                                    @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                    @elseif($appointment->status === 'rescheduled') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @if($appointment->status === 'scheduled')
                                                        <i class="fas fa-clock ml-1"></i>
                                                        ظ…ط¬ط¯ظˆظ„ط©
                                                    @elseif($appointment->status === 'completed')
                                                        <i class="fas fa-check-circle ml-1"></i>
                                                        ظ…ظƒطھظ…ظ„ط©
                                                    @elseif($appointment->status === 'cancelled')
                                                        <i class="fas fa-times-circle ml-1"></i>
                                                        ظ…ظ„ط؛ظٹط©
                                                    @elseif($appointment->status === 'rescheduled')
                                                        <i class="fas fa-calendar-alt ml-1"></i>
                                                        ظ…ط¹ط¯ظ„ط©
                                                    @else
                                                        {{ $appointment->status }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($appointment->notes)
                                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                        <p class="text-sm font-medium text-blue-900 mb-1">ظ…ظ„ط§ط­ط¸ط§طھ:</p>
                                        <p class="text-sm text-blue-800">{{ $appointment->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col space-y-2 space-y-reverse">
                                <a href="{{ route('client.appointments.show', $appointment) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-200">
                                    <i class="fas fa-eye ml-2"></i>
                                    ط¹ط±ط¶ ط§ظ„طھظپط§طµظٹظ„
                                </a>
                                
                                @if($appointment->status === 'scheduled')
                                    <div class="flex space-x-2 space-x-reverse">
                                        <button onclick="rescheduleAppointment({{ $appointment->id }})" 
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-200 text-sm">
                                            <i class="fas fa-calendar-alt ml-1"></i>
                                            ط¥ط¹ط§ط¯ط© ط¬ط¯ظˆظ„ط©
                                        </button>
                                        
                                        <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 text-sm"
                                                    onclick="return confirm('ظ‡ظ„ ط£ظ†طھ ظ…طھط£ظƒط¯ ظ…ظ† ط¥ظ„ط؛ط§ط، ظ‡ط°ط§ ط§ظ„ظ…ظˆط¹ط¯طں')">
                                                <i class="fas fa-times ml-1"></i>
                                                ط¥ظ„ط؛ط§ط،
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $appointments->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <i class="fas fa-calendar-alt text-6xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">ظ„ط§ طھظˆط¬ط¯ ظ…ظˆط§ط¹ظٹط¯</h3>
                <p class="mt-2 text-gray-500">ظ„ظ… طھط­ط¬ط² ط£ظٹ ظ…ظˆط§ط¹ظٹط¯ ط¨ط¹ط¯. ط§ط¨ط¯ط£ ط¨ط­ط¬ط² ظ…ظˆط¹ط¯ ظ…ط¹ ط¥ط­ط¯ظ‰ ط§ظ„ظ…ط¤ط³ط³ط§طھ ط§ظ„ظ…ط´ط§ط±ظٹط¹ظٹط©.</p>
                <div class="mt-6">
                    <a href="{{ route('client.appointments.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition duration-200">
                        <i class="fas fa-plus ml-2"></i>
                        ط­ط¬ط² ط£ظˆظ„ ظ…ظˆط¹ط¯
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">ط¥ط¹ط§ط¯ط© ط¬ط¯ظˆظ„ط© ط§ظ„ظ…ظˆط¹ط¯</h3>
                <button onclick="closeRescheduleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="rescheduleForm" class="mt-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„طھط§ط±ظٹط® ط§ظ„ط¬ط¯ظٹط¯</label>
                        <input type="datetime-local" name="appointment_time" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ط³ط¨ط¨ ط¥ط¹ط§ط¯ط© ط§ظ„ط¬ط¯ظˆظ„ط©</label>
                        <textarea name="reason" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="ط§ظƒطھط¨ ط³ط¨ط¨ ط¥ط¹ط§ط¯ط© ط§ظ„ط¬ط¯ظˆظ„ط©..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeRescheduleModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        ط¥ظ„ط؛ط§ط،
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-200">
                        ط¥ط±ط³ط§ظ„ ط·ظ„ط¨ ط¥ط¹ط§ط¯ط© ط§ظ„ط¬ط¯ظˆظ„ط©
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function rescheduleAppointment(appointmentId) {
    document.getElementById('rescheduleModal').classList.remove('hidden');
    
    // Set minimum date to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.querySelector('input[name="appointment_time"]').min = now.toISOString().slice(0, 16);
    
    // Handle form submission
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitReschedule(appointmentId, this);
    });
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.add('hidden');
}

function submitReschedule(appointmentId, form) {
    const formData = new FormData(form);
    
    fetch(`/client/appointments/${appointmentId}/reschedule`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('طھظ… ط¥ط±ط³ط§ظ„ ط·ظ„ط¨ ط¥ط¹ط§ط¯ط© ط§ظ„ط¬ط¯ظˆظ„ط© ط¨ظ†ط¬ط§ط­');
            closeRescheduleModal();
            location.reload();
        } else {
            alert('ط­ط¯ط« ط®ط·ط£ ظپظٹ ط¥ط±ط³ط§ظ„ ط§ظ„ط·ظ„ط¨');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('ط­ط¯ط« ط®ط·ط£ ظپظٹ ط¥ط±ط³ط§ظ„ ط§ظ„ط·ظ„ط¨');
    });
}
</script>
@endpush

