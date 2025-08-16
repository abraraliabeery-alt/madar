@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Hero Section -->
    <div class="hero-section text-center py-20 bg-gradient-to-br from-primary-600 to-primary-800 text-white rounded-lg my-8">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="text-5xl font-bold mb-6">مرحباً بك في منصة العقار</h1>
            <p class="text-xl mb-8 text-primary-100">اكتشف أفضل العقارات والمنشآت العقارية في منطقتك</p>
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('public.search') }}" method="GET" class="search-form">
                    <div class="flex shadow-lg rounded-lg overflow-hidden">
                        <input type="text" class="flex-1 px-6 py-4 text-lg text-gray-900 placeholder-gray-500 focus:outline-none" name="q" placeholder="ابحث عن عقار، منشأة، أو منطقة..." required>
                        <button class="bg-white text-primary-600 px-8 py-4 font-semibold hover:bg-gray-50 transition-colors duration-200" type="submit">
                            <i class="fas fa-search mr-2"></i> بحث
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Featured Cities -->
    @if(isset($featuredCities) && $featuredCities->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">المدن المميزة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredCities as $city)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden city-card">
                    <div class="relative">
                        @if($city->image)
                            <img src="{{ asset('storage/' . $city->image) }}" alt="{{ $city->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="bg-gradient-to-br from-primary-500 to-primary-700 h-48 flex items-center justify-center">
                                <i class="fas fa-city text-6xl text-white opacity-80"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 bg-white text-primary-600 px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                            {{ $city->products_count }} عقار
                        </div>
                    </div>
                    <div class="p-6">
                        <h5 class="text-xl font-semibold mb-3 text-gray-800">{{ $city->name }}</h5>
                        <p class="text-gray-600 mb-4">{{ Str::limit($city->description, 100) }}</p>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $city->name }}
                            </div>
                            <a href="{{ route('public.products.index', ['city' => $city->id]) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors duration-200">استعرض العقارات</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('public.cities.index') }}" class="inline-block bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-700 transition-colors duration-200">عرض جميع المدن</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Categories -->
    @if(isset($categories) && $categories->count() > 0)
    <section class="py-16">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">الفئات المميزة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($categories->take(4) as $category)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-8 text-center city-card">
                    @if($category->icon)
                        <i class="{{ $category->icon }} text-6xl text-primary-600 mb-6"></i>
                    @else
                        <i class="fas fa-building text-6xl text-primary-600 mb-6"></i>
                    @endif
                    <h5 class="text-xl font-semibold mb-4 text-gray-800">{{ $category->display_name ?? $category->name }}</h5>
                    <p class="text-gray-600 mb-6">{{ Str::limit($category->description, 80) }}</p>
                    <div class="text-sm text-gray-500 mb-4">{{ $category->products_count }} عقار</div>
                    <a href="{{ route('public.products.by-category', $category->id) }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors duration-200">استعرض الفئة</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Products -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">أحدث العقارات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredProducts->take(6) as $product)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden property-card">
                    <div class="relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="bg-gray-300 h-48 flex items-center justify-center">
                                <i class="fas fa-image text-6xl text-gray-500"></i>
                            </div>
                        @endif
                        @if($product->is_featured)
                            <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">مميز</div>
                        @endif
                        @if($product->is_verified)
                            <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">متحقق</div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h5 class="text-xl font-semibold mb-3 text-gray-800">{{ Str::limit($product->title, 50) }}</h5>
                        <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                        
                        <!-- Product Details -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            @if($product->area)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-ruler-combined mr-1"></i>
                                {{ $product->area }} م²
                            </div>
                            @endif
                            @if($product->rooms)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-bed mr-1"></i>
                                {{ $product->rooms }} غرفة
                            </div>
                            @endif
                            @if($product->bathrooms)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-bath mr-1"></i>
                                {{ $product->bathrooms }} حمام
                            </div>
                            @endif
                            @if($product->city)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $product->city->name }}
                            </div>
                            @endif
                        </div>
                        
                        @if($product->facility)
                        <div class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-building mr-1"></i>
                            {{ $product->facility->name }}
                        </div>
                        @endif
                        
                        @if($product->category)
                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $product->category->display_name ?? $product->category->name }}
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            @if($product->price)
                                <span class="text-2xl font-bold text-primary-600">{{ number_format($product->price) }} ريال</span>
                            @else
                                <span class="text-lg text-gray-500">السعر عند الطلب</span>
                            @endif
                            <a href="{{ route('public.products.show', $product->id) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors duration-200">عرض التفاصيل</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('public.products.index') }}" class="inline-block bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-700 transition-colors duration-200">عرض جميع العقارات</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Facilities -->
    @if(isset($featuredFacilities) && $featuredFacilities->count() > 0)
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">المنشآت المميزة</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredFacilities->take(3) as $facility)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden city-card">
                    <div class="relative">
                        @if($facility->logo)
                            <img src="{{ asset('storage/' . $facility->logo) }}" alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="bg-gray-300 h-48 flex items-center justify-center">
                                <i class="fas fa-building text-6xl text-gray-500"></i>
                            </div>
                        @endif
                        @if($facility->is_featured)
                            <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">مميز</div>
                        @endif
                        @if($facility->is_verified)
                            <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">متحقق</div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h5 class="text-xl font-semibold mb-3 text-gray-800">{{ Str::limit($facility->name, 50) }}</h5>
                        <p class="text-gray-600 mb-4">{{ Str::limit($facility->description, 100) }}</p>
                        
                        @if($facility->category)
                        <div class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $facility->category->display_name ?? $facility->category->name }}
                        </div>
                        @endif
                        
                        @if($facility->address)
                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ Str::limit($facility->address, 60) }}
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            @if($facility->rating)
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    <span class="text-gray-700">{{ number_format($facility->rating, 1) }}</span>
                                </div>
                            @endif
                            <a href="{{ route('public.facilities.show', $facility->id) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors duration-200">عرض التفاصيل</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('public.facilities.index') }}" class="inline-block bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-700 transition-colors duration-200">عرض جميع المنشآت</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-br from-primary-600 to-primary-800 text-white text-center rounded-2xl my-8 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute top-1/2 right-0 w-24 h-24 bg-white rounded-full translate-x-12"></div>
            <div class="absolute bottom-0 left-1/3 w-20 h-20 bg-white rounded-full -translate-y-10"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h2 class="text-4xl font-bold mb-6">هل تريد بيع أو إيجار عقارك؟</h2>
            <p class="text-xl mb-10 text-primary-100 leading-relaxed">انضم إلينا واحصل على أفضل الخدمات العقارية مع ضمان الجودة والموثوقية</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="inline-block bg-white text-primary-600 px-10 py-4 rounded-xl text-lg font-semibold hover:bg-gray-50 hover:scale-105 transition-all duration-300 shadow-lg">
                    <i class="fas fa-user-plus ml-2"></i>سجل الآن
                </a>
                <a href="{{ route('public.contact') }}" class="inline-block border-2 border-white text-white px-10 py-4 rounded-xl text-lg font-semibold hover:bg-white hover:text-primary-600 hover:scale-105 transition-all duration-300">
                    <i class="fas fa-phone ml-2"></i>تواصل معنا
                </a>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="space-y-2">
                    <i class="fas fa-shield-alt text-3xl text-primary-200"></i>
                    <p class="text-primary-100">حماية كاملة</p>
                </div>
                <div class="space-y-2">
                    <i class="fas fa-clock text-3xl text-primary-200"></i>
                    <p class="text-primary-100">خدمة 24/7</p>
                </div>
                <div class="space-y-2">
                    <i class="fas fa-star text-3xl text-primary-200"></i>
                    <p class="text-primary-100">جودة عالية</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
}

.search-form .flex {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-slide-in-left {
    animation: slideInLeft 0.6s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.6s ease-out;
}

/* Image hover effects */
.card-image {
    transition: transform 0.3s ease;
}

.card-image:hover {
    transform: scale(1.05);
}

/* Card hover effects */
.property-card {
    transition: all 0.3s ease;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Stats section */
.stats-item {
    transition: all 0.3s ease;
}

.stats-item:hover {
    transform: scale(1.05);
}

/* City cards */
.city-card {
    transition: all 0.3s ease;
}

.city-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .stats-item {
        margin-bottom: 1rem;
    }
}
</style>
@endpush
