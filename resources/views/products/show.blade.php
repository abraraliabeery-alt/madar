@extends('layouts.app')

@section('title', $product->name ?? __('products.page_titles.product_details'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <div class="flex justify-between items-center mb-6">
            <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
                <li class="inline-flex items-center">
                                            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            {{ __('products.breadcrumb.home') }}
                        </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="#" class="mr-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:mr-2">{{ __('products.breadcrumb.products') }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="mr-1 text-sm font-medium text-gray-500 md:mr-2">{{ $product->name ?? __('products.breadcrumb.product') }}</span>
                    </div>
                </li>
            </ol>
            </nav>
            <x-language-switcher />
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Product Images & Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Product Images -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-64 object-cover rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Product Gallery (if available) -->
                    @if($product->gallery && $product->gallery->count() > 0)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->gallery->take(4) as $image)
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ asset($image->path) }}"
                                         alt="Gallery image"
                                         class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition-opacity">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->name ?? __('products.product_info.description') }}</h1>

                    @if($product->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('products.product_info.description') }}</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    <!-- Product Features -->
                    @if($product->features && $product->features->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('products.product_info.features') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($product->features as $feature)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $feature->getTranslatedName() ?? 'Feature ' . $feature->id }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Product Attributes -->
                    @if($product->attributeValues && $product->attributeValues->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('products.product_info.attributes') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($product->attributeValues as $attrValue)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">{{ $attrValue->attribute->name ?? 'Attribute ' . $attrValue->attribute_id }}</span>
                                        <span class="font-medium text-gray-900">{{ $attrValue->value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Product Status -->
                    <div class="flex items-center space-x-4 space-x-reverse">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('products.status_badges.available') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('products.status_badges.unavailable') }}
                            </span>
                        @endif

                        @if($product->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                {{ __('products.status_badges.featured') }}
                            </span>
                        @endif

                        @if($product->is_verified)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('products.status_badges.verified') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Product Comments -->
                @if($product->comments && $product->comments->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.comments.comments_count') }} ({{ $product->comments->count() }})</h3>
                        <div class="space-y-4">
                            @foreach($product->comments->take(5) as $comment)
                                <div class="border-b border-gray-100 pb-4 last:border-b-0">
                                    <div class="flex items-start space-x-3 space-x-reverse">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $comment->user->avatar ? asset($comment->user->avatar) : 'https://ui-avatars.com/api/?name=' . $comment->user->name . '&color=7C3AED&background=EBF4FF&size=40' }}"
                                                 alt="{{ $comment->user->name }}"
                                                 class="w-8 h-8 rounded-full">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 space-x-reverse mb-1">
                                                <a href="{{ route('profile.public', $comment->user->id) }}"
                                                   class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                                    {{ $comment->user->name }}
                                                </a>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($product->comments->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    {{ __('products.comments.view_all_comments') }} ({{ $product->comments->count() }})
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Column - Publisher Info & Actions -->
            <div class="space-y-6">

                <!-- Publisher Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.headers.publisher_information') }}</h3>
                    <x-product-publisher :product="$product" />
                </div>

                <!-- Product Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.headers.actions') }}</h3>
                    <div class="space-y-3">
                        @auth
                            <!-- Add to Favorites -->
                            <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                {{ __('products.actions.add_to_favorites') }}
                            </button>

                            <!-- Contact Publisher -->
                            <button class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                {{ __('products.actions.contact_publisher') }}
                            </button>

                            <!-- Book Now -->
                            @if($product->is_active)
                                <button class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('products.actions.book_now') }}
                                </button>
                            @endif
                        @else
                            <!-- Login to Interact -->
                            <div class="text-center py-4">
                                <p class="text-gray-600 mb-3">{{ __('products.actions.login_to_interact') }}</p>
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    {{ __('products.actions.login') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Product Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.headers.product_details_section') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('products.product_info.publish_date') }}</span>
                            <span class="font-medium">{{ $product->created_at ? $product->created_at->format('d/m/Y') : __('products.product_info.undefined') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('products.product_info.last_update') }}</span>
                            <span class="font-medium">{{ $product->updated_at ? $product->updated_at->format('d/m/Y') : __('products.product_info.undefined') }}</span>
                        </div>

                        @if($product->category)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('products.product_info.category') }}:</span>
                                <span class="font-medium">{{ $product->category->name ?? 'Category ' . $product->category->id }}</span>
                            </div>
                        @endif

                        @if($product->facility)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('products.product_info.facility') }}:</span>
                                <span class="font-medium">{{ $product->facility->name ?? 'Facility ' . $product->facility->id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Share Product -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('products.headers.share_product') }}</h3>
                    <div class="flex space-x-2 space-x-reverse">
                        <button class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            {{ __('products.social_media.facebook') }}
                        </button>

                        <button class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            {{ __('products.social_media.twitter') }}
                        </button>

                        <button class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            {{ __('products.social_media.whatsapp') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
