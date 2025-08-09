@extends('layouts.app')

@section('title', 'طلب عرض سعر')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold">طلب عرض سعر من {{ $facility->name }}</h1>
            <p class="text-primary-100 mt-2">املأ البيانات وسيتم التواصل معك لتقديم العرض المناسب</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    <i class="fas fa-check-circle ml-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">نموذج طلب عرض السعر</h2>
                <form action="{{ route('public.facilities.quote', $facility) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-300 @enderror">
                            @error('phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع العقار المطلوب</label>
                            <input type="text" name="product_type" value="{{ old('product_type') }}" class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500 @error('product_type') border-red-300 @enderror" placeholder="مثال: شقة، فيلا، مكتب...">
                            @error('product_type')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">الميزانية المتوقعة</label>
                            <input type="text" name="budget" value="{{ old('budget') }}" class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500 @error('budget') border-red-300 @enderror" placeholder="مثال: حتى 500,000 ريال">
                            @error('budget')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">التفاصيل</label>
                        <textarea name="message" rows="5" required class="w-full px-3 py-2 border rounded-md focus:ring-primary-500 focus:border-primary-500 @error('message') border-red-300 @enderror" placeholder="صف طلبك ومتطلباتك بشكل مختصر">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">إرسال الطلب</button>
                        <a href="{{ route('public.facilities.show', $facility) }}" class="text-primary-600 hover:text-primary-700">العودة للمنشأة</a>
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
                @if($facility->phone)
                    <p class="text-sm text-gray-600 mt-2"><i class="fas fa-phone ml-2"></i>{{ $facility->phone }}</p>
                @endif
                @if($facility->email)
                    <p class="text-sm text-gray-600 mt-2"><i class="fas fa-envelope ml-2"></i>{{ $facility->email }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


