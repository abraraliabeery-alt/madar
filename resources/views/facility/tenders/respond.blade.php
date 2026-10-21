@extends('facility.layouts.app')

@section('title', 'رد المناقصة')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-5xl mx-auto">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $tender->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">كاتب الرد الذكي</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('facility.tenders.show', $tender) }}" class="text-sm text-gray-600 hover:text-gray-800">عرض التحليل</a>
                <a href="{{ route('facility.tenders.index') }}" class="text-sm text-gray-600 hover:text-gray-800">القائمة</a>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-md border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">توليد رد المناقصة</h2>
                <div class="flex items-center gap-2">
                    @if(!empty($tender->data['response']))
                        <a href="{{ route('facility.tenders.respond.download-word', $tender) }}" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                            تصدير Word
                        </a>
                        <a href="{{ route('facility.tenders.respond.download', $tender) }}" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                            تصدير PDF
                        </a>
                    @endif
                    <form method="POST" action="{{ route('facility.tenders.generate-response', $tender) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                            كتابة رد احترافي
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-sm text-gray-600 mb-4">
                يكتب الذكاء الاصطناعي ردًا احترافيًا باللغة العربية بناءً على كراسة الشروط وملف المنشأة.
            </p>

            @if(!empty($tender->data['response']))
                <div class="relative">
                    <textarea id="responseText" readonly rows="18" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-800 leading-relaxed bg-gray-50">{{ $tender->data['response'] }}</textarea>
                    <button type="button" onclick="copyResponse()" class="absolute top-2 left-2 bg-white border border-gray-300 text-gray-700 text-xs px-3 py-1.5 rounded hover:bg-gray-50">
                        نسخ
                    </button>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-md p-6 text-center text-gray-600">
                    لم يتم توليد الرد بعد. اضغط "كتابة رد احترافي" للبدء.
                </div>
            @endif
        </div>

        @if(!empty($tender->data['requirements']))
            <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
                <h2 class="text-lg font-semibold text-gray-800 p-4 border-b border-gray-100">المتطلبات المستخرجة</h2>
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">المتطلب</th>
                            <th class="px-4 py-3">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tender->data['requirements'] as $requirement)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">{{ $requirement['title'] ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $requirement['compliance_status'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    function copyResponse() {
        const textarea = document.getElementById('responseText');
        textarea.select();
        document.execCommand('copy');
        alert('تم نسخ الرد');
    }
</script>
@endsection
