@extends('layouts.app')

@section('title', $user->name . ' - الملف الشخصي')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 space-x-reverse">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&color=7C3AED&background=EBF4FF&size=120' }}"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-white shadow-lg">
                </div>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 space-x-reverse mb-2">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $user->name }}</h1>

                        <!-- User Type Badge -->
                        @if($primaryRole)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $primaryRole === 'admin' ? 'bg-red-100 text-red-800' :
                                   ($primaryRole === 'facility' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ $primaryRole === 'admin' ? 'مدير النظام' :
                                   ($primaryRole === 'facility' ? 'مرفق' : 'عميل') }}
                            </span>
                        @endif
                    </div>

                    <!-- Contact Info -->
                    <div class="flex flex-wrap items-center space-x-4 space-x-reverse text-sm text-gray-600">
                        @if($user->email)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                {{ $user->email }}
                            </div>
                        @endif

                        @if($user->phone_number)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                {{ $user->phone_number }}
                            </div>
                        @endif

                        @if($user->whatsapp_number)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                {{ $user->whatsapp_number }}
                            </div>
                        @endif
                    </div>

                    <!-- Location for Facility Users -->
                    @if($primaryRole === 'facility' && ($user->latitude || $user->longitude))
                        <div class="mt-3 flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>موقع المرفق متاح</span>
                            @if($user->google_maps_url)
                                <a href="{{ $user->google_maps_url }}" target="_blank" class="mr-2 text-blue-600 hover:text-blue-800">
                                    عرض على الخريطة
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- About Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">معلومات عامة</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الانضمام:</span>
                            <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>

                        @if($user->email_verified_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">حالة الحساب:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    موثق
                                </span>
                            </div>
                        @endif

                        @if($user->is_active !== null)
                            <div class="flex justify-between">
                                <span class="text-gray-600">حالة النشاط:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Role-specific Information -->
                @if($primaryRole === 'facility')
                    <!-- Facility Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">معلومات المرفق</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->bank_account)
                                <div>
                                    <span class="text-gray-600">رقم الحساب البنكي:</span>
                                    <span class="block font-medium">{{ $user->bank_account }}</span>
                                </div>
                            @endif

                            @if($user->bank)
                                <div>
                                    <span class="text-gray-600">البنك:</span>
                                    <span class="block font-medium">{{ $user->bank->name ?? 'Bank ' . $user->bank->id }}</span>
                                </div>
                            @endif

                            @if($user->latitude && $user->longitude)
                                <div class="md:col-span-2">
                                    <span class="text-gray-600">إحداثيات الموقع:</span>
                                    <span class="block font-medium">{{ $user->latitude }}, {{ $user->longitude }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($primaryRole === 'admin')
                    <!-- Admin Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">معلومات المدير</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">صلاحيات:</span>
                                <span class="font-medium">مدير النظام</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">إدارة:</span>
                                <span class="font-medium">جميع أجزاء النظام</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Products Section (for Facility Users) -->
                @if($primaryRole === 'facility' && $products->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">المنتجات</h2>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">عرض الكل</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($products as $product)
                                <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                                    <h3 class="font-medium text-gray-900 mb-2">{{ $product->name ?? 'Product ' . $product->id }}</h3>
                                    <p class="text-sm text-gray-600">{{ Str::limit($product->description ?? 'No description', 60) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Facilities Section (for Users with Facilities) -->
                @if($facilities->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">المرافق</h2>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">عرض الكل</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($facilities as $facility)
                                <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                                    <h3 class="font-medium text-gray-900 mb-2">{{ $facility->name ?? 'Facility ' . $facility->id }}</h3>
                                    <p class="text-sm text-gray-600">{{ Str::limit($facility->description ?? 'No description', 60) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">

                <!-- Social Media Links -->
                @if($user->facebook || $user->twitter || $user->instagram || $user->linkedin || $user->whatsapp_number)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">وسائل التواصل</h3>
                        <div class="space-y-3">
                            @if($user->facebook)
                                <a href="{{ $user->facebook }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    فيسبوك
                                </a>
                            @endif

                            @if($user->twitter)
                                <a href="{{ $user->twitter }}" target="_blank" class="flex items-center text-blue-400 hover:text-blue-600">
                                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    تويتر
                                </a>
                            @endif

                            @if($user->instagram)
                                <a href="{{ $user->instagram }}" target="_blank" class="flex items-center text-pink-600 hover:text-pink-800">
                                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.323c-.875.807-2.026 1.297-3.323 1.297zm7.718-1.297c-.875.807-2.026 1.297-3.323 1.297s-2.448-.49-3.323-1.297c-.928-.875-1.418-2.026-1.418-3.323s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.323z"/>
                                    </svg>
                                    انستغرام
                                </a>
                            @endif

                            @if($user->linkedin)
                                <a href="{{ $user->linkedin }}" target="_blank" class="flex items-center text-blue-700 hover:text-blue-900">
                                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    لينكد إن
                                </a>
                            @endif

                            @if($user->whatsapp_number)
                                <a href="https://wa.me/{{ $user->whatsapp_number }}" target="_blank" class="flex items-center text-green-600 hover:text-green-800">
                                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    واتساب
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Contact Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تواصل مع {{ $user->name }}</h3>
                    <div class="space-y-3">
                        @if($user->phone_number)
                            <a href="tel:{{ $user->phone_number }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                اتصل الآن
                            </a>
                        @endif

                        @if($user->whatsapp_number)
                            <a href="https://wa.me/{{ $user->whatsapp_number }}" target="_blank" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                واتساب
                            </a>
                        @endif

                        @if($user->email)
                            <a href="mailto:{{ $user->email }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                إرسال بريد
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
