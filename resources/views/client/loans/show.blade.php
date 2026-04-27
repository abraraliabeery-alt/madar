@extends('layouts.app')

@section('title', 'تفاصيل طلب التمويل')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">طلب تمويل #{{ $loanRequest->id }}</h1>
                <p class="text-gray-600 text-sm mt-1">تفاصيل الطلب والعروض المقدمة من موظفي البنوك.</p>
            </div>
            <a href="{{ route('client.loans.requests') }}" class="text-sm text-blue-600 hover:text-blue-700">عودة لطلبات التمويل</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm font-semibold text-gray-900">
                    @if($loanRequest->product)
                        {{ $loanRequest->product->title ?? $loanRequest->product->address ?? 'عقار بدون عنوان' }}
                    @else
                        طلب تمويل بدون عقار محدد
                    @endif
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200 text-[11px]">
                    {{ $loanRequest->status }}
                </span>
            </div>
            <div class="text-xs text-gray-600 mb-1">
                ملاحظاتك: {{ $loanRequest->notes ?: 'لا توجد ملاحظات مضافة.' }}
            </div>
            <div class="text-[11px] text-gray-400">
                تم الإنشاء في {{ $loanRequest->created_at ? $loanRequest->created_at->format('Y/m/d H:i') : '—' }}
            </div>
        </div>

        @if($contract)
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="text-sm font-semibold text-gray-900 mb-1">العقد المولد من هذا التمويل</div>
                        <div class="text-xs text-gray-600">
                            رقم العقد: <span class="font-mono">{{ $contract->contract_number ?? 'سيتم توليده' }}</span>
                        </div>
                        <div class="text-xs text-gray-600">
                            قيمة العقد: <span class="font-semibold">{{ number_format($contract->total_amount, 0) }} ر.س</span>
                        </div>
                        <div class="text-[11px] text-gray-500 mt-1">
                            الحالة الحالية: {{ $contract->status ?? 'draft' }}
                        </div>
                    </div>
                    <div class="text-xs text-right">
                        <a href="{{ route('client.contracts.show', $contract) }}" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded">
                            عرض تفاصيل العقد
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">العروض المقدمة</h2>

            @if($loanRequest->offers->count())
                <div class="space-y-3">
                    @foreach($loanRequest->offers as $offer)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-start justify-between gap-4 @if($loanRequest->chosen_offer_id === $offer->id) bg-green-50 @else bg-gray-50 @endif">
                            <div class="flex-1 text-xs text-gray-800">
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
                                <div class="text-gray-500 mb-1">
                                    موظف البنك: {{ $offer->banker->name ?? 'غير معروف' }}
                                </div>
                                @if($offer->notes)
                                    <div class="text-gray-600">ملاحظات البنك: {{ $offer->notes }}</div>
                                @endif
                            </div>
                            <div class="text-xs text-right min-w-[120px] flex flex-col items-end gap-2">
                                @if($loanRequest->chosen_offer_id === $offer->id)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-600 text-white text-[11px]">العرض المختار</span>
                                @else
                                    <form action="{{ route('client.loans.offers.choose', [$loanRequest, $offer]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px]">
                                            اختيار هذا العرض
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">لم يتم تقديم أي عروض تمويل على هذا الطلب حتى الآن.</p>
            @endif
        </div>
    </div>
</div>
@endsection
