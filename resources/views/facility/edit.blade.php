@extends('layouts.app')

@section('title', __('facility.edit.title'))

@section('content')
<div class="container mx-auto my-10 px-4">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.edit.title') }}</h4>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.name') }} *</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                                           id="name" name="name" value="{{ old('name', $facility->name) }}" required>
                                    @error('name')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.category') }} *</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">{{ __('facility.form.select_category') }}</option>
                                        @foreach(\App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $facility->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.description') }}</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $facility->description) }}</textarea>
                            @error('description')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.address') }} *</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror" 
                                           id="address" name="address" value="{{ old('address', $facility->address) }}" required>
                                    @error('address')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.phone') }} *</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone_number') border-red-500 @enderror" 
                                           id="phone_number" name="phone_number" value="{{ old('phone_number', $facility->phone_number) }}" required>
                                    @error('phone_number')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.email') }} *</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                                           id="email" name="email" value="{{ old('email', $facility->email) }}" required>
                                    @error('email')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.website') }}</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website') border-red-500 @enderror" 
                                           id="website" name="website" value="{{ old('website', $facility->website) }}">
                                    @error('website')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.latitude') }}</label>
                                    <input type="number" step="any" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('latitude') border-red-500 @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude', $facility->latitude) }}">
                                    @error('latitude')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.longitude') }}</label>
                                    <input type="number" step="any" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('longitude') border-red-500 @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude', $facility->longitude) }}">
                                    @error('longitude')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.logo') }}</label>
                                    @if($facility->logo)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $facility->logo) }}" 
                                                 alt="{{ __('facility.form.current_logo') }}" class="max-h-24 w-auto rounded-lg border border-gray-200">
                                        </div>
                                    @endif
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('logo') border-red-500 @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    @error('logo')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-gray-500 text-xs mt-1 block">{{ __('facility.form.image_help') }}</small>
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.cover_image') }}</label>
                                    @if($facility->cover_image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $facility->cover_image) }}" 
                                                 alt="{{ __('facility.form.current_cover') }}" class="max-h-24 w-auto rounded-lg border border-gray-200">
                                        </div>
                                    @endif
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cover_image') border-red-500 @enderror" 
                                           id="cover_image" name="cover_image" accept="image/*">
                                    @error('cover_image')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-gray-500 text-xs mt-1 block">{{ __('facility.form.image_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.whatsapp') }}</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp_number') border-red-500 @enderror" 
                                           id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $facility->whatsapp_number) }}">
                                    @error('whatsapp_number')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.working_hours') }}</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('working_hours') border-red-500 @enderror" 
                                           id="working_hours" name="working_hours" value="{{ old('working_hours', $facility->working_hours) }}"
                                           placeholder="{{ __('facility.form.working_hours_placeholder') }}">
                                    @error('working_hours')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.form.google_maps') }}</label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('google_maps_url') border-red-500 @enderror" 
                                   id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url', $facility->google_maps_url) }}">
                            @error('google_maps_url')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                            <a href="{{ route('facility.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 text-center">{{ __('facility.form.cancel') }}</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200">{{ __('facility.form.save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
