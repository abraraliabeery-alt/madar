@extends('layouts.app')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="text-center py-16 mb-16 hero">
        <h1 class="text-4xl font-bold text-gray-900 mb-4 hero-title">{{ __('general.home.title') }}</h1>
        <p class="text-lg text-gray-600 mb-8">{{ __('general.home.subtitle') }}</p>
        
        <!-- Search Form -->
        <div class="max-w-5xl mx-auto">
            <div class="bg-white/70 backdrop-blur rounded-2xl border border-gray-200 overflow-hidden">
                <div class="divide-y divide-gray-200">
                    <button type="button" class="w-full flex items-center justify-between px-5 py-4 text-gray-900 font-semibold" onclick="toggleHomeSection('home-basic')">
                        <span>{{ __('public.search.quick_search') }}</span>
                        <i class="fas fa-chevron-down" data-accordion-icon="home-basic"></i>
                    </button>
                    <div id="home-basic" class="px-5 pb-5">
                        <form action="{{ route('public.search') }}" method="GET" class="flex search-form" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                            <input type="text" 
                                   class="flex-1 px-4 py-3 text-gray-900 border border-gray-300 search-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                   name="q" 
                                   placeholder="{{ __('general.home.search_placeholder') }}" 
                                   required>
                            <button class="bg-primary-600 text-white px-6 py-3 search-button hover:bg-primary-700 transition-colors" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <button type="button" class="w-full flex items-center justify-between px-5 py-4 text-gray-900 font-semibold" onclick="toggleHomeSection('home-advanced')">
                        <span>{{ __('public.search.advanced_search_short') }}</span>
                        <i class="fas fa-chevron-down" data-accordion-icon="home-advanced"></i>
                    </button>
                    <div id="home-advanced" class="px-5 pb-5 hidden">
                        <div class="mb-4 flex items-center justify-center gap-6">
                            <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                <input type="radio" name="home_search_type" value="products" checked class="text-blue-600 focus:ring-blue-500" onchange="updateHomeAdvancedAction()">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.products') }}</span>
                            </label>
                            <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                <input type="radio" name="home_search_type" value="facilities" class="text-blue-600 focus:ring-blue-500" onchange="updateHomeAdvancedAction()">
                                <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.facilities') }}</span>
                            </label>
                        </div>

                        <form id="homeAdvancedSearchForm" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="home_q" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.search_term') }}</label>
                                    <input type="text" id="home_q" name="q" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.search.enter_search_term') }}">
                                </div>
                                <div>
                                    <label for="home_category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.common.category') }}</label>
                                    <select id="home_category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">{{ __('public.search.all_categories') }}</option>
                                        @foreach(($searchCategories ?? []) as $category)
                                            <option value="{{ $category->id }}">{{ $category->getTranslatedName() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="home_min_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.minimum_price') }}</label>
                                    <input type="number" id="home_min_price" name="min_price" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.search.minimum_price') }}">
                                </div>
                                <div>
                                    <label for="home_max_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.maximum_price') }}</label>
                                    <input type="number" id="home_max_price" name="max_price" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.search.maximum_price') }}">
                                </div>
                            </div>

                            <div id="homePropertyDetails" class="mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label for="home_bedrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.bedrooms') }}</label>
                                        <select id="home_bedrooms" name="bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">{{ __('public.advanced_search.any') }}</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label for="home_bathrooms" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.bathrooms') }}</label>
                                        <select id="home_bathrooms" name="bathrooms" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">{{ __('public.advanced_search.any') }}</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label for="home_min_area" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.min_area') }}</label>
                                        <input type="number" id="home_min_area" name="min_area" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.advanced_search.min_area_placeholder') }}">
                                    </div>
                                    <div>
                                        <label for="home_max_area" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.advanced_search.max_area') }}</label>
                                        <input type="number" id="home_max_area" name="max_area" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.advanced_search.max_area_placeholder') }}">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                            <input type="radio" name="property_type" value="sale" class="text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.for_sale') }}</span>
                                        </label>
                                        <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                            <input type="radio" name="property_type" value="rent" class="text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.for_rent') }}</span>
                                        </label>
                                        <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                            <input type="radio" name="property_type" value="" checked class="text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">{{ __('public.advanced_search.both') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                                <button type="submit" class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <i class="fas fa-search mr-2"></i> {{ __('public.search.title') }}
                                </button>
                                <a href="{{ route('public.search.advanced') }}" class="inline-flex items-center px-8 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                    <i class="fas fa-sliders-h mr-2"></i> فتح صفحة البحث المتقدم
                                </a>
                            </div>
                        </form>
                    </div>

                    <button type="button" class="w-full flex items-center justify-between px-5 py-4 text-gray-900 font-semibold" onclick="toggleHomeSection('home-map')">
                        <span>{{ __('public.search.map_search_short') }}</span>
                        <i class="fas fa-chevron-down" data-accordion-icon="home-map"></i>
                    </button>
                    <div id="home-map" class="px-5 pb-5 hidden">
                        <form action="{{ route('public.search.map') }}" method="GET" id="homeMapSearchForm">
                            <div class="mb-4 flex items-center justify-center gap-6">
                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                    <input type="radio" name="search_type" value="products" checked onchange="updateHomeMapFilters()">
                                    <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.products') }}</span>
                                </label>
                                <label class="flex items-center gap-2 rtl:flex-row-reverse">
                                    <input type="radio" name="search_type" value="facilities" onchange="updateHomeMapFilters()">
                                    <span class="text-sm font-medium text-gray-700">{{ __('public.navigation.facilities') }}</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="home_map_category_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.common.category') }}</label>
                                    <select id="home_map_category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">{{ __('public.search.all_categories') }}</option>
                                        @foreach(($searchCategories ?? []) as $category)
                                            <option value="{{ $category->id }}">{{ $category->getTranslatedName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="homeMapMinPrice">
                                    <label for="home_map_min_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.minimum_price') }}</label>
                                    <input type="number" id="home_map_min_price" name="min_price" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.search.minimum_price') }}">
                                </div>
                                <div id="homeMapMaxPrice">
                                    <label for="home_map_max_price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.search.maximum_price') }}</label>
                                    <input type="number" id="home_map_max_price" name="max_price" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('public.search.maximum_price') }}">
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                                <button type="submit" class="inline-flex items-center gap-2 rtl:flex-row-reverse px-8 py-3 bg-cyan-600 text-white font-medium rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                                    <i class="fas fa-map"></i>
                                    <span>{{ __('public.search.map_search') }}</span>
                                </button>
                                <a href="{{ route('public.search.map') }}" class="inline-flex items-center gap-2 rtl:flex-row-reverse px-8 py-3 bg-white border border-cyan-600 text-cyan-700 font-medium rounded-md hover:bg-cyan-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
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

    <section class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">خدماتنا</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('public.execution.marketplace') }}" class="bg-white rounded-2xl border border-gray-200 p-5 card-hover">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-3">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="font-semibold text-gray-900 mb-1">منصة المشاريع</div>
                <div class="text-sm text-gray-600">اعرض مشروعك أو استعرض المشاريع وقدم عروض التنفيذ.</div>
            </a>
            <a href="{{ route('public.facilities.index') }}" class="bg-white rounded-2xl border border-gray-200 p-5 card-hover">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mb-3">
                    <i class="fas fa-building"></i>
                </div>
                <div class="font-semibold text-gray-900 mb-1">دليل المقاولين والشركات</div>
                <div class="text-sm text-gray-600">استعرض المنشآت وتواصل مع الجهة المناسبة لتنفيذ مشروعك.</div>
            </a>
            <a href="{{ route('public.products.index') }}" class="bg-white rounded-2xl border border-gray-200 p-5 card-hover">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center mb-3">
                    <i class="fas fa-list"></i>
                </div>
                <div class="font-semibold text-gray-900 mb-1">استعراض المشاريع</div>
                <div class="text-sm text-gray-600">تصفّح المشاريع حسب الفئة والمدينة وخصائص الموقع.</div>
            </a>
            <a href="{{ url('/investment-studies') }}" class="bg-white rounded-2xl border border-gray-200 p-5 card-hover">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center mb-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="font-semibold text-gray-900 mb-1">مركز التحليل</div>
                <div class="text-sm text-gray-600">تحليل جدوى ومقترحات تطوير وفق الكود السعودي.</div>
            </a>
        </div>
    </section>

    <section class="mb-12 bg-white rounded-2xl border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
            <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                <div class="text-xs text-gray-500 mb-1">إجمالي المشاريع</div>
                <div class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total_products'] ?? 0) }}</div>
            </div>
            <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                <div class="text-xs text-gray-500 mb-1">الشركات والمنشآت</div>
                <div class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total_facilities'] ?? 0) }}</div>
            </div>
            <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                <div class="text-xs text-gray-500 mb-1">الفئات</div>
                <div class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total_categories'] ?? 0) }}</div>
            </div>
            <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                <div class="text-xs text-gray-500 mb-1">مشاريع مميزة</div>
                <div class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['featured_products'] ?? 0) }}</div>
            </div>
        </div>
    </section>

    <!-- Global View Toggle -->
    <div class="flex justify-end items-center mb-8">
        <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <span class="text-sm text-gray-600 mr-3 rtl:ml-3 rtl:mr-0">{{ __('general.view_toggle.display') }}</span>
            <button id="small-grid-view" 
                    class="view-toggle-btn bg-primary-600 text-white p-2 rounded-lg transition-colors"
                    onclick="switchView('small-grid')"
                    title="{{ __('general.view_toggle.small_grid') }}">
                <i class="fas fa-th"></i>
            </button>
            <button id="large-grid-view" 
                    class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                    onclick="switchView('large-grid')"
                    title="{{ __('general.view_toggle.large_grid') }}">
                <i class="fas fa-th-large"></i>
            </button>
            <button id="list-view" 
                    class="view-toggle-btn bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                    onclick="switchView('list')"
                    title="{{ __('general.view_toggle.list') }}">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Latest Projects (Execution Requests) -->
    @if(isset($latestExecutionRequests) && $latestExecutionRequests->count() > 0)
        <x-multi-view-grid
            :items="$latestExecutionRequests"
            type="execution_requests"
            title="المشاريع"
            :viewAllRoute="route('public.projects.index')"
            viewAllText="عرض جميع المشاريع"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="execution-requests"
            :showPrice="false"
        />
    @endif

    <!-- Featured Cities -->
    @if(isset($featuredCities) && $featuredCities->count() > 0)
        <x-multi-view-grid 
            :items="$featuredCities" 
            type="cities" 
            :title="__('general.home.featured_cities')"
            :viewAllRoute="route('public.cities.index')"
            :viewAllText="__('general.home.view_all_cities')"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="cities"
        />
    @endif

    <!-- Featured Categories -->
    @if(isset($categories) && $categories->count() > 0)
        <x-multi-view-grid 
            :items="$categories" 
            type="categories" 
            :title="__('general.home.featured_categories')"
            :maxItems="6"
            :showViewToggle="false"
            idPrefix="categories"
        />
    @endif

    <!-- Call to Action -->
    <section class="bg-gray-50 rounded-lg p-8 text-center">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('general.home.cta_title') }}</h2>
        <p class="text-gray-600 mb-6">{{ __('general.home.cta_subtitle') }}</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                {{ __('general.home.register_now') }}
            </a>
            <a href="{{ route('public.contact') }}" 
               class="border border-primary-600 text-primary-600 px-6 py-3 rounded-lg hover:bg-primary-50 transition-colors">
                {{ __('general.home.contact_us') }}
            </a>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

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

/* Language-specific styling */
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

/* Responsive language support */
@media (max-width: 768px) {
    [dir="rtl"] .hero-title {
        font-size: 2rem;
    }
    
    [dir="ltr"] .hero-title {
        font-size: 2rem;
    }
}

/* Language-specific text alignment */
[dir="rtl"] .text-left {
    text-align: right;
}

[dir="rtl"] .text-right {
    text-align: left;
}

[dir="ltr"] .text-left {
    text-align: left;
}

[dir="ltr"] .text-right {
    text-align: right;
}

/* Language-specific margins and padding */
[dir="rtl"] .ml-1 {
    margin-left: 0;
    margin-right: 0.25rem;
}

[dir="rtl"] .mr-1 {
    margin-right: 0;
    margin-left: 0.25rem;
}

[dir="ltr"] .ml-1 {
    margin-left: 0.25rem;
    margin-right: 0;
}

[dir="ltr"] .mr-1 {
    margin-right: 0.25rem;
    margin-left: 0;
}

/* Theme 1 (الافتراضي) */
body.theme-1 {
    background-color: #f9fafb;
}
body.theme-1 .hero {
    background: transparent;
}

</style>
@endpush

@push('scripts')
<script>
function toggleHomeSection(sectionId) {
    const allSections = ['home-basic', 'home-advanced', 'home-map'];
    allSections.forEach(id => {
        const el = document.getElementById(id);
        const icon = document.querySelector(`[data-accordion-icon="${id}"]`);
        if (!el) {
            return;
        }

        if (id === sectionId) {
            const isHidden = el.classList.contains('hidden');
            el.classList.toggle('hidden');
            if (icon) {
                icon.classList.toggle('fa-chevron-down', !isHidden);
                icon.classList.toggle('fa-chevron-up', isHidden);
            }
        } else {
            el.classList.add('hidden');
            if (icon) {
                icon.classList.add('fa-chevron-down');
                icon.classList.remove('fa-chevron-up');
            }
        }
    });
}

function updateHomeAdvancedAction() {
    const form = document.getElementById('homeAdvancedSearchForm');
    if (!form) {
        return;
    }

    const selected = document.querySelector('input[name="home_search_type"]:checked');
    const type = selected ? selected.value : 'products';
    const details = document.getElementById('homePropertyDetails');

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

function updateHomeMapFilters() {
    const selected = document.querySelector('#homeMapSearchForm input[name="search_type"]:checked');
    const type = selected ? selected.value : 'products';
    const minPrice = document.getElementById('homeMapMinPrice');
    const maxPrice = document.getElementById('homeMapMaxPrice');

    const showPrice = type === 'products';
    if (minPrice) {
        minPrice.style.display = showPrice ? 'block' : 'none';
    }
    if (maxPrice) {
        maxPrice.style.display = showPrice ? 'block' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateHomeAdvancedAction();
    updateHomeMapFilters();
});

// Helper function for locale-aware number formatting
function formatNumber(number, locale = '{{ app()->getLocale() }}') {
    if (locale === 'ar') {
        return new Intl.NumberFormat('ar-SA').format(number);
    } else {
        return new Intl.NumberFormat('en-US').format(number);
    }
}

/**
 * Switch between different view modes (small-grid, large-grid, list)
 * Includes validation to prevent invalid view types from hiding content
 * @param {string} viewType - The view type to switch to
 */
function switchView(viewType) {
    // Get all grid containers dynamically
    const gridContainers = document.querySelectorAll('[id$="-small-grid"], [id$="-large-grid"], [id$="-list"]');
    
    // Toggle buttons
    const smallGridBtn = document.getElementById('small-grid-view');
    const largeGridBtn = document.getElementById('large-grid-view');
    const listBtn = document.getElementById('list-view');
    
    // Validate view type - only allow valid options
    const validViewTypes = ['small-grid', 'large-grid', 'list'];
    if (!validViewTypes.includes(viewType)) {
        console.warn(`Invalid view type "${viewType}" detected. Falling back to list view.`);
        viewType = 'list';
        // Clear invalid preference from localStorage
        localStorage.removeItem('preferredView');
    }
    
    // Hide all views first
    gridContainers.forEach(container => {
        container.classList.add('hidden');
    });
    
    // Reset all button styles
    [smallGridBtn, largeGridBtn, listBtn].forEach(btn => {
        if (btn) {
            btn.classList.remove('bg-primary-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-600');
        }
    });
    
    // Show the selected view type
    const targetSuffix = viewType === 'small-grid' ? '-small-grid' : 
                        viewType === 'large-grid' ? '-large-grid' : '-list';
    
    const targetContainers = document.querySelectorAll(`[id$="${targetSuffix}"]`);
    targetContainers.forEach(container => {
        container.classList.remove('hidden');
    });
    
    // Update button styles
    let activeBtn = null;
    if (viewType === 'small-grid' && smallGridBtn) {
        activeBtn = smallGridBtn;
    } else if (viewType === 'large-grid' && largeGridBtn) {
        activeBtn = largeGridBtn;
    } else if (viewType === 'list' && listBtn) {
        activeBtn = listBtn;
    }
    
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
        activeBtn.classList.add('bg-primary-600', 'text-white');
    }
    
    // Store user preference in localStorage only if it's valid
    if (validViewTypes.includes(viewType)) {
        localStorage.setItem('preferredView', viewType);
    }
    
    // Safety check: if no view containers exist, show small grid as fallback
    if (gridContainers.length === 0) {
        console.warn('No view containers found. This might indicate a rendering issue.');
    }
}

// Set initial view based on user preference
document.addEventListener('DOMContentLoaded', function() {
    const preferredView = localStorage.getItem('preferredView');
    const validViewTypes = ['small-grid', 'large-grid', 'list'];
    
    // Use preferred view if valid, otherwise default to list view
    const initialView = validViewTypes.includes(preferredView) ? preferredView : 'list';
    
    // If no valid preference exists, set list as default
    if (!preferredView || !validViewTypes.includes(preferredView)) {
        localStorage.setItem('preferredView', 'list');
    }
    
    switchView(initialView);
    
    // Add language-specific functionality
    const currentLocale = '{{ app()->getLocale() }}';
    const isRTL = currentLocale === 'ar';
    
    // Set document direction based on language
    if (isRTL) {
        document.documentElement.setAttribute('dir', 'rtl');
        document.documentElement.setAttribute('lang', 'ar');
    } else {
        document.documentElement.setAttribute('dir', 'ltr');
        document.documentElement.setAttribute('lang', 'en');
    }
    
    // Add language-specific event listeners
    if (isRTL) {
        // RTL specific functionality
        console.log('RTL mode activated');
        
        // Add RTL-specific keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                // In RTL, left arrow should go to next item
                e.preventDefault();
                // Add your RTL navigation logic here
            }
        });
    } else {
        // LTR specific functionality
        console.log('LTR mode activated');
        
        // Add LTR-specific keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight') {
                // In LTR, right arrow should go to next item
                e.preventDefault();
                // Add your LTR navigation logic here
            }
        });
    }
    
    // Add accessibility improvements
    const viewToggleButtons = document.querySelectorAll('.view-toggle-btn');
    viewToggleButtons.forEach(button => {
        button.setAttribute('aria-label', button.title);
        button.setAttribute('role', 'button');
        button.setAttribute('tabindex', '0');
    });
});
</script>
@endpush
