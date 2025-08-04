@extends('layouts.app')

@section('title', 'المنشآت المميزة')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">المنشآت المميزة</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    اكتشف أفضل المنشآت العقارية المميزة والمعتمدة في المملكة
                </p>
            </div>
        </div>
    </div>

    <!-- Featured Info -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">المنشآت المميزة</h2>
                        <p class="text-gray-600">{{ $facilities->total() }} منشأة مميزة</p>
                    </div>
                </div>
                <a href="{{ route('public.facilities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-arrow-right ml-2"></i>عرض جميع المنشآت
                </a>
            </div>
        </div>
    </div>

    <!-- Facilities Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($facilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $facility)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover border-2 border-yellow-200">
                        <div class="relative">
                            <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                 alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                            <div class="absolute top-2 right-2 bg-yellow-600 text-white px-2 py-1 rounded text-xs font-medium">
                                <i class="fas fa-star ml-1"></i>مميزة
                            </div>
                            @if($facility->is_verified)
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check ml-1"></i>معتمدة
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    <a href="{{ route('public.facilities.show', $facility) }}" class="hover:text-primary-600 transition-colors">
                                        {{ $facility->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-sm text-gray-600 mr-2">({{ $facility->rating ?? 0 }})</span>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-4">{{ $facility->description ?? 'لا يوجد وصف متاح' }}</p>

                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                <span>{{ $facility->address ?? 'موقع غير محدد' }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-home ml-1"></i>{{ $facility->products_count ?? 0 }} عقار</span>
                                <span><i class="fas fa-calendar ml-1"></i>{{ $facility->created_at ? $facility->created_at->diffForHumans() : 'غير محدد' }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('public.facilities.show', $facility) }}"
                                   class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    عرض المنشأة
                                </a>
                                <a href="{{ route('public.products.by-facility', $facility) }}"
                                   class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    عرض العقارات
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($facilities->hasPages())
                <div class="mt-12">
                    {{ $facilities->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-star text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد منشآت مميزة</h3>
                    <p class="text-gray-600 mb-6">لا توجد منشآت مميزة متاحة حالياً.</p>
                    <a href="{{ route('public.facilities.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        عرض جميع المنشآت
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Why Featured Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">لماذا المنشآت المميزة؟</h2>
                <p class="text-lg text-gray-600">المنشآت المميزة هي الأفضل في مجالها</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">جودة عالية</h3>
                    <p class="text-gray-600">منشآت معتمدة وموثوقة تقدم خدمات عالية الجودة</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">معتمدة رسمياً</h3>
                    <p class="text-gray-600">جميع المنشآت المميزة معتمدة من الجهات الرسمية</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">تجربة عملاء</h3>
                    <p class="text-gray-600">تقييمات عالية من العملاء السابقين</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
