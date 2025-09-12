@extends('facility.layouts.app')

@section('title', 'عرض تفاصيل العقد')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">تفاصيل العقد</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.contracts.edit', $contract) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-edit"></i>
                    <span>تعديل</span>
                </a>
                <a href="{{ route('facility.contracts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة للقائمة</span>
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- معلومات أساسية -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">المعلومات الأساسية</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">رقم العقد:</span>
                            <span class="font-semibold text-gray-800">{{ $contract->contract_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">نوع العقد:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                @switch($contract->contract_type)
                                    @case('sale') بيع @break
                                    @case('rent_monthly') إيجار شهري @break
                                    @case('rent_yearly') إيجار سنوي @break
                                    @case('rent_daily') إيجار يومي @break
                                    @default {{ $contract->contract_type }}
                                @endswitch
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المنتج:</span>
                            <a href="{{ route('facility.products.show', $contract->product) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $contract->product->getTranslatedTitle() }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">العرض:</span>
                            <a href="{{ route('facility.offers.show', $contract->offer) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $contract->offer->getTranslatedTitle() }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الحالة:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @switch($contract->status)
                                    @case('active') bg-green-100 text-green-800 @break
                                    @case('expired') bg-red-100 text-red-800 @break
                                    @case('cancelled') bg-gray-100 text-gray-800 @break
                                    @default bg-yellow-100 text-yellow-800
                                @endswitch">
                                @switch($contract->status)
                                    @case('active') نشط @break
                                    @case('expired') منتهي @break
                                    @case('cancelled') ملغي @break
                                    @default {{ $contract->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">أطراف العقد</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">العميل:</span>
                            <div class="text-right">
                                <div class="font-semibold text-gray-800">{{ $contract->user->name }}</div>
                                <div class="text-sm text-gray-600">{{ $contract->user->email }}</div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المالك:</span>
                            <div class="text-right">
                                <div class="font-semibold text-gray-800">{{ $contract->owner->name }}</div>
                                <div class="text-sm text-gray-600">{{ $contract->owner->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- التفاصيل المالية -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">التفاصيل المالية</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ الإجمالي:</span>
                            <span class="font-bold text-lg text-gray-800">{{ number_format($contract->total_amount, 2) }} {{ $contract->currency }}</span>
                        </div>
                        @if($contract->deposit_amount)
                            <div class="flex justify-between">
                                <span class="text-gray-600">العربون:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($contract->deposit_amount, 2) }} {{ $contract->currency }}</span>
                            </div>
                        @endif
                        @if($contract->commission_rate)
                            <div class="flex justify-between">
                                <span class="text-gray-600">نسبة العمولة:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($contract->commission_rate, 2) }}%</span>
                            </div>
                        @endif
                        @if($contract->late_fee_rate)
                            <div class="flex justify-between">
                                <span class="text-gray-600">نسبة الرسوم المتأخرة:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($contract->late_fee_rate, 2) }}%</span>
                            </div>
                        @endif
                        @if($contract->early_payment_discount)
                            <div class="flex justify-between">
                                <span class="text-gray-600">خصم الدفع المبكر:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($contract->early_payment_discount, 2) }} {{ $contract->currency }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">تواريخ العقد</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ البداية:</span>
                            <span class="font-semibold text-gray-800">{{ $contract->start_date ? $contract->start_date->format('Y-m-d') : 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ النهاية:</span>
                            <span class="font-semibold text-gray-800">{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : 'غير محدد' }}</span>
                        </div>
                        @if($contract->contract_duration_months)
                            <div class="flex justify-between">
                                <span class="text-gray-600">مدة العقد:</span>
                                <span class="font-semibold text-gray-800">{{ $contract->contract_duration_months }} شهر</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="font-semibold text-gray-800">{{ $contract->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- شروط الدفع -->
            @if($contract->payment_frequency || $contract->total_installments)
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">شروط الدفع</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($contract->payment_frequency)
                            <div class="flex justify-between">
                                <span class="text-gray-600">تكرار الدفع:</span>
                                <span class="font-semibold text-gray-800">
                                    @switch($contract->payment_frequency)
                                        @case('monthly') شهري @break
                                        @case('quarterly') ربعي @break
                                        @case('yearly') سنوي @break
                                        @case('custom') مخصص @break
                                        @default {{ $contract->payment_frequency }}
                                    @endswitch
                                </span>
                            </div>
                        @endif
                        @if($contract->total_installments)
                            <div class="flex justify-between">
                                <span class="text-gray-600">إجمالي الأقساط:</span>
                                <span class="font-semibold text-gray-800">{{ $contract->total_installments }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- الشروط والأحكام -->
            @if($contract->terms_conditions || $contract->terms_conditions_ar || $contract->renewal_terms || $contract->termination_terms)
                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">الشروط والأحكام</h5>
                    <div class="space-y-4">
                        @if($contract->terms_conditions)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">الشروط والأحكام (الإنجليزية):</h6>
                                <p class="text-gray-600">{{ $contract->terms_conditions }}</p>
                            </div>
                        @endif
                        @if($contract->terms_conditions_ar)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">الشروط والأحكام (العربية):</h6>
                                <p class="text-gray-600">{{ $contract->terms_conditions_ar }}</p>
                            </div>
                        @endif
                        @if($contract->renewal_terms)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">شروط التجديد:</h6>
                                <p class="text-gray-600">{{ $contract->renewal_terms }}</p>
                            </div>
                        @endif
                        @if($contract->termination_terms)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">شروط الإنهاء:</h6>
                                <p class="text-gray-600">{{ $contract->termination_terms }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

