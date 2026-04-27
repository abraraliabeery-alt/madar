<div class="text-center py-16 mb-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-4 hero-title">{{ __('general.home.title') }}</h1>
    <p class="text-lg text-gray-600 mb-8">{{ __('general.home.subtitle') }}</p>

    <div class="max-w-2xl mx-auto">
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
</div>
