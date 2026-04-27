@extends('facility.layouts.app')

@section('title', 'إدارة الإيجارات')

@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-50 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 text-red-800 px-4 py-2 rounded">{{ session('error') }}</div>
    @endif
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">المنتجات المفعّلة للإيجار</h2>
        <div class="flex flex-wrap gap-2 items-center">
            <a href="{{ route('facility.reports.rentals.occupancy') }}" class="px-3 py-2 bg-indigo-600 text-white rounded-md" title="تقرير الإشغال">تقرير الإشغال</a>
            <a href="{{ route('facility.reports.rentals.collections') }}" class="px-3 py-2 bg-indigo-600 text-white rounded-md" title="تقرير التحصيل">تقرير التحصيل</a>
            <form method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="project_id" class="px-3 py-2 border rounded-md">
                <option value="">كل المشاريع</option>
                @isset($projects)
                    @foreach($projects as $p)
                        @php($pName = optional($p->translations->firstWhere('locale', app()->getLocale()))->name ?? ('#'.$p->id))
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $pName }}</option>
                    @endforeach
                @endisset
            </select>
            <select name="building_id" class="px-3 py-2 border rounded-md">
                <option value="">كل العمائر</option>
                @isset($buildings)
                    @foreach($buildings as $b)
                        @php($bName = optional($b->translations->firstWhere('locale', app()->getLocale()))->name ?? ('#'.$b->id))
                        <option value="{{ $b->id }}" {{ request('building_id') == $b->id ? 'selected' : '' }}>{{ $bName }}</option>
                    @endforeach
                @endisset
            </select>
            <select name="offer_status" class="px-3 py-2 border rounded-md">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('offer_status')==='active' ? 'selected' : '' }}>نشطة</option>
                <option value="inactive" {{ request('offer_status')==='inactive' ? 'selected' : '' }}>متوقفة</option>
                <option value="expiring" {{ request('offer_status')==='expiring' ? 'selected' : '' }}>قرب انتهاء</option>
            </select>
            <input name="search" value="{{ request('search') }}" class="px-3 py-2 border rounded-md" placeholder="بحث بالعنوان">
            <button class="px-4 py-2 bg-primary-600 text-white rounded-md">تطبيق</button>
            @if(request()->hasAny(['project_id','building_id','search']))
                <a href="{{ route('facility.rentals.index') }}" class="px-3 py-2 text-sm text-gray-700 underline">إزالة الفلاتر</a>
            @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">#</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">العنوان</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">الفئة</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">المشروع</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">العمارة</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">سعر الإيجار</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">الدورية</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    @php($rentOffer = $product->offers->first())
                    <tr>
                        <td class="px-4 py-2 text-sm">{{ $product->id }}</td>
                        <td class="px-4 py-2 text-sm">{{ $product->address }}</td>
                        <td class="px-4 py-2 text-sm">{{ optional($product->category)->getTranslatedName() }}</td>
                        <td class="px-4 py-2 text-sm">
                            @php($pName = optional(optional($product->project)->translations->firstWhere('locale', app()->getLocale()))->name ?? ($product->project_id ? '#'.$product->project_id : '-'))
                            {{ $pName }}
                        </td>
                        <td class="px-4 py-2 text-sm">
                            @php($bName = optional(optional($product->building)->translations->firstWhere('locale', app()->getLocale()))->name ?? ($product->building_id ? '#'.$product->building_id : '-'))
                            {{ $bName }}
                        </td>
                        <td class="px-4 py-2 text-sm">{{ $rentOffer?->price ? number_format($rentOffer->price) : '-' }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if($rentOffer)
                                @switch($rentOffer->offer_type)
                                    @case('rent_daily') <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700">يومي</span> @break
                                    @case('rent_monthly') <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-50 text-purple-700">شهري</span> @break
                                    @case('rent_yearly') <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-50 text-amber-700">سنوي</span> @break
                                    @default -
                                @endswitch
                            @else - @endif
                        </td>
                        <td class="px-4 py-2 text-sm">
                            @php($nearExpiry = $rentOffer && $rentOffer->is_active && $rentOffer->valid_to && \Carbon\Carbon::parse($rentOffer->valid_to)->between(\Carbon\Carbon::today(), \Carbon\Carbon::today()->addDays(14)))
                            @if($rentOffer?->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-50 text-green-700">نشط</span>
                                @if($nearExpiry)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-orange-50 text-orange-700 mr-1">قرب انتهاء</span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">غير نشط</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-left">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('facility.products.edit', $product) }}" class="text-primary-600 hover:underline">تعديل</a>
                                @if($rentOffer)
                                    <form method="POST" action="{{ route('facility.offers.toggle-status', $rentOffer) }}" onsubmit="return confirm('تبديل حالة الخطة؟');">
                                        @csrf
                                        <button class="text-xs px-2 py-1 rounded {{ $rentOffer->is_active ? 'bg-gray-200 text-gray-800' : 'bg-green-600 text-white' }}" title="تبديل الحالة">
                                            {{ $rentOffer->is_active ? 'إيقاف' : 'تفعيل' }}
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('facility.bookings.create', ['product_id' => $product->id]) }}" class="text-xs px-2 py-1 rounded bg-indigo-600 text-white" title="إنشاء حجز">حجز</a>
                                <a href="{{ route('facility.contracts.create', ['product_id' => $product->id]) }}" class="text-xs px-2 py-1 rounded bg-amber-600 text-white" title="إنشاء عقد">عقد</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">لا توجد منتجات مفعّلة للإيجار</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
