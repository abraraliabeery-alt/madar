@extends('facility.layouts.app')

@section('title', 'دورة حياة المشروع #' . $product->id)

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">دورة حياة المشروع</h1>
                <p class="text-gray-600 mt-1">نظرة شاملة على مسار هذا المشروع من الإنشاء حتى العروض والحجوزات والعقود.</p>
            </div>
            <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
                <a href="{{ route('facility.products.show', $product) }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-4 rounded-lg shadow-sm inline-flex items-center">
                    <i class="fas fa-eye ml-2"></i>
                    تفاصيل المشروع
                </a>
                <a href="{{ route('facility.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow inline-flex items-center">
                    <i class="fas fa-edit ml-2"></i>
                    تعديل المشروع
                </a>
            </div>
        </div>

        <!-- Funnel / Steps overview -->
        @php
            $hasOffers = $saleOffers->count() || $rentOffers->count();
            $hasBookings = $bookings->count() > 0;
            $hasContracts = $contracts->count() > 0;

            $steps = [];

            if(isset($lifecycleStages) && $lifecycleStages->count()) {
                foreach ($lifecycleStages as $stage) {
                    $key = $stage->key;
                    $translation = $stage->translations->firstWhere('locale', app()->getLocale());
                    $label = $translation->name ?? ucfirst($key);

                    $done = false;
                    if ($key === 'view') {
                        $done = ($product->views_count ?? 0) > 0;
                    } elseif ($key === 'favorite') {
                        $done = $product->favoredByUsers()->exists();
                    } elseif ($key === 'offer') {
                        $done = ($saleOffers->count() + $rentOffers->count()) > 0;
                    } elseif ($key === 'booking') {
                        $done = $hasBookings;
                    } elseif ($key === 'contract') {
                        $done = $hasContracts;
                    } elseif ($key === 'created') {
                        $done = true;
                    }

                    $steps[] = [
                        'key' => $key,
                        'label' => $label,
                        'done' => $done,
                    ];
                }
            }

            if (empty($steps)) {
                $steps = [
                    ['key' => 'created', 'label' => 'إنشاء المشروع', 'done' => true],
                    ['key' => 'offers', 'label' => 'إعداد العروض', 'done' => $hasOffers],
                    ['key' => 'bookings', 'label' => 'الحجوزات', 'done' => $hasBookings],
                    ['key' => 'contracts', 'label' => 'العقود', 'done' => $hasContracts],
                ];
            }
        @endphp

        <div class="bg-white rounded-xl shadow border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-route ml-2 text-indigo-600"></i>
                مسار دورة حياة هذا المشروع
            </h2>
            <div class="relative">
                <div class="hidden md:block absolute inset-x-6 top-1/2 -translate-y-1/2 h-[2px] bg-gray-100"></div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-0">
                    @foreach($steps as $index => $step)
                        @php
                            $isDone = $step['done'];
                            $isCurrent = !$step['done'] && ($index === 0 || $steps[$index-1]['done']);
                        @endphp
                        <div class="relative flex flex-col items-start md:items-center">
                            <div class="flex items-center md:flex-col md:items-center w-full">
                                <div class="flex items-center justify-center w-9 h-9 rounded-full border-2 {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : ($isCurrent ? 'border-indigo-500 bg-indigo-50 text-indigo-600' : 'border-gray-300 bg-white text-gray-400') }} shadow-sm z-10">
                                    @if($isDone)
                                        <i class="fas fa-check text-xs"></i>
                                    @elseif($isCurrent)
                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                    @endif
                                </div>
                                <div class="md:hidden flex-1 h-[2px] {{ $index < count($steps) - 1 ? 'bg-gray-100' : '' }}"></div>
                            </div>
                            <div class="mt-2 text-xs md:text-sm text-gray-700 font-medium text-right md:text-center w-full">
                                {{ $step['label'] }}
                            </div>
                            <div class="mt-1 text-[11px] text-gray-500 w-full text-right md:text-center">
                                @if($step['key'] === 'created')
                                    {{ $product->created_at ? $product->created_at->format('Y/m/d H:i') : '—' }}
                                @elseif($step['key'] === 'offers')
                                    {{ $hasOffers ? ($saleOffers->count() + $rentOffers->count()) . ' عرض' : 'لا يوجد عروض بعد' }}
                                @elseif($step['key'] === 'bookings')
                                    {{ $hasBookings ? $bookings->count() . ' حجز' : 'لا يوجد حجوزات' }}
                                @elseif($step['key'] === 'contracts')
                                    {{ $hasContracts ? $contracts->count() . ' عقد' : 'لا يوجد عقود' }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick stats for this product lifecycle -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
            <div class="bg-white rounded-xl shadow border border-gray-100 px-4 py-3 flex flex-col gap-1">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>المشاهدات</span>
                    <i class="fas fa-eye text-indigo-500 text-xs"></i>
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ number_format($product->views_count ?? 0) }}
                </div>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-100 px-4 py-3 flex flex-col gap-1">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>المفضلات</span>
                    <i class="fas fa-heart text-pink-500 text-xs"></i>
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ $product->favoredByUsers()->count() }}
                </div>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-100 px-4 py-3 flex flex-col gap-1">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>العروض</span>
                    <i class="fas fa-tags text-emerald-500 text-xs"></i>
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ $saleOffers->count() + $rentOffers->count() }}
                </div>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-100 px-4 py-3 flex flex-col gap-1">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>الحجوزات</span>
                    <i class="fas fa-calendar-check text-sky-500 text-xs"></i>
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ $bookings->count() }}
                </div>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-100 px-4 py-3 flex flex-col gap-1">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>العقود</span>
                    <i class="fas fa-file-signature text-amber-500 text-xs"></i>
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ $contracts->count() }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-building ml-2 text-blue-600"></i>
                        ملخص سريع للمشروع
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-gray-500 mb-1">الاسم / العنوان</div>
                            <div class="text-gray-900 font-medium">{{ $product->getTranslatedTitle() ?: $product->address }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">التصنيف</div>
                            <div class="text-gray-900">{{ optional($product->category)->getTranslatedName('ar') ?? 'غير محدد' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">الحالة العامة</div>
                            <div class="text-gray-900">{{ optional($product->status)->getTranslatedName('ar') ?? 'غير محددة' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">تاريخ الإنشاء</div>
                            <div class="text-gray-900">{{ $product->created_at ? $product->created_at->format('Y/m/d H:i') : '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags ml-2 text-green-600"></i>
                        العروض المرتبطة بالمشروع
                    </h2>

                    @if($saleOffers->count() || $rentOffers->count())
                        <div class="space-y-4 text-sm">
                            @if($saleOffers->count())
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-2">عروض البيع ({{ $saleOffers->count() }})</h3>
                                    <div class="space-y-2">
                                        @foreach($saleOffers as $offer)
                                            <div class="flex justify-between items-center border border-gray-200 rounded-lg px-3 py-2">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ number_format($offer->price, 0) }} ريال</div>
                                                    <div class="text-xs text-gray-500">ساري من {{ optional($offer->valid_from)->format('Y/m/d') ?? 'غير محدد' }} إلى {{ optional($offer->valid_to)->format('Y/m/d') ?? 'غير محدد' }}</div>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $offer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $offer->is_active ? 'نشط' : 'متوقف' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($rentOffers->count())
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-2">عروض الإيجار ({{ $rentOffers->count() }})</h3>
                                    <div class="space-y-2">
                                        @foreach($rentOffers as $offer)
                                            <div class="flex justify-between items-center border border-gray-200 rounded-lg px-3 py-2">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ number_format($offer->price, 0) }} ريال</div>
                                                    <div class="text-xs text-gray-500">نوع: {{ $offer->offer_type }}</div>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $offer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $offer->is_active ? 'نشط' : 'متوقف' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">لا توجد عروض مرتبطة بهذا المشروع حتى الآن.</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-check ml-2 text-indigo-600"></i>
                        الحجوزات المرتبطة
                    </h2>
                    @if($bookings->count())
                        <div class="space-y-2 text-sm">
                            @foreach($bookings as $booking)
                                <div class="flex justify-between items-center border border-gray-200 rounded-lg px-3 py-2">
                                    <div>
                                        <div class="font-medium text-gray-900">#{{ $booking->id }} - {{ $booking->booking_number ?? 'بدون رقم' }}</div>
                                        <div class="text-xs text-gray-500">تاريخ: {{ optional($booking->booking_date)->format('Y/m/d') ?? 'غير محدد' }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $booking->status ?? 'غير معروف' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">لا توجد حجوزات مسجلة لهذا المشروع.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-stream ml-2 text-purple-600"></i>
                        حالة دورة الحياة
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-gray-600">إنشاء المشروع</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">منجز</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $hasOffers ? 'bg-emerald-500' : 'bg-yellow-400' }}"></span>
                                <span class="text-gray-600">إعداد العروض</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $hasOffers ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $hasOffers ? 'منجز' : 'قيد الإعداد' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $hasBookings ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                <span class="text-gray-600">الحجوزات</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $hasBookings ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $hasBookings ? 'يوجد حجوزات' : 'لا يوجد' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $hasContracts ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                <span class="text-gray-600">العقود</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $hasContracts ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $hasContracts ? 'يوجد عقود' : 'لا يوجد' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-file-signature ml-2 text-emerald-600"></i>
                        العقود المرتبطة
                    </h2>
                    @if($contracts->count())
                        <div class="space-y-2 text-sm">
                            @foreach($contracts as $contract)
                                <div class="flex justify-between items-center border border-gray-200 rounded-lg px-3 py-2">
                                    <div>
                                        <div class="font-medium text-gray-900">عقد #{{ $contract->id }}</div>
                                        <div class="text-xs text-gray-500">من {{ optional($contract->start_date)->format('Y/m/d') ?? 'غير محدد' }} إلى {{ optional($contract->end_date)->format('Y/m/d') ?? 'غير محدد' }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $contract->status ?? 'غير معروف' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">لا توجد عقود مسجلة لهذا المشروع حتى الآن.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
