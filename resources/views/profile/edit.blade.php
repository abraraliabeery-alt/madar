@extends('layouts.app')

@section('title', __('profile.edit_title'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('profile.edit_title') }}</h1>
                <p class="text-gray-600">{{ __('profile.update_personal_info') }}</p>

                @if($primaryRole)
                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <span class="mr-2">{{ __('profile.user_type') }}</span>
                        <span class="capitalize">{{ $primaryRole === 'admin' ? __('profile.admin') : ($primaryRole === 'facility' ? __('profile.facility') : __('profile.client')) }}</span>
                    </div>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.profile_picture') }}</label>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="flex-shrink-0">
                            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&color=7C3AED&background=EBF4FF' }}"
                                 alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">{{ __('profile.avatar_help') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                               required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                               required>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.phone_number') }}</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- WhatsApp Number -->
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.whatsapp_number') }}</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <!-- Facility-specific fields -->
                @if($primaryRole === 'facility' || $user->hasRole('facility'))
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('profile.facility_info') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bank Account -->
                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.bank_account') }}</label>
                            <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $user->bank_account) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <!-- Bank -->
                        <div>
                            <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.bank') }}</label>
                            <select name="bank_id" id="bank_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">{{ __('profile.select_bank') }}</option>
                                @foreach(\App\Models\Bank::all() as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id', $user->bank_id) == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name ?? 'Bank ' . $bank->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location Fields -->
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.latitude') }}</label>
                            <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $user->latitude) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.latitude_placeholder') }}">
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.longitude') }}</label>
                            <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $user->longitude) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.longitude_placeholder') }}">
                        </div>

                        <!-- Google Maps URL -->
                        <div class="md:col-span-2">
                            <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.google_maps_url') }}</label>
                            <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url', $user->google_maps_url) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.google_maps_placeholder') }}">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admin-specific fields -->
                @if($primaryRole === 'admin' || $user->hasRole('admin'))
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('profile.notification_settings') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Notification Settings -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="notification_email" id="notification_email" value="1"
                                       {{ old('notification_email', $user->notification_email) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="notification_email" class="mr-3 block text-sm font-medium text-gray-700">
                                    {{ __('profile.email_notifications') }}
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="notification_sms" id="notification_sms" value="1"
                                       {{ old('notification_sms', $user->notification_sms) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="notification_sms" class="mr-3 block text-sm font-medium text-gray-700">
                                    {{ __('profile.sms_notifications') }}
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="notification_push" id="notification_push" value="1"
                                       {{ old('notification_push', $user->notification_push) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="notification_push" class="mr-3 block text-sm font-medium text-gray-700">
                                    {{ __('profile.push_notifications') }}
                                </label>
                            </div>
                        </div>

                        <!-- Notification Frequency -->
                        <div>
                            <label for="notification_frequency" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.notification_frequency') }}</label>
                            <select name="notification_frequency" id="notification_frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">{{ __('profile.select_frequency') }}</option>
                                <option value="daily" {{ old('notification_frequency', $user->notification_frequency) === 'daily' ? 'selected' : '' }}>{{ __('profile.daily') }}</option>
                                <option value="weekly" {{ old('notification_frequency', $user->notification_frequency) === 'weekly' ? 'selected' : '' }}>{{ __('profile.weekly') }}</option>
                                <option value="monthly" {{ old('notification_frequency', $user->notification_frequency) === 'monthly' ? 'selected' : '' }}>{{ __('profile.monthly') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Social Media Section -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('profile.social_media') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Facebook -->
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.facebook') }}</label>
                            <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $user->facebook) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.facebook_placeholder') }}">
                        </div>

                        <!-- Twitter -->
                        <div>
                            <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.twitter') }}</label>
                            <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $user->twitter) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.twitter_placeholder') }}">
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.instagram') }}</label>
                            <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $user->instagram) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.instagram_placeholder') }}">
                        </div>

                        <!-- LinkedIn -->
                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.linkedin') }}</label>
                            <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $user->linkedin) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.linkedin_placeholder') }}">
                        </div>

                        <!-- Snapchat -->
                        <div>
                            <label for="snapchat" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.snapchat') }}</label>
                            <input type="text" name="snapchat" id="snapchat" value="{{ old('snapchat', $user->snapchat) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.snapchat_placeholder') }}">
                        </div>

                        <!-- TikTok -->
                        <div>
                            <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.tiktok') }}</label>
                            <input type="url" name="tiktok" id="tiktok" value="{{ old('tiktok', $user->tiktok) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.tiktok_placeholder') }}">
                        </div>

                        <!-- Pinterest -->
                        <div>
                            <label for="pinterest" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.pinterest') }}</label>
                            <input type="url" name="pinterest" id="pinterest" value="{{ old('pinterest', $user->pinterest) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.pinterest_placeholder') }}">
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label for="youtube" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.youtube') }}</label>
                            <input type="url" name="youtube" id="youtube" value="{{ old('youtube', $user->youtube) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.youtube_placeholder') }}">
                        </div>

                        <!-- Telegram -->
                        <div>
                            <label for="telegram" class="block text-sm font-medium text-gray-700 mb-2">{{ __('profile.telegram') }}</label>
                            <input type="text" name="telegram" id="telegram" value="{{ old('telegram', $user->telegram) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="{{ __('profile.telegram_placeholder') }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('profile.save_changes') }}
                    </button>
                    <a href="{{ route('profile.change-password') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        {{ __('profile.change_password') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
