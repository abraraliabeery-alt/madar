@extends('facility.layouts.app')

@section('title')
    {{ request('type') === 'sale' ? 'إدارة البيع' : 'إدارة خطط الإيجار' }}
@endsection

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">{{ request('type') === 'sale' ? 'إدارة البيع' : 'إدارة خطط الإيجار' }}</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.offers.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>{{ request('type') === 'sale' ? 'إضافة عرض بيع' : 'إضافة خطة إيجار' }}</span>
                </a>
                <a href="{{ route('facility.offers.statistics') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-chart-bar"></i>
                    <span>الإحصائيات</span>
                </a>
            </div>
        </div>

        <!-- فلترة وبحث -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">جميع الأنواع</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                        <option value="rent_monthly" {{ request('type') == 'rent_monthly' ? 'selected' : '' }}>إيجار شهري</option>
                        <option value="rent_yearly" {{ request('type') == 'rent_yearly' ? 'selected' : '' }}>إيجار سنوي</option>
                        <option value="rent_daily" {{ request('type') == 'rent_daily' ? 'selected' : '' }}>إيجار يومي</option>
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="البحث في خطط الإيجار..." value="{{ request('search') }}">
                </div>
                <div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>بحث
                    </button>
                </div>
            </form>
        </div>

        @if($offers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخطة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العمولة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأولوية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($offers as $offer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="flex items-center">
                                            <strong class="text-gray-900">{{ $offer->offer_title ?: 'خطة ' . $offer->offer_type }}</strong>
                                            @if($offer->is_featured)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 mr-2">مميز</span>
                                            @endif
                                        </div>
                                        @if($offer->offer_description)
                                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($offer->offer_description, 50) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('facility.products.show', $offer->product) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $offer->product->getTranslatedTitle() }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        @switch($offer->offer_type)
                                            @case('sale') بيع @break
                                            @case('rent_monthly') إيجار شهري @break
                                            @case('rent_yearly') إيجار سنوي @break
                                            @case('rent_daily') إيجار يومي @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <strong class="text-gray-900 flex items-center">
                                            {{ number_format($offer->price, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                        </strong>
                                        @if($offer->deposit_amount)
                                            <p class="text-sm text-gray-500 mt-1 flex items-center">
                                                عربون: {{ number_format($offer->deposit_amount, 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($offer->commission_rate)
                                        {{ number_format($offer->commission_rate * 100, 2) }}%
                                    @elseif($offer->commission_amount)
                                        <span class="flex items-center">
                                            {{ number_format($offer->commission_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($offer->isActive())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                    @elseif($offer->isExpired())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">منتهي</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">غير نشط</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $offer->priority * 10 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $offer->priority }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('facility.offers.show', $offer) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('facility.offers.edit', $offer) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @isset($productsList)
                                        <form method="POST" action="{{ route('facility.offers.copy', $offer) }}" class="inline flex items-center gap-1">
                                            @csrf
                                            <select name="product_id" class="border rounded px-2 py-1 text-xs">
                                                <option value="">نسخ إلى منتج</option>
                                                @foreach($productsList as $p)
                                                    @if($p->id !== $offer->product_id)
                                                        <option value="{{ $p->id }}">#{{ $p->id }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-1 rounded text-xs" title="نسخ الخطة">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        @endisset
                                        <form method="POST" action="{{ route('facility.offers.toggle-status', $offer) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="{{ $offer->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-3 py-1 rounded text-xs transition-colors">
                                                <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('facility.offers.destroy', $offer) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخطة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-6">
                {{ $offers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-gift text-gray-400 text-6xl mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-500 mb-2">لا توجد خطط إيجار</h5>
                <p class="text-gray-400 mb-6">ابدأ بإنشاء خطة إيجار جديدة لعقارك</p>
                <a href="{{ route('facility.offers.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>إضافة خطة إيجار
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="type"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush