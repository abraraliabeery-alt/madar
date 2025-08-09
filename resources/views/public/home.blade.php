@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<!-- Hero Section -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                مرحباً بك في منصة عقار
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                منصة عقارية متكاملة تقدم أفضل الخدمات في مجال العقارات
            </p>

            <!-- Search Form -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl p-6 shadow-2xl">
                    <form role="search" action="{{ route('public.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="home-search-form">
                        <div>
                            <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">نوع العقار</label>
                            <select id="property_type" name="property_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-black">
                                <option value="">جميع الأنواع</option>
                                <option value="شقة">شقة</option>
                                <option value="فيلا">فيلا</option>
                                <option value="مكتب">مكتب</option>
                                <option value="محل تجاري">محل تجاري</option>
                            </select>
                        </div>
                        <div>
                            <label for="q" class="block text-sm font-medium text-gray-700 mb-2">الموقع أو الكلمات المفتاحية</label>
                            <input id="q" name="q" type="text" placeholder="أدخل المدينة أو الحي أو كلمة بحث" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900">
                        </div>
                        <div>
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">السعر</label>
                            <select id="price_range" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-black">
                                <option value="any">جميع الأسعار</option>
                                <option value="lt-500000">أقل من 500,000 ريال</option>
                                <option value="500000-1000000">500,000 - 1,000,000 ريال</option>
                                <option value="gt-1000000">أكثر من 1,000,000 ريال</option>
                            </select>
                            <input type="hidden" name="min_price" id="min_price" value="">
                            <input type="hidden" name="max_price" id="max_price" value="">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full btn-primary text-white px-6 py-3 rounded-lg font-medium">
                                <i class="fas fa-search ml-2"></i>بحث
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">من نحن</h2>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                منصة عقار هي منصة متكاملة تقدم خدمات عقارية شاملة تشمل بيع وتأجير وإدارة العقارات.
                نهدف إلى تسهيل عملية البحث عن العقارات المناسبة وتقديم أفضل الخدمات لعملائنا.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">البحث السريع</h3>
                <p class="text-gray-600">ابحث عن عقارك المثالي بسهولة وسرعة مع أدوات بحث متقدمة</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">موثوقية عالية</h3>
                <p class="text-gray-600">جميع العقارات والمنشآت معتمدة ومتحقق منها</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">دعم متواصل</h3>
                <p class="text-gray-600">فريق دعم متخصص لمساعدتك في كل خطوة</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">إحصائيات المنصة</h2>
            <p class="text-gray-600 text-lg">أرقام تتحدث عن نجاحنا</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format($stats['total_products'] ?? 0) }}+</div>
                <div class="text-gray-600">عقار متاح</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format($stats['total_facilities'] ?? 0) }}+</div>
                <div class="text-gray-600">منشأة عقارية</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format($stats['total_categories'] ?? 0) }}</div>
                <div class="text-gray-600">فئة عقارية</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format($stats['featured_products'] ?? 0) }}+</div>
                <div class="text-gray-600">منتج مميز</div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">خدماتنا</h2>
            <p class="text-gray-600 text-lg">نقدم مجموعة شاملة من الخدمات العقارية</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6 text-center card-hover">
                <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-home text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">بيع العقارات</h3>
                <p class="text-gray-600 text-sm mb-4">بيع العقارات السكنية والتجارية</p>
                <a href="#" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                    اعرف المزيد <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-6 text-center card-hover">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">تأجير العقارات</h3>
                <p class="text-gray-600 text-sm mb-4">تأجير العقارات قصيرة وطويلة المدى</p>
                <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm">
                    اعرف المزيد <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-violet-100 rounded-xl p-6 text-center card-hover">
                <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إدارة الممتلكات</h3>
                <p class="text-gray-600 text-sm mb-4">إدارة شاملة للممتلكات العقارية</p>
                <a href="#" class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                    اعرف المزيد <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-amber-100 rounded-xl p-6 text-center card-hover">
                <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">الاستشارات</h3>
                <p class="text-gray-600 text-sm mb-4">استشارات متخصصة في مجال العقارات</p>
                <a href="#" class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                    اعرف المزيد <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Coming Soon Properties -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">عقارات قادمة</h2>
            <p class="text-gray-600 text-lg">عقارات ستتوفر قريباً — احفظها وتابع توفرها</p>
        </div>

        @if(isset($comingSoonProducts) && $comingSoonProducts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($comingSoonProducts as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                        <div class="relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
                            <div class="absolute top-4 right-4 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                قريباً
                            </div>
                            @if(!is_null($product->price))
                                <div class="absolute top-4 left-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ number_format($product->price) }} ريال
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600">
                                    {{ $product->title }}
                                </a>
                            </h3>
                            @if($product->address)
                                <p class="text-gray-600 text-sm mb-4">{{ $product->address }}</p>
                            @endif
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                @if(!is_null($product->rooms))
                                    <span><i class="fas fa-bed ml-1"></i>{{ $product->rooms }} غرف</span>
                                @endif
                                @if(!is_null($product->bathrooms))
                                    <span><i class="fas fa-bath ml-1"></i>{{ $product->bathrooms }} حمام</span>
                                @endif
                                @if(!is_null($product->area))
                                    <span><i class="fas fa-ruler-combined ml-1"></i>{{ (int) $product->area }} م²</span>
                                @endif
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ optional($product->facility)->name ?? 'منشأة غير محددة' }}</span>
                                    @if($product->available_from)
                                        <span class="text-primary-600 font-medium text-sm">متاح من {{ $product->available_from->format('Y-m-d') }}</span>
                                    @else
                                        <span class="text-primary-600 font-medium text-sm">قريباً</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center mt-12">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-2xl mx-auto">
                    <h3 class="text-xl font-semibold text-blue-900 mb-2">لا توجد عقارات قادمة حالياً</h3>
                    <p class="text-blue-700">
                        تابعنا لمعرفة أحدث الإضافات والعقارات القادمة.
                    </p>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">تواصل معنا</h2>
            <p class="text-gray-600 text-lg">نحن هنا لمساعدتك في كل ما تحتاجه</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone text-primary-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">اتصل بنا</h3>
                <p class="text-gray-600">+966 50 123 4567</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-primary-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">راسلنا</h3>
                <p class="text-gray-600">info@aqar.com</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-primary-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">موقعنا</h3>
                <p class="text-gray-600">الرياض، المملكة العربية السعودية</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg text-white">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">انضم إلينا قريباً</h2>
        <p class="text-xl mb-8 text-blue-100">
            سجل بريدك الإلكتروني لتصلك آخر التحديثات والإعلانات
        </p>
        <div class="max-w-md mx-auto">
            <div class="flex">
                <input type="email" placeholder="أدخل بريدك الإلكتروني" class="flex-1 px-4 py-3 rounded-r-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                <button class="bg-white text-primary-600 px-6 py-3 rounded-l-lg font-medium hover:bg-gray-100 transition-colors">
                    اشتراك
                </button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #4facfe 0%, #0083fe 100%);
    }

    .container {
        max-width: 1200px;
    }

    /* RTL Support */
    .rtl {
        direction: rtl;
        text-align: right;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* New styles for the new design */
    .btn-primary {
        background-color: #4facfe;
        border-color: #4facfe;
    }

    .btn-primary:hover {
        background-color: #00f2fe;
        border-color: #00f2fe;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
    <script>
        (function() {
            function ready(fn){
                if (document.readyState !== 'loading') fn();
                else document.addEventListener('DOMContentLoaded', fn);
            }
            ready(function(){
                const priceRange = document.getElementById('price_range');
                const minInput = document.getElementById('min_price');
                const maxInput = document.getElementById('max_price');
                const form = document.getElementById('home-search-form');
                if (!priceRange || !minInput || !maxInput || !form) return;

                const applyRange = (value) => {
                    switch (value) {
                        case 'lt-500000':
                            minInput.value = '';
                            maxInput.value = '500000';
                            break;
                        case '500000-1000000':
                            minInput.value = '500000';
                            maxInput.value = '1000000';
                            break;
                        case 'gt-1000000':
                            minInput.value = '1000000';
                            maxInput.value = '';
                            break;
                        default:
                            minInput.value = '';
                            maxInput.value = '';
                    }
                };

                priceRange.addEventListener('change', (e) => applyRange(e.target.value));
                form.addEventListener('submit', () => applyRange(priceRange.value));
            });
        })();
    </script>
@endpush
