@extends('layouts.app')

@section('title', __('client.notification_settings.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('client.notification_settings.title') }}</h1>
            <p class="text-gray-600">{{ __('client.notification_settings.subtitle') }}</p>
        </div>

        <!-- Settings Form -->
        <div class="bg-white rounded-lg shadow-sm">
            <form method="POST" action="{{ route('client.notifications.settings.update') }}">
                @csrf

                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('client.notification_settings.title') }}</h2>

                    <!-- Email Notifications -->
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.email_notifications') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('client.notification_settings.email_notifications_desc') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="notification_email" id="notification_email"
                                   value="1" {{ auth()->user()->notification_email ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        </div>
                    </div>

                    <!-- SMS Notifications -->
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.sms_notifications') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('client.notification_settings.sms_notifications_desc') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="notification_sms" id="notification_sms"
                                   value="1" {{ auth()->user()->notification_sms ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        </div>
                    </div>

                    <!-- Push Notifications -->
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.push_notifications') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('client.notification_settings.push_notifications_desc') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="notification_push" id="notification_push"
                                   value="1" {{ auth()->user()->notification_push ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        </div>
                    </div>
                </div>

                <!-- Notification Types -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('client.notification_settings.notification_types') }}</h2>

                    <!-- Booking Notifications -->
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.booking_notifications') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('client.notification_settings.booking_notifications_desc') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="booking_notifications" id="booking_notifications"
                                   value="1" checked disabled
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        </div>
                    </div>

                    <!-- Product Notifications -->
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.product_notifications') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('client.notification_settings.product_notifications_desc') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="product_notifications" id="product_notifications"
                                   value="1" checked disabled
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        </div>
                    </div>

                    <!-- Status Change Notifications -->
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.status_notifications') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('client.notification_settings.status_notifications_desc') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="status_notifications" id="status_notifications"
                                   value="1" checked disabled
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        </div>
                    </div>
                </div>

                <!-- Frequency Settings -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('client.notification_settings.frequency_settings') }}</h2>

                    <div class="space-y-4">
                        <div>
                            <label for="notification_frequency" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('client.notification_settings.frequency_label') }}
                            </label>
                            <select name="notification_frequency" id="notification_frequency"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="immediate" selected>{{ __('client.notification_settings.frequency.immediate') }}</option>
                                <option value="hourly">{{ __('client.notification_settings.frequency.hourly') }}</option>
                                <option value="daily">{{ __('client.notification_settings.frequency.daily') }}</option>
                                <option value="weekly">{{ __('client.notification_settings.frequency.weekly') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('client.notifications') }}" class="text-sm text-gray-600 hover:text-gray-700">
                            {{ __('client.notification_settings.back_to_notifications') }}
                        </a>
                        <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                            {{ __('client.notification_settings.save_settings') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Notification Preview -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('client.notification_settings.preview_title') }}</h2>

            <div class="space-y-4">
                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.preview.booking_created') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('client.notification_settings.preview.5_minutes_ago') }}</p>
                    </div>
                </div>

                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-home text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.preview.new_product_added') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('client.notification_settings.preview.1_hour_ago') }}</p>
                    </div>
                </div>

                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-sync-alt text-yellow-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ __('client.notification_settings.preview.status_changed') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('client.notification_settings.preview.3_hours_ago') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
