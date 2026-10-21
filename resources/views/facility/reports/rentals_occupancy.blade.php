@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">تقارير الإيجارات - الإشغال</h1>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-white p-4 rounded shadow mb-6">
        <select name="root_id" class="border rounded px-3 py-2">
            <option value="">كل الأصول الرئيسية</option>
            @foreach($roots as $r)
                <option value="{{ $r->id }}" @selected(request('root_id')==$r->id)>{{ $r->getTranslatedTitle() ?? ('#'.$r->id) }}</option>
            @endforeach
        </select>
        <select name="parent_id" class="border rounded px-3 py-2">
            <option value="">كل الأصول الأبوية</option>
            @foreach($parents as $p)
                <option value="{{ $p->id }}" @selected(request('parent_id')==$p->id)>{{ $p->getTranslatedTitle() ?? ('#'.$p->id) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button class="bg-gray-800 text-white px-4 py-2 rounded">تصفية</button>
            <a href="{{ route('facility.reports.rentals.occupancy') }}" class="px-4 py-2 border rounded">تفريغ</a>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">عدد الوحدات</div>
            <div class="text-2xl font-semibold">{{ $rentableCount }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">الوحدات المشغولة</div>
            <div class="text-2xl font-semibold">{{ $occupiedLikeCount }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">نسبة الإشغال (تقريبية)</div>
            <div class="text-2xl font-semibold">{{ $rentableCount ? round(($occupiedLikeCount/$rentableCount)*100,1) : 0 }}%</div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto mb-6">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-right">
                    <th class="px-6 py-3">الأصل الرئيسي</th>
                    <th class="px-6 py-3">إجمالي الوحدات</th>
                    <th class="px-6 py-3">مشغولة</th>
                    <th class="px-6 py-3">النسبة %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byRoot as $rid=>$row)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $rid ? ($labels[$rid] ?? ('#'.$rid)) : 'وحدات مستقلة' }}</td>
                        <td class="px-6 py-3">{{ $row['total'] }}</td>
                        <td class="px-6 py-3">{{ $row['active'] }}</td>
                        <td class="px-6 py-3">{{ $row['rate'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-6 text-center text-gray-500">لا بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-right">
                    <th class="px-6 py-3">الأصل الأبوي</th>
                    <th class="px-6 py-3">إجمالي الوحدات</th>
                    <th class="px-6 py-3">مشغولة</th>
                    <th class="px-6 py-3">النسبة %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byParent as $pid=>$row)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $pid ? ($labels[$pid] ?? ('#'.$pid)) : 'وحدات مستقلة' }}</td>
                        <td class="px-6 py-3">{{ $row['total'] }}</td>
                        <td class="px-6 py-3">{{ $row['active'] }}</td>
                        <td class="px-6 py-3">{{ $row['rate'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-6 text-center text-gray-500">لا بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
