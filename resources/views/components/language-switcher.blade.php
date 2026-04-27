@php
    $currentLocale = app()->getLocale();
    $otherLocale = $currentLocale === 'ar' ? 'en' : 'ar';
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" @click.away="open = false" 
            class="flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-2 space-x-reverse' : 'space-x-2' }} text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
        <i class="fas fa-globe text-lg"></i>
        <span class="font-semibold">{{ strtoupper($currentLocale) }}</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>
    
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
        
        <a href="{{ route('public.language.change', 'ar') }}" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentLocale === 'ar' ? 'bg-primary-50 text-primary-700' : '' }}">
            <div class="flex items-center">
                <span class="{{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} leading-none flex-shrink-0" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" xmlns="http://www.w3.org/2000/svg" class="block rounded-sm">
                        <rect width="18" height="12" rx="2" fill="#0B7A3B"/>
                        <path d="M4.2 6.3h8.6" stroke="#ffffff" stroke-width="1" stroke-linecap="round" opacity="0.95"/>
                        <path d="M6.2 8.7h6.2" stroke="#ffffff" stroke-width="1" stroke-linecap="round" opacity="0.95"/>
                    </svg>
                </span>
                <span>{{ __('layout.navigation.arabic') }}</span>
                @if($currentLocale === 'ar')
                    <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}"></i>
                @endif
            </div>
        </a>
        
        <a href="{{ route('public.language.change', 'en') }}" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentLocale === 'en' ? 'bg-primary-50 text-primary-700' : '' }}">
            <div class="flex items-center">
                <span class="{{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} leading-none flex-shrink-0" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" xmlns="http://www.w3.org/2000/svg" class="block rounded-sm">
                        <rect width="18" height="12" rx="2" fill="#ffffff"/>
                        <rect y="0" width="18" height="1" fill="#B22234"/>
                        <rect y="2" width="18" height="1" fill="#B22234"/>
                        <rect y="4" width="18" height="1" fill="#B22234"/>
                        <rect y="6" width="18" height="1" fill="#B22234"/>
                        <rect y="8" width="18" height="1" fill="#B22234"/>
                        <rect y="10" width="18" height="1" fill="#B22234"/>
                        <rect width="8" height="6" rx="1" fill="#3C3B6E"/>
                        <circle cx="2" cy="2" r="0.55" fill="#ffffff"/>
                        <circle cx="4" cy="2" r="0.55" fill="#ffffff"/>
                        <circle cx="6" cy="2" r="0.55" fill="#ffffff"/>
                        <circle cx="3" cy="4" r="0.55" fill="#ffffff"/>
                        <circle cx="5" cy="4" r="0.55" fill="#ffffff"/>
                        <circle cx="7" cy="4" r="0.55" fill="#ffffff"/>
                    </svg>
                </span>
                <span>{{ __('layout.navigation.english') }}</span>
                @if($currentLocale === 'en')
                    <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}"></i>
                @endif
            </div>
        </a>

        <a href="{{ route('public.language.change', 'ur') }}" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentLocale === 'ur' ? 'bg-primary-50 text-primary-700' : '' }}">
            <div class="flex items-center">
                <span class="{{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} leading-none flex-shrink-0" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" xmlns="http://www.w3.org/2000/svg" class="block rounded-sm">
                        <rect width="18" height="12" rx="2" fill="#0A7F3F"/>
                        <rect width="4" height="12" rx="2" fill="#ffffff"/>
                        <path d="M12.2 6.2a3.0 3.0 0 1 1-1.6-2.7a2.4 2.4 0 1 0 1.6 2.7Z" fill="#ffffff" opacity="0.95"/>
                        <path d="M13.9 4.2l.35.95.95.05-.75.58.25.92-.8-.5-.8.5.25-.92-.75-.58.95-.05.35-.95Z" fill="#ffffff" opacity="0.95"/>
                    </svg>
                </span>
                <span>{{ __('layout.navigation.urdu') }}</span>
                @if($currentLocale === 'ur')
                    <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}"></i>
                @endif
            </div>
        </a>

        <a href="{{ route('public.language.change', 'zh') }}" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentLocale === 'zh' ? 'bg-primary-50 text-primary-700' : '' }}">
            <div class="flex items-center">
                <span class="{{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} leading-none flex-shrink-0" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" xmlns="http://www.w3.org/2000/svg" class="block rounded-sm">
                        <rect width="18" height="12" rx="2" fill="#DE2910"/>
                        <path d="M4 2.2l.6 1.6 1.6.1-1.25 1 .42 1.55L4 5.6l-1.37.85.42-1.55-1.25-1 1.6-.1L4 2.2Z" fill="#FFDE00"/>
                    </svg>
                </span>
                <span>{{ __('layout.navigation.chinese') }}</span>
                @if($currentLocale === 'zh')
                    <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}"></i>
                @endif
            </div>
        </a>
    </div>
</div>
