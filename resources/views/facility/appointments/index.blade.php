@extends('facility.layouts.app')

@section('title', 'مواعيد المنشأة')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 mb-1">مواعيد المنشأة</h1>
        <p class="text-sm text-gray-500">عرض مواعيد العملاء المرتبطة بهذه المنشأة.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">إجمالي المواعيد</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow border border-blue-100 p-4">
        <p class="text-xs text-blue-500 mb-1">المجدولة</p>
        <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['scheduled'] ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow border border-green-100 p-4">
        <p class="text-xs text-green-500 mb-1">المكتملة</p>
        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['completed'] ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow border border-red-100 p-4">
        <p class="text-xs text-red-500 mb-1">الملغاة</p>
        <p class="text-2xl font-bold text-red-600">{{ number_format($stats['cancelled'] ?? 0) }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-gray-200">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">الحالة</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                    <option value="">الكل</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتملة</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                    <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>معاد جدولتها</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">من تاريخ</label>
                <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">إلى تاريخ</label>
                <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-primary-600 text-white hover:bg-primary-700">تطبيق</button>
                <a href="{{ route('facility.appointments.index') }}" class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">إعادة تعيين</a>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-x-auto">
    <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-700">قائمة المواعيد</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوقت</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">موضوع الموعد</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $appointment->user->name ?? 'عميل غير معروف' }}<br>
                            <span class="text-xs text-gray-500">{{ $appointment->user->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ optional($appointment->appointment_time)->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ optional($appointment->appointment_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php($status = $appointment->status)
                            @php($statusClasses = [
                                'scheduled' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'rescheduled' => 'bg-yellow-100 text-yellow-800',
                            ])
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __('client.appointments.status_' . $status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $appointment->subject ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('facility.appointments.show', $appointment) }}" class="text-primary-600 hover:text-primary-800">تفاصيل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                            لا توجد مواعيد حتى الآن.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-gray-200">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
