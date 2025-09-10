@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">الملف الشخصي</h1>
            <p class="text-gray-600">إدارة معلوماتك الشخصية وإعدادات الحساب</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">المعلومات الشخصية</h2>
                    </div>
                    
                    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone_number') border-red-500 @enderror">
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bank -->
                            <div>
                                <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-2">البنك</label>
                                <select id="bank_id" name="bank_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_id') border-red-500 @enderror">
                                    <option value="">اختر البنك</option>
                                    @foreach(\App\Models\Bank::all() as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id', $user->bank_id) == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bank Account -->
                            <div>
                                <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">رقم الحساب البنكي</label>
                                <input type="text" id="bank_account" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_account') border-red-500 @enderror">
                                @error('bank_account')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Avatar -->
                            <div>
                                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('avatar') border-red-500 @enderror">
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">روابط التواصل الاجتماعي</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">فيسبوك</label>
                                    <input type="url" id="facebook" name="facebook" value="{{ old('facebook', $user->facebook) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">تويتر</label>
                                    <input type="url" id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">إنستغرام</label>
                                    <input type="url" id="instagram" name="instagram" value="{{ old('instagram', $user->instagram) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">لينكد إن</label>
                                    <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">الموقع</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">خط العرض</label>
                                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">خط الطول</label>
                                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Picture -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الصورة الشخصية</h3>
                    <div class="text-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture" 
                                 class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                        @else
                            <div class="w-24 h-24 bg-primary-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-user text-primary-600 text-2xl"></i>
                            </div>
                        @endif
                        <p class="text-sm text-gray-600">يمكنك تغيير الصورة من النموذج</p>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">حالة الحساب</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">البريد الإلكتروني</span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check ml-1"></i> مفعل
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle ml-1"></i> غير مفعل
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">تاريخ الانضمام</span>
                            <span class="text-sm text-gray-900">{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">آخر تحديث</span>
                            <span class="text-sm text-gray-900">{{ $user->updated_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-2">
                        <a href="{{ route('client.change-password') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-key ml-2"></i> تغيير كلمة المرور
                        </a>
                        <a href="{{ route('client.notifications.settings') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-bell ml-2"></i> إعدادات الإشعارات
                        </a>
                        <a href="{{ route('client.settings.privacy') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-shield-alt ml-2"></i> إعدادات الخصوصية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-primary-100 {
    background-color: #dbeafe;
}

.text-primary-600 {
    color: #2563eb;
}

.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}

.focus\:ring-primary-500:focus {
    --tw-ring-color: #3b82f6;
}

.border-primary-500 {
    border-color: #3b82f6;
}
</style>
@endpush
