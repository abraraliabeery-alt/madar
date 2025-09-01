<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'عقار') }} - @yield('title', 'الرئيسية')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        /* RTL/LTR specific styles */
        [dir="rtl"] .rtl\:text-right {
            text-align: right;
        }

        [dir="rtl"] .rtl\:text-left {
            text-align: left;
        }

        [dir="rtl"] .rtl\:mr-2 {
            margin-right: 0.5rem;
        }

        [dir="rtl"] .rtl\:ml-2 {
            margin-left: 0.5rem;
        }

        [dir="rtl"] .rtl\:mr-1 {
            margin-right: 0.25rem;
        }

        [dir="rtl"] .rtl\:ml-1 {
            margin-left: 0.25rem;
        }

        [dir="rtl"] .rtl\:mr-3 {
            margin-right: 0.75rem;
        }

        [dir="rtl"] .rtl\:ml-3 {
            margin-left: 0.75rem;
        }

        [dir="rtl"] .rtl\:space-x-8 {
            margin-right: 2rem;
        }

        [dir="rtl"] .rtl\:space-x-4 {
            margin-right: 1rem;
        }

        [dir="rtl"] .rtl\:space-x-2 {
            margin-right: 0.5rem;
        }

        [dir="rtl"] .rtl\:space-x-reverse {
            direction: rtl;
        }

        [dir="ltr"] .ltr\:text-left {
            text-align: left;
        }

        [dir="ltr"] .ltr\:text-right {
            text-align: right;
        }

        [dir="ltr"] .ltr\:ml-2 {
            margin-left: 0.5rem;
        }

        [dir="ltr"] .ltr\:mr-2 {
            margin-right: 0.5rem;
        }

        [dir="ltr"] .ltr\:ml-1 {
            margin-left: 0.25rem;
        }

        [dir="ltr"] .ltr\:mr-1 {
            margin-right: 0.25rem;
        }

        [dir="ltr"] .ltr\:ml-3 {
            margin-left: 0.75rem;
        }

        [dir="ltr"] .ltr\:mr-3 {
            margin-right: 0.75rem;
        }

        [dir="ltr"] .ltr\:space-x-8 {
            margin-left: 2rem;
        }

        [dir="ltr"] .ltr\:space-x-4 {
            margin-left: 1rem;
        }

        [dir="ltr"] .ltr\:space-x-2 {
            margin-left: 0.5rem;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        /* Hide Alpine.js elements by default */
        [x-cloak] {
            display: none !important;
        }

        /* Mobile menu default hidden state */
        .mobile-menu {
            display: none;
        }

        .mobile-menu.show {
            display: block;
        }

        /* Desktop dropdown menus default hidden state */
        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }
    </style>

    @stack('styles')
</head>
<body class="font-cairo bg-gray-50 text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('public.home') }}" class="text-2xl font-bold text-primary-600">
                            <i class="fas fa-home {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ app()->getLocale() == 'ar' ? 'عقار' : 'Aqar' }}
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-8 space-x-reverse' : 'space-x-8' }}">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('layout.navigation.home') }}
                    </a>
                    
                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                            {{ __('layout.navigation.categories') }}
                            <i class="fas fa-chevron-down {{ app()->getLocale() == 'ar' ? 'mr-1' : 'ml-1' }} text-xs"></i>
                        </button>
                        <div :class="{ 'show': open }" x-transition class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50 dropdown-menu">
                            @php
                                $categories = \App\Models\Category::withCount(['products' => function ($query) {
                                    $query->where('is_active', true);
                                }])->where('is_active', true)->take(8)->get();
                            @endphp
                            @foreach($categories as $category)
                                <a href="{{ route('public.products.by-category', $category->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600">
                                    <div class="flex justify-between items-center">
                                        <span>@if($category->display_name){{ $category->display_name }}@else{{ App\Helpers\LanguageHelper::getCategoryName($category) }}@endif</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <a href="{{ route('public.categories.index') }}" class="block px-4 py-2 text-sm text-primary-600 hover:bg-gray-100">
                                    {{ __('layout.navigation.view_all_categories') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cities Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                            {{ __('layout.navigation.cities') }}
                            <i class="fas fa-chevron-down {{ app()->getLocale() == 'ar' ? 'mr-1' : 'ml-1' }} text-xs"></i>
                        </button>
                        <div :class="{ 'show': open }" x-transition class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50 dropdown-menu">
                            @php
                                $cities = \App\Models\City::withCount(['products' => function ($query) {
                                    $query->where('is_active', true);
                                }])->where('is_active', true)->take(8)->get();
                            @endphp
                            @foreach($cities as $city)
                                <a href="{{ route('public.cities.products', $city) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600">
                                    <div class="flex justify-between items-center">
                                        <span>@cityName($city)</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $city->products_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <a href="{{ route('public.cities.index') }}" class="block px-4 py-2 text-sm text-primary-600 hover:bg-gray-100">
                                    {{ __('layout.navigation.view_all_cities') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('public.products.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('layout.navigation.properties') }}
                    </a>
                    @if(\App\Helpers\FacilityHelper::isMultiMode())
                        <a href="{{ route('public.facilities.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('layout.navigation.facilities') }}
                        </a>
                    @endif
                    
                    <a href="{{ route('public.about') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('layout.navigation.about_us') }}
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('layout.navigation.contact_us') }}
                    </a>
                    
                    <!-- Language Switcher - Only for guest users -->
                    @guest
                        <x-language-switcher />
                    @endguest
                </div>

                <!-- Desktop User Menu -->
                <div class="hidden md:flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-4 space-x-reverse' : 'space-x-4' }}">
                    @auth
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-700 hover:text-primary-600 transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute -top-1 {{ app()->getLocale() == 'ar' ? '-right-1' : '-left-1' }} bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div :class="{ 'show': open }" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto dropdown-menu">

                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">{{ __('layout.notifications.title') }}</h3>
                                </div>

                                @if(auth()->user()->notifications->count() > 0)
                                    @foreach(auth()->user()->notifications->take(5) as $notification)
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-75' : '' }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    @if($notification->data['type'] == 'booking_created')
                                                        <i class="fas fa-calendar-check text-green-500"></i>
                                                    @elseif($notification->data['type'] == 'new_product_added')
                                                        <i class="fas fa-home text-blue-500"></i>
                                                    @elseif($notification->data['type'] == 'booking_status_changed')
                                                        <i class="fas fa-sync-alt text-yellow-500"></i>
                                                    @else
                                                        <i class="fas fa-bell text-gray-500"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-1 {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }}">
                                                    <p class="text-sm text-gray-900">{{ $notification->data['message'] ?? __('layout.notifications.new_notification') }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if(!$notification->read_at)
                                                    <div class="flex-shrink-0">
                                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach

                                    <div class="border-t border-gray-200 px-4 py-2">
                                        <a href="{{ route('client.notifications') }}" class="text-sm text-primary-600 hover:text-primary-700">
                                            {{ __('layout.notifications.view_all_notifications') }}
                                        </a>
                                    </div>
                                @else
                                    <div class="px-4 py-8 text-center">
                                        <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500">{{ __('layout.notifications.no_new_notifications') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Logged in user menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-2 space-x-reverse' : 'space-x-2' }} text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . auth()->user()->name . '&color=7C3AED&background=EBF4FF' }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div :class="{ 'show': open }" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 dropdown-menu">

                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.dashboard') }}
                                    </a>
                                @endif

                                @if(auth()->user()->hasRole('facility'))
                                    <a href="{{ route('facility.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-building {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.facility_management') }}
                                    </a>
                                @endif 

                                @if(auth()->user()->hasRole('client'))
                                    <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.my_account') }}
                                    </a>
                                @endif

                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.edit_profile') }}
                                </a>

                                <hr class="my-1">

                                <!-- Language Switcher in Profile Menu -->
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <div class="text-xs text-gray-500 mb-2">{{ __('layout.navigation.language') }}</div>
                                    <div class="space-y-1">
                                        <a href="{{ route('public.language.change', 'ar') }}" 
                                           class="flex items-center px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded {{ app()->getLocale() === 'ar' ? 'bg-primary-50 text-primary-700' : '' }}">
                                            <span class="w-4 h-3 bg-green-500 rounded {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} flex-shrink-0"></span>
                                            <span>{{ __('layout.navigation.arabic') }}</span>
                                            @if(app()->getLocale() === 'ar')
                                                <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} text-xs"></i>
                                            @endif
                                        </a>
                                        <a href="{{ route('public.language.change', 'en') }}" 
                                           class="flex items-center px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded {{ app()->getLocale() === 'en' ? 'bg-primary-50 text-primary-700' : '' }}">
                                            <span class="w-4 h-3 bg-blue-500 rounded {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} flex-shrink-0"></span>
                                            <span>{{ __('layout.navigation.english') }}</span>
                                            @if(app()->getLocale() === 'en')
                                                <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} text-xs"></i>
                                            @endif
                                        </a>
                                    </div>
                                </div>

                                <hr class="my-1">

                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest user buttons -->
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('layout.user_menu.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-2 rounded-md text-sm font-medium">
                            {{ __('layout.user_menu.create_account') }}
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-primary-600 p-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div :class="{ 'show': mobileMenuOpen }"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="md:hidden mobile-menu"
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('layout.navigation.home') }}
                    </a>
                    <a href="{{ route('public.products.index') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('layout.navigation.properties') }}
                    </a>
                    @if(\App\Helpers\FacilityHelper::isMultiMode())
                        <a href="{{ route('public.facilities.index') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                            {{ __('layout.navigation.facilities') }}
                        </a>
                    @endif
                    <a href="{{ route('public.about') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('layout.navigation.about_us') }}
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('layout.navigation.contact_us') }}
                    </a>
                    
                    <!-- Mobile Language Switcher -->
                    <div class="px-3 py-2">
                        <x-language-switcher />
                    </div>

                    @auth
                        <!-- Mobile Notifications -->
                        <hr class="my-2">
                        <div class="px-3 py-2">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-medium text-gray-700">{{ __('layout.notifications.title') }}</span>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </div>
                            @if(auth()->user()->notifications->count() > 0)
                                <div class="mt-2 space-y-2">
                                    @foreach(auth()->user()->notifications->take(3) as $notification)
                                        <div class="flex items-start p-2 bg-gray-50 rounded">
                                            <div class="flex-shrink-0 {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }}">
                                                @if($notification->data['type'] == 'booking_created')
                                                    <i class="fas fa-calendar-check text-green-500"></i>
                                                @elseif($notification->data['type'] == 'new_product_added')
                                                    <i class="fas fa-home text-blue-500"></i>
                                                @elseif($notification->data['type'] == 'booking_status_changed')
                                                    <i class="fas fa-sync-alt text-yellow-500"></i>
                                                @else
                                                    <i class="fas fa-bell text-gray-500"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">{{ $notification->data['message'] ?? __('layout.notifications.new_notification') }}</p>
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <a href="{{ route('client.notifications') }}" class="block text-sm text-primary-600 hover:text-primary-700 text-center py-2">
                                        {{ __('layout.notifications.view_all_notifications') }}
                                    </a>
                                </div>
                            @else
                                <div class="mt-2 text-center py-4">
                                    <i class="fas fa-bell-slash text-gray-400 text-xl mb-2"></i>
                                    <p class="text-sm text-gray-500">{{ __('layout.notifications.no_new_notifications') }}</p>
                                </div>
                            @endif
                        </div>

                        <hr class="my-2">
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-tachometer-alt {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.dashboard') }}
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('facility'))
                            <a href="{{ route('facility.dashboard') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-building {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.facility_management') }}
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('client'))
                            <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-user {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.my_account') }}
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-user-edit {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.edit_profile') }}
                        </a>

                        <hr class="my-2">

                        <!-- Mobile Language Switcher in Profile Menu -->
                        <div class="px-3 py-2 border-t border-gray-200">
                            <div class="text-sm text-gray-500 mb-2">{{ __('layout.navigation.language') }}</div>
                            <div class="space-y-1">
                                <a href="{{ route('public.language.change', 'ar') }}" 
                                   class="flex items-center px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded {{ app()->getLocale() === 'ar' ? 'bg-primary-50 text-primary-700' : '' }}">
                                    <span class="w-4 h-3 bg-green-500 rounded {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} flex-shrink-0"></span>
                                    <span>{{ __('layout.navigation.arabic') }}</span>
                                    @if(app()->getLocale() === 'ar')
                                        <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} text-xs"></i>
                                    @endif
                                </a>
                                <a href="{{ route('public.language.change', 'en') }}" 
                                   class="flex items-center px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded {{ app()->getLocale() === 'en' ? 'bg-primary-50 text-primary-700' : '' }}">
                                    <span class="w-4 h-3 bg-blue-500 rounded {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} flex-shrink-0"></span>
                                    <span>{{ __('layout.navigation.english') }}</span>
                                    @if(app()->getLocale() === 'en')
                                        <i class="fas fa-check text-primary-600 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} text-xs"></i>
                                    @endif
                                </a>
                            </div>
                        </div>

                        <hr class="my-2">

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} px-3 py-2 text-red-600 hover:text-red-700 text-base font-medium">
                                <i class="fas fa-sign-out-alt {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('layout.user_menu.logout') }}
                            </button>
                        </form>
                    @else
                        <hr class="my-2">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                            {{ __('layout.user_menu.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-white block px-3 py-2 rounded-md text-base font-medium text-center">
                            {{ __('layout.user_menu.create_account') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ app()->getLocale() == 'ar' ? 'عقار' : 'Aqar' }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        {{ app()->getLocale() == 'ar' ? 'منصة عقارية متكاملة تقدم أفضل الخدمات في مجال العقارات' : 'An integrated real estate platform offering the best services in the real estate field' }}
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ app()->getLocale() == 'ar' ? 'خدماتنا' : 'Our Services' }}</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'بيع العقارات' : 'Property Sales' }}</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'تأجير العقارات' : 'Property Rentals' }}</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'إدارة الممتلكات' : 'Property Management' }}</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'الاستشارات العقارية' : 'Real Estate Consultations' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ app()->getLocale() == 'ar' ? 'روابط سريعة' : 'Quick Links' }}</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('public.home') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}</a></li>
                        <li><a href="{{ route('public.products.index') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'العقارات' : 'Properties' }}</a></li>
                        <li><a href="{{ route('public.facilities.index') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'المنشآت' : 'Facilities' }}</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'اتصل بنا' : 'Contact Us' }}</a></li>
                        <li><a href="{{ route('public.how-it-works') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'كيف يعمل' : 'How It Works' }}</a></li>
                        <li><a href="{{ route('public.pricing') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'الأسعار' : 'Pricing' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ app()->getLocale() == 'ar' ? 'صفحات إضافية' : 'Additional Pages' }}</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('public.testimonials') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'آراء العملاء' : 'Testimonials' }}</a></li>
                        <li><a href="{{ route('public.blog') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'المدونة' : 'Blog' }}</a></li>
                        <li><a href="{{ route('public.news') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'الأخبار' : 'News' }}</a></li>
                        <li><a href="{{ route('public.careers') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'الوظائف' : 'Careers' }}</a></li>
                        <li><a href="{{ route('public.terms') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'الشروط والأحكام' : 'Terms of Service' }}</a></li>
                        <li><a href="{{ route('public.privacy') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a></li>
                        <li><a href="{{ route('public.cookies') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'سياسة ملفات تعريف الارتباط' : 'Cookies Policy' }}</a></li>
                        <li><a href="{{ route('public.advertising') }}" class="hover:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'سياسة الإعلانات' : 'Advertising Policy' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ app()->getLocale() == 'ar' ? 'تواصل معنا' : 'Contact Us' }}</h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <p><i class="fas fa-phone {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>+966 50 123 4567</p>
                        <p><i class="fas fa-envelope {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>info@aqar.com</p>
                        <p><i class="fas fa-map-marker-alt {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ app()->getLocale() == 'ar' ? 'الرياض، المملكة العربية السعودية' : 'Riyadh, Saudi Arabia' }}</p>
                    </div>
                    <div class="flex {{ app()->getLocale() == 'ar' ? 'space-x-4 space-x-reverse' : 'space-x-4' }} mt-4">
                        @php
                            $socialLinks = [
                                'facebook' => \App\Models\Setting::getValue('social_facebook'),
                                'twitter' => \App\Models\Setting::getValue('social_twitter'),
                                'instagram' => \App\Models\Setting::getValue('social_instagram'),
                                'linkedin' => \App\Models\Setting::getValue('social_linkedin'),
                                'youtube' => \App\Models\Setting::getValue('social_youtube'),
                                'snapchat' => \App\Models\Setting::getValue('social_snapchat'),
                            ];
                        @endphp
                        
                        @if($socialLinks['facebook'])
                            <a href="{{ $socialLinks['facebook'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                        @endif
                        
                        @if($socialLinks['twitter'])
                            <a href="{{ $socialLinks['twitter'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                        @endif
                        
                        @if($socialLinks['instagram'])
                            <a href="{{ $socialLinks['instagram'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        @endif
                        
                        @if($socialLinks['linkedin'])
                            <a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-linkedin text-xl"></i>
                            </a>
                        @endif
                        
                        @if($socialLinks['youtube'])
                            <a href="{{ $socialLinks['youtube'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-youtube text-xl"></i>
                            </a>
                        @endif
                        
                        @if($socialLinks['snapchat'])
                            <a href="{{ $socialLinks['snapchat'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-snapchat text-xl"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ app()->getLocale() == 'ar' ? 'عقار' : 'Aqar' }}. {{ app()->getLocale() == 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
