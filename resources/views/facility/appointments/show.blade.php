@extends('facility.layouts.app')

@section('title', 'تفاصيل الموعد')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 mb-1">تفاصيل الموعد</h1>
        <p class="text-sm text-gray-500">عرض بيانات الموعد مع العميل.</p>
    </div>
    <div class="flex items-center space-x-2 space-x-reverse">
        <form action="{{ route('facility.appointments.update-status', $appointment) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="status" value="completed">
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                تعليم كمكتمل
            </button>
        </form>
        <form action="{{ route('facility.appointments.update-status', $appointment) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700">
                إلغاء الموعد
            </button>
        </form>
        <a href="{{ route('facility.appointments.index') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
            عودة لقائمة المواعيد
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">بيانات الموعد</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">التاريخ</dt>
                <dd class="text-gray-900">{{ optional($appointment->appointment_time)->format('Y-m-d') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الوقت</dt>
                <dd class="text-gray-900">{{ optional($appointment->appointment_time)->format('H:i') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الحالة</dt>
                <dd class="text-gray-900">
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
                </dd>
            </div>
            <div>
                <dt class="text-gray-500 mb-1">موضوع الموعد</dt>
                <dd class="text-gray-900">{{ $appointment->subject ?? '-' }}</dd>
            </div>
            @if($appointment->notes)
                <div>
                    <dt class="text-gray-500 mb-1">ملاحظات إضافية</dt>
                    <dd class="text-gray-900 whitespace-pre-line">{{ $appointment->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">بيانات العميل</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">الاسم</dt>
                <dd class="text-gray-900">{{ $appointment->user->name ?? 'عميل غير معروف' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">البريد الإلكتروني</dt>
                <dd class="text-gray-900">{{ $appointment->user->email ?? '-' }}</dd>
            </div>
            @if($appointment->user && $appointment->user->phone_number)
                <div class="flex justify-between">
                    <dt class="text-gray-500">رقم الجوال</dt>
                    <dd class="text-gray-900">{{ $appointment->user->phone_number }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>
@endsection
