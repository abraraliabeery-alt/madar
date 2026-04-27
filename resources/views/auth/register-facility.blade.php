@extends('layouts.app')

@section('title', __('facility.create.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-building text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">{{ __('facility.create.title') }}</h2>
            <p class="text-gray-600 mt-2">{{ __('facility.create.subtitle') ?? __('facility.create.title') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="POST" action="{{ route('facility.onboarding.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                                   placeholder="{{ __('facility.form.name') }}">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="facility_category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.facility_category') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-layer-group text-gray-400"></i>
                            </div>
                            <select id="facility_category_id" name="facility_category_id" required
                                    class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('facility_category_id') border-red-500 @enderror">
                                <option value="">{{ __('facility.form.select_facility_category') }}</option>
                                @foreach($facilityCategories as $facilityCategory)
                                    <option value="{{ $facilityCategory->id }}" {{ old('facility_category_id') == $facilityCategory->id ? 'selected' : '' }}>
                                        {{ $facilityCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('facility_category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.description') }}</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('description') border-red-500 @enderror"
                                  placeholder="{{ __('facility.form.description') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-location-dot text-gray-400"></i>
                            </div>
                            <input id="address" type="text" name="address" value="{{ old('address') }}" required
                                   class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('address') border-red-500 @enderror"
                                   placeholder="{{ __('facility.form.address') }}">
                        </div>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.phone') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" required
                                   class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('phone_number') border-red-500 @enderror"
                                   placeholder="{{ __('facility.form.phone') }}">
                        </div>
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.email') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                                   placeholder="{{ __('facility.form.email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.website') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <input id="website" type="url" name="website" value="{{ old('website') }}"
                                   class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('website') border-red-500 @enderror"
                                   placeholder="{{ __('facility.form.website') }}">
                        </div>
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.logo') }}</label>
                        <input id="logo" type="file" name="logo" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('logo') border-red-500 @enderror">
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ __('facility.form.image_help') }}</p>
                    </div>

                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.cover_image') }}</label>
                        <input id="cover_image" type="file" name="cover_image" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('cover_image') border-red-500 @enderror">
                        @error('cover_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ __('facility.form.image_help') }}</p>
                    </div>

                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.latitude') }}</label>
                        <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('latitude') border-red-500 @enderror">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.longitude') }}</label>
                        <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('longitude') border-red-500 @enderror">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.whatsapp') }}</label>
                        <input id="whatsapp_number" type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('whatsapp_number') border-red-500 @enderror">
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.working_hours') }}</label>
                        <input id="working_hours" type="text" name="working_hours" value="{{ old('working_hours') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('working_hours') border-red-500 @enderror"
                               placeholder="{{ __('facility.form.working_hours_placeholder') }}">
                        @error('working_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.google_maps') }}</label>
                        <input id="google_maps_url" type="url" name="google_maps_url" value="{{ old('google_maps_url') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors @error('google_maps_url') border-red-500 @enderror">
                        @error('google_maps_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('home') }}" class="w-full sm:w-auto text-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        {{ __('facility.form.cancel') }}
                    </a>
                    <button type="submit" class="w-full sm:flex-1 bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 rounded-lg font-medium text-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-building ml-2"></i>
                        {{ __('facility.form.create') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    <a href="{{ route('dashboard') }}" class="text-blue-700 hover:text-blue-800 font-medium">
                        {{ __('auth.register.back_to_home') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
