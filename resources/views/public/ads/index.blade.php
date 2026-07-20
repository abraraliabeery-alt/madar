@extends('layouts.app')

@section('title', 'بحث الإعلانات')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">بحث الإعلانات</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">نتائج مجمعة من عدة مصادر مع توضيح المصدر وزر يفتح الإعلان في موقعه.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary-900 rounded-2xl border border-gray-200 dark:border-secondary-800 shadow-sm p-5 mb-6">
            <form method="GET" action="{{ route('public.ads.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">المدينة</label>
                    <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="مثال: الرياض">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">الحي</label>
                    <input type="text" name="district" value="{{ $filters['district'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="مثال: الربوة">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">نوع العقار</label>
                    <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="apartment" {{ ($filters['property_type'] ?? '') === 'apartment' ? 'selected' : '' }}>شقة</option>
                        <option value="villa" {{ ($filters['property_type'] ?? '') === 'villa' ? 'selected' : '' }}>فيلا</option>
                        <option value="land" {{ ($filters['property_type'] ?? '') === 'land' ? 'selected' : '' }}>أرض</option>
                        <option value="building" {{ ($filters['property_type'] ?? '') === 'building' ? 'selected' : '' }}>عمارة</option>
                        <option value="office" {{ ($filters['property_type'] ?? '') === 'office' ? 'selected' : '' }}>مكتب</option>
                        <option value="shop" {{ ($filters['property_type'] ?? '') === 'shop' ? 'selected' : '' }}>محل</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">الهدف</label>
                    <select name="purpose" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="rent" {{ ($filters['purpose'] ?? '') === 'rent' ? 'selected' : '' }}>إيجار</option>
                        <option value="sale" {{ ($filters['purpose'] ?? '') === 'sale' ? 'selected' : '' }}>بيع</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">سعر من</label>
                    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">سعر إلى</label>
                    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0">
                </div>

                <div class="md:col-span-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse px-6 py-3 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700">
                        <i class="fas fa-search"></i>
                        <span>بحث</span>
                    </button>

                    <div class="text-xs text-gray-500 dark:text-gray-300">
                        المصادر:
                        @foreach($sources as $s)
                            <span class="inline-flex items-center px-2 py-1 rounded-full border border-gray-200 dark:border-secondary-700 bg-gray-50 dark:bg-secondary-800 ml-1">{{ $s['label'] }}</span>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($results as $item)
                <div class="bg-white dark:bg-secondary-900 rounded-2xl border border-gray-200 dark:border-secondary-800 shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">{{ $item['title'] ?? 'إعلان' }}</div>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-300">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 dark:bg-secondary-800 border border-gray-200 dark:border-secondary-700">{{ $item['source_label'] ?? '' }}</span>
                            </div>
                        </div>
                        @if(!empty($item['price']))
                            <div class="shrink-0 text-sm font-extrabold text-primary-700 dark:text-primary-200">
                                {{ number_format((int) $item['price']) }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rtl:flex-row-reverse w-full px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-black">
                            <i class="fas fa-external-link-alt"></i>
                            <span>فتح الإعلان</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3">
                    <div class="bg-white dark:bg-secondary-900 rounded-2xl border border-gray-200 dark:border-secondary-800 shadow-sm p-8 text-center">
                        <div class="text-gray-900 dark:text-white font-semibold">ابدأ بالبحث</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">اختر المدينة والحي ثم اضغط بحث لعرض النتائج.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
