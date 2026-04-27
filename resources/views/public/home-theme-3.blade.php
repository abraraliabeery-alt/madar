@extends('layouts.app')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('content')
<div class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-10 space-y-16">
        {{-- Hero: clean minimal, بطاقة كبيرة على اليمين --}}
        <section class="grid grid-cols-1 lg:grid-cols-[1.2fr,0.9fr] gap-10 items-center">
            <div>
                <h1 class="hero-title text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 leading-tight">
                    {{ __('general.home.title') }}
                </h1>
                <p class="text-slate-600 mb-6 text-sm md:text-base max-w-xl">
                    {{ __('general.home.subtitle') }}
                </p>
                <form action="{{ route('public.search') }}" method="GET" class="flex search-form mb-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                    <input type="text"
                           class="flex-1 px-4 py-3 text-slate-900 border border-slate-300 bg-white search-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent rounded-lg shadow-sm"
                           name="q"
                           placeholder="{{ __('general.home.search_placeholder') }}"
                           required>
                    <button class="ml-2 bg-slate-900 text-white px-5 py-3 rounded-lg search-button hover:bg-slate-700 transition-colors flex items-center gap-2" type="submit">
                        <i class="fas fa-search"></i>
                        <span class="text-sm">{{ __('general.home.view_all_properties') }}</span>
                    </button>
                </form>
                <div class="flex flex-wrap gap-4 text-xs text-slate-500 mt-4">
                    <span class="inline-flex items-center px-3 py-1 bg-white rounded-full shadow-sm border border-slate-200">
                        <i class="fas fa-check-circle text-emerald-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('general.home.latest_properties') }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 bg-white rounded-full shadow-sm border border-slate-200">
                        <i class="fas fa-city text-primary-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('general.home.featured_cities') }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 bg-white rounded-full shadow-sm border border-slate-200">
                        <i class="fas fa-layer-group text-amber-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('general.home.featured_categories') }}
                    </span>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('general.home.latest_properties') }}</h3>
                        <span class="text-[10px] px-2 py-1 rounded-full bg-primary-50 text-primary-700 border border-primary-100">{{ __('general.home.view_all_properties') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-xs text-slate-600">
                        <div class="space-y-1">
                            <p class="font-semibold text-slate-900">+24</p>
                            <p class="text-slate-500">{{ __('general.home.featured_cities') }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="font-semibold text-slate-900">+12</p>
                            <p class="text-slate-500">{{ __('general.home.featured_categories') }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="font-semibold text-emerald-600">98%</p>
                            <p class="text-slate-500">Trust score</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span><i class="fas fa-user-friends {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>+120 {{ __('general.home.register_now') }}</span>
                        <span><i class="fas fa-clock {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('general.view_toggle.display') }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grids بشكل كروت نظيفة --}}
        <div class="space-y-12">
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                <x-multi-view-grid 
                    :items="$featuredProducts" 
                    type="products" 
                    :title="__('general.home.latest_properties')"
                    :viewAllRoute="route('public.products.index')"
                    :viewAllText="__('general.home.view_all_properties')"
                    :maxItems="6"
                    :showViewToggle="true"
                    idPrefix="products-clean"
                />
            @endif

            @if(isset($featuredCities) && $featuredCities->count() > 0)
                <x-multi-view-grid 
                    :items="$featuredCities" 
                    type="cities" 
                    :title="__('general.home.featured_cities')"
                    :viewAllRoute="route('public.cities.index')"
                    :viewAllText="__('general.home.view_all_cities')"
                    :maxItems="6"
                    :showViewToggle="false"
                    idPrefix="cities-clean"
                />
            @endif

            @if(isset($categories) && $categories->count() > 0)
                <x-multi-view-grid 
                    :items="$categories" 
                    type="categories" 
                    :title="__('general.home.featured_categories')"
                    :maxItems="6"
                    :showViewToggle="false"
                    idPrefix="categories-clean"
                />
            @endif

            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
                <h2 class="text-2xl font-semibold text-slate-900 mb-3">{{ __('general.home.cta_title') }}</h2>
                <p class="text-slate-600 mb-6">{{ __('general.home.cta_subtitle') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-slate-900 text-white px-6 py-3 rounded-lg hover:bg-slate-700 transition-colors text-sm font-semibold">
                        {{ __('general.home.register_now') }}
                    </a>
                    <a href="{{ route('public.contact') }}" 
                       class="border border-slate-300 text-slate-800 px-6 py-3 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                        {{ __('general.home.contact_us') }}
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
