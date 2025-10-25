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
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
                        sans: ['Figtree', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        figtree: ['Figtree', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        inter: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        poppins: ['Poppins', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        roboto: ['Roboto', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        'open-sans': ['Open Sans', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        lato: ['Lato', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        cairo: ['Cairo', 'Segoe UI', 'Tahoma', 'Arial', 'sans-serif'],
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
            font-family: 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        /* RTL Language Support */
        [dir="rtl"] {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif;
        }
        
        /* LTR Language Support */
        [dir="ltr"] {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* RTL/LTR helpers (كما هي) */
        [x-cloak] { display: none !important; }

        .mobile-menu { display: none; }
        .mobile-menu.show { display: block; }

        .dropdown-menu { display: none; }
        .dropdown-menu.show { display: block; }

        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,.1), 0 10px 10px -5px rgba(0,0,0,.04); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all .3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102,126,234,.4); }
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
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <button @click="open = !open" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                        {{ __('layout.navigation.categories') }}
                        <i class="fas fa-chevron-down {{ app()->getLocale() == 'ar' ? 'mr-1' : 'ml-1' }} text-xs"></i>
                    </button>
                    <div x-cloak :class="open ? 'show' : ''" x-transition
                         class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50 dropdown-menu">
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
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <button @click="open = !open" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                        {{ __('layout.navigation.cities') }}
                        <i class="fas fa-chevron-down {{ app()->getLocale() == 'ar' ? 'mr-1' : 'ml-1' }} text-xs"></i>
                    </button>
                    <div x-cloak :class="open ? 'show' : ''" x-transition
                         class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50 dropdown-menu">
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
                            <a href="{{ route('public.cities.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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

                @guest
                    <x-language-switcher />
                @endguest
            </div>

            <!-- Desktop User Menu -->
            <div class="hidden md:flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-4 space-x-reverse' : 'space-x-4' }}">
                @auth
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open=false" @keydown.escape.window="open=false">
                        <button @click="open = !open" class="relative p-2 text-gray-700 hover:text-primary-600 transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 {{ app()->getLocale() == 'ar' ? '-right-1' : '-left-1' }} bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                                    </span>
                            @endif
                        </button>

                        <div x-cloak :class="open ? 'show' : ''" x-transition
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
                                                <p class="text-sm text-gray-900">
                                                    @if(isset($notification->data['message']))
                                                        {{ $notification->data['message'] }}
                                                    @elseif(isset($notification->data['type']))
                                                        @if($notification->data['type'] == 'booking_created')
                                                            تم إنشاء حجز جديد
                                                        @elseif($notification->data['type'] == 'booking_status_changed')
                                                            تم تحديث حالة الحجز
                                                        @elseif($notification->data['type'] == 'new_product_added')
                                                            تم إضافة عقار جديد
                                                        @else
                                                            {{ __('layout.notifications.new_notification') }}
                                                        @endif
                                                    @else
                                                        {{ __('layout.notifications.new_notification') }}
                                                    @endif
                                                </p>
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
                    <div class="relative" x-data="{ open: false }" @click.outside="open=false" @keydown.escape.window="open=false">
                        <button @click="open = !open" class="flex items-center {{ app()->getLocale() == 'ar' ? 'space-x-2 space-x-reverse' : 'space-x-2' }} text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . auth()->user()->name . '&color=7C3AED&background=EBF4FF' }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-8 h-8 rounded-full object-cover">
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div x-cloak :class="open ? 'show' : ''" x-transition
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
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-primary-600 p-2" aria-expanded="false" :aria-expanded="mobileMenuOpen.toString()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu (FIXED > HERE) -->
        <div x-cloak :class="{ 'show': mobileMenuOpen }"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="md:hidden mobile-menu">
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

                <div class="px-3 py-2">
                    <x-language-switcher />
                </div>

                @auth
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
                                            <p class="text-sm text-gray-900">
                                                @if(isset($notification->data['message']))
                                                    {{ $notification->data['message'] }}
                                                @elseif(isset($notification->data['type']))
                                                    @if($notification->data['type'] == 'booking_created')
                                                        تم إنشاء حجز جديد
                                                    @elseif($notification->data['type'] == 'booking_status_changed')
                                                        تم تحديث حالة الحجز
                                                    @elseif($notification->data['type'] == 'new_product_added')
                                                        تم إضافة عقار جديد
                                                    @else
                                                        {{ __('layout.notifications.new_notification') }}
                                                    @endif
                                                @else
                                                    {{ __('layout.notifications.new_notification') }}
                                                @endif
                                            </p>
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
    <!-- Global Validation Errors Display -->
    <div id="global-validation-errors" class="hidden fixed top-20 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full">
        <div class="bg-red-50 border border-red-200 rounded-md p-4 shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800" id="validation-error-title">
                        {{ __('validation.errors.title') }}
                    </h3>
                    <div class="mt-2 text-sm text-red-700" id="validation-error-message"></div>
                    <div class="mt-4">
                        <button type="button" onclick="hideGlobalValidationErrors()" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-2 rounded-md text-sm font-medium">
                            {{ __('validation.errors.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('content')
</main>

<!-- Footer (كما هو) -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- محتوى الفوتر كما هو في كودك الأصلي -->
        @php /* إبقيه كما أرسلته لتقليل طول الرد */ @endphp
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} {{ app()->getLocale() == 'ar' ? 'عقار' : 'Aqar' }}. {{ app()->getLocale() == 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS (اختياري) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Global Validation Error Handler (كما هو) -->
<script>
    // Global validation error handling
    window.showGlobalValidationErrors = function(errors) {
        const container = document.getElementById('global-validation-errors');
        const title = document.getElementById('validation-error-title');
        const message = document.getElementById('validation-error-message');

        if (!container) return;

        // Clear previous errors
        message.innerHTML = '';

        // Create error list
        let errorHtml = '<ul class="list-disc list-inside space-y-1">';
        if (typeof errors === 'string') {
            errorHtml += `<li>${errors}</li>`;
        } else if (Array.isArray(errors)) {
            errors.forEach(error => {
                errorHtml += `<li>${error}</li>`;
            });
        } else if (typeof errors === 'object') {
            Object.keys(errors).forEach(key => {
                if (Array.isArray(errors[key])) {
                    errors[key].forEach(error => {
                        errorHtml += `<li>${error}</li>`;
                    });
                } else {
                    errorHtml += `<li>${errors[key]}</li>`;
                }
            });
        }
        errorHtml += '</ul>';

        message.innerHTML = errorHtml;
        container.classList.remove('hidden');

        // Auto-hide after 10 seconds
        setTimeout(() => {
            hideGlobalValidationErrors();
        }, 10000);
    };

    window.hideGlobalValidationErrors = function() {
        const container = document.getElementById('global-validation-errors');
        if (container) {
            container.classList.add('hidden');
        }
    };

    // Intercept form submissions to handle validation errors
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Laravel validation errors from session
        @if($errors->any())
        showGlobalValidationErrors(@json($errors->all()));
        @endif

        // Intercept form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM') {
                // Store form data for potential resubmission
                form.dataset.submitting = 'true';
            }
        });

        // Handle AJAX form submissions
        document.addEventListener('ajax:error', function(e) {
            const response = e.detail[0];
            if (response && response.responseJSON && response.responseJSON.errors) {
                showGlobalValidationErrors(response.responseJSON.errors);
            } else if (response && response.responseJSON && response.responseJSON.message) {
                showGlobalValidationErrors(response.responseJSON.message);
            }
        });

        // Handle fetch API errors
        window.addEventListener('unhandledrejection', function(e) {
            if (e.reason && e.reason.errors) {
                showGlobalValidationErrors(e.reason.errors);
            }
        });
    });

    // Utility function to show success messages
    window.showGlobalSuccessMessage = function(message) {
        const container = document.getElementById('global-validation-errors');
        const title = document.getElementById('validation-error-title');
        const messageDiv = document.getElementById('validation-error-message');

        if (!container) return;

        // Change styling to success
        container.querySelector('.bg-red-50').classList.remove('bg-red-50', 'border-red-200');
        container.querySelector('.bg-red-50').classList.add('bg-green-50', 'border-green-200');
        container.querySelector('.text-red-400').classList.remove('text-red-400');
        container.querySelector('.text-red-400').classList.add('text-green-400');
        container.querySelector('.fas.fa-exclamation-triangle').classList.remove('fa-exclamation-triangle');
        container.querySelector('.fas').classList.add('fa-check-circle');

        title.classList.remove('text-red-800');
        title.classList.add('text-green-800');
        title.textContent = '{{ __("validation.success.title") }}';

        messageDiv.classList.remove('text-red-700');
        messageDiv.classList.add('text-green-700');
        messageDiv.innerHTML = `<p>${message}</p>`;

        container.classList.remove('hidden');

        // Auto-hide after 5 seconds
        setTimeout(() => {
            hideGlobalValidationErrors();
        }, 5000);
    };

    // Utility function to show info messages
    window.showGlobalInfoMessage = function(message) {
        const container = document.getElementById('global-validation-errors');
        const title = document.getElementById('validation-error-title');
        const messageDiv = document.getElementById('validation-error-message');

        if (!container) return;

        // Change styling to info
        container.querySelector('.bg-red-50').classList.remove('bg-red-50', 'border-red-200');
        container.querySelector('.bg-red-50').classList.add('bg-blue-50', 'border-blue-200');
        container.querySelector('.text-red-400').classList.remove('text-red-400');
        container.querySelector('.text-red-400').classList.add('text-blue-400');
        container.querySelector('.fas.fa-exclamation-triangle').classList.remove('fa-exclamation-triangle');
        container.querySelector('.fas').classList.add('fa-info-circle');

        title.classList.remove('text-red-800');
        title.classList.add('text-blue-800');
        title.textContent = '{{ __("validation.info.title") }}';

        messageDiv.classList.remove('text-red-700');
        messageDiv.classList.add('text-blue-700');
        messageDiv.innerHTML = `<p>${message}</p>`;

        container.classList.remove('hidden');

        // Auto-hide after 7 seconds
        setTimeout(() => {
            hideGlobalValidationErrors();
        }, 7000);
    };
</script>

@stack('scripts')
</body>
</html>
