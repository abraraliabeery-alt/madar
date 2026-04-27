@extends('layouts.app')

@section('title', __('layout.navigation.factories'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-secondary-900 border border-gray-200 dark:border-secondary-800 rounded-2xl p-6 sm:p-10">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white">{{ __('layout.navigation.factories') }}</h1>
                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Factories directory is being prepared.' : 'قائمة المصانع قيد الإعداد.' }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-primary-600 text-white flex items-center justify-center">
                        <i class="fas fa-industry text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="rounded-xl border border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-950 p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        {{ app()->getLocale() === 'en' ? 'Soon' : 'قريبًا' }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Browse by specialty.' : 'تصفح حسب التخصص.' }}
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-950 p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        {{ app()->getLocale() === 'en' ? 'Soon' : 'قريبًا' }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Capacity and lead-time details.' : 'تفاصيل الطاقة والمدة.' }}
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-950 p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        {{ app()->getLocale() === 'en' ? 'Soon' : 'قريبًا' }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'Contact factories directly.' : 'التواصل المباشر مع المصانع.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
