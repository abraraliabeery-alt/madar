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
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('public.home') }}" class="text-2xl font-bold text-primary-600">
                            <i class="fas fa-home mr-2"></i>عقار
                        </a>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        الرئيسية
                    </a>
                    <a href="#" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        العقارات
                    </a>
                    <a href="#" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        المنشآت
                    </a>
                    <a href="#" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        من نحن
                    </a>
                    <a href="#" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        اتصل بنا
                    </a>
                </div>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                        إنشاء حساب
                    </a>
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
                        <li><a href="#" class="hover:text-white transition-colors">بيع العقارات</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">تأجير العقارات</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">إدارة الممتلكات</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">الاستشارات العقارية</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">الرئيسية</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">العقارات</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">المنشآت</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">اتصل بنا</a></li>
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
