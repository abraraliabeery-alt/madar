@extends('layouts.app')

@section('title', __('auth.register.title'))

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" style="background:var(--brand-bg);color:var(--brand-fg);">
    <div class="max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:var(--brand-brown);">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold">{{ __('auth.register.title') }}</h2>
            <p class="mt-2" style="color:var(--brand-muted);">{{ __('auth.register.join_us') }}</p>
        </div>

        <!-- Register Form -->
        <div class="rounded-2xl shadow-xl p-8" style="background:var(--brand-bg);">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.register.full_name') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-user" style="color:var(--brand-muted);"></i>
                        </div>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            autofocus
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.register.full_name_placeholder') }}"
                        >
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.register.email') }}
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
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.register.email_placeholder') }}"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.register.phone') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone" style="color:var(--brand-muted);"></i>
                        </div>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            autocomplete="tel"
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.register.phone_placeholder') }}"
                        >
                    </div>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Type -->
                <div class="mb-6">
                    <label for="primary_role" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.register.account_type') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-users" style="color:var(--brand-muted);"></i>
                        </div>
                        <select
                            id="primary_role"
                            name="primary_role"
                            required
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                        >
                            <option value="">{{ __('auth.register.select_account_type') }}</option>
                            <option value="client" {{ old('primary_role') == 'client' ? 'selected' : '' }}>{{ __('auth.register.client') }}</option>
                            <option value="facility" {{ old('primary_role') == 'facility' ? 'selected' : '' }}>{{ __('auth.register.facility') }}</option>
                        </select>
                    </div>
                    @error('primary_role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.register.password') }}
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
                            autocomplete="new-password"
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.register.password_placeholder') }}"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color:var(--brand-fg);">
                        {{ __('auth.register.confirm_password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock" style="color:var(--brand-muted);"></i>
                        </div>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full pr-10 pl-4 py-3 rounded-lg transition-colors"
                            style="background:var(--brand-bg);color:var(--brand-fg);border:1px solid var(--brand-border);"
                            placeholder="{{ __('auth.register.confirm_password_placeholder') }}"
                        >
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <input
                            id="terms"
                            name="terms"
                            type="checkbox"
                            required
                            class="h-4 w-4 rounded mt-1"
                            style="accent-color:var(--brand-brown);border:1px solid var(--brand-border);"
                        >
                        <label for="terms" class="mr-2 block text-sm" style="color:var(--brand-fg);">
                            {{ __('auth.register.terms_agreement') }}
                            <a href="#" class="font-medium" style="color:var(--brand-brown);">{{ __('auth.register.terms_conditions') }}</a>
                            {{ __('auth.login.or') }}
                            <a href="#" class="font-medium" style="color:var(--brand-brown);">{{ __('auth.register.privacy_policy') }}</a>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg font-medium text-lg hover:shadow-lg transition-all duration-300"
                    style="background:var(--brand-brown);color:var(--brand-bg);"
                >
                    <i class="fas fa-user-plus ml-2"></i>
                    {{ __('auth.register.create_account_button') }}
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full" style="border-top:1px solid var(--brand-border);"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2" style="background:var(--brand-bg);color:var(--brand-muted);">{{ __('auth.register.or') }}</span>
                    </div>
                </div>
            </div>

            <!-- Social Register -->
            <div class="space-y-3">
                <button class="w-full flex items-center justify-center px-4 py-3 rounded-lg transition-colors" style="border:1px solid var(--brand-border);color:var(--brand-fg);">
                    <i class="fab fa-google text-red-500 ml-3"></i>
                    {{ __('auth.register.register_with_google') }}
                </button>

                <button class="w-full flex items-center justify-center px-4 py-3 rounded-lg transition-colors" style="border:1px solid var(--brand-border);color:var(--brand-fg);">
                    <i class="fab fa-facebook text-blue-600 ml-3"></i>
                    {{ __('auth.register.register_with_facebook') }}
                </button>
            </div>

            <!-- Login Link -->
            <div class="mt-8 text-center">
                <p style="color:var(--brand-muted);">
                    {{ __('auth.register.have_account') }}
                    <a href="{{ route('login') }}" class="font-medium" style="color:var(--brand-brown);">
                        {{ __('auth.register.sign_in') }}
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="font-medium" style="color:var(--brand-muted);">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('auth.register.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
