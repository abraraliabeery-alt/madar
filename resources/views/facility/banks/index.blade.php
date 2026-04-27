@extends('facility.layouts.app')

@section('title', 'البنوك')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">البنوك المتاحة</h1>
        <p class="text-gray-600 mt-1 text-sm">قائمة البنوك المسجلة في النظام (عرض فقط).</p>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        @if($banks->count())
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($banks as $bank)
                    <div class="border border-gray-200 rounded-lg p-4 flex items-center gap-3 bg-gray-50">
                        @if($bank->logo)
                            <img src="{{ asset('storage/'.$bank->logo) }}" alt="{{ $bank->name }}" class="w-10 h-10 object-contain rounded">
                        @else
                            <div class="w-10 h-10 rounded bg-blue-600 text-white flex items-center justify-center">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-semibold text-gray-800">{{ $bank->name }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">لا توجد بنوك مسجلة حاليًا.</p>
        @endif
    </div>
@endsection
