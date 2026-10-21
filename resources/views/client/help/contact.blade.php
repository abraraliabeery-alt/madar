@extends('client.layouts.app')

@section('title', __('client.contact_us'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.contact_us') }}</h1>
            <a href="{{ route('client.help') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('client.help.contact.send') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.subject') }}</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full rounded-md border-gray-300">
                    @error('subject')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.message') }}</label>
                    <textarea name="message" rows="5" required class="w-full rounded-md border-gray-300">{{ old('message') }}</textarea>
                    @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-md py-2 hover:bg-blue-700">{{ __('client.send') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
