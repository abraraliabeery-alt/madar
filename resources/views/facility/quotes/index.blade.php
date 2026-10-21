@extends('facility.layouts.app')

@section('title', 'عروض الأسعار')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">عروض الأسعار</h1>
            <a href="{{ route('facility.quotes.create') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                + إنشاء عرض سعر
            </a>
        </div>

        <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">الرقم</th>
                        <th class="px-4 py-3">العميل</th>
                        <th class="px-4 py-3">البنود</th>
                        <th class="px-4 py-3">الإجمالي</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotes as $quote)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-3 font-medium">{{ $quote->quote_number }}</td>
                            <td class="px-4 py-3">{{ $quote->client_name ?: '—' }}</td>
                            <td class="px-4 py-3">{{ $quote->items_count }}</td>
                            <td class="px-4 py-3">{{ number_format($quote->total, 2) }} ر.س</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $quote->status->color() }}-100 text-{{ $quote->status->color() }}-800">
                                    {{ $quote->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('facility.quotes.show', $quote) }}" class="text-teal-600 hover:text-teal-800">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-gray-500" colspan="6">لا يوجد عروض أسعار حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $quotes->links() }}
    </div>
</div>
@endsection
