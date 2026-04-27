@extends('layouts.app')

@section('title', 'سجل النشاط - منطقة العميل')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">سجل النشاط</h1>
                <p class="text-gray-600 text-sm">كل ما حدث في حسابك من حجوزات، عقود ومواعيد في مكان واحد.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-right ml-2"></i>
                    الرجوع للوحة العميل
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200 overflow-x-auto">
                <nav class="flex space-x-4 space-x-reverse px-4" aria-label="Tabs">
                    <button type="button" data-filter="all" class="activity-tab active py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
                        الكل
                    </button>
                    <button type="button" data-filter="booking" class="activity-tab py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                        الحجوزات
                    </button>
                    <button type="button" data-filter="contract" class="activity-tab py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                        العقود
                    </button>
                    <button type="button" data-filter="appointment" class="activity-tab py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                        المواعيد
                    </button>
                </nav>
            </div>
        </div>

        @if($activities->count() > 0)
            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                <div class="relative">
                    <!-- Vertical line -->
                    <div class="hidden sm:block absolute inset-y-0 right-4 w-px bg-gray-200" aria-hidden="true"></div>

                    <div class="space-y-6">
                        @foreach($activities as $activity)
                            <div class="activity-item relative flex sm:pr-12" data-type="{{ $activity['type'] }}">
                                <!-- Dot -->
                                <div class="hidden sm:flex absolute right-4 w-3 h-3 rounded-full ring-4 ring-white
                                    @if($activity['type'] === 'booking') bg-blue-500
                                    @elseif($activity['type'] === 'contract') bg-green-500
                                    @elseif($activity['type'] === 'appointment') bg-purple-500
                                    @else bg-gray-400 @endif">
                                </div>

                                <!-- Card -->
                                <div class="flex-1 bg-gray-50 sm:bg-white border border-gray-100 rounded-lg p-4 sm:p-5 shadow-sm">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center
                                                @if($activity['type'] === 'booking') bg-blue-100 text-blue-600
                                                @elseif($activity['type'] === 'contract') bg-green-100 text-green-600
                                                @elseif($activity['type'] === 'appointment') bg-purple-100 text-purple-600
                                                @else bg-gray-100 text-gray-600 @endif">
                                                @if($activity['type'] === 'booking')
                                                    <i class="fas fa-calendar-check text-sm"></i>
                                                @elseif($activity['type'] === 'contract')
                                                    <i class="fas fa-file-contract text-sm"></i>
                                                @elseif($activity['type'] === 'appointment')
                                                    <i class="fas fa-clock text-sm"></i>
                                                @else
                                                    <i class="fas fa-bell text-sm"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="text-sm sm:text-base font-semibold text-gray-900">{{ $activity['title'] }}</h3>
                                                <p class="mt-1 text-sm text-gray-600">{{ $activity['description'] }}</p>

                                                <!-- Context info per type -->
                                                <div class="mt-2 text-xs sm:text-sm text-gray-500 space-y-1">
                                                    @if($activity['type'] === 'booking' && $activity['data'])
                                                        <p>
                                                            <span class="font-medium text-gray-700">العقار:</span>
                                                            {{ $activity['data']->product->name ?? 'غير متوفر' }}
                                                        </p>
                                                        <p>
                                                            <span class="font-medium text-gray-700">المنشأة:</span>
                                                            {{ $activity['data']->facility->name ?? 'غير متوفر' }}
                                                        </p>
                                                    @elseif($activity['type'] === 'contract' && $activity['data'])
                                                        <p>
                                                            <span class="font-medium text-gray-700">العقار:</span>
                                                            {{ $activity['data']->product->name ?? 'غير متوفر' }}
                                                        </p>
                                                        <p>
                                                            <span class="font-medium text-gray-700">الحالة:</span>
                                                            {{ $activity['data']->status ?? '-' }}
                                                        </p>
                                                    @elseif($activity['type'] === 'appointment' && $activity['data'])
                                                        <p>
                                                            <span class="font-medium text-gray-700">المنشأة:</span>
                                                            {{ $activity['data']->facility->name ?? 'غير متوفر' }}
                                                        </p>
                                                        <p>
                                                            <span class="font-medium text-gray-700">موعد الزيارة:</span>
                                                            {{ optional($activity['data']->appointment_time)->format('Y/m/d H:i') }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Actions -->
                                                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs sm:text-sm">
                                                    @if($activity['type'] === 'booking' && $activity['data'])
                                                        <a href="{{ route('client.bookings.show', $activity['data']->id) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100">
                                                            عرض الحجز
                                                            <i class="fas fa-arrow-left mr-1 text-xs"></i>
                                                        </a>
                                                    @elseif($activity['type'] === 'contract' && $activity['data'])
                                                        <a href="{{ route('client.contracts.show', $activity['data']->id) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 hover:bg-green-100">
                                                            عرض العقد
                                                            <i class="fas fa-arrow-left mr-1 text-xs"></i>
                                                        </a>
                                                    @elseif($activity['type'] === 'appointment' && $activity['data'])
                                                        <a href="{{ route('client.appointments.show', $activity['data']->id) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 text-purple-700 hover:bg-purple-100">
                                                            تفاصيل الموعد
                                                            <i class="fas fa-arrow-left mr-1 text-xs"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-1 text-xs text-gray-500">
                                            <span>{{ optional($activity['date'])->format('Y/m/d') }}</span>
                                            <span>{{ optional($activity['date'])->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="bg-white rounded-lg shadow-sm p-10 text-center">
                <div class="mx-auto w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fas fa-list text-gray-400 text-3xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">لا يوجد نشاط حتى الآن</h2>
                <p class="text-gray-600 mb-6 text-sm">ستظهر هنا الحجوزات، العقود والمواعيد عند بدء استخدامك للمنصة.</p>
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-md bg-primary-600 text-white text-sm font-medium hover:bg-primary-700">
                    ابدأ من لوحة العميل
                    <i class="fas fa-arrow-left mr-2 text-xs"></i>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.activity-tab');
        const items = document.querySelectorAll('.activity-item');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                tabs.forEach(t => {
                    t.classList.remove('active', 'border-blue-600', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });

                this.classList.add('active', 'border-blue-600', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');

                items.forEach(item => {
                    const type = item.getAttribute('data-type');
                    const shouldShow = (filter === 'all') || (type === filter);
                    item.style.display = shouldShow ? 'flex' : 'none';
                });
            });
        });
    });
</script>
@endpush
