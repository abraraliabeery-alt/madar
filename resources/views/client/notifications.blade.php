@extends('layouts.app')

@section('title', __('client.notifications.title'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('client.notifications.title') }}</h1>
            <p class="text-gray-600">{{ __('client.notifications.subtitle') }}</p>
        </div>

        <!-- Notification Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <span class="text-sm text-gray-600">
                        {{ auth()->user()->notifications->count() }} إشعار إجمالي
                    </span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }} غير مقروء
                        </span>
                    @endif
                </div>

                <div class="flex items-center space-x-3 space-x-reverse">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('client.notifications.mark-all-read') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                تحديد الكل كمقروء
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('client.notifications.settings') }}" class="text-sm text-gray-600 hover:text-gray-700 font-medium">
                        إعدادات الإشعارات
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow-sm">
            @if(auth()->user()->notifications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach(auth()->user()->notifications as $notification)
                        <div class="p-6 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-75' : '' }}">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    @if($notification->data['type'] == 'booking_created')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-green-600"></i>
                                        </div>
                                    @elseif($notification->data['type'] == 'new_product_added')
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-home text-blue-600"></i>
                                        </div>
                                    @elseif($notification->data['type'] == 'booking_status_changed')
                                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-sync-alt text-yellow-600"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-bell text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $notification->data['message'] ?? 'إشعار جديد' }}
                                            </p>

                                            @if(isset($notification->data['booking_id']))
                                                <p class="text-sm text-gray-600 mt-1">
                                                    رقم الحجز: #{{ $notification->data['booking_id'] }}
                                                </p>
                                            @endif

                                            @if(isset($notification->data['product_name']))
                                                <p class="text-sm text-gray-600 mt-1">
                                                    العقار: {{ $notification->data['product_name'] }}
                                                </p>
                                            @endif

                                            @if(isset($notification->data['old_status']) && isset($notification->data['new_status']))
                                                <p class="text-sm text-gray-600 mt-1">
                                                    تغيير الحالة من <span class="font-medium">{{ $notification->data['old_status'] }}</span>
                                                    إلى <span class="font-medium">{{ $notification->data['new_status'] }}</span>
                                                </p>
                                            @endif

                                            <p class="text-xs text-gray-500 mt-2">
                                                {{ $notification->created_at->format('Y-m-d H:i') }}
                                                ({{ $notification->created_at->diffForHumans() }})
                                            </p>
                                        </div>

                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            @if(!$notification->read_at)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    جديد
                                                </span>
                                            @endif

                                            @if(!$notification->read_at)
                                                <form method="POST" action="{{ route('client.notifications.mark-read') }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                    <button type="submit" class="text-xs text-primary-600 hover:text-primary-700">
                                                        تحديد كمقروء
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ auth()->user()->notifications()->paginate(20)->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد إشعارات</h3>
                    <p class="text-gray-600 mb-6">ستظهر هنا الإشعارات الجديدة عند وصولها</p>
                    <a href="{{ route('public.products.index') }}" class="btn-primary text-white px-6 py-2 rounded-lg">
                        استكشف العقارات
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
