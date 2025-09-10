@extends('layouts.app')

@section('title', $product->name ?? __('facility.products.product_details'))

@section('content')
<div class="w-full px-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $product->name ?? __('facility.products.product_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('facility.products.product_details_subtitle') }}</p>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse mt-4 sm:mt-0">
            <a href="{{ route('facility.products.edit', $product) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center">
                <i class="fas fa-edit ml-2"></i>
                {{ __('facility.products.edit_button') }}
            </a>
            <a href="{{ route('facility.products.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('facility.products.back_to_list') }}
            </a>
        </div>
    </div>

    <!-- Product Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Product Images & Basic Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.product_image') }}</h3>
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.product_information') }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.name') }}</label>
                        <p class="text-gray-900">{{ $product->name }}</p>
                    </div>
                    
                    @if($product->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.description') }}</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.price') }}</label>
                            <p class="text-gray-900 text-lg font-semibold">
                                @if($product->price)
                                    {{ number_format($product->price, 2) }} ر.س
                                @else
                                    <span class="text-gray-500">{{ __('facility.products.not_set') }}</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.category') }}</label>
                            <p class="text-gray-900">
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->getTranslatedName('ar') }}
                                    </span>
                                @else
                                    <span class="text-gray-500">{{ __('facility.products.not_set') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Features -->
            @if($product->features && $product->features->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.features') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($product->features as $feature)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                                @if($feature->icon)
                                    @if(Str::startsWith($feature->icon, 'fas ') || Str::startsWith($feature->icon, 'fa ') || Str::startsWith($feature->icon, 'fab '))
                                        <i class="{{ $feature->icon }} text-blue-600 ml-2"></i>
                                    @else
                                        <img src="{{ Storage::url($feature->icon) }}" alt="icon" width="20" class="ml-2">
                                    @endif
                                @endif
                                <span class="text-gray-900">{{ $feature->getTranslatedName('ar') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Product Attributes -->
            @if($product->attributes && $product->attributes->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.attributes') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($product->attributes as $attribute)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">{{ $attribute->getTranslatedName('ar') }}</span>
                                <span class="font-medium text-gray-900">{{ $attribute->pivot->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Location Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.location') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.address') }}</label>
                        <p class="text-gray-900">{{ $product->address }}</p>
                    </div>
                    
                    @if($product->latitude && $product->longitude)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.latitude') }}</label>
                                <p class="text-gray-900">{{ $product->latitude }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('facility.products.longitude') }}</label>
                                <p class="text-gray-900">{{ $product->longitude }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($product->google_maps_url)
                        <div>
                            <a href="{{ $product->google_maps_url }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                {{ __('facility.products.view_on_google_maps') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Status & Actions -->
        <div class="space-y-6">
            
            <!-- Product Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.status') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ __('facility.products.active_status') }}</span>
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check ml-1"></i>
                                {{ __('facility.products.active') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times ml-1"></i>
                                {{ __('facility.products.inactive') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ __('facility.products.featured_status') }}</span>
                        @if($product->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star ml-1"></i>
                                {{ __('facility.products.featured') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ __('facility.products.not_featured') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ __('facility.products.verified_status') }}</span>
                        @if($product->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-shield-alt ml-1"></i>
                                {{ __('facility.products.verified') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ __('facility.products.pending_verification') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('facility.products.edit', $product) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-edit ml-2"></i>
                        {{ __('facility.products.edit_product') }}
                    </a>
                    
                    <form action="{{ route('facility.products.toggle-status', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $product->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                            <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }} ml-2"></i>
                            {{ $product->is_active ? __('facility.products.deactivate') : __('facility.products.activate') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('facility.products.toggle-featured', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $product->is_featured ? 'bg-gray-600 hover:bg-gray-700' : 'bg-yellow-600 hover:bg-yellow-700' }}">
                            <i class="fas fa-star ml-2"></i>
                            {{ $product->is_featured ? __('facility.products.remove_from_featured') : __('facility.products.add_to_featured') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('facility.products.destroy', $product) }}" method="POST" class="w-full" onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-trash ml-2"></i>
                            {{ __('facility.products.delete_product') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('facility.products.statistics') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.views') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->views_count ?? 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.rating') }}</span>
                        <div class="flex items-center">
                            <span class="font-medium text-gray-900 ml-2">{{ $product->rating ?? 0 }}</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($product->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.rating_count') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->rating_count ?? 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.created_at') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->created_at->format('Y/m/d') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('facility.products.updated_at') }}</span>
                        <span class="font-medium text-gray-900">{{ $product->updated_at->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
