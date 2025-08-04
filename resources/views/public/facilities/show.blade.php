@extends('layouts.app')

@section('title', $facility->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $facility->name }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ $facility->description ?? 'منشأة عقارية معتمدة وموثوقة' }}
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
                        @if($facility->is_featured)
                            <div class="bg-yellow-600 text-white px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-star ml-1"></i>مميزة
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

    <!-- Facility Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- About Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">عن المنشأة</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ $facility->description ?? 'لا يوجد وصف متاح لهذه المنشأة.' }}
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
                                <i class="fas fa-globe text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">الموقع الإلكتروني</h3>
                                <p class="text-gray-600">
                                    @if($facility->website)
                                        <a href="{{ $facility->website }}" target="_blank" class="text-primary-600 hover:text-primary-700">
                                            {{ $facility->website }}
                                        </a>
                                    @else
                                        غير محدد
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">العقارات المتاحة</h2>
                        <a href="{{ route('public.products.by-facility', $facility) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                            عرض جميع العقارات
                        </a>
                    </div>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($products as $product)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                         alt="{{ $product->title }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
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
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">لا توجد عقارات متاحة حالياً</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تواصل مع المنشأة</h3>
                    <div class="space-y-4">
                        @if($facility->phone)
                            <a href="tel:{{ $facility->phone }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-phone ml-3"></i>
                                <span>{{ $facility->phone }}</span>
                            </a>
                        @endif
                        @if($facility->email)
                            <a href="mailto:{{ $facility->email }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-envelope ml-3"></i>
                                <span>{{ $facility->email }}</span>
                            </a>
                        @endif
                        @if($facility->website)
                            <a href="{{ $facility->website }}" target="_blank" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-globe ml-3"></i>
                                <span>الموقع الإلكتروني</span>
                            </a>
                        @endif
                    </div>

                    <div class="mt-6 space-y-3">
                        <a href="{{ route('public.facilities.appointment', $facility) }}"
                           class="w-full btn-primary text-white py-2 rounded-lg font-medium text-center block">
                            حجز موعد
                        </a>
                        <a href="{{ route('public.facilities.quote', $facility) }}"
                           class="w-full border border-primary-600 text-primary-600 py-2 rounded-lg font-medium text-center block hover:bg-primary-50 transition-colors">
                            طلب عرض سعر
                        </a>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات المنشأة</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">عدد العقارات</span>
                            <span class="font-semibold text-gray-900">{{ $facility->products_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">التقييم</span>
                            <span class="font-semibold text-gray-900">{{ $facility->rating ?? 0 }}/5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">تاريخ الإنشاء</span>
                            <span class="font-semibold text-gray-900">{{ $facility->created_at ? $facility->created_at->format('Y/m/d') : 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Similar Facilities -->
                @if($similarFacilities->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">منشآت مشابهة</h3>
                        <div class="space-y-4">
                            @foreach($similarFacilities as $similar)
                                <a href="{{ route('public.facilities.show', $similar) }}"
                                   class="flex items-center space-x-3 space-x-reverse hover:bg-gray-50 p-2 rounded transition-colors">
                                    <img src="{{ $similar->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}"
                                         alt="{{ $similar->name }}" class="w-12 h-12 rounded object-cover">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $similar->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $similar->category->name ?? '' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
