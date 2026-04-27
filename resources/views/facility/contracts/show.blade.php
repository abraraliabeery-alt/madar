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

                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">مسار الوساطة والعمولات</h5>
                    @php
                        $commissionParties = $contract->commissions;
                        $hasCommissions = $commissionParties && $commissionParties->count() > 0;
                    @endphp
                    {{-- نموذج إضافة وسيط جديد بسيط --}}
                    <form method="POST" action="{{ route('facility.contracts.commissions.store', $contract) }}" class="mb-4 space-y-2">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-xs">
                            <div>
                                <label class="block text-gray-600 mb-1">اسم الوسيط</label>
                                <input type="text" name="name" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="مثال: صديق محيل">
                            </div>
                            <div>
                                <label class="block text-gray-600 mb-1">الدور</label>
                                <input type="text" name="role" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="referrer / marketer / agent">
                            </div>
                            <div>
                                <label class="block text-gray-600 mb-1">نوع العمولة</label>
                                <select name="commission_type" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="percentage">نسبة % من قيمة العقد</option>
                                    <option value="fixed">مبلغ ثابت</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-600 mb-1">القيمة</label>
                                <input type="number" step="0.01" name="commission_value" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="مثال: 1 أو 5000">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md shadow-sm">
                                <i class="fas fa-plus ml-1 text-[10px]"></i>
                                إضافة وسيط
                            </button>
                        </div>
                    </form>
                    @if($hasCommissions)
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-gray-600 block mb-1">مسار الوساطة (من المصدر حتى المشتري):</span>
                                <div class="flex flex-wrap items-center gap-1">
                                    @foreach($commissionParties as $index => $party)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 text-xs font-medium">
                                            {{ $party->name ?? optional($party->user)->name ?? 'وسيط' }}
                                            @if($party->role)
                                                <span class="mx-1 text-[10px] text-purple-500">({{ $party->role }})</span>
                                            @endif
                                        </span>
                                        @if(!$loop->last)
                                            <span class="text-gray-400 text-xs mx-1">→</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-3">
                                <span class="text-gray-600">إجمالي عمولات الوسطاء:</span>
                                <span class="font-bold text-gray-900 flex items-center">
                                    {{ number_format($contract->total_commissions_amount, 2) }}
                                    <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                </span>
                            </div>

                            <details class="mt-3">
                                <summary class="text-xs text-blue-600 cursor-pointer hover:underline">عرض تفاصيل العمولات</summary>
                                <div class="mt-2 border-t border-gray-200 pt-2 space-y-1 text-xs text-gray-700">
                                    @foreach($commissionParties as $party)
                                        <div class="flex justify-between">
                                            <span>
                                                {{ $party->name ?? optional($party->user)->name ?? 'وسيط' }}
                                                @if($party->role)
                                                    <span class="text-gray-400">({{ $party->role }})</span>
                                                @endif
                                            </span>
                                            <span>
                                                {{ number_format($party->calculated_amount, 2) }} ر.س
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">لا توجد بيانات وساطة مسجّلة لهذا العقد حتى الآن.</p>
                    @endif
                </div>
            </div>

            <!-- التفاصيل المالية ومسار الوساطة -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">التفاصيل المالية</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ الإجمالي:</span>
                            <span class="font-bold text-lg text-gray-800 flex items-center">
                                {{ number_format($contract->total_amount, 2) }}
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-5 h-5 mr-1">
                            </span>
                        </div>
                        @if($contract->deposit_amount)
                            <div class="flex justify-between">
                                <span class="text-gray-600">العربون:</span>
                                <span class="font-semibold text-gray-800 flex items-center">
                                    {{ number_format($contract->deposit_amount, 2) }}
                                    <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                </span>
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
                                <span class="font-semibold text-gray-800 flex items-center">
                                    {{ number_format($contract->early_payment_discount, 2) }}
                                    <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                </span>
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

