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
                    <a href="{{ route('public.products.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        العقارات
                    </a>
                    <a href="{{ route('public.facilities.index') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        المنشآت
                    </a>
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
                    <a href="{{ route('public.facilities.index') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        المنشآت
                    </a>
                    <a href="{{ route('public.about') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        من نحن
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                        اتصل بنا
                    </a>

                    @auth
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
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
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
