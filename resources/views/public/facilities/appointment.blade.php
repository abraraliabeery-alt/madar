@extends('layouts.app')

@section('title', 'حجز موعد')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold">حجز موعد مع {{ $facility->name }}</h1>
            <p class="text-primary-100 mt-2">اختر التاريخ والوقت المناسبين وسنتواصل معك للتأكيد</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">نموذج الحجز</h2>
                <form action="{{ route('public.facilities.appointment', $facility) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">التاريخ</label>
                                <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الوقت</label>
                                <input type="time" name="appointment_time" value="{{ old('appointment_time') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رسالتك</label>
                        <textarea name="message" rows="4" class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500" placeholder="أية تفاصيل إضافية...">{{ old('message') }}</textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">إرسال الطلب</button>
                        <a href="{{ route('public.facilities.show', $facility) }}" class="ml-3 text-primary-600 hover:text-primary-700">العودة للمنشأة</a>
                    </div>
                </form>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">معلومات المنشأة</h2>
                <div class="flex items-center space-x-3 space-x-reverse mb-4">
                    <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=100&q=80' }}" class="w-12 h-12 rounded object-cover" alt="{{ $facility->name }}">
                    <div>
                        <p class="font-medium text-gray-900">{{ $facility->name }}</p>
                        <p class="text-sm text-gray-600">{{ $facility->category->name ?? '' }}</p>
                    </div>
                </div>
                @if($facility->address)
                    <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt ml-2"></i>{{ $facility->address }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


