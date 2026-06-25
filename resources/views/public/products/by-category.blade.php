@extends('layouts.app')

@section('title', __('products.by_category.title', ['category' => $category->name]))

@section('content')
<div class="min-h-screen bg-gray-50">
    @php
        $categoryName = App\Helpers\LanguageHelper::getCategoryName($category);
        $isInvestmentLand = ($category->id == 7) || (str_contains($categoryName, 'استثمار') && (str_contains($categoryName, 'أراضي') || str_contains($categoryName, 'اراضي')));
    @endphp

    @if($isInvestmentLand)
        <div class="bg-gradient-to-b from-slate-50 via-white to-white">
            <section class="relative overflow-hidden">
                <div class="absolute inset-0">
                    <div class="absolute -top-20 -right-24 w-[32rem] h-[32rem] bg-primary-200/40 blur-3xl rounded-full"></div>
                    <div class="absolute -bottom-24 -left-24 w-[34rem] h-[34rem] bg-orange-200/40 blur-3xl rounded-full"></div>
                </div>
                <div class="relative max-w-7xl mx-auto px-4 py-12 md:py-16">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-2 rtl:flex-row-reverse px-3 py-1 rounded-full bg-white/70 backdrop-blur text-primary-700 text-sm font-semibold border border-primary-100">
                                <i class="fas fa-chart-line"></i>
                                <span>شراكات اراضي للاستثمار</span>
                            </div>
                            <h1 class="mt-4 text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                                منصة تربط مالك الارض
                                <span class="text-primary-700">بالمطور المناسب</span>
                            </h1>
                            <p class="mt-4 text-gray-600 text-lg leading-relaxed">
                                اعرض ارضك، استقبل عروض الشراكة، وقارن بين المطورين بخطوات واضحة داخل المنصة.
                            </p>

                            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                <a href="#category-products" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-gray-900 text-white px-7 py-3 rounded-xl hover:bg-black transition-colors">
                                    <i class="fas fa-layer-group"></i>
                                    <span>استعرض الاراضي</span>
                                </a>
                                <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-white border border-gray-900 text-gray-900 px-7 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-handshake"></i>
                                    <span>طلب شراكة</span>
                                </a>
                            </div>

                            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white/80 backdrop-blur rounded-2xl border border-gray-200 p-4">
                                    <div class="text-xs text-gray-500">اراضي وفرص</div>
                                    <div class="mt-1 text-2xl font-extrabold text-gray-900">{{ number_format($stats['properties'] ?? $products->total()) }}</div>
                                </div>
                                <div class="bg-white/80 backdrop-blur rounded-2xl border border-gray-200 p-4">
                                    <div class="text-xs text-gray-500">مطورون/منشات</div>
                                    <div class="mt-1 text-2xl font-extrabold text-gray-900">{{ number_format($stats['developers'] ?? 0) }}</div>
                                </div>
                                <div class="bg-white/80 backdrop-blur rounded-2xl border border-gray-200 p-4">
                                    <div class="text-xs text-gray-500">عروض نشطة</div>
                                    <div class="mt-1 text-2xl font-extrabold text-gray-900">{{ number_format($stats['offers'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="bg-white/90 backdrop-blur rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">ابحث عن فرصة شراكة</div>
                                            <div class="mt-1 text-xs text-gray-500">اختر طريقة البحث المناسبة لك</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-2xl bg-primary-600 text-white grid place-items-center">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>

                                    <div class="mt-5 grid grid-cols-3 gap-2">
                                        <button type="button" class="inv-tab px-3 py-2 rounded-xl text-sm font-semibold border border-gray-200 bg-gray-900 text-white" data-inv-tab="inv-basic" onclick="toggleInvestmentSearchSection('inv-basic')">سريع</button>
                                        <button type="button" class="inv-tab px-3 py-2 rounded-xl text-sm font-semibold border border-gray-200 bg-white text-gray-700 hover:bg-gray-50" data-inv-tab="inv-advanced" onclick="toggleInvestmentSearchSection('inv-advanced')">متقدم</button>
                                        <button type="button" class="inv-tab px-3 py-2 rounded-xl text-sm font-semibold border border-gray-200 bg-white text-gray-700 hover:bg-gray-50" data-inv-tab="inv-map" onclick="toggleInvestmentSearchSection('inv-map')">خريطة</button>
                                    </div>
                                </div>

                                <div class="px-6 pb-6">
                                    <div id="inv-basic">
                                        <form action="{{ route('public.search') }}" method="GET" class="flex search-form" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                                            <input type="text"
                                                   class="flex-1 px-4 py-3 text-gray-900 border border-gray-300 search-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                                   name="q"
                                                   placeholder="ابحث عن ارض، موقع، مطور..."
                                                   required>
                                            <button class="bg-primary-600 text-white px-6 py-3 search-button hover:bg-primary-700 transition-colors" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div id="inv-advanced" class="hidden">
                                        <div class="mb-4 flex items-center justify-center gap-6">
                                            <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                <input type="radio" name="inv_search_type" value="products" checked class="text-blue-600 focus:ring-blue-500" onchange="updateInvestmentAdvancedAction()">
                                                <span class="text-sm font-medium text-gray-700">اراض</span>
                                            </label>
                                            <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                <input type="radio" name="inv_search_type" value="facilities" class="text-blue-600 focus:ring-blue-500" onchange="updateInvestmentAdvancedAction()">
                                                <span class="text-sm font-medium text-gray-700">مطورون</span>
                                            </label>
                                        </div>

                                        <form id="investmentAdvancedSearchForm" method="GET">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="inv_q" class="block text-sm font-medium text-gray-700 mb-2">مصطلح البحث</label>
                                                    <input type="text" id="inv_q" name="q" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="مثال: شراكة، تطوير، تجاري...">
                                                </div>
                                                <div>
                                                    <label for="inv_category_id" class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                                                    <select id="inv_category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="">جميع الفئات</option>
                                                        @foreach(($searchCategories ?? []) as $c)
                                                            <option value="{{ $c->id }}">{{ $c->getTranslatedName() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="inv_min_price" class="block text-sm font-medium text-gray-700 mb-2">الحد الادنى</label>
                                                    <input type="number" id="inv_min_price" name="min_price" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="الحد الادنى">
                                                </div>
                                                <div>
                                                    <label for="inv_max_price" class="block text-sm font-medium text-gray-700 mb-2">الحد الاعلى</label>
                                                    <input type="number" id="inv_max_price" name="max_price" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="الحد الاعلى">
                                                </div>
                                            </div>

                                            <div id="invPropertyDetails" class="mt-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <div>
                                                        <label for="inv_bedrooms" class="block text-sm font-medium text-gray-700 mb-2">غرف النوم</label>
                                                        <select id="inv_bedrooms" name="bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="">اي</option>
                                                            @for($i = 1; $i <= 10; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="inv_bathrooms" class="block text-sm font-medium text-gray-700 mb-2">الحمامات</label>
                                                        <select id="inv_bathrooms" name="bathrooms" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="">اي</option>
                                                            @for($i = 1; $i <= 10; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="inv_min_area" class="block text-sm font-medium text-gray-700 mb-2">اقل مساحة (م²)</label>
                                                        <input type="number" id="inv_min_area" name="min_area" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اقل مساحة">
                                                    </div>
                                                    <div>
                                                        <label for="inv_max_area" class="block text-sm font-medium text-gray-700 mb-2">اعلى مساحة (م²)</label>
                                                        <input type="number" id="inv_max_area" name="max_area" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اعلى مساحة">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                                                <button type="submit" class="inline-flex items-center gap-2 rtl:flex-row-reverse px-8 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-search"></i>
                                                    <span>بحث</span>
                                                </button>
                                                <a href="{{ route('public.search.advanced') }}" class="inline-flex items-center gap-2 rtl:flex-row-reverse px-8 py-3 bg-white border border-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-sliders-h"></i>
                                                    <span>{{ __('public.search.advanced_search_short') }}</span>
                                                </a>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="inv-map" class="hidden">
                                        <form action="{{ route('public.search.map') }}" method="GET" id="investmentMapSearchForm">
                                            <div class="mb-4 flex items-center justify-center gap-6">
                                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                    <input type="radio" name="search_type" value="products" checked onchange="updateInvestmentMapFilters()">
                                                    <span class="text-sm font-medium text-gray-700">مشاريع</span>
                                                </label>
                                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                    <input type="radio" name="search_type" value="facilities" onchange="updateInvestmentMapFilters()">
                                                    <span class="text-sm font-medium text-gray-700">مطورون</span>
                                                </label>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label for="inv_map_category_id" class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                                                    <select id="inv_map_category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="">جميع الفئات</option>
                                                        @foreach(($searchCategories ?? []) as $c)
                                                            <option value="{{ $c->id }}">{{ $c->getTranslatedName() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="invMapMinPrice">
                                                    <label for="inv_map_min_price" class="block text-sm font-medium text-gray-700 mb-2">الحد الادنى</label>
                                                    <input type="number" id="inv_map_min_price" name="min_price" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="الحد الادنى">
                                                </div>
                                                <div id="invMapMaxPrice">
                                                    <label for="inv_map_max_price" class="block text-sm font-medium text-gray-700 mb-2">الحد الاعلى</label>
                                                    <input type="number" id="inv_map_max_price" name="max_price" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="الحد الاعلى">
                                                </div>
                                            </div>

                                            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                                                <button type="submit" class="inline-flex items-center gap-2 rtl:flex-row-reverse px-8 py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-map"></i>
                                                    <span>{{ __('public.search.map_search_short') }}</span>
                                                </button>
                                                <a href="{{ route('public.search.map') }}" class="inline-flex items-center gap-2 rtl:flex-row-reverse px-8 py-3 bg-white border border-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    <span>فتح الخريطة</span>
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="max-w-7xl mx-auto px-4 -mt-2">
                <details class="bg-white border border-gray-200 rounded-3xl p-5 md:p-6">
                    <summary class="cursor-pointer select-none flex items-center justify-between rtl:flex-row-reverse gap-3">
                        <div class="flex items-center gap-2 rtl:flex-row-reverse text-gray-900 font-bold">
                            <i class="fas fa-circle-info text-primary-700"></i>
                            <span>معلومات الشراكة (اختياري)</span>
                        </div>
                        <div class="text-sm text-gray-500">عرض</div>
                    </summary>

                    <div class="mt-5">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div class="bg-white border border-gray-200 rounded-3xl p-6 hover:shadow-sm transition-shadow">
                                <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white grid place-items-center">
                                    <i class="fas fa-file-contract text-xl"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-gray-900">عقود وشراكات</h3>
                                <p class="mt-2 text-sm text-gray-600">تنظيم مسار الشراكة من العرض حتى التعاقد وتوثيق البنود.</p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-3xl p-6 hover:shadow-sm transition-shadow">
                                <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white grid place-items-center">
                                    <i class="fas fa-calendar-check text-xl"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-gray-900">جدولة ومتابعة</h3>
                                <p class="mt-2 text-sm text-gray-600">مواعيد، مستندات، ومراحل واضحة مع اشعارات داخل المنصة.</p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-3xl p-6 hover:shadow-sm transition-shadow">
                                <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white grid place-items-center">
                                    <i class="fas fa-chart-pie text-xl"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-gray-900">شفافية وتقارير</h3>
                                <p class="mt-2 text-sm text-gray-600">مؤشرات، عروض مقارنة، وتقارير تساعدك على اتخاذ قرار صحيح.</p>
                            </div>
                        </div>

                        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-3xl p-6 md:p-8">
                            <div class="flex items-start justify-between gap-6 rtl:flex-row-reverse">
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">كيف تبدأ الشراكة؟</h2>
                                    <p class="mt-2 text-gray-600">اربع خطوات مختصرة وواضحة حتى تصل لاتفاق مناسب.</p>
                                </div>
                                <div class="hidden md:block w-12 h-12 rounded-2xl bg-gray-900 text-white grid place-items-center">
                                    <i class="fas fa-route"></i>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="rounded-2xl border border-gray-200 p-5 bg-white">
                                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                                        <div class="font-bold text-gray-900">تجهيز بيانات الارض</div>
                                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">1</div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">الموقع، المساحة، نوع الاستخدام، والمستندات.</div>
                                </div>
                                <div class="rounded-2xl border border-gray-200 p-5 bg-white">
                                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                                        <div class="font-bold text-gray-900">استقبال عروض الشراكة</div>
                                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">2</div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">عروض واضحة بالنسب والمدة ومسار التنفيذ.</div>
                                </div>
                                <div class="rounded-2xl border border-gray-200 p-5 bg-white">
                                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                                        <div class="font-bold text-gray-900">مقارنة المطورين</div>
                                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">3</div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">الخبرة، المشاريع السابقة، والتقييمات.</div>
                                </div>
                                <div class="rounded-2xl border border-gray-200 p-5 bg-white">
                                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                                        <div class="font-bold text-gray-900">تفاوض وتعاقد</div>
                                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">4</div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">تثبيت البنود ومتابعة المراحل داخل المنصة.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </details>
            </section>
        </div>
    @else
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('products.by_category.title', ['category' => $categoryName]) }}</h1>
                    <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                        {{ __('products.by_category.subtitle', ['category' => $categoryName]) }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(!$isInvestmentLand)
        <div class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-home text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ App\Helpers\LanguageHelper::getCategoryName($category) }}</h2>
                            <p class="text-gray-600">{{ $products->total() }} {{ __('products.by_category.properties_available') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('public.products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-arrow-right ml-2"></i>{{ __('products.by_category.view_all_properties') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div id="category-products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($isInvestmentLand)
            <details class="mb-6 bg-white border border-gray-200 rounded-3xl p-5 md:p-6">
                <summary class="cursor-pointer select-none flex items-center justify-between rtl:flex-row-reverse gap-3">
                    <div class="flex items-center gap-2 rtl:flex-row-reverse">
                        <i class="fas fa-handshake text-primary-700"></i>
                        <span class="text-gray-900 font-extrabold">شراكات أراضي (اختياري)</span>
                    </div>
                    <div class="text-sm text-gray-500">عرض</div>
                </summary>

                <div class="mt-4">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between rtl:md:flex-row-reverse">
                        <div>
                            <h2 class="text-xl md:text-2xl font-extrabold text-gray-900">اعرض أرضك أو اختر مطوّرًا مناسبًا</h2>
                            <p class="mt-2 text-sm md:text-base text-gray-600">ارسل بيانات الأرض لتستقبل عروض الشراكة، أو استعرض الفرص الحالية وابدأ بالمقارنة.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 rtl:flex-row-reverse">
                            <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-gray-900 text-white px-6 py-3 rounded-xl hover:bg-black transition-colors">
                                <i class="fas fa-paper-plane"></i>
                                <span>طلب شراكة</span>
                            </a>
                            <a href="#inv-basic" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-white border border-gray-900 text-gray-900 px-6 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                                <i class="fas fa-search"></i>
                                <span>ابحث عن فرصة</span>
                            </a>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row gap-2 rtl:flex-row-reverse">
                        <a href="#inv-owner" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse px-4 py-2 rounded-xl bg-primary-600 text-white hover:bg-primary-700 transition-colors text-sm font-semibold">
                            <i class="fas fa-user-check"></i>
                            <span>أنا مالك أرض</span>
                        </a>
                        <a href="#inv-developer" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-800 hover:bg-gray-50 transition-colors text-sm font-semibold">
                            <i class="fas fa-building"></i>
                            <span>أنا مطوّر</span>
                        </a>
                        <a href="#inv-basic" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-800 hover:bg-gray-50 transition-colors text-sm font-semibold">
                            <i class="fas fa-filter"></i>
                            <span>فلترة سريعة</span>
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div id="inv-owner" class="rounded-2xl bg-gray-50 border border-gray-200 p-5 scroll-mt-28">
                            <div class="flex items-center justify-between rtl:flex-row-reverse">
                                <div class="font-bold text-gray-900">لمالك الأرض</div>
                                <div class="w-10 h-10 rounded-2xl bg-white border border-gray-200 grid place-items-center text-gray-900">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">جهّز الموقع والمساحة والمستندات، واستقبل عروض واضحة بالنسب والمدة.</p>
                        </div>
                        <div id="inv-developer" class="rounded-2xl bg-gray-50 border border-gray-200 p-5 scroll-mt-28">
                            <div class="flex items-center justify-between rtl:flex-row-reverse">
                                <div class="font-bold text-gray-900">للمطوّر</div>
                                <div class="w-10 h-10 rounded-2xl bg-white border border-gray-200 grid place-items-center text-gray-900">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">استعرض الفرص، تواصل مع الملاك، وقدّم عرض شراكة بمسار تنفيذ واضح.</p>
                        </div>
                    </div>
                </div>
            </details>
        @endif

        <div class="mb-6">
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 rtl:flex-row-reverse flex-wrap">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-gray-700">{{ __('general.nav.home') }}</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('public.products.index') }}" class="hover:text-gray-700">{{ __('general.nav.properties') }}</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900 font-semibold">{{ $categoryName }}</li>
                </ol>
            </nav>

            <div class="mt-4 sticky top-4 z-30">
                <div class="bg-white/90 backdrop-blur border border-gray-200 rounded-2xl px-4 py-3 shadow-sm">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900">{{ $categoryName }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ number_format($products->total() ?? 0) }} {{ __('products.by_category.properties_available') }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 rtl:flex-row-reverse sm:items-center">
                            <a href="{{ route('public.products.by-category', $category) }}" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-800 hover:bg-gray-50 transition-colors text-sm font-semibold">
                                <i class="fas fa-rotate-left"></i>
                                <span>إعادة تعيين</span>
                            </a>

                            <form method="GET" class="flex items-center gap-3 rtl:flex-row-reverse" id="category-sort-form">
                                @foreach(request()->except(['sort_by','sort_order','page']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $v)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach

                                <input type="hidden" name="sort_by" id="category_sort_by" value="{{ $sortBy ?? request('sort_by', 'created_at') }}">
                                <input type="hidden" name="sort_order" id="category_sort_order" value="{{ $sortOrder ?? request('sort_order', 'desc') }}">

                                <label class="text-sm text-gray-600" for="category_sort_selector">{{ __('products.search.sort_by') }}</label>
                                <select id="category_sort_selector" class="px-3 py-2 border border-gray-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                    <option value="created_at_desc" {{ ((($sortBy ?? request('sort_by','created_at'))==='created_at') && (($sortOrder ?? request('sort_order','desc'))==='desc')) ? 'selected' : '' }}>{{ __('products.search.latest') }}</option>
                                    <option value="price_asc" {{ ((($sortBy ?? request('sort_by'))==='price') && (($sortOrder ?? request('sort_order'))==='asc')) ? 'selected' : '' }}>{{ __('products.search.price_low_to_high') }}</option>
                                    <option value="price_desc" {{ ((($sortBy ?? request('sort_by'))==='price') && (($sortOrder ?? request('sort_order'))==='desc')) ? 'selected' : '' }}>{{ __('products.search.price_high_to_low') }}</option>
                                    <option value="title_asc" {{ ((($sortBy ?? request('sort_by'))==='title') && (($sortOrder ?? request('sort_order'))==='asc')) ? 'selected' : '' }}>{{ __('products.search.name') }}</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    @if($isInvestmentLand)
                        <div class="mt-3 flex flex-col md:flex-row gap-2 rtl:md:flex-row-reverse">
                            <div class="flex items-center gap-2 rtl:flex-row-reverse text-xs text-gray-600 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                                <i class="fas fa-info-circle text-gray-500"></i>
                                <span>هذا القسم مخصص لفرص شراكات الأراضي. السعر قد يظهر عند الحاجة داخل تفاصيل الإعلان.</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <x-multi-view-grid 
                :items="$products" 
                type="products" 
                :showPagination="true"
                :showViewToggle="true"
                :showPrice="!$isInvestmentLand"
                idPrefix="products"
            />
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('products.by_category.no_properties') }}</h3>
                    @if($isInvestmentLand)
                        <p class="text-gray-600 mb-6">لا توجد فرص منشورة حاليًا. ارسل بيانات أرضك لتبدأ باستقبال عروض الشراكة، أو جرّب البحث بكلمات مختلفة.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center rtl:flex-row-reverse">
                            <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-gray-900 text-white px-6 py-3 rounded-xl hover:bg-black transition-colors">
                                <i class="fas fa-paper-plane"></i>
                                <span>طلب شراكة</span>
                            </a>
                            <a href="#inv-basic" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-white border border-gray-200 text-gray-800 px-6 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                                <i class="fas fa-search"></i>
                                <span>بحث</span>
                            </a>
                        </div>
                    @else
                        <p class="text-gray-600 mb-6">{{ __('products.by_category.no_properties_message', ['category' => App\Helpers\LanguageHelper::getCategoryName($category)]) }}</p>
                        <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('products.by_category.view_all_properties') }}
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Category Description -->
    @if($category->description)
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('products.by_category.category_description', ['category' => App\Helpers\LanguageHelper::getCategoryName($category)]) }}</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        @categoryDescription($category)
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Related Categories -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('products.by_category.other_categories') }}</h2>
                <p class="text-lg text-gray-600">{{ __('products.by_category.browse_other_categories') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories ?? [] as $relatedCategory)
                    @if($relatedCategory->id !== $category->id)
                        <a href="{{ route('public.products.by-category', $relatedCategory) }}"
                           class="bg-white rounded-lg p-6 text-center hover:bg-primary-50 transition-colors shadow-md">
                            <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-home text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">@categoryName($relatedCategory)</h3>
                            <p class="text-gray-600 text-sm">{{ $relatedCategory->products_count ?? 0 }} {{ __('products.categories.properties_count') }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
[dir="rtl"] .search-form { flex-direction: row-reverse; }
[dir="rtl"] .search-input { border-radius: 0 0.75rem 0.75rem 0; }
[dir="rtl"] .search-button { border-radius: 0.75rem 0 0 0.75rem; }
[dir="ltr"] .search-form { flex-direction: row; }
[dir="ltr"] .search-input { border-radius: 0 0.75rem 0.75rem 0; }
[dir="ltr"] .search-button { border-radius: 0.75rem 0 0 0.75rem; }
</style>
@endpush

@push('scripts')
<script>
 (function(){
     const selector = document.getElementById('category_sort_selector');
     const sortBy = document.getElementById('category_sort_by');
     const sortOrder = document.getElementById('category_sort_order');
     const form = document.getElementById('category-sort-form');
     if (!selector || !sortBy || !sortOrder || !form) return;

     selector.addEventListener('change', function(){
         const value = this.value;
         if (value === 'price_asc') { sortBy.value = 'price'; sortOrder.value = 'asc'; }
         else if (value === 'price_desc') { sortBy.value = 'price'; sortOrder.value = 'desc'; }
         else if (value === 'title_asc') { sortBy.value = 'title'; sortOrder.value = 'asc'; }
         else { sortBy.value = 'created_at'; sortOrder.value = 'desc'; }
         form.submit();
     });
 })();

function toggleInvestmentSearchSection(sectionId) {
    const allSections = ['inv-basic', 'inv-advanced', 'inv-map'];
    const tabs = document.querySelectorAll('[data-inv-tab]');

    tabs.forEach(tab => {
        const isActive = tab.getAttribute('data-inv-tab') === sectionId;
        tab.classList.toggle('bg-gray-900', isActive);
        tab.classList.toggle('text-white', isActive);
        tab.classList.toggle('bg-white', !isActive);
        tab.classList.toggle('text-gray-700', !isActive);
    });

    allSections.forEach(id => {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }
        if (id === sectionId) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    });
}

function updateInvestmentAdvancedAction() {
    const form = document.getElementById('investmentAdvancedSearchForm');
    if (!form) {
        return;
    }

    const selected = document.querySelector('input[name="inv_search_type"]:checked');
    const type = selected ? selected.value : 'products';
    const details = document.getElementById('invPropertyDetails');

    if (type === 'products') {
        form.action = '{{ route("public.search.products") }}';
        if (details) {
            details.classList.remove('hidden');
        }
    } else {
        form.action = '{{ route("public.search.facilities") }}';
        if (details) {
            details.classList.add('hidden');
        }
    }
}

function updateInvestmentMapFilters() {
    const selected = document.querySelector('#investmentMapSearchForm input[name="search_type"]:checked');
    const type = selected ? selected.value : 'products';
    const minPrice = document.getElementById('invMapMinPrice');
    const maxPrice = document.getElementById('invMapMaxPrice');

    const showPrice = type === 'products';
    if (minPrice) {
        minPrice.style.display = showPrice ? 'block' : 'none';
    }
    if (maxPrice) {
        maxPrice.style.display = showPrice ? 'block' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateInvestmentAdvancedAction();
    updateInvestmentMapFilters();
});
</script>
@endpush
