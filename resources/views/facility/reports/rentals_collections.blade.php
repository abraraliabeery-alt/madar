@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">تقارير الإيجارات - التحصيل والمتأخرات</h1>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white p-4 rounded shadow mb-6">
        <div>
            <label class="block mb-1 text-sm">من تاريخ</label>
            <input type="date" name="date_from" value="{{ request('date_from', optional($dateFrom)->format('Y-m-d')) }}" class="border rounded px-3 py-2 w-full">
        </div>
        <div>
            <label class="block mb-1 text-sm">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ request('date_to', optional($dateTo)->format('Y-m-d')) }}" class="border rounded px-3 py-2 w-full">
        </div>
        <div class="flex items-end gap-2">
            <button class="bg-gray-800 text-white px-4 py-2 rounded">تصفية</button>
            <a href="{{ route('facility.reports.rentals.collections') }}" class="px-4 py-2 border rounded">تفريغ</a>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">إجمالي الفواتير</div>
            <div class="text-2xl font-semibold">{{ number_format($invoicesTotal, 2) }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">إجمالي المدفوعات</div>
            <div class="text-2xl font-semibold">{{ number_format($paymentsTotal, 2) }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">عدد المتأخرات</div>
            <div class="text-2xl font-semibold">{{ $arrearsCount }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-gray-500">قيمة المتأخرات</div>
            <div class="text-2xl font-semibold">{{ number_format($arrearsTotal, 2) }}</div>
        </div>
    </div>

    <div class="text-gray-500">الفترة: {{ optional($dateFrom)->format('Y-m-d') }} إلى {{ optional($dateTo)->format('Y-m-d') }}</div>
</div>
@endsection
