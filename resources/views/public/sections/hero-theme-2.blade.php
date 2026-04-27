<section class="relative overflow-hidden rounded-3xl mb-16 gradient-bg text-white">
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,_#ffffff_0,_transparent_60%)]"></div>
    <div class="relative max-w-7xl mx-auto px-6 py-16 flex flex-col lg:flex-row items-center gap-10">
        <div class="flex-1 text-center lg:text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}">
            <p class="uppercase tracking-widest text-xs mb-3 opacity-80">{{ __('general.home.title') }}</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 hero-title leading-tight">
                {{ __('general.home.subtitle') }}
            </h1>
            <p class="text-sm md:text-base opacity-90 mb-8">
                {{ __('general.home.search_placeholder') }}
            </p>
            <div class="max-w-xl mx-auto lg:mx-0">
                <form action="{{ route('public.search') }}" method="GET" class="flex search-form" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                    <input type="text"
                           class="flex-1 px-4 py-3 text-gray-900 border border-transparent search-input focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent rounded-l-lg"
                           name="q"
                           placeholder="{{ __('general.home.search_placeholder') }}"
                           required>
                    <button class="bg-black/70 text-white px-6 py-3 search-button hover:bg-black transition-colors rounded-r-lg" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="flex-1 flex justify-center">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 w-full max-w-md shadow-xl border border-white/20">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm opacity-80">{{ __('general.home.featured_cities') }}</span>
                    <span class="text-xs bg-white/20 px-3 py-1 rounded-full">{{ __('general.home.latest_properties') }}</span>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="opacity-90">{{ __('general.home.view_all_properties') }}</span>
                        <span class="font-semibold">+24</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="opacity-90">{{ __('general.home.view_all_cities') }}</span>
                        <span class="font-semibold">+12</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
