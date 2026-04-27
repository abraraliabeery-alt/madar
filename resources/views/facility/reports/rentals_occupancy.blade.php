@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">تقارير الإيجارات - الإشغال</h1>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-white p-4 rounded shadow mb-6">
        <select name="project_id" class="border rounded px-3 py-2">
            <option value="">كل المشاريع</option>
            @foreach($projects as $p)
                <option value="{{ $p->id }}" @selected(request('project_id')==$p->id)>{{ $p->name ?? ('#'.$p->id) }}</option>
            @endforeach
        </select>
        <select name="building_id" class="border rounded px-3 py-2">
            <option value="">كل العمائر</option>
            @foreach($buildings as $b)
                <option value="{{ $b->id }}" @selected(request('building_id')==$b->id)>{{ $b->name ?? ('#'.$b->id) }}</option>
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
            <div class="text-gray-500">الوحدات بخطط نشطة</div>
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
                    <th class="px-6 py-3">المشروع</th>
                    <th class="px-6 py-3">إجمالي الوحدات</th>
                    <th class="px-6 py-3">نشطة</th>
                    <th class="px-6 py-3">النسبة %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byProject as $pid=>$row)
                    <tr class="border-t">
                        <td class="px-6 py-3">#{{ $pid }}</td>
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
                    <th class="px-6 py-3">العمارة</th>
                    <th class="px-6 py-3">إجمالي الوحدات</th>
                    <th class="px-6 py-3">نشطة</th>
                    <th class="px-6 py-3">النسبة %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byBuilding as $bid=>$row)
                    <tr class="border-t">
                        <td class="px-6 py-3">#{{ $bid }}</td>
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
