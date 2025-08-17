@extends('layouts.app')

@section('title', __('errors.404.title'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- 404 Icon -->
            <div class="mb-6">
                <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
            </div>
            
            <!-- Error Number -->
            <h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>
            
            <!-- Error Title -->
            <h2 class="text-2xl font-bold text-gray-900 mb-4">الصفحة غير موجودة</h2>
            
            <!-- Error Description -->
            <p class="text-gray-600 mb-6">
                عذراً، الصفحة التي تبحث عنها غير موجودة.
            </p>
            <p class="text-sm text-gray-500 mb-8">
                ربما تم نقلها أو حذفها، أو أن الرابط غير صحيح.
            </p>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('public.home') }}" 
                   class="w-full btn-primary text-white py-3 px-6 rounded-lg font-medium inline-block">
                    <i class="fas fa-home ml-2"></i>
                    الصفحة الرئيسية
                </a>
                
                <a href="{{ url()->previous() }}" 
                   class="w-full border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium inline-block hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    رجوع
                </a>
            </div>
        </div>
        
        <!-- Additional Help -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                إذا كنت تعتقد أن هذا خطأ، يرجى 
                <a href="{{ route('public.contact') }}" class="text-primary-600 hover:text-primary-700 underline">
                    التواصل معنا
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
