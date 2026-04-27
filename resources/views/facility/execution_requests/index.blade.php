@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">طلبات التنفيذ</h1>
            <p class="text-sm text-gray-500">نظام عام لطلبات وعروض المنفِّذين (مقاولات، صيانة، تصميم، وغيرها).</p>
        </div>
        <a href="{{ route('facility.execution-requests.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
            <i class="fas fa-plus ml-2"></i>
            طلب تنفيذ جديد
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العنوان</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">النوع</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الأولوية</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العروض</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">تاريخ الإنشاء</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $request)
                    @php
                        $translation = $request->translations->firstWhere('locale', app()->getLocale());
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $translation->title ?? ('#'.$request->id) }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $request->type ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium
                                @if($request->priority === 'high') bg-red-100 text-red-700
                                @elseif($request->priority === 'low') bg-gray-100 text-gray-600
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ $request->priority }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $request->status }}</td>
                        <td class="px-4 py-3 text-xs text-gray-700">{{ $request->bids_count }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $request->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-left text-xs">
                            <a href="{{ route('facility.execution-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-800">عرض</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                            لا توجد طلبات تنفيذ حتى الآن.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection
