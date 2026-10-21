@extends('client.layouts.app')

@section('title', __('client.new_ticket'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.new_ticket') }}</h1>
            <a href="{{ route('client.help.tickets') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('client.help.tickets.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.subject') }}</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full rounded-md border-gray-300">
                    @error('subject')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.priority') }}</label>
                    <select name="priority" class="w-full rounded-md border-gray-300">
                        <option value="low">{{ __('client.priority_low') }}</option>
                        <option value="normal" selected>{{ __('client.priority_normal') }}</option>
                        <option value="high">{{ __('client.priority_high') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.message') }}</label>
                    <textarea name="message" rows="5" required class="w-full rounded-md border-gray-300">{{ old('message') }}</textarea>
                    @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-md py-2 hover:bg-blue-700">{{ __('client.submit_ticket') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
