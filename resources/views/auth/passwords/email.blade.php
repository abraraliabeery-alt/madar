@extends('layouts.app')

@section('title', __('auth.passwords.email.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-violet-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">{{ __('auth.passwords.email.forgot_password') }}</h2>
            <p class="text-gray-600 mt-2">{{ __('auth.passwords.email.enter_email_reset') }}</p>
        </div>

        <!-- Reset Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-500 mt-1 ml-2"></i>
                        <p class="text-green-700 text-sm">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('auth.passwords.email.email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus
                            class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                            placeholder="{{ __('auth.passwords.email.email_placeholder') }}"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-500 to-violet-600 text-white py-3 rounded-lg font-medium text-lg hover:shadow-lg transition-all duration-300"
                >
                    <i class="fas fa-paper-plane ml-2"></i>
                    {{ __('auth.passwords.email.send_reset_link') }}
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                    <i class="fas fa-arrow-right ml-2"></i>
                    {{ __('auth.passwords.email.back_to_login') }}
                </a>
            </div>
        </div>

        <!-- Help Text -->
        <div class="mt-6 text-center">
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-purple-900 mb-2">{{ __('auth.passwords.email.important_tips') }}</h3>
                <ul class="text-sm text-purple-700 space-y-1 text-right">
                    <li>• {{ __('auth.passwords.email.tips.correct_email') }}</li>
                    <li>• {{ __('auth.passwords.email.tips.check_spam') }}</li>
                    <li>• {{ __('auth.passwords.email.tips.link_valid') }}</li>
                </ul>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('auth.passwords.email.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
