@php
    $currentLocale = app()->getLocale();
    $otherLocale = $currentLocale === 'ar' ? 'en' : 'ar';
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" @click.away="open = false" 
            class="flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-2 space-x-reverse' : 'space-x-2' }} text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
        <i class="fas fa-globe text-lg"></i>
        <span class="hidden sm:inline">{{ __('layout.navigation.language') }}</span>
        <span class="font-semibold">{{ strtoupper($currentLocale) }}</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>
    
    <div x-show="open" 
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
                <span class="w-6 h-4 bg-green-500 rounded {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} flex-shrink-0"></span>
                <span>{{ __('layout.navigation.arabic') }}</span>
                @if($currentLocale === 'ar')
                    <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}"></i>
                @endif
            </div>
        </a>
        
        <a href="{{ route('public.language.change', 'en') }}" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentLocale === 'en' ? 'bg-primary-50 text-primary-700' : '' }}">
            <div class="flex items-center">
                <span class="w-6 h-4 bg-blue-500 rounded {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} flex-shrink-0"></span>
                <span>{{ __('layout.navigation.english') }}</span>
                @if($currentLocale === 'en')
                    <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}"></i>
                @endif
            </div>
        </a>
    </div>
</div>
