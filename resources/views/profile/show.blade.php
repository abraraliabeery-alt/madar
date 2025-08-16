@extends('layouts.app')

@section('title', __('profile.title'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 space-x-reverse">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&color=7C3AED&background=EBF4FF&size=120' }}"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-white shadow-lg">
                </div>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 space-x-reverse mb-2">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $user->name }}</h1>

                        <!-- User Type Badge -->
                        @if($primaryRole)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $primaryRole === 'admin' ? 'bg-red-100 text-red-800' :
                                   ($primaryRole === 'facility' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ $primaryRole === 'admin' ? __('profile.admin') :
                                   ($primaryRole === 'facility' ? __('profile.facility') : __('profile.client')) }}
                            </span>
                        @endif
                    </div>

                    <!-- Contact Info -->
                    <div class="flex flex-wrap items-center space-x-4 space-x-reverse text-sm text-gray-600">
                        @if($user->email)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                {{ $user->email }}
                            </div>
                        @endif

                        @if($user->phone_number)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                {{ $user->phone_number }}
                            </div>
                        @endif

                        @if($user->whatsapp_number)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                {{ $user->whatsapp_number }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex-shrink-0">
                    <div class="flex space-x-3 space-x-reverse">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            {{ __('profile.edit_profile') }}
                        </a>

                        <a href="{{ route('profile.public', $user->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('profile.view_public') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- About Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('profile.general_info') }}</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('profile.join_date') }}</span>
                            <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>

                        @if($user->email_verified_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('profile.account_status') }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('profile.verified') }}
                                </span>
                            </div>
                        @endif

                        @if($user->is_active !== null)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('profile.activity_status') }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? __('profile.active') : __('profile.inactive') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Role-specific Information -->
                @if($primaryRole === 'facility')
                    <!-- Facility Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('profile.facility_info') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->bank_account)
                                <div>
                                    <span class="text-gray-600">{{ __('profile.bank_account') }}:</span>
                                    <span class="block font-medium">{{ $user->bank_account }}</span>
                                </div>
                            @endif

                            @if($user->bank)
                                <div>
                                    <span class="text-gray-600">{{ __('profile.bank') }}:</span>
                                    <span class="block font-medium">{{ $user->bank->name ?? 'Bank ' . $user->bank->id }}</span>
                                </div>
                            @endif

                            @if($user->latitude && $user->longitude)
                                <div class="md:col-span-2">
                                    <span class="text-gray-600">{{ __('profile.coordinates') }}</span>
                                    <span class="block font-medium">{{ $user->latitude }}, {{ $user->longitude }}</span>
                                </div>
                            @endif

                            @if($user->google_maps_url)
                                <div class="md:col-span-2">
                                    <span class="text-gray-600">{{ __('profile.map_link') }}</span>
                                    <a href="{{ $user->google_maps_url }}" target="_blank" class="block font-medium text-blue-600 hover:text-blue-800">
                                        {{ $user->google_maps_url }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($primaryRole === 'admin')
                    <!-- Admin Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('profile.admin_info') }}</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('profile.permissions') }}</span>
                                <span class="font-medium">{{ __('profile.admin') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('profile.manages') }}</span>
                                <span class="font-medium">{{ __('profile.all_system_parts') }}</span>
                            </div>

                            @if($user->notification_email !== null || $user->notification_sms !== null || $user->notification_push !== null)
                                <div class="pt-3 border-t">
                                    <h4 class="font-medium text-gray-900 mb-2">{{ __('profile.notification_settings') }}:</h4>
                                    <div class="space-y-2">
                                        @if($user->notification_email !== null)
                                            <div class="flex items-center">
                                                <span class="text-gray-600 text-sm">{{ __('profile.email_notifications') }}:</span>
                                                <span class="mr-auto text-sm font-medium {{ $user->notification_email ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $user->notification_email ? __('profile.active') : __('profile.inactive') }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($user->notification_sms !== null)
                                            <div class="flex items-center">
                                                <span class="text-gray-600 text-sm">{{ __('profile.sms_notifications') }}:</span>
                                                <span class="mr-auto text-sm font-medium {{ $user->notification_sms ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $user->notification_sms ? __('profile.active') : __('profile.inactive') }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($user->notification_push !== null)
                                            <div class="flex items-center">
                                                <span class="text-gray-600 text-sm">{{ __('profile.push_notifications') }}:</span>
                                                <span class="mr-auto text-sm font-medium {{ $user->notification_push ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $user->notification_push ? __('profile.active') : __('profile.inactive') }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($user->notification_frequency)
                                            <div class="flex items-center">
                                                <span class="text-gray-600 text-sm">{{ __('profile.notification_frequency') }}:</span>
                                                <span class="mr-auto text-sm font-medium">
                                                    {{ $user->notification_frequency === 'daily' ? __('profile.daily') : ($user->notification_frequency === 'weekly' ? __('profile.weekly') : __('profile.monthly')) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Social Media Section -->
                @if($user->facebook || $user->twitter || $user->instagram || $user->linkedin || $user->whatsapp_number)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('profile.social_media') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->facebook)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <a href="{{ $user->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $user->facebook }}</a>
                                </div>
                            @endif

                            @if($user->twitter)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    <a href="{{ $user->twitter }}" target="_blank" class="text-blue-400 hover:text-blue-600">{{ $user->twitter }}</a>
                                </div>
                            @endif

                            @if($user->instagram)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.323c-.875.807-2.026 1.297-3.323 1.297zm7.718-1.297c-.875.807-2.026 1.297-3.323 1.297s-2.448-.49-3.323-1.297c-.928-.875-1.418-2.026-1.418-3.323s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.323z"/>
                                    </svg>
                                    <a href="{{ $user->instagram }}" target="_blank" class="text-pink-600 hover:text-pink-800">{{ $user->instagram }}</a>
                                </div>
                            @endif

                            @if($user->linkedin)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    <a href="{{ $user->linkedin }}" target="_blank" class="text-blue-700 hover:text-blue-900">{{ $user->linkedin }}</a>
                                </div>
                            @endif

                            @if($user->whatsapp_number)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    <span class="text-green-600">{{ $user->whatsapp_number }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('profile.quick_actions') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('profile.edit') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            {{ __('profile.edit_profile_btn') }}
                        </a>

                        <a href="{{ route('profile.change-password') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('profile.change_password_btn') }}
                        </a>

                        <a href="{{ route('profile.public', $user->id) }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('profile.view_public_profile') }}
                        </a>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('profile.account_status_title') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ __('profile.email_status') }}</span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('profile.verified') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('profile.unverified') }}
                                </span>
                            @endif
                        </div>

                        @if($user->is_active !== null)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('profile.activity_status') }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? __('profile.active') : __('profile.inactive') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ __('profile.join_date') }}</span>
                            <span class="text-sm font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
