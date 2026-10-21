@extends('client.layouts.app')

@section('title', __('client.change_password'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.change_password') }}</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('client.change-password.update') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.current_password') }}</label>
                    <input type="password" name="current_password" required class="w-full rounded-md border-gray-300">
                    @error('current_password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.new_password') }}</label>
                    <input type="password" name="password" required minlength="8" class="w-full rounded-md border-gray-300">
                    @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required class="w-full rounded-md border-gray-300">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-md py-2 hover:bg-blue-700">{{ __('client.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
