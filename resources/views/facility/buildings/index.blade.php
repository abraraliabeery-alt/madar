@extends('facility.layouts.app')

@section('title', 'العمارات')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-6xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">العمارات</h1>
                <p class="text-sm text-gray-500 mt-1">إدارة عمارات المنشأة</p>
            </div>
            <a href="{{ route('facility.buildings.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                إضافة عمارة
            </a>
        </div>

        <div class="mb-6 bg-white rounded-md border border-gray-200 p-4">
            <form method="GET" action="{{ route('facility.buildings.index') }}" class="flex gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث باسم العمارة" class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md">بحث</button>
            </form>
        </div>

        <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700">الاسم</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700">الشقق</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700">الأدوار</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700">الحالة</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $building)
                        @php
                            $translation = $building->translations->firstWhere('locale', app()->getLocale());
                            $name = $translation->name ?? ('#' . $building->id);
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $building->Number_of_Apartments ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $building->Number_of_floors ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($building->is_active)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">نشط</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('facility.buildings.edit', $building) }}" class="text-blue-600 hover:underline">تعديل</a>
                                <form action="{{ route('facility.buildings.destroy', $building) }}" method="POST" class="inline-block mr-3" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">لا توجد عمارات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $buildings->links() }}
        </div>
    </div>
</div>
@endsection
