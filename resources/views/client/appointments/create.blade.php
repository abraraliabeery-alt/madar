@extends('layouts.app')

@section('title', __('client.appointments.create_title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold">{{ __('client.appointments.create_title') }}</h1>
            <p class="text-primary-100 mt-2">{{ __('client.appointments.create_subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('client.appointments.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('client.appointments.facility') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="facility_id" id="facility_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 @error('facility_id') border-red-500 @enderror">
                            <option value="">{{ __('client.appointments.select_facility') }}</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }} - {{ $facility->category->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('facility_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('client.appointments.appointment_time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="appointment_time" id="appointment_time" required 
                               value="{{ old('appointment_time') }}"
                               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 @error('appointment_time') border-red-500 @enderror">
                        @error('appointment_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('client.appointments.subject') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" required 
                               value="{{ old('subject') }}"
                               placeholder="{{ __('client.appointments.subject_placeholder') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 @error('subject') border-red-500 @enderror">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('client.appointments.notes') }}
                    </label>
                    <textarea name="notes" id="notes" rows="4" 
                              placeholder="{{ __('client.appointments.notes_placeholder') }}"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse">
                    <a href="{{ route('client.appointments') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ __('client.appointments.cancel') }}
                    </a>
                    <button type="submit" 
                            class="btn-primary text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-calendar-plus ml-2"></i>
                        {{ __('client.appointments.book_appointment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().slice(0, 16);
    document.getElementById('appointment_time').min = minDate;
});
</script>
@endsection


