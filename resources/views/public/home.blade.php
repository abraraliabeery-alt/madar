@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="text-center py-16 mb-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">مرحباً بك في منصة العقار</h1>
        <p class="text-lg text-gray-600 mb-8">اكتشف أفضل العقارات والمنشآت العقارية في منطقتك</p>
        
        <!-- Search Form -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('public.search') }}" method="GET" class="flex">
                <input type="text" 
                       class="flex-1 px-4 py-3 text-gray-900 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                       name="q" 
                       placeholder="ابحث عن عقار، منشأة، أو منطقة..." 
                       required>
                <button class="bg-primary-600 text-white px-6 py-3 rounded-l-lg hover:bg-primary-700 transition-colors" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Featured Products -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <section class="mb-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">أحدث العقارات</h2>
            
            <!-- View Toggle -->
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm text-gray-600 mr-3 rtl:ml-3 rtl:mr-0">عرض:</span>
                <button id="grid-view" 
                        class="view-toggle-btn bg-primary-600 text-white p-2 rounded-lg transition-colors"
                        onclick="switchView('grid')">
                    <i class="fas fa-th-large"></i>
                </button>
                <button id="row-view" 
                        class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                        onclick="switchView('row')">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Grid View -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProducts->take(6) as $product)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <!-- Product Image -->
                <div class="relative h-48 bg-gray-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-home text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badges -->
                    @if($product->is_featured)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">مميز</span>
                    @endif
                    @if($product->is_verified)
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">متحقق</span>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $product->title }}</h3>
                    
                    <!-- Key Details -->
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                        @if($product->area)
                            <span><i class="fas fa-ruler-combined ml-1"></i>{{ $product->area }} م²</span>
                        @endif
                        @if($product->rooms)
                            <span><i class="fas fa-bed ml-1"></i>{{ $product->rooms }}</span>
                        @endif
                        @if($product->bathrooms)
                            <span><i class="fas fa-bath ml-1"></i>{{ $product->bathrooms }}</span>
                        @endif
                    </div>
                    
                    <!-- Location -->
                    @if($product->city)
                        <div class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-map-marker-alt ml-1"></i>{{ $product->city->name }}
                        </div>
                    @endif
                    
                    <!-- Price and Action -->
                    <div class="flex justify-between items-center">
                        @if($product->price)
                            <span class="text-xl font-bold text-primary-600">{{ number_format($product->price) }} ريال</span>
                        @else
                            <span class="text-gray-500">السعر عند الطلب</span>
                        @endif
                        <a href="{{ route('public.products.show', $product->id) }}" 
                           class="text-primary-600 hover:text-primary-700 font-medium">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Row View (Hidden by default) -->
        <div id="products-row" class="hidden space-y-4">
            @foreach($featuredProducts->take(6) as $product)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex">
                    <!-- Product Image -->
                    <div class="relative w-48 h-32 bg-gray-100 flex-shrink-0">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-home text-2xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badges -->
                        @if($product->is_featured)
                            <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">مميز</span>
                        @endif
                        @if($product->is_verified)
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">متحقق</span>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-900 text-lg">{{ $product->title }}</h3>
                            @if($product->price)
                                <span class="text-xl font-bold text-primary-600">{{ number_format($product->price) }} ريال</span>
                            @else
                                <span class="text-gray-500">السعر عند الطلب</span>
                            @endif
                        </div>
                        
                        <!-- Key Details -->
                        <div class="flex items-center gap-6 text-sm text-gray-600 mb-3">
                            @if($product->area)
                                <span><i class="fas fa-ruler-combined ml-1"></i>{{ $product->area }} م²</span>
                            @endif
                            @if($product->rooms)
                                <span><i class="fas fa-bed ml-1"></i>{{ $product->rooms }}</span>
                            @endif
                            @if($product->bathrooms)
                                <span><i class="fas fa-bath ml-1"></i>{{ $product->bathrooms }}</span>
                            @endif
                        </div>
                        
                        <!-- Location and Action -->
                        <div class="flex justify-between items-center">
                            @if($product->city)
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt ml-1"></i>{{ $product->city->name }}
                                </div>
                            @endif
                            <a href="{{ route('public.products.show', $product->id) }}" 
                               class="text-primary-600 hover:text-primary-700 font-medium">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('public.products.index') }}" 
               class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                عرض جميع العقارات
            </a>
        </div>
    </section>
    @endif

    <!-- Featured Cities -->
    @if(isset($featuredCities) && $featuredCities->count() > 0)
    <section class="mb-16">
        <h2 class="text-2xl font-semibold text-gray-900 mb-8">المدن المميزة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredCities as $city)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="relative h-40 bg-gray-100">
                    @if($city->image)
                        <img src="{{ asset('storage/' . $city->image) }}" 
                             alt="{{ $city->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-city text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2 bg-white text-primary-600 px-2 py-1 rounded text-sm">
                        {{ $city->products_count }} عقار
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-2">{{ $city->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $city->description }}</p>
                    <a href="{{ route('public.products.index', ['city' => $city->id]) }}" 
                       class="text-primary-600 hover:text-primary-700 font-medium">
                        استعرض العقارات
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('public.cities.index') }}" 
               class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                عرض جميع المدن
            </a>
        </div>
    </section>
    @endif

    <!-- Featured Categories -->
    @if(isset($categories) && $categories->count() > 0)
    <section class="mb-16">
        <h2 class="text-2xl font-semibold text-gray-900 mb-8">الفئات المميزة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories->take(4) as $category)
            <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                @if($category->icon)
                    <i class="{{ $category->icon }} text-4xl text-primary-600 mb-4"></i>
                @else
                    <i class="fas fa-building text-4xl text-primary-600 mb-4"></i>
                @endif
                <h3 class="font-medium text-gray-900 mb-2">{{ $category->display_name ?? $category->name }}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $category->description }}</p>
                <div class="text-sm text-gray-500 mb-4">{{ $category->products_count }} عقار</div>
                <a href="{{ route('public.products.by-category', $category->id) }}" 
                   class="text-primary-600 hover:text-primary-700 font-medium">
                    استعرض الفئة
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    <section class="bg-gray-50 rounded-lg p-8 text-center">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">هل تريد بيع أو إيجار عقارك؟</h2>
        <p class="text-gray-600 mb-6">انضم إلينا واحصل على أفضل الخدمات العقارية</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                سجل الآن
            </a>
            <a href="{{ route('public.contact') }}" 
               class="border border-primary-600 text-primary-600 px-6 py-3 rounded-lg hover:bg-primary-50 transition-colors">
                تواصل معنا
            </a>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.view-toggle-btn {
    transition: all 0.2s ease-in-out;
}

.view-toggle-btn:hover {
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
function switchView(viewType) {
    const gridView = document.getElementById('products-grid');
    const rowView = document.getElementById('products-row');
    const gridBtn = document.getElementById('grid-view');
    const rowBtn = document.getElementById('row-view');
    
    if (viewType === 'grid') {
        gridView.classList.remove('hidden');
        rowView.classList.add('hidden');
        gridBtn.classList.remove('bg-gray-200', 'text-gray-600');
        gridBtn.classList.add('bg-primary-600', 'text-white');
        rowBtn.classList.remove('bg-primary-600', 'text-white');
        rowBtn.classList.add('bg-gray-200', 'text-gray-600');
    } else {
        rowView.classList.remove('hidden');
        gridView.classList.add('hidden');
        rowBtn.classList.remove('bg-gray-200', 'text-gray-600');
        rowBtn.classList.add('bg-primary-600', 'text-white');
        gridBtn.classList.remove('bg-primary-600', 'text-white');
        gridBtn.classList.add('bg-gray-200', 'text-gray-600');
    }
    
    // Store user preference in localStorage
    localStorage.setItem('preferredView', viewType);
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('preferredView') || 'grid';
    switchView(preferredView);
});
</script>
@endpush
