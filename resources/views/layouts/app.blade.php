<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
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
                            <i class="fas fa-home mr-2"></i>عقار
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        الرئيسية
                    </a>
                    
                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                            الفئات
                            <i class="fas fa-chevron-down mr-1 text-xs"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50">
                            @php
                                $categories = \App\Models\Category::withCount(['products' => function ($query) {
                                    $query->where('is_active', true);
                                }])->where('is_active', true)->take(8)->get();
                            @endphp
                            @foreach($categories as $category)
                                <a href="{{ route('public.products.by-category', $category->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600">
                                    <div class="flex justify-between items-center">
                                        <span>{{ $category->display_name ?? $category->name }}</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <a href="{{ route('public.categories.index') }}" class="block px-4 py-2 text-sm text-primary-600 hover:bg-gray-100">
                                    عرض جميع الفئات
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cities Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                            المدن
                            <i class="fas fa-chevron-down mr-1 text-xs"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50">
                            @php
                                $cities = \App\Models\City::withCount(['products' => function ($query) {
                                    $query->where('is_active', true);
                                }])->where('is_active', true)->take(8)->get();
                            @endphp
                            @foreach($cities as $city)
                                <a href="{{ route('public.products.index', ['city' => $city->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600">
                                    <div class="flex justify-between items-center">
                                        <span>{{ $city->name }}</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $city->products_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <a href="{{ route('public.cities.index') }}" class="block px-4 py-2 text-sm text-primary-600 hover:bg-gray-100">
                                    عرض جميع المدن
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('public.products.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        العقارات
                    </a>
                    @if(\App\Helpers\FacilityHelper::isMultiMode())
                        <a href="{{ route('public.facilities.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            المنشآت
                        </a>
                    @endif
                    
                    <a href="{{ route('public.about') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        من نحن
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        اتصل بنا
                    </a>
                </div>

                <!-- Desktop User Menu -->
                <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                    @auth
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-700 hover:text-primary-600 transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto">

                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">الإشعارات</h3>
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
                                                <div class="flex-1 mr-3">
                                                    <p class="text-sm text-gray-900">{{ $notification->data['message'] ?? 'إشعار جديد' }}</p>
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
                                            عرض كل الإشعارات
                                        </a>
                                    </div>
                                @else
                                    <div class="px-4 py-8 text-center">
                                        <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500">لا توجد إشعارات جديدة</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Logged in user menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 space-x-reverse text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . auth()->user()->name . '&color=7C3AED&background=EBF4FF' }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">

                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt ml-2"></i>لوحة التحكم
                                    </a>
                                @endif

                                @if(auth()->user()->hasRole('facility'))
                                    <a href="{{ route('facility.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-building ml-2"></i>إدارة المنشأة
                                    </a>
                                @endif

                                @if(auth()->user()->hasRole('client'))
                                    <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user ml-2"></i>حسابي
                                    </a>
                                @endif

                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit ml-2"></i>تعديل الملف الشخصي
                                </a>

                                <hr class="my-1">

                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt ml-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest user buttons -->
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                            إنشاء حساب
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
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        الرئيسية
                    </a>
                    <a href="{{ route('public.products.index') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        العقارات
                    </a>
                    @if(\App\Helpers\FacilityHelper::isMultiMode())
                        <a href="{{ route('public.facilities.index') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                            المنشآت
                        </a>
                    @endif
                    <a href="{{ route('public.about') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        من نحن
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        اتصل بنا
                    </a>

                    @auth
                        <!-- Mobile Notifications -->
                        <hr class="my-2">
                        <div class="px-3 py-2">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-medium text-gray-700">الإشعارات</span>
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
                                            <div class="flex-shrink-0 mr-2">
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
                                                <p class="text-sm text-gray-900">{{ $notification->data['message'] ?? 'إشعار جديد' }}</p>
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <a href="{{ route('client.notifications') }}" class="block text-sm text-primary-600 hover:text-primary-700 text-center py-2">
                                        عرض كل الإشعارات
                                    </a>
                                </div>
                            @else
                                <div class="mt-2 text-center py-4">
                                    <i class="fas fa-bell-slash text-gray-400 text-xl mb-2"></i>
                                    <p class="text-sm text-gray-500">لا توجد إشعارات جديدة</p>
                                </div>
                            @endif
                        </div>

                        <hr class="my-2">
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-tachometer-alt ml-2"></i>لوحة التحكم
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('facility'))
                            <a href="{{ route('facility.dashboard') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-building ml-2"></i>إدارة المنشأة
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('client'))
                            <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                                <i class="fas fa-user ml-2"></i>حسابي
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-user-edit ml-2"></i>تعديل الملف الشخصي
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-right px-3 py-2 text-red-600 hover:text-red-700 text-base font-medium">
                                <i class="fas fa-sign-out-alt ml-2"></i>تسجيل الخروج
                            </button>
                        </form>
                    @else
                        <hr class="my-2">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-white block px-3 py-2 rounded-md text-base font-medium text-center">
                            إنشاء حساب
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">عقار</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        منصة عقارية متكاملة تقدم أفضل الخدمات في مجال العقارات
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">خدماتنا</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">بيع العقارات</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">تأجير العقارات</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">إدارة الممتلكات</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover:text-white transition-colors">الاستشارات العقارية</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('public.home') }}" class="hover:text-white transition-colors">الرئيسية</a></li>
                        <li><a href="{{ route('public.products.index') }}" class="hover:text-white transition-colors">العقارات</a></li>
                        <li><a href="{{ route('public.facilities.index') }}" class="hover:text-white transition-colors">المنشآت</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover:text-white transition-colors">اتصل بنا</a></li>
                        
                        <!-- Dynamic Footer Links -->
                        @php
                            $footerLinks = \App\Models\Page::ofType('footer')->active()->ordered()->take(4)->get();
                        @endphp
                        @foreach($footerLinks as $link)
                            <li><a href="{{ route('public.' . $link->slug) }}" class="hover:text-white transition-colors">{{ $link->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">تواصل معنا</h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <p><i class="fas fa-phone ml-2"></i>+966 50 123 4567</p>
                        <p><i class="fas fa-envelope ml-2"></i>info@aqar.com</p>
                        <p><i class="fas fa-map-marker-alt ml-2"></i>الرياض، المملكة العربية السعودية</p>
                    </div>
                    <div class="flex space-x-4 space-x-reverse mt-4">
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
                <p>&copy; {{ date('Y') }} عقار. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
