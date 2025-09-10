@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">الإشعارات</h1>
                    <p class="text-gray-600">إدارة إشعاراتك ورسائلك</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <button onclick="markAllAsRead()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-check-double ml-2"></i> تعيين الكل كمقروء
                    </button>
                    <a href="{{ route('client.notifications.settings') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-cog ml-2"></i> الإعدادات
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 space-x-reverse" aria-label="Tabs">
                    <button onclick="filterNotifications('all')" 
                            class="filter-tab active py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                        جميع الإشعارات
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $notifications->count() }}</span>
                    </button>
                    <button onclick="filterNotifications('unread')" 
                            class="filter-tab py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        غير المقروءة
                        <span class="ml-2 bg-red-100 text-red-900 py-0.5 px-2.5 rounded-full text-xs">{{ $notifications->where('read_at', null)->count() }}</span>
                    </button>
                    <button onclick="filterNotifications('bookings')" 
                            class="filter-tab py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        الحجوزات
                        <span class="ml-2 bg-blue-100 text-blue-900 py-0.5 px-2.5 rounded-full text-xs">{{ $notifications->where('type', 'booking')->count() }}</span>
                    </button>
                    <button onclick="filterNotifications('contracts')" 
                            class="filter-tab py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        العقود
                        <span class="ml-2 bg-green-100 text-green-900 py-0.5 px-2.5 rounded-full text-xs">{{ $notifications->where('type', 'contract')->count() }}</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div class="notification-item bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-blue-500' }}" 
                         data-type="{{ $notification->type }}" 
                         data-read="{{ $notification->read_at ? 'true' : 'false' }}">
                        <div class="flex items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if($notification->type == 'booking') bg-blue-100 text-blue-600
                                    @elseif($notification->type == 'contract') bg-green-100 text-green-600
                                    @elseif($notification->type == 'payment') bg-yellow-100 text-yellow-600
                                    @elseif($notification->type == 'appointment') bg-purple-100 text-purple-600
                                    @else bg-gray-100 text-gray-600 @endif">
                                    @if($notification->type == 'booking')
                                        <i class="fas fa-calendar-check"></i>
                                    @elseif($notification->type == 'contract')
                                        <i class="fas fa-file-contract"></i>
                                    @elseif($notification->type == 'payment')
                                        <i class="fas fa-credit-card"></i>
                                    @elseif($notification->type == 'appointment')
                                        <i class="fas fa-clock"></i>
                                    @else
                                        <i class="fas fa-bell"></i>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 mr-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                جديد
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mt-1">{{ $notification->body }}</p>
                                
                                @if($notification->data)
                                    <div class="mt-3">
                                        @if(isset($notification->data['action_url']))
                                            <a href="{{ $notification->data['action_url'] }}" 
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                                                عرض التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex-shrink-0">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    @if(!$notification->read_at)
                                        <button onclick="markAsRead({{ $notification->id }})" 
                                                class="text-gray-400 hover:text-gray-600" 
                                                title="تعيين كمقروء">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteNotification({{ $notification->id }})" 
                                            class="text-red-400 hover:text-red-600" 
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد إشعارات</h3>
                    <p class="text-gray-600 mb-6">ستظهر إشعاراتك هنا عند توفرها</p>
                    <a href="{{ route('client.offers.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        تصفح العروض المتاحة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterNotifications(filter) {
        // Remove active class from all tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Add active class to clicked tab
        event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
        event.target.classList.remove('border-transparent', 'text-gray-500');
        
        // Filter notifications
        const notifications = document.querySelectorAll('.notification-item');
        
        notifications.forEach(notification => {
            const type = notification.getAttribute('data-type');
            const isRead = notification.getAttribute('data-read') === 'true';
            
            let show = true;
            
            if (filter === 'unread' && isRead) {
                show = false;
            } else if (filter === 'bookings' && type !== 'booking') {
                show = false;
            } else if (filter === 'contracts' && type !== 'contract') {
                show = false;
            }
            
            notification.style.display = show ? 'block' : 'none';
        });
    }

    function markAsRead(notificationId) {
        fetch(`/client/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function markAllAsRead() {
        if (confirm('هل أنت متأكد من تعيين جميع الإشعارات كمقروءة؟')) {
            fetch('/client/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function deleteNotification(notificationId) {
        if (confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
            fetch(`/client/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
</script>
@endpush

@push('styles')
<style>
.filter-tab.active {
    border-color: #3b82f6;
    color: #3b82f6;
}

.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateY(-1px);
}

.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}
</style>
@endpush