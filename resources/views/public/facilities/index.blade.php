@extends('layouts.app')

@section('title', 'المنشآت')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">المنشآت العقارية</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    اكتشف أفضل المنشآت العقارية المعتمدة والموثوقة في المملكة
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('public.facilities.index') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                               placeholder="ابحث عن منشأة...">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                        <select name="category" id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">جميع الفئات</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">الموقع</label>
                        <select name="location" id="location"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">جميع المواقع</option>
                            <option value="riyadh" {{ request('location') == 'riyadh' ? 'selected' : '' }}>الرياض</option>
                            <option value="jeddah" {{ request('location') == 'jeddah' ? 'selected' : '' }}>جدة</option>
                            <option value="dammam" {{ request('location') == 'dammam' ? 'selected' : '' }}>الدمام</option>
                            <option value="makkah" {{ request('location') == 'makkah' ? 'selected' : '' }}>مكة المكرمة</option>
                            <option value="medina" {{ request('location') == 'medina' ? 'selected' : '' }}>المدينة المنورة</option>
                        </select>
                    </div>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">التقييم</label>
                        <select name="rating" id="rating"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">جميع التقييمات</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 نجوم</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 نجوم وأكثر</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 نجوم وأكثر</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-search ml-2"></i>بحث
                    </button>
                    <a href="{{ route('public.facilities.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        مسح الفلاتر
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Facilities Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">نتائج البحث</h2>
                <p class="text-gray-600">{{ $facilities->total() ?? 0 }} منشأة متاحة</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <span class="text-sm text-gray-600">ترتيب حسب:</span>
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="latest">الأحدث</option>
                    <option value="rating">التقييم الأعلى</option>
                    <option value="name">الاسم</option>
                    <option value="products">عدد العقارات</option>
                </select>
            </div>
        </div>

        @if(isset($facilities) && $facilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $facility)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                        <div class="relative">
                            <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                 alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                            @if($facility->is_featured)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    مميزة
                                </div>
                            @endif
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
                                <span>{{ $facility->location ?? 'موقع غير محدد' }}</span>
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
                    <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد نتائج</h3>
                    <p class="text-gray-600 mb-6">لم نتمكن من العثور على منشآت تطابق معايير البحث الخاصة بك.</p>
                    <a href="{{ route('public.facilities.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        عرض جميع المنشآت
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Featured Categories -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">فئات المنشآت</h2>
                <p class="text-lg text-gray-600">تصفح المنشآت حسب التخصص</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories ?? [] as $category)
                    <a href="{{ route('public.facilities.by-category', $category) }}"
                       class="bg-gray-50 rounded-lg p-6 text-center hover:bg-primary-50 transition-colors">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-building text-primary-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $category->facilities_count ?? 0 }} منشأة</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-primary-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">إحصائيات المنشآت</h2>
                <p class="text-primary-100">أرقام تعكس ثقة عملائنا في منشآتنا المعتمدة</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['total_facilities'] ?? 0 }}</div>
                    <div class="text-primary-100">منشأة معتمدة</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['verified_facilities'] ?? 0 }}</div>
                    <div class="text-primary-100">منشأة موثقة</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['total_products'] ?? 0 }}</div>
                    <div class="text-primary-100">عقار متاح</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $stats['satisfied_clients'] ?? 0 }}</div>
                    <div class="text-primary-100">عميل راضي</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
