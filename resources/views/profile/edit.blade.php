@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">تعديل الملف الشخصي</h1>
                <p class="text-gray-600">قم بتحديث معلوماتك الشخصية</p>
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

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="flex-shrink-0">
                            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&color=7C3AED&background=EBF4FF' }}"
                                 alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF حتى 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                           required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                           required>
                </div>

                <!-- Phone Number -->
                <div class="mb-6">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        حفظ التغييرات
                    </button>
                    <a href="{{ route('profile.change-password') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        تغيير كلمة المرور
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
