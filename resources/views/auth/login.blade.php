@extends('layouts.app')

@section('title', __('auth.login.title'))

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" style="background:var(--brand-bg);color:var(--brand-fg);">
    <div class="max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="text-2xl font-bold mb-4">{{ __('public.home.site_title') }}</div>
            <h2 class="text-3xl font-bold">{{ __('auth.login.welcome_back') }}</h2>
            <p class="mt-2" style="color:var(--brand-muted);">{{ __('auth.login.login_to_account') }}</p>
        </div>

        <!-- Login Form -->
        <div class="rounded-2xl shadow-xl p-8" style="background:var(--brand-bg);">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.login.email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope" style="color:var(--brand-muted);"></i>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.login.email_placeholder') }}"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.login.password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock" style="color:var(--brand-muted);"></i>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.login.password_placeholder') }}"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 rounded"
                            style="accent-color:var(--brand-brown);border:1px solid var(--brand-border);"
                        >
                        <label for="remember" class="mr-2 block text-sm" style="color:var(--brand-fg);">
                            {{ __('auth.login.remember_me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium" style="color:var(--brand-brown);">
                            {{ __('auth.login.forgot_password') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg font-medium text-lg hover:shadow-lg transition-all duration-300"
                    style="background:var(--brand-brown);color:var(--brand-bg);"
                >
                    <i class="fas fa-sign-in-alt ml-2"></i>
                    {{ __('auth.login.login_button') }}
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full" style="border-top:1px solid var(--brand-border);"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2" style="background:var(--brand-bg);color:var(--brand-muted);">{{ __('auth.login.or') }}</span>
                    </div>
                </div>
            </div>

            <!-- Social Login -->
            <div class="space-y-3">
                <button class="w-full flex items-center justify-center px-4 py-3 rounded-lg transition-colors" style="border:1px solid var(--brand-border);color:var(--brand-fg);">
                    <i class="fab fa-google text-red-500 ml-3"></i>
                    {{ __('auth.login.login_with_google') }}
                </button>

                <button class="w-full flex items-center justify-center px-4 py-3 rounded-lg transition-colors" style="border:1px solid var(--brand-border);color:var(--brand-fg);">
                    <i class="fab fa-facebook text-blue-600 ml-3"></i>
                    {{ __('auth.login.login_with_facebook') }}
                </button>
            </div>

            <!-- Register Link -->
            <div class="mt-8 text-center">
                <p style="color:var(--brand-muted);">
                    {{ __('auth.login.no_account') }}
                    <a href="{{ route('register') }}" class="font-medium" style="color:var(--brand-brown);">
                        {{ __('auth.login.create_account') }}
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="font-medium" style="color:var(--brand-muted);">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('auth.login.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
