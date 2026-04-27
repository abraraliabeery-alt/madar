<section class="mb-16">
    <div class="relative max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div class="order-2 lg:order-1">
            <div class="inline-flex items-center px-3 py-1 mb-4 border border-dashed border-primary-400 rounded-full text-xs text-primary-700 bg-primary-50">
                <span class="w-2 h-2 rounded-full bg-primary-500 mr-2"></span>
                {{ __('general.home.title') }}
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 hero-title leading-tight">
                {{ __('general.home.subtitle') }}
            </h1>
            <p class="text-gray-600 mb-6 text-sm md:text-base">
                {{ __('general.home.search_placeholder') }}
            </p>
            <form action="{{ route('public.search') }}" method="GET" class="flex search-form mb-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                <input type="text"
                       class="flex-1 px-4 py-3 text-gray-900 border border-gray-300 search-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent rounded-lg"
                       name="q"
                       placeholder="{{ __('general.home.search_placeholder') }}"
                       required>
                <button class="ml-2 bg-primary-600 text-white px-5 py-3 rounded-lg search-button hover:bg-primary-700 transition-colors flex items-center space-x-2" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full">
                    <i class="fas fa-check-circle text-green-500 ml-1"></i>{{ __('general.home.latest_properties') }}
                </span>
                <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full">
                    <i class="fas fa-city text-primary-500 ml-1"></i>{{ __('general.home.featured_cities') }}
                </span>
            </div>
        </div>
        <div class="order-1 lg:order-2">
            <div class="relative h-64 md:h-80 rounded-3xl overflow-hidden shadow-xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex items-center justify-center">
                <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top,_#f97316_0,_transparent_60%)]"></div>
                <div class="relative text-center">
                    <div class="text-6xl mb-4">
                        <i class="fas fa-city"></i>
                    </div>
                    <p class="text-sm opacity-80 mb-1">{{ __('general.home.view_all_cities') }}</p>
                    <p class="text-lg font-semibold">{{ __('general.home.view_all_properties') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
