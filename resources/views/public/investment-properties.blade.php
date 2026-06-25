@extends('layouts.app')

@section('title', 'مشاريع للاستثمار')

@section('content')
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
                        <a href="#investment-list" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-gray-900 text-white px-7 py-3 rounded-xl hover:bg-black transition-colors">
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
                            <div class="mt-1 text-2xl font-extrabold text-gray-900">{{ number_format($stats['properties'] ?? 0) }}</div>
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
                                                @foreach(($searchCategories ?? []) as $category)
                                                    <option value="{{ $category->id }}">{{ $category->getTranslatedName() }}</option>
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

                                        <div class="mt-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                    <input type="radio" name="property_type" value="sale" class="text-blue-600 focus:ring-blue-500">
                                                    <span class="text-sm font-medium text-gray-700">للبيع</span>
                                                </label>
                                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                    <input type="radio" name="property_type" value="rent" class="text-blue-600 focus:ring-blue-500">
                                                    <span class="text-sm font-medium text-gray-700">للايجار</span>
                                                </label>
                                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                                    <input type="radio" name="property_type" value="" checked class="text-blue-600 focus:ring-blue-500">
                                                    <span class="text-sm font-medium text-gray-700">كلاهما</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($searchFeatures) && $searchFeatures->count() > 0)
                                        <div class="mt-6">
                                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                @foreach($searchFeatures as $feature)
                                                    <label class="flex items-center gap-2">
                                                        <input type="checkbox" name="features[]" value="{{ $feature->id }}" class="text-blue-600 focus:ring-blue-500 rounded">
                                                        <span class="text-sm text-gray-700">{{ $feature->getTranslatedName() ?: ($feature->translations->first()->name ?? '') }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

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
                                                @foreach(($searchCategories ?? []) as $category)
                                                    <option value="{{ $category->id }}">{{ $category->getTranslatedName() }}</option>
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

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('web.investment-studies.form') }}" class="group bg-gray-900 text-white rounded-2xl p-5 hover:bg-black transition-colors">
                            <div class="flex items-center justify-between rtl:flex-row-reverse">
                                <div>
                                    <div class="text-sm font-semibold">دراسة استثمار</div>
                                    <div class="mt-1 text-xs text-white/70">بالذكاء الاصطناعي</div>
                                </div>
                                <i class="fas fa-robot text-xl text-white/90"></i>
                            </div>
                        </a>
                        <a href="{{ route('public.execution.marketplace') }}" class="group bg-white border border-gray-200 rounded-2xl p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between rtl:flex-row-reverse">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">منصة التنفيذ</div>
                                    <div class="mt-1 text-xs text-gray-500">عقود ومناقصات</div>
                                </div>
                                <i class="fas fa-gavel text-xl text-gray-700"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
    </section>

    <section class="max-w-7xl mx-auto px-4 mt-14">
        <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-10">
            <div class="flex items-start justify-between gap-6 rtl:flex-row-reverse">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">كيف تبدأ الشراكة؟</h2>
                    <p class="mt-2 text-gray-600">اربع خطوات مختصرة وواضحة حتى تصل لاتفاق مناسب.</p>
                </div>
                <div class="hidden md:block w-12 h-12 rounded-2xl bg-gray-900 text-white grid place-items-center">
                    <i class="fas fa-route"></i>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-gray-200 p-5 bg-gray-50">
                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                        <div class="font-bold text-gray-900">تجهيز بيانات الارض</div>
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">1</div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">الموقع، المساحة، نوع الاستخدام، والمستندات.</div>
                </div>
                <div class="rounded-2xl border border-gray-200 p-5 bg-gray-50">
                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                        <div class="font-bold text-gray-900">استقبال عروض الشراكة</div>
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">2</div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">عروض واضحة بالنسب والمدة ومسار التنفيذ.</div>
                </div>
                <div class="rounded-2xl border border-gray-200 p-5 bg-gray-50">
                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                        <div class="font-bold text-gray-900">مقارنة المطورين</div>
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">3</div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">الخبرة، المشاريع السابقة، والتقييمات.</div>
                </div>
                <div class="rounded-2xl border border-gray-200 p-5 bg-gray-50">
                    <div class="flex items-center justify-between rtl:flex-row-reverse">
                        <div class="font-bold text-gray-900">تفاوض وتعاقد</div>
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 grid place-items-center font-extrabold text-gray-900">4</div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">تثبيت البنود ومتابعة المراحل داخل المنصة.</div>
                </div>
            </div>
        </div>
    </section>

    <section id="investment-list" class="max-w-7xl mx-auto px-4 mt-16 pb-10">
        @if(isset($featuredInvestmentProperties) && $featuredInvestmentProperties->count() > 0)
            <x-multi-view-grid
                :items="$featuredInvestmentProperties"
                type="products"
                title="فرص شراكة مميزة"
                :viewAllRoute="route('public.products.featured')"
                viewAllText="عرض كل الفرص"
                :maxItems="8"
                :showViewToggle="false"
                idPrefix="investment-featured"
            />
        @endif

        @if(isset($latestInvestmentProperties) && $latestInvestmentProperties->count() > 0)
            <x-multi-view-grid
                :items="$latestInvestmentProperties"
                type="products"
                title="احدث الاراضي للاستثمار"
                :viewAllRoute="route('public.products.latest')"
                viewAllText="عرض الاحدث"
                :maxItems="12"
                :showViewToggle="true"
                idPrefix="investment-latest"
            />
        @endif

        <section class="mt-12 bg-gradient-to-br from-gray-900 to-black rounded-3xl p-8 md:p-10 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-extrabold">جاهز لبدء شراكة؟</h2>
            <p class="mt-3 text-white/75">تواصل معنا او انشئ حسابا وابدأ باستقبال عروض الشراكة.</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-white text-gray-900 px-7 py-3 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-paper-plane"></i>
                    <span>تواصل معنا</span>
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse bg-transparent border border-white/40 text-white px-7 py-3 rounded-xl hover:bg-white/10 transition-colors">
                    <i class="fas fa-user-plus"></i>
                    <span>انشاء حساب</span>
                </a>
            </div>
        </section>
    </section>
</div>
@endsection

@push('styles')
<style>
/* Match home search styling (scoped by existing classes) */
[dir="rtl"] .search-form {
    flex-direction: row-reverse;
}

[dir="rtl"] .search-input {
    border-radius: 0 0.5rem 0.5rem 0;
}

[dir="rtl"] .search-button {
    border-radius: 0.5rem 0 0 0.5rem;
}

[dir="ltr"] .search-form {
    flex-direction: row;
}

[dir="ltr"] .search-input {
    border-radius: 0 0.5rem 0.5rem 0;
}

[dir="ltr"] .search-button {
    border-radius: 0.5rem 0 0 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
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
