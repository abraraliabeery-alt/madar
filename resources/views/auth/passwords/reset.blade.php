@extends('layouts.app')

@section('title', 'إعادة تعيين كلمة المرور')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">إعادة تعيين كلمة المرور</h2>
            <p class="text-gray-600 mt-2">أدخل كلمة المرور الجديدة</p>
        </div>

        <!-- Reset Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        البريد الإلكتروني
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ $email ?? old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus
                            class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                            placeholder="أدخل بريدك الإلكتروني"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور الجديدة
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                            placeholder="أدخل كلمة المرور الجديدة"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        تأكيد كلمة المرور
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                            placeholder="أعد إدخال كلمة المرور الجديدة"
                        >
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="mb-6">
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-orange-900 mb-2">متطلبات كلمة المرور:</h3>
                        <ul class="text-sm text-orange-700 space-y-1 text-right">
                            <li>• يجب أن تكون 8 أحرف على الأقل</li>
                            <li>• تحتوي على حرف كبير وحرف صغير</li>
                            <li>• تحتوي على رقم واحد على الأقل</li>
                            <li>• تحتوي على رمز خاص (@#$%^&*)</li>
                        </ul>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-orange-500 to-amber-600 text-white py-3 rounded-lg font-medium text-lg hover:shadow-lg transition-all duration-300"
                >
                    <i class="fas fa-save ml-2"></i>
                    حفظ كلمة المرور الجديدة
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-medium">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة لتسجيل الدخول
                </a>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 text-center">
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-shield-alt text-orange-500 ml-2"></i>
                    <h3 class="text-sm font-semibold text-orange-900">ملاحظة أمنية</h3>
                </div>
                <p class="text-sm text-orange-700">
                    تأكد من اختيار كلمة مرور قوية وفريدة لحسابك. لا تشارك كلمة المرور مع أي شخص.
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</div>
@endsection
