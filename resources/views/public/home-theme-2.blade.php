@extends('layouts.app')

@section('meta')
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="language-alternate" content="{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('content')
<div class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-10 space-y-16">
        {{-- Hero: full-width dark with glass cards --}}
        <section class="relative overflow-hidden rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900 via-slate-950 to-black p-8 lg:p-12 mb-4">
            <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top,_#38bdf8_0,_transparent_55%)]"></div>
            <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400 mb-3">{{ __('general.home.title') }}</p>
                    <h1 class="hero-title text-3xl md:text-4xl lg:text-5xl font-black mb-4 leading-tight">
                        {{ __('general.home.subtitle') }}
                    </h1>
                    <p class="text-sm md:text-base text-slate-300 mb-8 max-w-xl">
                        {{ __('general.home.search_placeholder') }}
                    </p>
                    <form action="{{ route('public.search') }}" method="GET" class="flex search-form mb-6" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                        <input type="text"
                               class="flex-1 px-4 py-3 bg-slate-900/70 border border-slate-700 text-white search-input focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent rounded-lg"
                               name="q"
                               placeholder="{{ __('general.home.search_placeholder') }}"
                               required>
                        <button class="ml-2 bg-cyan-500 text-slate-950 px-5 py-3 rounded-lg search-button hover:bg-cyan-400 transition-colors flex items-center gap-2" type="submit">
                            <i class="fas fa-search"></i>
                            <span class="text-sm font-semibold">{{ __('general.home.register_now') }}</span>
                        </button>
                    </form>
                    <div class="flex flex-wrap gap-3 text-xs text-slate-300">
                        <span class="inline-flex items-center px-3 py-1 bg-slate-900/70 border border-slate-700 rounded-full">
                            <i class="fas fa-shield-alt text-emerald-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('general.home.latest_properties') }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-slate-900/70 border border-slate-700 rounded-full">
                            <i class="fas fa-city text-cyan-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('general.home.featured_cities') }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full max-w-md">
                        <div class="bg-slate-900/70 border border-slate-700 rounded-2xl p-4 flex flex-col justify-between shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs text-slate-400">{{ __('general.home.latest_properties') }}</span>
                                <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/40">Live</span>
                            </div>
                            <p class="text-sm text-slate-200 mb-2 line-clamp-2">{{ __('general.home.view_all_properties') }}</p>
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span><i class="fas fa-building {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>+24 {{ __('general.home.featured_categories') }}</span>
                                <span><i class="fas fa-chart-line {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>+12%</span>
                            </div>
                        </div>
                        <div class="bg-slate-900/70 border border-slate-700 rounded-2xl p-4 flex flex-col justify-between shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs text-slate-400">{{ __('general.home.featured_cities') }}</span>
                                <span class="text-[10px] px-2 py-1 rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/40">Hot</span>
                            </div>
                            <p class="text-sm text-slate-200 mb-2 line-clamp-2">{{ __('general.home.view_all_cities') }}</p>
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span><i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>+18 {{ __('general.home.view_all_properties') }}</span>
                                <span><i class="fas fa-clock {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grids + CTA re-used من الصفحة الأصلية عشان التوافق مع البيانات --}}
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
                    idPrefix="products-dark"
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
                    idPrefix="cities-dark"
                />
            @endif

            @if(isset($categories) && $categories->count() > 0)
                <x-multi-view-grid 
                    :items="$categories" 
                    type="categories" 
                    :title="__('general.home.featured_categories')"
                    :maxItems="6"
                    :showViewToggle="false"
                    idPrefix="categories-dark"
                />
            @endif

            <section class="rounded-2xl border border-slate-800 bg-slate-900/70 p-8 text-center">
                <h2 class="text-2xl font-semibold mb-3 text-white">{{ __('general.home.cta_title') }}</h2>
                <p class="text-slate-300 mb-6">{{ __('general.home.cta_subtitle') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-cyan-500 text-slate-950 px-6 py-3 rounded-lg hover:bg-cyan-400 transition-colors text-sm font-semibold">
                        {{ __('general.home.register_now') }}
                    </a>
                    <a href="{{ route('public.contact') }}" 
                       class="border border-slate-600 text-slate-100 px-6 py-3 rounded-lg hover:bg-slate-800 transition-colors text-sm">
                        {{ __('general.home.contact_us') }}
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
