@extends('facility.layouts.app')

@section('title', 'عرض تفاصيل العرض')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">تفاصيل العرض</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.offers.edit', $offer) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-edit"></i>
                    <span>تعديل</span>
                </a>
                <a href="{{ route('facility.offers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
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
                            <span class="text-gray-600">العنوان:</span>
                            <span class="font-semibold text-gray-800">{{ $offer->offer_title ?: 'عرض ' . $offer->offer_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">نوع العرض:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                @switch($offer->offer_type)
                                    @case('sale') بيع @break
                                    @case('rent_monthly') إيجار شهري @break
                                    @case('rent_yearly') إيجار سنوي @break
                                    @case('rent_daily') إيجار يومي @break
                                @endswitch
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المنتج:</span>
                            <a href="{{ route('facility.products.show', $offer->product) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $offer->product->getTranslatedTitle() }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الأولوية:</span>
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $offer->priority * 10 }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $offer->priority }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الحالة:</span>
                            @if($offer->isActive())
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                            @elseif($offer->isExpired())
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">منتهي</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">غير نشط</span>
                            @endif
                        </div>
                        @if($offer->is_featured)
                            <div class="flex justify-between">
                                <span class="text-gray-600">مميز:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">نعم</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">التفاصيل المالية</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">السعر:</span>
                            <span class="font-bold text-lg text-gray-800">{{ number_format($offer->price, 2) }} {{ $offer->currency }}</span>
                        </div>
                        @if($offer->deposit_amount)
                            <div class="flex justify-between">
                                <span class="text-gray-600">العربون:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($offer->deposit_amount, 2) }} {{ $offer->currency }}</span>
                            </div>
                        @endif
                        @if($offer->commission_rate)
                            <div class="flex justify-between">
                                <span class="text-gray-600">نسبة العمولة:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($offer->commission_rate * 100, 2) }}%</span>
                            </div>
                        @endif
                        @if($offer->commission_amount)
                            <div class="flex justify-between">
                                <span class="text-gray-600">مبلغ العمولة:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($offer->commission_amount, 2) }} {{ $offer->currency }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- تواريخ العقد -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">تواريخ العقد</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ البداية:</span>
                            <span class="font-semibold text-gray-800">{{ $offer->valid_from ? \Carbon\Carbon::parse($offer->valid_from)->format('Y-m-d') : 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ النهاية:</span>
                            <span class="font-semibold text-gray-800">{{ $offer->valid_to ? \Carbon\Carbon::parse($offer->valid_to)->format('Y-m-d') : 'غير محدد' }}</span>
                        </div>
                        @if($offer->min_contract_duration)
                            <div class="flex justify-between">
                                <span class="text-gray-600">مدة العقد الأدنى:</span>
                                <span class="font-semibold text-gray-800">{{ $offer->min_contract_duration }} شهر</span>
                            </div>
                        @endif
                        @if($offer->max_contract_duration)
                            <div class="flex justify-between">
                                <span class="text-gray-600">مدة العقد القصوى:</span>
                                <span class="font-semibold text-gray-800">{{ $offer->max_contract_duration }} شهر</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">إعدادات إضافية</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تجديد تلقائي:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $offer->auto_renew ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $offer->auto_renew ? 'نعم' : 'لا' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="font-semibold text-gray-800">{{ $offer->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">آخر تحديث:</span>
                            <span class="font-semibold text-gray-800">{{ $offer->updated_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الوصف والشروط -->
            @if($offer->offer_description || $offer->terms_conditions || $offer->special_conditions || $offer->marketing_notes)
                <div class="bg-gray-50 rounded-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">الوصف والشروط</h5>
                    <div class="space-y-4">
                        @if($offer->offer_description)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">وصف العرض:</h6>
                                <p class="text-gray-600">{{ $offer->offer_description }}</p>
                            </div>
                        @endif
                        @if($offer->terms_conditions)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">الشروط والأحكام:</h6>
                                <p class="text-gray-600">{{ $offer->terms_conditions }}</p>
                            </div>
                        @endif
                        @if($offer->special_conditions)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">شروط خاصة:</h6>
                                <p class="text-gray-600">{{ $offer->special_conditions }}</p>
                            </div>
                        @endif
                        @if($offer->marketing_notes)
                            <div>
                                <h6 class="font-semibold text-gray-700 mb-2">ملاحظات تسويقية:</h6>
                                <p class="text-gray-600">{{ $offer->marketing_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

