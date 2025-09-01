@extends('layouts.app')

@section('title', __('facility.products.create.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.products.create.title') }}</h4>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.basic_info') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.name') }} *</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.category') }} *</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror" 
                                                id="category_id" name="category_id" required>
                                            <option value="">{{ __('facility.products.create.select_category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.description') }}</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.price') }} *</label>
                                        <input type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.status') }} *</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_id') border-red-500 @enderror" 
                                                id="status_id" name="status_id" required>
                                            <option value="">{{ __('facility.products.create.select_status') }}</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" 
                                                        {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.location_info') }}
                            </h5>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.address') }} *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror" 
                                       id="address" name="address" value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.latitude') }}</label>
                                        <input type="number" step="any" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('latitude') border-red-500 @enderror" 
                                               id="latitude" name="latitude" value="{{ old('latitude') }}">
                                        @error('latitude')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.longitude') }}</label>
                                        <input type="number" step="any" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('longitude') border-red-500 @enderror" 
                                               id="longitude" name="longitude" value="{{ old('longitude') }}">
                                        @error('longitude')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.google_maps') }}</label>
                                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('google_maps_url') border-red-500 @enderror" 
                                               id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url') }}">
                                        @error('google_maps_url')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.property_details') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.bedrooms') }}</label>
                                        <input type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bedrooms') border-red-500 @enderror" 
                                               id="bedrooms" name="bedrooms" value="{{ old('bedrooms') }}">
                                        @error('bedrooms')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.bathrooms') }}</label>
                                        <input type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bathrooms') border-red-500 @enderror" 
                                               id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}">
                                        @error('bathrooms')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="area" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.area') }} (m²)</label>
                                        <input type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('area') border-red-500 @enderror" 
                                               id="area" name="area" value="{{ old('area') }}">
                                        @error('area')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="parking_spaces" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.parking') }}</label>
                                        <input type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('parking_spaces') border-red-500 @enderror" 
                                               id="parking_spaces" name="parking_spaces" value="{{ old('parking_spaces') }}">
                                        @error('parking_spaces')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="floor_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.floor_number') }}</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('floor_number') border-red-500 @enderror" 
                                               id="floor_number" name="floor_number" value="{{ old('floor_number') }}">
                                        @error('floor_number')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="total_floors" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.total_floors') }}</label>
                                        <input type="number" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_floors') border-red-500 @enderror" 
                                               id="total_floors" name="total_floors" value="{{ old('total_floors') }}">
                                        @error('total_floors')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="owner_user_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.owner') }} *</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('owner_user_id') border-red-500 @enderror" 
                                                id="owner_user_id" name="owner_user_id" required>
                                            <option value="">{{ __('facility.products.create.select_owner') }}</option>
                                            <option value="{{ Auth::id() }}" selected>{{ Auth::user()->name }} ({{ __('facility.products.create.me') }})</option>
                                        </select>
                                        @error('owner_user_id')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.media') }}
                            </h5>
                            
                            <div class="mb-4">
                                <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.create.main_image') }}</label>
                                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('main_image') border-red-500 @enderror" 
                                       id="main_image" name="main_image" accept="image/*">
                                @error('main_image')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-gray-500 text-xs mt-1 block">{{ __('facility.form.image_help') }}</small>
                            </div>
                        </div>

                        <!-- Features & Options -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.create.features_options') }}
                            </h5>
                            
                            <!-- Boolean Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="furnished" name="furnished" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('furnished') ? 'checked' : '' }}>
                                    <label for="furnished" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.furnished') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="available_for_rent" name="available_for_rent" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('available_for_rent') ? 'checked' : '' }}>
                                    <label for="available_for_rent" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.for_rent') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="available_for_sale" name="available_for_sale" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('available_for_sale') ? 'checked' : '' }}>
                                    <label for="available_for_sale" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.for_sale') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">
                                        {{ __('facility.products.create.featured') }}
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Features Selection -->
                            @if($features->count() > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('facility.products.create.features') }}</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($features as $feature)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="feature_{{ $feature->id }}" name="features[]" value="{{ $feature->id }}" 
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                               {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}>
                                        <label for="feature_{{ $feature->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $feature->getTranslatedName('ar') }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('features')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('facility.products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 text-center">
                                {{ __('facility.form.cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                                {{ __('facility.products.create.create_product') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
