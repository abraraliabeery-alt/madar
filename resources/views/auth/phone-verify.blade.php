@extends('layouts.app')

@section('title', 'تأكيد رمز التحقق')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-halved text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">تأكيد الدخول</h2>
            <p class="text-gray-600 mt-2">أدخل رمز التحقق المرسل إلى {{ $phone_number }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="POST" action="{{ route('phone.otp.verify') }}">
                @csrf

                <div class="mb-6">
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">رمز التحقق</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input
                            id="otp"
                            type="text"
                            name="otp"
                            value="{{ old('otp') }}"
                            required
                            autocomplete="one-time-code"
                            autofocus
                            class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                            placeholder="******"
                        >
                    </div>
                    @error('otp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-medium text-lg hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-right-to-bracket ml-2"></i>
                    دخول
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('phone.otp.login.form') }}" class="text-primary-600 hover:text-primary-700 font-medium">إعادة إرسال الكود</a>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-arrow-right ml-2"></i>
                رجوع
            </a>
        </div>
    </div>
</div>
@endsection
