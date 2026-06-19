@extends('facility.layouts.app')

@section('title', 'وسيط مشاريع ذكي')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">وسيط مشاريع ذكي</h1>
            <p class="text-sm text-gray-500 mt-1">أدخل الطلبات والعروض كنصوص (كل سطر عنصر) ثم طابقها باستخدام الذكاء الاصطناعي.</p>
        </div>
        <a href="{{ route('facility.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">العودة للوحة التحكم</a>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-100">
        <form action="{{ route('facility.smart-broker.match') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الطلبات على المشاريع (كل سطر طلب)</label>
                    <textarea name="requests_text" rows="10" class="w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="مثال: مطلوب شقة 3 غرف بالرياض حي النرجس بميزانية 900 ألف">{{ old('requests_text', $requests_text ?? '') }}</textarea>
                    @error('requests_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">العروض على المشاريع (كل سطر عرض)</label>
                    <textarea name="offers_text" rows="10" class="w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="مثال: شقة 3 غرف بالرياض النرجس 880 ألف - قرب الخدمات">{{ old('offers_text', $offers_text ?? '') }}</textarea>
                    @error('offers_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-end sm:justify-between">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">أفضل كم عرض لكل طلب؟</label>
                    <select name="top_k" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        @php($selectedTopK = old('top_k', $top_k ?? 3))
                        @foreach([1,2,3,4,5] as $k)
                            <option value="{{ $k }}" {{ (int)$selectedTopK === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-wand-magic-sparkles ml-1 text-xs"></i>
                    مطابقة ذكية
                </button>
            </div>
        </form>
    </div>

    @if(!empty($result))
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">أفضل مطابقات</h2>
                        <span class="text-xs text-gray-500">الحالة: {{ $result['status'] ?? '—' }}</span>
                    </div>

                    @php($requests = $result['requests'] ?? [])
                    @php($matches = $result['matches'] ?? [])

                    @if(empty($requests))
                        <p class="text-sm text-gray-500">لا توجد مدخلات كافية.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($requests as $ri => $req)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">طلب #{{ $ri + 1 }}</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $req }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-2">
                                        @foreach(($matches[$ri] ?? []) as $mi => $m)
                                            <div class="bg-gray-50 border border-gray-100 rounded-md p-3">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-xs text-gray-500">عرض #{{ $mi + 1 }}</p>
                                                    <span class="text-xs font-medium text-indigo-700">{{ $m['score'] ?? 0 }}%</span>
                                                </div>
                                                <p class="text-sm text-gray-800 mt-1">{{ $m['offer'] ?? '' }}</p>
                                                <p class="text-xs text-gray-500 mt-2">{{ $m['reason'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">ملخص الذكاء الاصطناعي</h2>
                    @if(!empty($result['ai_summary']))
                        <div class="text-sm leading-relaxed text-gray-800 whitespace-pre-line">{{ $result['ai_summary'] }}</div>
                    @else
                        <p class="text-sm text-gray-500">لا يوجد ملخص. إذا كانت الحالة "disabled" أو "fallback" فهذا يعني أن إعدادات الذكاء الاصطناعي غير مفعلة أو غير مكتملة.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
