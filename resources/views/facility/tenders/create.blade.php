@extends('facility.layouts.app')

@section('title', 'تحليل مناقصة جديدة')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-md border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">تحليل مناقصة جديدة</h1>

        <form method="POST" action="{{ route('facility.tenders.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="document" class="block text-sm font-medium text-gray-700 mb-1">ملف كراسة الشروط (اختياري)</label>
                <input type="file" id="document" name="document" accept=".txt,.docx,.xlsx,.xls,.pdf"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">صيغ مدعومة: TXT, DOCX, XLSX, XLS, PDF</p>
                @error('document')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">اسم المناقصة</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">الرقم المرجعي (اختياري)</label>
                <input type="text" id="reference" name="reference" value="{{ old('reference') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('reference')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">نص كراسة الشروط</label>
                <p class="text-xs text-gray-500 mb-2">الصق نص كراسة الشروط أو نبذة المناقصة لاستخراج المتطلبات والامتثال.</p>
                <textarea id="content" name="content" rows="12" required
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 px-4 rounded-md">
                    تحليل بالذكاء الاصطناعي
                </button>
                <a href="{{ route('facility.tenders.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
