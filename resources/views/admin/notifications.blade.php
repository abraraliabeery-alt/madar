@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bell ms-2"></i>الإشعارات
                    </h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check-double ms-1"></i>تحديد الكل كمقروء
                            </button>
                        </form>
                        <a href="{{ route('admin.notifications.settings') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-cog ms-1"></i>الإعدادات
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="notifications-list">
                            @foreach($notifications as $notification)
                                <div class="notification-item p-3 border-bottom {{ $notification->read_at ? 'read' : 'unread' }}">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="notification-icon me-3">
                                                @if($notification->data['type'] ?? '' === 'booking_created')
                                                    <i class="fas fa-calendar-check text-primary"></i>
                                                @elseif($notification->data['type'] ?? '' === 'new_user')
                                                    <i class="fas fa-user-plus text-success"></i>
                                                @elseif($notification->data['type'] ?? '' === 'new_product')
                                                    <i class="fas fa-box text-info"></i>
                                                @elseif($notification->data['type'] ?? '' === 'new_facility')
                                                    <i class="fas fa-building text-warning"></i>
                                                @else
                                                    <i class="fas fa-bell text-secondary"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $notification->data['message'] ?? 'إشعار جديد' }}</h6>
                                                    <p class="text-muted mb-1">
                                                        @if(isset($notification->data['product_name']))
                                                            المنتج: {{ $notification->data['product_name'] }}
                                                        @endif
                                                        @if(isset($notification->data['booking_date']))
                                                            تاريخ الحجز: {{ $notification->data['booking_date'] }}
                                                        @endif
                                                        @if(isset($notification->data['total_amount']))
                                                            المبلغ: {{ $notification->data['total_amount'] }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}
                                                        @endif
                                                    </p>
                                                    <small class="text-muted">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    @if(!$notification->read_at)
                                                        <form action="{{ route('admin.notifications.mark-read') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <span class="badge {{ $notification->read_at ? 'bg-secondary' : 'bg-primary' }}">
                                                        {{ $notification->read_at ? 'مقروء' : 'جديد' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد إشعارات</h5>
                            <p class="text-muted">ستظهر هنا الإشعارات الجديدة عند وصولها</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #f0f8ff;
    border-left: 4px solid #007bff;
}

.notification-item.read {
    opacity: 0.7;
}

.notification-icon i {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #f8f9fa;
}

.notifications-list .pagination {
    justify-content: center;
}
</style>
@endsection
