@extends('layouts.app')

@section('title', 'تغيير كلمة المرور')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">تغيير كلمة المرور</h1>
                <p class="text-gray-600">قم بتحديث كلمة المرور الخاصة بك</p>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.change-password.update') }}" method="POST">
                @csrf

                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية</label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('current_password') border-red-500 @enderror"
                           required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                           required>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        تغيير كلمة المرور
                    </button>
                    <a href="{{ route('profile.edit') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        العودة للملف الشخصي
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
