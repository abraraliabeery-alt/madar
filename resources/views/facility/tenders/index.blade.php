@extends('facility.layouts.app')

@section('title', 'تحليل المناقصات')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">تحليل المناقصات</h1>
            <a href="{{ route('facility.tenders.create') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                + تحليل مناقصة جديدة
            </a>
        </div>

        <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">المناقصة</th>
                        <th class="px-4 py-3">المرجع</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3">المتطلبات</th>
                        <th class="px-4 py-3">التاريخ</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenders as $tender)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-3 font-medium">{{ $tender->title }}</td>
                            <td class="px-4 py-3">{{ $tender->reference ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tender->status === 'analyzed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $tender->status === 'analyzed' ? 'تم التحليل' : 'مسودة' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ count($tender->data['requirements'] ?? []) }}</td>
                            <td class="px-4 py-3">{{ $tender->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('facility.tenders.show', $tender) }}" class="text-teal-600 hover:text-teal-800">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-gray-500" colspan="6">لا توجد مناقصات محللة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $tenders->links() }}
    </div>
</div>
@endsection
