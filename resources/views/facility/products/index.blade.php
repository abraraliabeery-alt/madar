@extends('layouts.app')

@section('title', __('facility.products.title'))

@section('content')
<div class="w-full px-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ __('facility.products.title') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('facility.products.subtitle') }}</p>
        </div>
        <a href="{{ route('facility.products.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center mt-4 sm:mt-0">
            <i class="fas fa-plus ml-2"></i>
            {{ __('facility.products.add_new') }}
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('facility.products.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.search') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('facility.products.search_placeholder') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.category') }}</label>
                    <select id="category_id" 
                            name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('facility.products.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->getTranslatedName('ar') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.products.status') }}</label>
                    <select id="status_id" 
                            name="status_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('facility.products.all_statuses') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" 
                                    {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 inline-flex items-center justify-center">
                    <i class="fas fa-search ml-2"></i>
                    {{ __('facility.products.search_button') }}
                </button>
                <a href="{{ route('facility.products.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 inline-flex items-center justify-center">
                    <i class="fas fa-undo ml-2"></i>
                    {{ __('facility.products.clear_filters') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    @if($products->count() > 0)
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-gray-700">
                {{ __('facility.products.showing_results', ['first' => $products->firstItem() ?? 0, 'last' => $products->lastItem() ?? 0, 'total' => $products->total()]) }}
                @if(request()->hasAny(['search', 'category_id', 'status_id']))
                    <span class="text-blue-600">({{ __('facility.products.filtered') }})</span>
                @endif
            </p>
        </div>
    @endif

    <!-- Products List -->
    @if($products->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden lg:block">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.products.product') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.products.category') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.products.status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.products.price') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.products.created_at') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility.products.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product->main_image)
                                            <img class="h-12 w-12 rounded-lg object-cover ml-4" 
                                                 src="{{ asset('storage/' . $product->main_image) }}" 
                                                 alt="{{ $product->title }}">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center ml-4">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->getTranslatedName('ar') ?? __('facility.products.unspecified') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $product->statuses()->latest()->first();
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $status->name ?? __('facility.products.unspecified') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($product->price)
                                        {{ number_format($product->price) }} ر.س
                                    @else
                                        <span class="text-gray-500">{{ __('facility.products.unspecified') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <a href="{{ route('facility.products.show', $product) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition duration-200"
                                           title="{{ __('facility.products.view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('facility.products.edit', $product) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition duration-200"
                                           title="{{ __('facility.products.edit_button') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('facility.products.destroy', $product) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition duration-200"
                                                    title="{{ __('facility.products.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($products as $product)
                        <div class="p-6">
                            <div class="flex items-start space-x-4 space-x-reverse">
                                @if($product->main_image)
                                    <img class="h-16 w-16 rounded-lg object-cover flex-shrink-0" 
                                         src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="{{ $product->title }}">
                                @else
                                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $product->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($product->description, 100) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $product->category->getTranslatedName('ar') ?? __('facility.products.unspecified') }}
                                            </span>
                                            @php
                                                $status = $product->statuses()->latest()->first();
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $status->name ?? __('facility.products.unspecified') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <a href="{{ route('facility.products.show', $product) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition duration-200">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('facility.products.edit', $product) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 transition duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('facility.products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transition duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                                        <span class="text-sm text-gray-500">{{ $product->created_at->format('Y/m/d') }}</span>
                                        @if($product->price)
                                            <span class="text-lg font-semibold text-gray-900">{{ number_format($product->price) }} ر.س</span>
                                        @else
                                            <span class="text-sm text-gray-500">{{ __('facility.products.price_not_set') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-6">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-box text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('facility.products.no_products') }}</h3>
            <p class="text-gray-500 mb-6">
                @if(request()->hasAny(['search', 'category_id', 'status_id']))
                    {{ __('facility.products.no_search_results') }}
                @else
                    {{ __('facility.products.no_products_yet') }}
                @endif
            </p>
            @if(request()->hasAny(['search', 'category_id', 'status_id']))
                <a href="{{ route('facility.products.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 inline-flex items-center">
                    <i class="fas fa-undo ml-2"></i>
                    {{ __('facility.products.view_all') }}
                </a>
            @else
                <a href="{{ route('facility.products.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus ml-2"></i>
                    {{ __('facility.products.add_new') }}
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
