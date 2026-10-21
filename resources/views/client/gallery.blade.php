@extends('client.layouts.app')

@section('title', __('client.gallery'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.gallery') }}</h1>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('client.gallery') }}" class="px-3 py-1 rounded-md {{ request()->routeIs('client.gallery') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('client.all') }}</a>
                <a href="{{ route('client.gallery.products') }}" class="px-3 py-1 rounded-md {{ request()->routeIs('client.gallery.products') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('client.products') }}</a>
                <a href="{{ route('client.gallery.facilities') }}" class="px-3 py-1 rounded-md {{ request()->routeIs('client.gallery.facilities') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('client.facilities') }}</a>
            </div>
        </div>

        @if($products->count())
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('client.products') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($products as $product)
                    <a href="{{ route('public.products.show', $product) }}" class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-48 object-cover" alt="">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400"><i class="fas fa-image text-3xl"></i></div>
                        @endif
                        <div class="p-4">
                            <p class="font-semibold text-gray-900">{{ $product->getTranslatedTitle() }}</p>
                            <p class="text-sm text-gray-500">{{ optional($product->facility)->name }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mb-8">{{ $products->links() }}</div>
            @endif
        @endif

        @if($facilities->count())
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('client.facilities') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $facility)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        @if($facility->logo)
                            <img src="{{ asset('storage/' . $facility->logo) }}" class="w-full h-40 object-cover" alt="">
                        @else
                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400"><i class="fas fa-building text-3xl"></i></div>
                        @endif
                        <div class="p-4">
                            <p class="font-semibold text-gray-900">{{ $facility->name }}</p>
                            <p class="text-sm text-gray-500">{{ $facility->address }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($facilities instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-6">{{ $facilities->links() }}</div>
            @endif
        @endif

        @if(!$products->count() && !$facilities->count())
            <p class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">{{ __('client.no_results') }}</p>
        @endif
    </div>
</div>
@endsection
