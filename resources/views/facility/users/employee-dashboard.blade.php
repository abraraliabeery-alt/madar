@extends('facility.layouts.app')

@section('title', 'لوحة المسوّق')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">لوحة المسوّق</h1>
        <p class="text-gray-600 mt-1">ملخص مشاريعك وأدائك داخل هذه المنشأة.</p>
    </div>

    {{-- إحصائيات سريعة --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">عدد المشاريع المسؤولة عنها</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total_products'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border border-yellow-200 p-4">
            <div class="text-xs text-gray-500 mb-1">مشاريع تحتاج تحسين</div>
            <div class="text-2xl font-bold text-yellow-700">{{ $stats['needs_attention'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border border-blue-200 p-4">
            <div class="text-xs text-gray-500 mb-1">إجمالي الحجوزات لمشاريعك</div>
            <div class="text-2xl font-bold text-blue-700">{{ $stats['total_bookings'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border border-green-200 p-4">
            <div class="text-xs text-gray-500 mb-1">إجمالي المشاهدات لمشاريعك</div>
            <div class="text-2xl font-bold text-green-700">{{ $stats['total_views'] ?? 0 }}</div>
        </div>
    </div>

    {{-- قائمة المشاريع الخاصة بالموظف --}}
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">مشاريعي</h2>
            <a href="{{ route('facility.products.index', ['seller_user_id' => $user->id]) }}" class="text-sm text-blue-600 hover:text-blue-800">مشاهدة في قائمة المنتجات</a>
        </div>

        @if($products->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المالك</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحجوزات</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المشاهدات</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تحتاج تحسين؟</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-xs">
                        @foreach($products as $product)
                            @php
                                // يحتاج تحسين إذا لم تكن هناك صورة رئيسية أو لا توجد إحداثيات موقع
                                $needsAttention = !$product->main_image || !$product->latitude || !$product->longitude;
                            @endphp
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $product->title ?? $product->address }}</div>
                                    <div class="text-gray-500">{{ $product->category ? $product->category->getTranslatedName('ar') : 'بدون تصنيف' }}</div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $product->owner ? $product->owner->name : 'غير محدد' }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $product->status ? $product->status->name : 'غير محدد' }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                    {{ (int) $product->bookings_count }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                    {{ (int) $product->views_count }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    @if($needsAttention)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200 text-[11px]">
                                            تحتاج تحسين
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200 text-[11px]">
                                            جيدة
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-left">
                                    <a href="{{ route('facility.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 text-xs">تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <p class="text-sm text-gray-500">لا توجد مشاريع مرتبطة بك كمسوّق مسؤول حتى الآن.</p>
        @endif
    </div>
@endsection
