@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $product->title }}</h1>
                    <p class="text-xl text-primary-100 mb-6">
                        {{ $product->address ?? 'موقع غير محدد' }}
                    </p>
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <div class="text-2xl font-bold">
                            {{ number_format($product->price) }} ريال
                        </div>
                        @if($product->is_featured)
                            <div class="bg-yellow-600 text-white px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-star ml-1"></i>مميز
                            </div>
                        @endif
                        @if($product->is_verified)
                            <div class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-check ml-1"></i>موثق
                            </div>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $product->title }}" class="rounded-lg shadow-xl w-full">
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Description Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">وصف العقار</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ $product->description ?? 'لا يوجد وصف متاح لهذا العقار.' }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="bg-primary-100 p-4 rounded-lg mb-3">
                                <i class="fas fa-bed text-2xl text-primary-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $product->rooms ?? 0 }}</h3>
                            <p class="text-gray-600 text-sm">غرف النوم</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-primary-100 p-4 rounded-lg mb-3">
                                <i class="fas fa-bath text-2xl text-primary-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $product->bathrooms ?? 0 }}</h3>
                            <p class="text-gray-600 text-sm">الحمامات</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-primary-100 p-4 rounded-lg mb-3">
                                <i class="fas fa-ruler-combined text-2xl text-primary-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ number_format($product->area ?? 0) }}</h3>
                            <p class="text-gray-600 text-sm">متر مربع</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section -->
                @if($product->gallery && $product->gallery->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">معرض الصور</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($product->gallery as $image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $image }}" alt="{{ $product->title }}"
                                         class="w-full h-48 object-cover rounded-lg hover:opacity-75 transition-opacity cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Features Section -->
                @if($product->features && $product->features->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">المميزات</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->features as $feature)
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 ml-3"></i>
                                    <span class="text-gray-700">{{ $feature->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">التعليقات</h2>

                    @auth
                        <form action="{{ route('public.products.comment', $product) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">أضف تعليقك</label>
                                <textarea name="comment" id="comment" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                          placeholder="اكتب تعليقك هنا..." required></textarea>
                            </div>
                            <button type="submit" class="btn-primary text-white px-4 py-2 rounded-lg font-medium">
                                إرسال التعليق
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <p class="text-gray-600">يجب <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700">تسجيل الدخول</a> لإضافة تعليق.</p>
                        </div>
                    @endauth


                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Price Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">السعر</h3>
                    <div class="text-3xl font-bold text-primary-600 mb-4">
                        {{ number_format($product->price) }} ريال
                    </div>
                    <div class="space-y-3">
                        <a href="#" class="w-full btn-primary text-white py-3 rounded-lg font-medium text-center block">
                            احجز الآن
                        </a>
                        <a href="#" class="w-full border border-primary-600 text-primary-600 py-3 rounded-lg font-medium text-center block hover:bg-primary-50 transition-colors">
                            طلب عرض سعر
                        </a>
                        @auth
                            <form action="{{ route('public.products.favorite.add', $product) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-medium text-center block hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-heart ml-2"></i>إضافة للمفضلة
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-medium text-center block hover:bg-gray-50 transition-colors">
                                <i class="fas fa-heart ml-2"></i>إضافة للمفضلة
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل العقار</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">النوع</span>
                            <span class="font-semibold text-gray-900">{{ $product->property_type ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الطابق</span>
                            <span class="font-semibold text-gray-900">{{ $product->floor ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">عدد الطوابق</span>
                            <span class="font-semibold text-gray-900">{{ $product->floors_count ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">مواقف السيارات</span>
                            <span class="font-semibold text-gray-900">{{ $product->parking_spaces ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">متاح من</span>
                            <span class="font-semibold text-gray-900">{{ $product->available_from ? $product->available_from->format('Y/m/d') : 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Facility Info -->
                @if($product->facility)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">المنشأة</h3>
                        <div class="flex items-center space-x-3 space-x-reverse mb-4">
                            <img src="{{ $product->facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}"
                                 alt="{{ $product->facility->name }}" class="w-12 h-12 rounded object-cover">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $product->facility->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->facility->category->name ?? '' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('public.facilities.show', $product->facility) }}"
                           class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            عرض المنشأة
                        </a>
                    </div>
                @endif

                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات التواصل</h3>
                    <div class="space-y-3">
                        @if($product->contact_phone)
                            <a href="tel:{{ $product->contact_phone }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-phone ml-3"></i>
                                <span>{{ $product->contact_phone }}</span>
                            </a>
                        @endif
                        @if($product->contact_email)
                            <a href="mailto:{{ $product->contact_email }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-envelope ml-3"></i>
                                <span>{{ $product->contact_email }}</span>
                            </a>
                        @endif
                        @if($product->address)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt ml-3"></i>
                                <span>{{ $product->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
