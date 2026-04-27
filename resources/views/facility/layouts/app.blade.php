<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ألف (أ)') }} - @yield('title', 'لوحة تحكم المنشأة')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;600;700;800;900&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                        'tajawal': ['Tajawal', 'Segoe UI', 'Tahoma', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f4ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Additional Styles -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            font-family: 'Tajawal', 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif;
        }
        
        /* Custom styles for components that need specific styling */
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        
        /* DataTables customization */
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 focus:border-primary-500 focus:ring-2 focus:ring-primary-200;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply rounded-lg mx-1 px-3 py-1 border border-gray-300 text-primary-600 hover:bg-primary-500 hover:text-white hover:border-primary-500;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-primary-500 text-white border-primary-500;
        }
        
        /* Select2 customization */
        .select2-container--default .select2-selection--single {
            @apply border border-gray-300 rounded-lg h-10;
        }
        
        .select2-container--default .select2-selection--single:focus {
            @apply border-primary-500 ring-2 ring-primary-200;
        }
        
        /* Summernote customization */
        .note-editor {
            @apply border border-gray-300 rounded-lg;
        }
        
        .note-editor:focus-within {
            @apply border-primary-500 ring-2 ring-primary-200;
        }

        /* Dark mode (facility) */
        html[data-theme="dark"] body {
            background-color: #111827;
            color: #e5e7eb;
        }
        html[data-theme="dark"] .bg-white { background-color: #1f2937 !important; }
        html[data-theme="dark"] .text-gray-900 { color: #e5e7eb !important; }
        html[data-theme="dark"] .text-gray-700,
        html[data-theme="dark"] .text-gray-600 { color: #d1d5db !important; }
        html[data-theme="dark"] .border-gray-200 { border-color: #374151 !important; }
        html[data-theme="dark"] .bg-gray-50 { background-color: #0f172a !important; }
        html[data-theme="dark"] .hover\:bg-gray-100:hover { background-color: #374151 !important; }
        html[data-theme="dark"] .sidebar-gradient { background: linear-gradient(135deg, #1f2937 0%, #111827 100%); }

        /* Global icon spacing */
        i.fa, i.fas, i.far, i.fal, i.fab, i.fad {
            margin-inline: 0.25rem;
        }
    </style>
</head>
<body class="font-tajawal bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <nav class="sidebar-gradient fixed top-0 right-0 w-72 h-screen z-50 transition-all duration-300 ease-in-out md:translate-x-0 translate-x-full" id="sidebar">
            <div class="p-6 border-b border-white border-opacity-10">
                <a href="{{ route('facility.dashboard') }}" class="text-white text-xl font-bold no-underline hover:text-white">
                    <i class="fas fa-building mr-2"></i>
                    <span class="brand-text">منشأتي</span>
                </a>
            </div>
            
            @php
                $sidebarUser = auth()->user();
                $sidebarFacility = $sidebarUser->facilities()->first();
            @endphp

            <div class="py-4 h-screen overflow-y-auto" style="height: calc(100vh - 80px);">
                <ul class="flex flex-col">
                    <!-- Essentials -->
                    <li class="px-6 pt-1 pb-2 text-white text-opacity-70 text-xs uppercase tracking-wider">
                        الأساسيات
                    </li>
                    <li>
                        <a href="{{ route('facility.dashboard') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.dashboard') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-home w-5 text-center"></i>
                            <span class="mr-3">لوحة التحكم</span>
                        </a>
                    </li>
                    @if(config('features.facility_home_v2'))
                    <li>
                        <a href="{{ route('facility.home-v2') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.home-v2') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-gauge-high w-5 text-center"></i>
                            <span class="mr-3 flex items-center gap-2">
                                لوحة متقدمة
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-yellow-400 text-gray-900">تجريبية</span>
                            </span>
                        </a>
                    </li>
                    @endif
                    
                    <li>
                        <a href="{{ route('facility.projects.index') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.projects.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-diagram-project w-5 text-center"></i>
                            <span class="mr-3 flex items-center gap-2">
                                المشاريع
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('facility.execution-requests.workspace') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.execution-requests.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-gavel w-5 text-center"></i>
                            <span class="mr-3 flex items-center gap-2">
                                طلبات التنفيذ
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('facility.tasks.index') }}" class="flex items-center px-6 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.tasks.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-list-check w-5 text-center"></i>
                            <span class="mr-3">المهام</span>
                        </a>
                    </li>
                    <li class="px-6 my-3"><div class="h-px bg-white bg-opacity-10"></div></li>

                    <!-- Finance -->
                    <li class="px-6 pt-4 pb-2 text-white text-opacity-70 text-xs uppercase tracking-wider flex items-center justify-between cursor-pointer" data-section-toggle="finance">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                            المالية
                        </span>
                        <i class="fas fa-chevron-down text-[10px] opacity-70"></i>
                    </li>
                    <li data-section="finance">
                        <a href="{{ route('facility.accounting.dashboard') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.accounting.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-calculator w-5 text-center"></i>
                            <span class="mr-3">المحاسبة</span>
                        </a>
                    </li>

                    <li data-section="finance">
                        <a href="{{ route('facility.financial.dashboard') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.financial.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-coins w-5 text-center"></i>
                            <span class="mr-3">النظام المالي</span>
                        </a>
                    </li>
                    
                    <li class="px-6 my-3"><div class="h-px bg-white bg-opacity-10"></div></li>

                    <!-- Facility & Users -->
                    <li class="px-6 pt-4 pb-2 text-white text-opacity-70 text-xs uppercase tracking-wider flex items-center justify-between cursor-pointer" data-section-toggle="facility-users">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-sky-300"></span>
                            المنشأة والمستخدمون
                        </span>
                        <i class="fas fa-chevron-down text-[10px] opacity-70"></i>
                    </li>
                    <li data-section="facility-users">
                        <a href="{{ route('facility.banks.index') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.banks.index') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-building-columns w-5 text-center"></i>
                            <span class="mr-3">البنوك</span>
                        </a>
                    </li>
                    <li data-section="facility-users">
                        <a href="{{ route('facility.appointments.index') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.appointments.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-calendar-alt w-5 text-center"></i>
                            <span class="mr-3">المواعيد</span>
                        </a>
                    </li>
                    
                    <li data-section="facility-users">
                        <a href="{{ route('facility.users.employee-dashboard') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.users.employee-dashboard') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-user-tie w-5 text-center"></i>
                            <span class="mr-3 flex items-center gap-2">
                                لوحة الموظف
                            </span>
                        </a>
                    </li>

                    <li data-section="facility-users">
                        <a href="{{ route('facility.users.index') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.users.*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span class="mr-3">المستخدمين</span>
                        </a>
                    </li>
                    
                    <li data-section="facility-users">
                        <a href="{{ route('facility.edit') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.edit') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-cog w-5 text-center"></i>
                            <span class="mr-3">الإعدادات</span>
                        </a>
                    </li>

                    <li data-section="facility-users">
                        <a href="{{ route('facility.notifications') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.notifications') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-bell w-5 text-center"></i>
                            <span class="mr-3">الإشعارات والتواصل</span>
                        </a>
                    </li>
                    
                    <li class="px-6 my-3"><div class="h-px bg-white bg-opacity-10"></div></li>

                    <!-- Reports -->
                    <li class="px-6 pt-4 pb-2 text-white text-opacity-70 text-xs uppercase tracking-wider flex items-center justify-between cursor-pointer" data-section-toggle="reports">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-pink-300"></span>
                            التقارير والتحليلات
                        </span>
                        <i class="fas fa-chevron-down text-[10px] opacity-70"></i>
                    </li>
                    <li data-section="reports">
                        <a href="{{ route('facility.reports') }}" class="flex items-center px-6 py-2.5 md:py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.reports*') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-center"></i>
                            <span class="mr-3">التقارير</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('facility.profile') }}" class="flex items-center px-6 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300 {{ request()->routeIs('facility.profile') ? 'bg-white bg-opacity-20 border-l-4 border-white' : '' }}">
                            <i class="fas fa-id-card w-5 text-center"></i>
                            <span class="mr-3">الملف التعريفي</span>
                        </a>
                    </li>

                    @php($currentFacility = auth()->user()->facilities()->first())
                    <li>
                        <a href="{{ $currentFacility ? route('facility.customization.edit', $currentFacility) : route('facility.dashboard') }}" class="flex items-center px-6 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300">
                            <i class="fas fa-wand-magic-sparkles w-5 text-center"></i>
                            <span class="mr-3">تخصيص الموقع</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $currentFacility ? route('public.facility.site.home', $currentFacility->slug ?? $currentFacility->id) : route('facility.dashboard') }}" target="_blank" class="flex items-center px-6 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 transition-all duration-300">
                            <i class="fas fa-external-link-alt w-5 text-center"></i>
                            <span class="mr-3">معاينة الموقع</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 mr-72 min-h-screen transition-all duration-300 ease-in-out" id="mainContent">
            <!-- Header -->
            <header class="bg-white shadow-sm px-8 py-4 sticky top-0 z-40">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <button class="text-gray-600 hover:text-gray-900 md:hidden mr-3" id="sidebarToggle">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h4 class="text-gray-800 font-semibold mb-0">@yield('title', 'لوحة تحكم المنشأة')</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="flex items-center space-x-2 space-x-reverse mb-0">
                                    <li><a href="{{ route('facility.dashboard') }}" class="text-primary-600 hover:text-primary-800 no-underline">
الرئيسية</a></li>
                                    @yield('breadcrumbs')
                                </ol>
                            </nav>
                            @php($headerFacility = auth()->user()->facilities()->first())
                            @if($headerFacility)
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700">
                                        <i class="fas fa-building ml-1 text-[10px]"></i>
                                        {{ $headerFacility->name ?? 'منشأة بدون اسم' }}
                                    </span>
                                    @if($headerFacility->status)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 ml-1"></span>
                                            <i class="{{ $headerFacility->status->icon_class }} ml-1 text-[10px]"></i>
                                            {{ $headerFacility->status->getTranslatedName() }}
                                        </span>
                                    @elseif(!empty($headerFacility->is_active))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 ml-1"></span>
                                            منشأة نشطة
                                        </span>
                                    @endif
                                    <a href="{{ route('facility.edit') }}" class="inline-flex items-center px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">
                                        <i class="fas fa-sliders-h ml-1 text-[10px]"></i>
                                        إعدادات المنشأة
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <!-- Theme Toggle -->
                        <button class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-600 hover:text-gray-900 shadow-sm transition-colors" id="admin-theme-toggle" type="button" title="تبديل الوضع الليلي/النهاري">
                            <i class="fas fa-moon"></i>
                        </button>
                        <div class="hidden md:flex items-center bg-gray-100 rounded-lg overflow-hidden">
                            <a href="{{ route('public.language.change', ['locale' => 'ar']) }}" class="px-3 py-1 text-sm {{ app()->getLocale() === 'ar' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-200' }}">AR</a>
                            <a href="{{ route('public.language.change', ['locale' => 'en']) }}" class="px-3 py-1 text-sm {{ app()->getLocale() === 'en' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-200' }}">EN</a>
                        </div>
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative text-gray-600 hover:text-gray-900 focus:outline-none" id="notificationsDropdown">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    3
                                </span>
                            </button>
                            <div class="hidden absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50" id="notificationsMenu">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h6 class="text-sm font-semibold text-gray-800">الإشعارات</h6>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">إشعار جديد</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">تحديث النظام</a>
                                <div class="border-t border-gray-200"></div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">عرض كل الإشعارات</a>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none" id="userDropdown">
                                <img src="{{ auth()->user()->profile_picture ? Storage::url(auth()->user()->profile_picture) : asset('assets/images/default-avatar.svg') }}" 
                                     alt="صورة المستخدم" class="rounded-full mr-2" width="32" height="32">
                                <span>{{ auth()->user()->name }}</span>
                            </button>
                            <div class="hidden absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50" id="userMenu">
                                <a href="{{ route('facility.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>الإعدادات
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>الملف الشخصي
                                </a>
                                <div class="border-t border-gray-200"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                        <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('warning') }}
                        </div>
                        <button type="button" class="text-yellow-700 hover:text-yellow-900" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ session('info') }}
                        </div>
                        <button type="button" class="text-blue-700 hover:text-blue-900" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('theme.js') }}"></script>
    
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        });

        // Collapsible sidebar sections (rentals, sales, etc.)
        (function () {
            const ACTIVE_CLASS = 'bg-white bg-opacity-20 border-l-4 border-white';

            // Initialize sections: collapse all unless they contain an active item
            document.querySelectorAll('[data-section-toggle]').forEach(function (header) {
                const key = header.getAttribute('data-section-toggle');
                const items = Array.from(document.querySelectorAll('[data-section="' + key + '"]'));

                if (!items.length) return;

                const hasActive = items.some(function (li) {
                    const link = li.querySelector('a');
                    return link && link.className && link.className.indexOf('bg-white bg-opacity-20 border-l-4 border-white') !== -1;
                });

                if (!hasActive) {
                    items.forEach(function (li) { li.classList.add('hidden'); });
                }

                header.addEventListener('click', function () {
                    items.forEach(function (li) {
                        li.classList.toggle('hidden');
                    });
                });
            });
        })();

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target)) {
                sidebar.classList.add('translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }
        });

        // Dropdown functionality
        document.getElementById('notificationsDropdown').addEventListener('click', function() {
            const menu = document.getElementById('notificationsMenu');
            menu.classList.toggle('hidden');
        });

        document.getElementById('userDropdown').addEventListener('click', function() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const notificationsMenu = document.getElementById('notificationsMenu');
            const userDropdown = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenu');
            
            if (!notificationsDropdown.contains(event.target) && !notificationsMenu.contains(event.target)) {
                notificationsMenu.classList.add('hidden');
            }
            
            if (!userDropdown.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);

        // Initialize DataTables
        $(document).ready(function() {
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                },
                pageLength: 25,
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });

        // Initialize Select2
        $('.select2').select2({
            dir: 'rtl',
            width: '100%'
        });

        // Initialize Summernote
        $('.summernote').summernote({
            height: 200,
            lang: 'ar-AR',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
    
    @stack('scripts')
</body>
</html>
