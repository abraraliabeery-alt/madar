@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">الملف الشخصي</h1>
                    <p class="text-gray-600">عرض وتعديل معلوماتك الشخصية</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('user.profile.edit', $user) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit ml-2"></i> تعديل
                    </a>
                    <a href="{{ route('user.profile.statistics', $user) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-chart-bar ml-2"></i> الإحصائيات
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">المعلومات الشخصية</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div>
                                <h3 class="text-md font-medium text-gray-900 mb-4">المعلومات الأساسية</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">الاسم الكامل</label>
                                        <p class="text-gray-900">{{ $user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">البريد الإلكتروني</label>
                                        <p class="text-gray-900">{{ $user->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">رقم الهاتف</label>
                                        <p class="text-gray-900">{{ $user->phone_number }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">تاريخ الانضمام</label>
                                        <p class="text-gray-900">{{ $user->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Info -->
                            <div>
                                <h3 class="text-md font-medium text-gray-900 mb-4">المعلومات البنكية</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">البنك</label>
                                        <p class="text-gray-900">{{ $user->bank->name ?? 'غير محدد' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">رقم الحساب</label>
                                        <p class="text-gray-900">{{ $user->bank_account ?: 'غير محدد' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="mt-6">
                            <h3 class="text-md font-medium text-gray-900 mb-4">روابط التواصل الاجتماعي</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @if($user->facebook)
                                    <div class="flex items-center">
                                        <i class="fab fa-facebook text-blue-600 ml-2"></i>
                                        <a href="{{ $user->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                                            فيسبوك
                                        </a>
                                    </div>
                                @endif
                                @if($user->twitter)
                                    <div class="flex items-center">
                                        <i class="fab fa-twitter text-blue-400 ml-2"></i>
                                        <a href="{{ $user->twitter }}" target="_blank" class="text-blue-400 hover:text-blue-500">
                                            تويتر
                                        </a>
                                    </div>
                                @endif
                                @if($user->instagram)
                                    <div class="flex items-center">
                                        <i class="fab fa-instagram text-pink-600 ml-2"></i>
                                        <a href="{{ $user->instagram }}" target="_blank" class="text-pink-600 hover:text-pink-700">
                                            إنستغرام
                                        </a>
                                    </div>
                                @endif
                                @if($user->linkedin)
                                    <div class="flex items-center">
                                        <i class="fab fa-linkedin text-blue-700 ml-2"></i>
                                        <a href="{{ $user->linkedin }}" target="_blank" class="text-blue-700 hover:text-blue-800">
                                            لينكد إن
                                        </a>
                                    </div>
                                @endif
                                @if($user->whatsapp_number)
                                    <div class="flex items-center">
                                        <i class="fab fa-whatsapp text-green-600 ml-2"></i>
                                        <span class="text-green-600">{{ $user->whatsapp_number }}</span>
                                    </div>
                                @endif
                                @if($user->telegram)
                                    <div class="flex items-center">
                                        <i class="fab fa-telegram text-blue-500 ml-2"></i>
                                        <span class="text-blue-500">{{ $user->telegram }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Location -->
                        @if($user->latitude && $user->longitude)
                            <div class="mt-6">
                                <h3 class="text-md font-medium text-gray-900 mb-4">الموقع</h3>
                                <div class="space-y-2">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">خط العرض</label>
                                        <p class="text-gray-900">{{ $user->latitude }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">خط الطول</label>
                                        <p class="text-gray-900">{{ $user->longitude }}</p>
                                    </div>
                                    @if($user->google_maps_url)
                                        <div>
                                            <a href="{{ $user->google_maps_url }}" target="_blank" 
                                               class="text-blue-600 hover:text-blue-700">
                                                <i class="fas fa-map-marker-alt ml-1"></i> عرض على خرائط جوجل
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
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
                                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                        @else
                            <div class="w-32 h-32 bg-primary-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-user text-primary-600 text-4xl"></i>
                            </div>
                        @endif
                        <p class="text-sm text-gray-600">يمكنك تغيير الصورة من صفحة التعديل</p>
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
                            <span class="text-sm text-gray-600">الدور الأساسي</span>
                            <span class="text-sm text-gray-900">{{ ucfirst($user->primary_role) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">آخر تحديث</span>
                            <span class="text-sm text-gray-900">{{ $user->updated_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                @if($user->roles->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">الأدوار</h3>
                        <div class="space-y-2">
                            @foreach($user->roles as $role)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">{{ $role->getTranslatedName() }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $role->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Facilities -->
                @if($user->facilities->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">المنشآت</h3>
                        <div class="space-y-2">
                            @foreach($user->facilities as $facility)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">{{ $facility->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $facility->facilityCategory->name ?? 'غير محدد' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-2">
                        <a href="{{ route('user.profile.edit', $user) }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-edit ml-2"></i> تعديل الملف الشخصي
                        </a>
                        <a href="{{ route('user.profile.statistics', $user) }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-chart-bar ml-2"></i> الإحصائيات
                        </a>
                        <a href="{{ route('user.profile.export', $user) }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-download ml-2"></i> تصدير البيانات
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
</style>
@endpush
