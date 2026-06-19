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
