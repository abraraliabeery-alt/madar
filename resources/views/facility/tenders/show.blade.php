@extends('facility.layouts.app')

@section('title', $tender->title)

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-5xl mx-auto">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $tender->title }}</h1>
                @if($tender->reference)
                    <p class="text-sm text-gray-500 mt-1">المرجع: {{ $tender->reference }}</p>
                @endif
                @if($tender->data['file_path'] ?? false)
                    <p class="text-sm mt-1">
                        <a href="{{ asset('storage/' . $tender->data['file_path']) }}" target="_blank" class="text-teal-600 hover:text-teal-800 underline">تحميل الملف المرفق</a>
                    </p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tender->status === 'analyzed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $tender->status === 'analyzed' ? 'تم التحليل' : 'مسودة' }}
                </span>
                <a href="{{ route('facility.tenders.index') }}" class="text-sm text-gray-600 hover:text-gray-800">العودة للقائمة</a>
                <a href="{{ route('facility.tenders.respond', $tender) }}" class="text-sm text-teal-600 hover:text-teal-800">كتابة رد</a>
                <form method="POST" action="{{ route('facility.tenders.destroy', $tender) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">حذف</button>
                </form>
            </div>
        </div>

        @if($tender->data['extraction_error'] ?? false)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-md p-4 mb-6">
                <p class="font-medium">لم يكتمل التحليل الذكي</p>
                <p class="text-sm">{{ $tender->data['extraction_error'] }}</p>
            </div>
        @endif

        @if(!empty($tender->data['summary']))
            <div class="bg-white rounded-md border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">ملخص المناقصة</h2>
                <p class="text-gray-700 leading-relaxed">{{ $tender->data['summary'] }}</p>
            </div>
        @endif

        @if(!empty($tender->data['requirements']))
            <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
                <h2 class="text-lg font-semibold text-gray-800 p-4 border-b border-gray-100">مصفوفة الامتثال</h2>
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 w-16">#</th>
                            <th class="px-4 py-3">المتطلب</th>
                            <th class="px-4 py-3">الوصف</th>
                            <th class="px-4 py-3">الأولوية</th>
                            <th class="px-4 py-3">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tender->data['requirements'] as $index => $requirement)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3 text-gray-500">{{ $requirement['id'] ?? ($index + 1) }}</td>
                                <td class="px-4 py-3 font-medium">{{ $requirement['title'] ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $requirement['description'] ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $requirement['priority'] ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @php($status = $requirement['compliance_status'] ?? 'unknown')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ match($status) {
                                            'compliant' => 'bg-green-100 text-green-800',
                                            'partial' => 'bg-yellow-100 text-yellow-800',
                                            'non_compliant' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        } }}">
                                        {{ match($status) {
                                            'compliant' => 'متوافق',
                                            'partial' => 'جزئي',
                                            'non_compliant' => 'غير متوافق',
                                            default => 'غير محدد',
                                        } }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(empty($tender->data['summary']) && empty($tender->data['requirements']))
            <div class="bg-gray-50 border border-gray-200 rounded-md p-6 text-center text-gray-600">
                لم يتم استخراج المتطلبات بعد. تأكد من نص المناقصة أو أعد المحاولة.
            </div>
        @endif
    </div>
</div>
@endsection
