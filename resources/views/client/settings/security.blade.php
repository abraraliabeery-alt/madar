@extends('client.layouts.app')

@section('title', __('client.security_settings'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.security_settings') }}</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.change_password') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.change_password_desc') }}</p>
                </div>
                <a href="{{ route('client.change-password') }}" class="bg-blue-600 text-white rounded-md px-4 py-2 text-sm hover:bg-blue-700">{{ __('client.change') }}</a>
            </div>
            <hr>
            <div>
                <p class="font-semibold text-gray-900 mb-1">{{ __('client.account_email') }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-900 mb-1">{{ __('client.account_phone') }}</p>
                <p class="text-sm text-gray-500" dir="ltr">{{ $user->phone_number ?? $user->phone }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
