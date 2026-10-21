@extends('client.layouts.app')

@section('title', __('client.settings'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.settings') }}</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4">
            <a href="{{ route('client.profile') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.profile') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.profile_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
            <a href="{{ route('client.change-password') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.change_password') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.change_password_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
            <a href="{{ route('client.settings.privacy') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.privacy_settings') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.privacy_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
            <a href="{{ route('client.settings.security') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.security_settings') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.security_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
            <a href="{{ route('client.notifications.settings') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.notification_settings') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.notification_settings_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
        </div>
    </div>
</div>
@endsection
