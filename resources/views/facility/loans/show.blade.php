@extends('facility.layouts.app')

@section('title', 'تفاصيل طلب التمويل')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">طلب تمويل #{{ $loanRequest->id }}</h1>
            <p class="text-gray-600 mt-1 text-sm">تفاصيل الطلب والعروض البنكية والعقد المرتبط إن وجد.</p>
        </div>
        <a href="{{ route('facility.loans.requests') }}" class="text-sm text-blue-600 hover:text-blue-700">عودة لطلبات التمويل</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm font-semibold text-gray-900">
                    @if($loanRequest->product)
                        {{ $loanRequest->product->title ?? $loanRequest->product->address ?? 'مشروع بدون عنوان' }}
                    @else
                        طلب تمويل بدون مشروع محدد
                    @endif
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200 text-[11px]">
                    {{ $loanRequest->status }}
                </span>
            </div>
            <div class="text-xs text-gray-600 mb-1">
                العميل: <span class="font-medium text-gray-800">{{ $loanRequest->user->name ?? 'غير محدد' }}</span>
            </div>
            <div class="text-xs text-gray-600 mb-1">
                ملاحظات العميل: {{ $loanRequest->notes ?: 'لا توجد ملاحظات مضافة.' }}
            </div>
            <div class="text-[11px] text-gray-400">
                تم إنشاء الطلب في {{ $loanRequest->created_at ? $loanRequest->created_at->format('Y/m/d H:i') : '—' }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="text-sm font-semibold text-gray-900 mb-2">العقد المرتبط (إن وجد)</div>
            @if($contract)
                <div class="text-xs text-gray-600 mb-1">
                    رقم العقد: <span class="font-mono">{{ $contract->contract_number ?? '—' }}</span>
                </div>
                <div class="text-xs text-gray-600 mb-1">
                    قيمة العقد: <span class="font-semibold">{{ number_format($contract->total_amount, 0) }} ر.س</span>
                </div>
                <div class="text-[11px] text-gray-500 mb-3">
                    الحالة الحالية: {{ $contract->status ?? 'draft' }}
                </div>
                <a href="{{ route('facility.contracts.edit', $contract->id ?? $contract) }}" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">
                    عرض / تعديل العقد
                </a>
            @else
                <p class="text-xs text-gray-400">لا يوجد عقد منشأ بعد لهذا الطلب.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <h2 class="text-sm font-semibold text-gray-900 mb-3">العروض البنكية المقدمة</h2>

        @if($loanRequest->offers->count())
            <div class="space-y-3 text-xs">
                @foreach($loanRequest->offers as $offer)
                    <div class="border rounded-lg px-3 py-2 flex items-start justify-between gap-4 @if($loanRequest->chosen_offer_id === $offer->id) bg-green-50 border-green-200 @else bg-gray-50 border-gray-200 @endif">
                        <div class="flex-1 text-gray-800">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold">مبلغ التمويل:</span>
                                <span>{{ number_format($offer->amount, 0) }} ر.س</span>
                            </div>
                            <div class="text-gray-700 mb-1">
                                النسبة: {{ $offer->profit_rate }}% | المدة: {{ $offer->term_months }} شهر
                            </div>
                            <div class="text-gray-700 mb-1">
                                قسط تقريبي: {{ number_format($offer->monthly_payment, 0) }} ر.س
                            </div>
                            @if($offer->fees)
                                <div class="text-gray-700 mb-1">
                                    رسوم إضافية: {{ number_format($offer->fees, 0) }} ر.س
                                </div>
                            @endif
                            @if($offer->notes)
                                <div class="text-gray-600">ملاحظات البنك: {{ $offer->notes }}</div>
                            @endif
                        </div>
                        <div class="text-right text-[11px] min-w-[110px] flex flex-col items-end gap-1">
                            <div class="text-gray-600">
                                موظف البنك: <span class="font-medium">{{ $offer->banker->name ?? 'غير معروف' }}</span>
                            </div>
                            @if($loanRequest->chosen_offer_id === $offer->id)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-600 text-white">العرض المختار</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">لم يتم تقديم أي عروض بنكية على هذا الطلب حتى الآن.</p>
        @endif
    </div>
@endsection
