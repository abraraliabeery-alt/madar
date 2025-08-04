@extends('layouts.app')

@section('title', 'عقارات ' . $facility->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">عقارات {{ $facility->name }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        اكتشف جميع العقارات المتاحة من {{ $facility->name }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <div class="flex items-center text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                            @endfor
                            <span class="text-white mr-2">({{ $facility->rating ?? 0 }})</span>
                        </div>
                        @if($facility->is_verified)
                            <div class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-check ml-1"></i>معتمدة
                            </div>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $facility->name }}" class="rounded-lg shadow-xl w-full">
                </div>
            </div>
        </div>
    </div>

    <!-- Facility Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-primary-100 p-3 rounded-full">
                        <i class="fas fa-building text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $facility->name }}</h2>
                        <p class="text-gray-600">{{ $products->total() }} عقار متاح</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('public.facilities.show', $facility) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-building ml-2"></i>عرض المنشأة
                    </a>
                    <a href="{{ route('public.products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-arrow-right ml-2"></i>عرض جميع العقارات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                        <div class="relative">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                 alt="{{ $product->title }}" class="w-full h-48 object-cover">
                            @if($product->is_featured)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    مميز
                                </div>
                            @endif
                            @if($product->is_verified)
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check ml-1"></i>موثق
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                                    {{ $product->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $product->address ?? 'موقع غير محدد' }}</p>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <span><i class="fas fa-bed ml-1"></i>{{ $product->rooms ?? 0 }} غرف</span>
                                <span><i class="fas fa-bath ml-1"></i>{{ $product->bathrooms ?? 0 }} حمامات</span>
                                <span><i class="fas fa-ruler-combined ml-1"></i>{{ $product->area ?? 0 }} م²</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-lg font-bold text-primary-600">
                                    {{ number_format($product->price) }} ريال
                                </div>
                                <a href="{{ route('public.products.show', $product) }}"
                                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد عقارات</h3>
                    <p class="text-gray-600 mb-6">لا توجد عقارات متاحة من {{ $facility->name }} حالياً.</p>
                    <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        عرض جميع العقارات
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Facility Details -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">عن {{ $facility->name }}</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">
                        {{ $facility->description ?? 'منشأة عقارية معتمدة وموثوقة تقدم أفضل الخدمات العقارية.' }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-map-marker-alt text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">العنوان</h3>
                                <p class="text-gray-600">{{ $facility->address ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-phone text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">الهاتف</h3>
                                <p class="text-gray-600">{{ $facility->phone ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-envelope text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">البريد الإلكتروني</h3>
                                <p class="text-gray-600">{{ $facility->email ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-full mr-4">
                                <i class="fas fa-home text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">عدد العقارات</h3>
                                <p class="text-gray-600">{{ $facility->products_count ?? 0 }} عقار</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $facility->name }}" class="rounded-lg shadow-xl w-full">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
