@extends('facility.layouts.app')

@section('title', 'عرض سعر #' . $quote->quote_number)

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-6xl mx-auto">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $quote->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $quote->quote_number }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('facility.quotes.edit', $quote) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium py-2.5 px-4 rounded-md">تعديل</a>
                <a href="{{ route('facility.quotes.download', $quote) }}" target="_blank" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 px-4 rounded-md">تحميل PDF</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-md border border-gray-200 p-5">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">بيانات العميل</h5>
                <p><span class="text-gray-500">الاسم:</span> {{ $quote->client_name ?: '—' }}</p>
                <p class="mt-2"><span class="text-gray-500">البريد:</span> {{ $quote->client_email ?: '—' }}</p>
                <p class="mt-2"><span class="text-gray-500">الجوال:</span> {{ $quote->client_phone ?: '—' }}</p>
            </div>
            <div class="bg-white rounded-md border border-gray-200 p-5">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">الملخص</h5>
                <p><span class="text-gray-500">المجموع الفرعي:</span> {{ number_format($quote->subtotal, 2) }} ر.س</p>
                <p class="mt-2"><span class="text-gray-500">الخصومات:</span> {{ number_format($quote->discounts_total, 2) }} ر.س</p>
                <p class="mt-2 text-xl font-bold text-teal-700"><span class="text-gray-500">الإجمالي:</span> {{ number_format($quote->total, 2) }} ر.س</p>
            </div>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">بنود السعر</h5>
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">البند</th>
                        <th class="px-4 py-3">الكمية</th>
                        <th class="px-4 py-3">الوحدة</th>
                        <th class="px-4 py-3">سعر الوحدة</th>
                        <th class="px-4 py-3">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $item)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $item->name }}</p>
                                @if($item->description)
                                    <p class="text-xs text-gray-500">{{ $item->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ number_format($item->quantity, 2) }}</td>
                            <td class="px-4 py-3">{{ $item->unit ?: '—' }}</td>
                            <td class="px-4 py-3">{{ number_format($item->unit_price, 2) }} ر.س</td>
                            <td class="px-4 py-3">{{ number_format($item->line_total, 2) }} ر.س</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($quote->terms->count())
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">الشروط والأحكام</h5>
                <ul class="list-disc list-inside space-y-2">
                    @foreach($quote->terms as $term)
                        <li>
                            @if($term->title)<strong>{{ $term->title }}:</strong>@endif
                            {{ $term->content }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($quote->discounts->count())
            <div class="bg-white rounded-md border border-gray-200 p-5 mb-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">الخصومات</h5>
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">الخصم</th>
                            <th class="px-4 py-3">القيمة</th>
                            <th class="px-4 py-3">الشرط</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quote->discounts as $discount)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">{{ $discount->name }}</td>
                                <td class="px-4 py-3">
                                    {{ number_format($discount->method === \App\Enums\DiscountMethod::Percentage ? $discount->value : $discount->calculateAmount($quote->subtotal), 2) }}
                                    {{ $discount->method === \App\Enums\DiscountMethod::Percentage ? '%' : 'ر.س' }}
                                </td>
                                <td class="px-4 py-3">{{ $discount->condition ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
