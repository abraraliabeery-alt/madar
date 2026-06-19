@extends('facility.layouts.app')

@section('title', __('facility.products.title'))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-semibold text-gray-800">{{ __('facility.products.title') }}</h1>
                    @if(isset($products))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $products->total() }}
                        </span>
                    @endif
                </div>

                {{-- Quick filters for current employee --}}
                @php $currentUserId = auth()->id(); @endphp
                @if($currentUserId)
                    <div class="flex flex-wrap items-center gap-2 mt-2 text-xs">
                        <span class="text-gray-500">اختصارات سريعة:</span>
                        <a href="{{ route('facility.products.index', array_merge(request()->query(), ['seller_user_id' => $currentUserId])) }}"
                           class="inline-flex items-center px-3 py-1 rounded-full border text-gray-700 bg-gray-50 hover:bg-gray-100">
                            <i class="fas fa-user ml-1 text-[10px]"></i>
                            مشاريعي
                        </a>
                        @if(config('features.facility_listings_qs'))
                            <a href="{{ route('facility.products.index', array_merge(request()->query(), ['seller_user_id' => $currentUserId, 'quality' => 'attention'])) }}"
                               class="inline-flex items-center px-3 py-1 rounded-full border border-red-200 text-red-700 bg-red-50 hover:bg-red-100">
                                <i class="fas fa-exclamation-triangle ml-1 text-[10px]"></i>
                                مشاريعي التي تحتاج تحسين
                            </a>
                        @endif
                    </div>
                @endif
                <p class="text-gray-600 mt-1">{{ __('facility.products.subtitle') }}</p>
            </div>
            <a href="{{ route('facility.products.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition inline-flex items-center mt-4 sm:mt-0">
                <i class="fas fa-plus ml-2"></i>
                {{ __('facility.products.add_new') }}
            </a>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            @php
                // إذا كنا في صفحة تصنيف نستخدم راوت التصنيف، وإلا صفحة المنتجات العامة
                $filtersAction = isset($currentCategory)
                    ? route('facility.categories.products', $currentCategory)
                    : route('facility.products.index');
            @endphp
            <form action="{{ $filtersAction }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2 xl:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('facility.products.search') }}
                        </label>
                        <div class="relative flex items-center">
                            <input id="search" name="search" type="text"
                                   value="{{ request('search') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                                   placeholder="{{ __('facility.products.search_placeholder') }}">
                            <button type="button" id="voice-search-btn"
                                    class="absolute left-2 text-gray-500 hover:text-blue-600 focus:outline-none"
                                    title="بحث صوتي">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('facility.products.category') }}
                        </label>
                        <select id="category_id" name="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('facility.products.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
                                    {{ $category->getTranslatedName('ar') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('facility.products.status') }}
                        </label>
                        <select id="status_id" name="status_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('facility.products.all_statuses') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ (string)request('status_id') === (string)$status->id ? 'selected' : '' }}>
                                    {{ $status->getTranslatedName('ar') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Seller (responsible employee) --}}
                    @if(isset($sellers) && $sellers->count() > 0)
                        <div>
                            <label for="seller_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                الموظف المسؤول
                            </label>
                            <select id="seller_user_id" name="seller_user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">الكل</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ (string)request('seller_user_id') === (string)$seller->id ? 'selected' : '' }}>
                                        {{ $seller->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Quality filter (if backend supports it) --}}
                    @if(config('features.facility_listings_qs'))
                    <div>
                        <label for="quality" class="block text-sm font-medium text-gray-700 mb-2">
                            جودة الإعلان
                        </label>
                        <select id="quality" name="quality"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" {{ request('quality') === null ? 'selected' : '' }}>الكل</option>
                            <option value="attention" {{ request('quality') === 'attention' ? 'selected' : '' }}>تحتاج تحسين</option>
                        </select>
                    </div>
                    @endif

                    {{-- Owner --}}
                    @if(isset($owners) && $owners->count() > 0)
                        <div>
                            <label for="owner_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                المالك
                            </label>
                            <select id="owner_user_id" name="owner_user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">الكل</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ (string)request('owner_user_id') === (string)$owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Featured only --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مميز فقط</label>
                        <div class="flex items-center h-10">
                            <input type="checkbox" id="featured" name="featured" value="1"
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded ml-2"
                                   {{ request('featured') ? 'checked' : '' }}>
                            <label for="featured" class="text-sm text-gray-700">إظهار المنتجات المميزة فقط</label>
                        </div>
                    </div>

                    {{-- Verified only --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">موثقة فقط</label>
                        <div class="flex items-center h-10">
                            <input type="checkbox" id="verified" name="verified" value="1"
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded ml-2"
                                   {{ request('verified') ? 'checked' : '' }}>
                            <label for="verified" class="text-sm text-gray-700">إظهار المنتجات الموثقة فقط</label>
                        </div>
                    </div>
                    
                    {{-- Sort --}}
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                            ترتيب حسب
                        </label>
                        <select id="sort" name="sort"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="recent" {{ request('sort','recent') === 'recent' ? 'selected' : '' }}>الأحدث أولاً</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>السعر من الأقل للأعلى</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>السعر من الأعلى للأقل</option>
                        </select>
                    </div>

                    {{-- Price range (inline) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            السعر من / إلى
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input id="price_min" name="price_min" type="number" min="0" step="0.01"
                                   value="{{ request('price_min') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="من">
                            <input id="price_max" name="price_max" type="number" min="0" step="0.01"
                                   value="{{ request('price_max') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="إلى">
                        </div>
                    </div>
                </div>

                {{-- Attribute-based filters for current category --}}
                @if(isset($categoryAttributes) && $categoryAttributes->count())
                    <div class="pt-2 border-t border-gray-100 mt-2">
                        <h4 class="text-xs font-semibold text-gray-500 mb-2">فلاتر حسب خصائص التصنيف</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach($categoryAttributes as $attribute)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $attribute->getTranslatedName('ar') }}
                                    </label>
                                    @php
                                        $attrType = strtolower($attribute->type ?? '');
                                        $isNumeric = in_array($attrType, ['number','numeric','integer','float','decimal']);
                                        $isBoolean = in_array($attrType, ['bool','boolean','yes_no']);
                                    @endphp

                                    @if($isNumeric)
                                        <div class="grid grid-cols-2 gap-2">
                                            <input
                                                type="number"
                                                name="attr_min[{{ $attribute->id }}]"
                                                value="{{ request('attr_min.' . $attribute->id) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                step="0.01"
                                                placeholder="من">
                                            <input
                                                type="number"
                                                name="attr_max[{{ $attribute->id }}]"
                                                value="{{ request('attr_max.' . $attribute->id) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                step="0.01"
                                                placeholder="إلى">
                                        </div>
                                    @elseif($isBoolean)
                                        <select
                                            name="attr_bool[{{ $attribute->id }}]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">الكل</option>
                                            <option value="1" {{ request('attr_bool.' . $attribute->id) === '1' ? 'selected' : '' }}>نعم</option>
                                            <option value="0" {{ request('attr_bool.' . $attribute->id) === '0' ? 'selected' : '' }}>لا</option>
                                        </select>
                                    @else
                                        <input
                                            type="text"
                                            name="attr[{{ $attribute->id }}]"
                                            value="{{ request('attr.' . $attribute->id) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="اكتب قيمة للفلترة...">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow inline-flex items-center justify-center">
                        <i class="fas fa-search ml-2"></i>
                        {{ __('facility.products.search_button') }}
                    </button>
                    <a href="{{ route('facility.products.index') }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2.5 px-6 rounded-lg shadow inline-flex items-center justify-center">
                        <i class="fas fa-undo ml-2"></i>
                        {{ __('facility.products.clear_filters') }}
                    </a>
                </div>
            </form>
        </div>

        {{-- Simple stats bar --}}
        @if($products->count() > 0)
            <div class="bg-blue-50 border border-blue-100 text-blue-800 text-xs md:text-sm rounded-lg px-4 py-3 mb-4 flex flex-wrap items-center gap-3">
                <span>
                    إجمالي المعروض في هذه الصفحة:
                    <strong>{{ $products->count() }}</strong>
                </span>
                <span class="hidden sm:inline">|</span>
                <span>
                    المميزة منها:
                    <strong>{{ $products->where('is_featured', true)->count() }}</strong>
                </span>
                <span class="hidden sm:inline">|</span>
                <span>
                    المشاريع التي لديها عروض <strong>بيع</strong>:
                    <strong>{{ $products->where('sale_offers_count', '>', 0)->count() }}</strong>
                </span>
            </div>
        @endif

        {{-- Results summary (only if there are products) --}}
        @if($products->count() > 0)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6 text-sm text-gray-700">
                <span>
                    {{ __('facility.products.showing_results', [
                        'first' => $products->firstItem() ?? 0,
                        'last'  => $products->lastItem() ?? 0,
                        'total' => $products->total(),
                    ]) }}
                </span>

                @if(request()->hasAny(['search', 'category_id', 'status_id']))
                    <span class="text-blue-600 mr-2">
                        ({{ __('facility.products.filtered') ?? 'نتائج بعد تطبيق فلاتر' }})
                    </span>
                @endif
            </div>
        @endif

        {{-- Products list or empty state --}}
        @if($products->count() > 0)
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                {{-- Bulk info bar + desktop view toggle --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" id="bulk-open-edit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs md:text-sm font-medium py-2 px-4 rounded-lg">
                            فتح تحرير المحدد
                        </button>
                        @if(config('features.facility_listings_qs'))
                            <a href="{{ route('facility.products.index', array_merge(request()->query(), ['quality' => 'attention'])) }}"
                               class="bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium py-2 px-3 rounded-full border border-red-200 flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                                المشاريع التي تحتاج تحسين
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-xs md:text-sm text-gray-500">
                        <span class="hidden sm:inline">تحديد متعدد لفتح نماذج التحرير في تبويبات جديدة</span>
                        <div class="hidden lg:flex items-center gap-1" aria-label="تغيير نمط العرض">
                            <button type="button" id="view-table-btn"
                                    class="px-2 py-1 rounded-md border border-gray-300 bg-gray-200 text-gray-700 text-xs flex items-center gap-1">
                                <i class="fas fa-table"></i>
                                جدول
                            </button>
                            <button type="button" id="view-grid-btn"
                                    class="px-2 py-1 rounded-md border border-gray-300 bg-white text-gray-700 text-xs flex items-center gap-1">
                                <i class="fas fa-th-large"></i>
                                شبكة
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Desktop table --}}
                <div id="desktop-table-view" class="hidden lg:block">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-right">
                                <input id="select-all-desktop" type="checkbox" class="h-4 w-4 border-gray-300 rounded">
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('facility.products.product') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('facility.products.category') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الموظف المسؤول
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('facility.products.status') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('facility.products.price') }}
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المشاهدات
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحجوزات
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('facility.products.created_at') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('facility.products.actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-4">
                                    <input type="checkbox" class="row-select-desktop h-4 w-4 border-gray-300 rounded"
                                           data-edit-url="{{ route('facility.products.edit', $product) }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product->main_image)
                                            <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->title }}"
                                                 class="h-12 w-12 rounded-lg object-cover ml-4">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center ml-4">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 flex items-center gap-1">
                                                <span>{{ $product->getTranslatedTitle() ?: $product->address }}</span>
                                                @if($product->is_featured)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[10px]">
                                                        <i class="fas fa-star ml-1"></i>
                                                        مميز
                                                    </span>
                                                @endif
                                                @if($product->sale_offers_count > 0)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px]">
                                                        <i class="fas fa-tag ml-1"></i>
                                                        للبيع
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ Str::limit($product->additional_info, 60) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    {{ $product->category ? $product->category->getTranslatedName('ar') : __('facility.products.unspecified') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    @if($product->seller)
                                        <a href="{{ route('facility.products.index', array_merge(request()->query(), ['seller_user_id' => $product->seller->id])) }}"
                                           class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $product->seller->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    {{ $product->status ? $product->status->getTranslatedName('ar') : __('facility.products.unspecified') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if(!is_null($product->price) && is_numeric($product->price) && $product->price > 0)
                                        {{ number_format($product->price, 2) }}
                                        <span class="text-xs text-gray-500 mr-1">{{ __('facility.products.currency_sar') ?? 'ر.س' }}</span>
                                    @else
                                        <span class="text-xs text-red-600">{{ __('facility.products.price_not_set') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">
                                        <i class="fas fa-eye ml-1 text-[10px]"></i>
                                        {{ (int) $product->views_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                                        <i class="fas fa-calendar-check ml-1 text-[10px]"></i>
                                        {{ (int) $product->bookings_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $product->created_at ? $product->created_at->format('Y/m/d') : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <a href="{{ route('facility.products.show', $product) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('facility.products.edit', $product) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 rounded-lg">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <a href="{{ route('facility.products.lifecycle', $product) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg"
                                           title="دورة حياة المشروع">
                                            <i class="fas fa-stream text-sm"></i>
                                        </a>
                                        <form action="{{ route('facility.products.destroy', $product) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Desktop grid view --}}
                <div id="desktop-grid-view" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-4">
                        @foreach($products as $product)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition bg-white">
                                <div class="flex items-start space-x-4 space-x-reverse">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->title }}"
                                             class="h-20 w-20 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900 flex items-center gap-1">
                                                    <span>{{ $product->getTranslatedTitle() ?: $product->address }}</span>
                                                    @if($product->is_featured)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[10px]">
                                                            <i class="fas fa-star ml-1"></i>
                                                            مميز
                                                        </span>
                                                    @endif
                                                    @if($product->sale_offers_count > 0)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px]">
                                                            <i class="fas fa-tag ml-1"></i>
                                                            للبيع
                                                        </span>
                                                    @endif
                                                </h3>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ Str::limit($product->additional_info, 80) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between mt-2 text-xs text-gray-600">
                                            <span>
                                                {{ $product->category ? $product->category->getTranslatedName('ar') : __('facility.products.unspecified') }}
                                            </span>
                                            <span>
                                                {{ $product->status ? $product->status->name : __('facility.products.unspecified') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $product->created_at ? $product->created_at->format('Y/m/d') : '—' }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-[11px]">
                                                    <i class="fas fa-eye ml-1 text-[10px]"></i>
                                                    {{ (int) $product->views_count }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px]">
                                                    <i class="fas fa-calendar-check ml-1 text-[10px]"></i>
                                                    {{ (int) $product->bookings_count }}
                                                </span>
                                                <span class="text-sm font-semibold text-gray-900">
                                                    @if(!is_null($product->price) && is_numeric($product->price) && $product->price > 0)
                                                        {{ number_format($product->price, 2) }}
                                                        <span class="text-xs text-gray-500">{{ __('facility.products.currency_sar') ?? 'ر.س' }}</span>
                                                    @else
                                                        <span class="text-xs text-red-600">{{ __('facility.products.price_not_set') }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 space-x-reverse mt-3">
                                            <a href="{{ route('facility.products.show', $product) }}"
                                               class="inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('facility.products.edit', $product) }}"
                                               class="inline-flex items-center justify-center w-9 h-9 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 rounded-lg">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <a href="{{ route('facility.products.lifecycle', $product) }}"
                                               class="inline-flex items-center justify-center w-9 h-9 text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg"
                                               title="دورة حياة المشروع">
                                                <i class="fas fa-stream text-sm"></i>
                                            </a>
                                            <form action="{{ route('facility.products.destroy', $product) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="lg:hidden divide-y divide-gray-200">
                    @foreach($products as $product)
                        <div class="p-4">
                            <div class="flex items-start space-x-4 space-x-reverse">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->title }}"
                                         class="h-16 w-16 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900 flex items-center gap-1">
                                                <span>{{ $product->getTranslatedTitle() ?: $product->address }}</span>
                                                @if($product->is_featured)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[10px]">
                                                        <i class="fas fa-star ml-1"></i>
                                                        مميز
                                                    </span>
                                                @endif
                                            </h3>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($product->additional_info, 80) }}
                                            </p>
                                        </div>
                                        <input type="checkbox" class="row-select-mobile h-4 w-4 border-gray-300 rounded"
                                               data-edit-url="{{ route('facility.products.edit', $product) }}">
                                    </div>
                                    <div class="flex items-center justify-between mt-2 text-xs text-gray-600">
                                        <span>
                                            {{ $product->category ? $product->category->getTranslatedName('ar') : __('facility.products.unspecified') }}
                                        </span>
                                        <span>
                                            @if($product->seller)
                                                <a href="{{ route('facility.products.index', array_merge(request()->query(), ['seller_user_id' => $product->seller->id])) }}"
                                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                                    {{ $product->seller->name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">غير محدد</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-500">
                                            {{ $product->created_at ? $product->created_at->format('Y/m/d') : '—' }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-[11px]">
                                                <i class="fas fa-eye ml-1 text-[10px]"></i>
                                                {{ (int) $product->views_count }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px]">
                                                <i class="fas fa-calendar-check ml-1 text-[10px]"></i>
                                                {{ (int) $product->bookings_count }}
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900">
                                                @if(!is_null($product->price) && is_numeric($product->price) && $product->price > 0)
                                                    {{ number_format($product->price, 2) }}
                                                    <span class="text-xs text-gray-500">{{ __('facility.products.currency_sar') ?? 'ر.س' }}</span>
                                                @else
                                                    <span class="text-xs text-red-600">{{ __('facility.products.price_not_set') }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 space-x-reverse mt-2">
                                        <a href="{{ route('facility.products.show', $product) }}"
                                           class="inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('facility.products.edit', $product) }}"
                                           class="inline-flex items-center justify-center w-9 h-9 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 rounded-lg">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('facility.products.destroy', $product) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('{{ __('facility.products.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            {{-- Empty state --}}
            <div class="bg-white rounded-lg shadow border border-gray-200 p-10 text-center">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-box text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    {{ __('facility.products.no_products') }}
                </h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->hasAny(['search', 'category_id', 'status_id']))
                        {{ __('facility.products.no_search_results') }}
                    @else
                        {{ __('facility.products.no_products_yet') }}
                    @endif
                </p>
                <a href="{{ route('facility.products.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow inline-flex items-center">
                    <i class="fas fa-plus ml-2"></i>
                    {{ __('facility.products.add_new') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const selectAll = document.getElementById('select-all-desktop');
        const bulkBtn = document.getElementById('bulk-open-edit');
        const voiceBtn = document.getElementById('voice-search-btn');
        const searchInput = document.getElementById('search');
        const searchForm = searchInput ? searchInput.closest('form') : null;
        const viewTableBtn = document.getElementById('view-table-btn');
        const viewGridBtn = document.getElementById('view-grid-btn');
        const desktopTableView = document.getElementById('desktop-table-view');
        const desktopGridView = document.getElementById('desktop-grid-view');

        function desktopChecks() {
            return Array.from(document.querySelectorAll('.row-select-desktop'));
        }

        function mobileChecks() {
            return Array.from(document.querySelectorAll('.row-select-mobile'));
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                desktopChecks().forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }

        if (bulkBtn) {
            bulkBtn.addEventListener('click', function () {
                const selected = desktopChecks().filter(cb => cb.checked)
                    .concat(mobileChecks().filter(cb => cb.checked));

                if (selected.length === 0) {
                    alert('لم يتم تحديد أي منتجات');
                    return;
                }

                selected.forEach(cb => {
                    const url = cb.getAttribute('data-edit-url');
                    if (url) {
                        window.open(url, '_blank');
                    }
                });
            });
        }

        // Desktop view toggle (table/grid)
        function setView(mode) {
            if (!desktopTableView || !desktopGridView || !viewTableBtn || !viewGridBtn) return;

            if (mode === 'grid') {
                // إظهار الشبكة وإخفاء الجدول على الديسكتوب
                desktopTableView.style.display = 'none';
                desktopGridView.style.display = 'block';

                viewGridBtn.classList.add('bg-gray-200');
                viewGridBtn.classList.remove('bg-white');
                viewTableBtn.classList.remove('bg-gray-200');
                viewTableBtn.classList.add('bg-white');
            } else {
                // إظهار الجدول وإخفاء الشبكة على الديسكتوب
                desktopGridView.style.display = 'none';
                desktopTableView.style.display = 'block';

                viewTableBtn.classList.add('bg-gray-200');
                viewTableBtn.classList.remove('bg-white');
                viewGridBtn.classList.remove('bg-gray-200');
                viewGridBtn.classList.add('bg-white');
            }
        }

        if (viewTableBtn && viewGridBtn && desktopTableView && desktopGridView) {
            // default to table view
            setView('table');

            viewTableBtn.addEventListener('click', function () {
                setView('table');
            });

            viewGridBtn.addEventListener('click', function () {
                setView('grid');
            });
        }

        // Voice search using Web Speech API (if supported)
        if (voiceBtn && searchInput && searchForm) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                // If not supported, hide the button to avoid confusing the user
                voiceBtn.style.display = 'none';
            } else {
                const recognition = new SpeechRecognition();
                recognition.lang = '{{ app()->getLocale() === 'ar' ? 'ar-SA' : 'en-US' }}';
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                voiceBtn.addEventListener('click', function () {
                    try {
                        recognition.start();
                    } catch (e) {
                        // ignore repeated start errors
                    }
                });

                recognition.addEventListener('result', function (event) {
                    const transcript = event.results[0][0].transcript;
                    searchInput.value = transcript;
                });

                recognition.addEventListener('end', function () {
                    if (searchInput.value && searchForm) {
                        searchForm.submit();
                    }
                });
            }
        }
    })();
</script>
@endpush
