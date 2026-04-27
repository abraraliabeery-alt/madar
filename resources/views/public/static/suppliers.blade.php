@extends('layouts.app')

@section('title', __('layout.navigation.suppliers'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-secondary-900 border border-gray-200 dark:border-secondary-800 rounded-2xl p-6 sm:p-10">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white">{{ __('layout.navigation.suppliers') }}</h1>
                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Suppliers directory is being prepared.' : 'قائمة الموردين قيد الإعداد.' }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-primary-600 text-white flex items-center justify-center">
                        <i class="fas fa-truck-loading text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="rounded-xl border border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-950 p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        {{ app()->getLocale() === 'en' ? 'Soon' : 'قريبًا' }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Filter by category and region.' : 'تصفية حسب الفئة والمنطقة.' }}
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-950 p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        {{ app()->getLocale() === 'en' ? 'Soon' : 'قريبًا' }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Verified profiles and contacts.' : 'ملفات موثقة وبيانات تواصل.' }}
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-950 p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        {{ app()->getLocale() === 'en' ? 'Soon' : 'قريبًا' }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Request quotes from suppliers.' : 'طلب عروض أسعار من الموردين.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
