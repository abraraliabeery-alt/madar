@extends('layouts.app')

@section('title', __('facility.products.edit.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">{{ __('facility.products.edit.title') }}</h4>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.basic_info') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.name') }} *</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.category') }} *</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror" 
                                                id="category_id" name="category_id" required>
                                            <option value="">{{ __('facility.products.edit.select_category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.city') }} *</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city_id') border-red-500 @enderror" 
                                                id="city_id" name="city_id" required>
                                            <option value="">{{ __('facility.products.edit.select_city') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" 
                                                        {{ old('city_id', $product->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->localized_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.description') }}</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                          id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.price') }} *</label>
                                        <input type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" 
                                               id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                        @error('price')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.status') }} *</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_id') border-red-500 @enderror" 
                                                id="status_id" name="status_id" required>
                                            <option value="">{{ __('facility.products.edit.select_status') }}</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" 
                                                        {{ old('status_id', $product->status ? $product->status->id : '') == $status->id ? 'selected' : '' }}>
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
                                {{ __('facility.products.edit.location_info') }}
                            </h5>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.address') }} *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror" 
                                       id="address" name="address" value="{{ old('address', $product->address) }}" required>
                                @error('address')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.latitude') }}</label>
                                        <input type="number" step="any" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('latitude') border-red-500 @enderror" 
                                               id="latitude" name="latitude" value="{{ old('latitude', $product->latitude) }}">
                                        @error('latitude')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.longitude') }}</label>
                                        <input type="number" step="any" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('longitude') border-red-500 @enderror" 
                                               id="longitude" name="longitude" value="{{ old('longitude', $product->longitude) }}">
                                        @error('longitude')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.google_maps') }}</label>
                                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('google_maps_url') border-red-500 @enderror" 
                                               id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url', $product->google_maps_url) }}">
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
                                {{ __('facility.products.edit.property_details') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.bedrooms') }}</label>
                                        <input type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bedrooms') border-red-500 @enderror" 
                                               id="bedrooms" name="bedrooms" value="{{ old('bedrooms', $product->bedrooms) }}">
                                        @error('bedrooms')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.bathrooms') }}</label>
                                        <input type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bathrooms') border-red-500 @enderror" 
                                               id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $product->bathrooms) }}">
                                        @error('bathrooms')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="area" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.area') }} (m²)</label>
                                        <input type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('area') border-red-500 @enderror" 
                                               id="area" name="area" value="{{ old('area', $product->area) }}">
                                        @error('area')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="parking_spaces" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.parking') }}</label>
                                        <input type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('parking_spaces') border-red-500 @enderror" 
                                               id="parking_spaces" name="parking_spaces" value="{{ old('parking_spaces', $product->parking_spaces) }}">
                                        @error('parking_spaces')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <div class="mb-4">
                                        <label for="floor_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.floor_number') }}</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('floor_number') border-red-500 @enderror" 
                                               id="floor_number" name="floor_number" value="{{ old('floor_number', $product->floor_number) }}">
                                        @error('floor_number')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="mb-4">
                                        <label for="total_floors" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.total_floors') }}</label>
                                        <input type="number" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_floors') border-red-500 @enderror" 
                                               id="total_floors" name="total_floors" value="{{ old('total_floors', $product->total_floors) }}">
                                        @error('total_floors')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <div class="form-check mb-4">
                                        <input type="checkbox" class="form-check-input" id="furnished" name="furnished" value="1" {{ old('furnished', $product->furnished) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="furnished">{{ __('facility.products.edit.furnished') }}</label>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="form-check mb-4">
                                        <input type="checkbox" class="form-check-input" id="available_for_rent" name="available_for_rent" value="1" {{ old('available_for_rent', $product->available_for_rent) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="available_for_rent">{{ __('facility.products.edit.available_for_rent') }}</label>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="form-check mb-4">
                                        <input type="checkbox" class="form-check-input" id="available_for_sale" name="available_for_sale" value="1" {{ old('available_for_sale', $product->available_for_sale) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="available_for_sale">{{ __('facility.products.edit.available_for_sale') }}</label>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="form-check mb-4">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">{{ __('facility.products.edit.is_featured') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.media') }}
                            </h5>
                            
                            <div class="mb-4">
                                <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.edit.main_image') }}</label>
                                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('main_image') border-red-500 @enderror" 
                                       id="main_image" name="main_image" accept="image/*">
                                @if($product->main_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="Current image" class="w-32 h-32 object-cover rounded">
                                    </div>
                                @endif
                                @error('main_image')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mb-8">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                {{ __('facility.products.edit.features') }}
                            </h5>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($features as $feature)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="feature_{{ $feature->id }}" name="features[]" value="{{ $feature->id }}" 
                                           {{ in_array($feature->id, old('features', $product->features->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_{{ $feature->id }}">
                                        @if($feature->icon)
                                            <img src="{{ asset($feature->icon) }}" alt="icon" width="20" class="inline mr-2">
                                        @endif
                                        {{ $feature->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 rtl:space-x-reverse">
                            <a href="{{ route('facility.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                {{ __('facility.products.edit.cancel') }}
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('facility.products.edit.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
