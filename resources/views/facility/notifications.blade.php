@extends('facility.layouts.app')

@section('title', __('facility.sidebar.notifications'))

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h1 class="h4 fw-bold mb-0">{{ __('facility.sidebar.notifications') }}</h1>
        <form method="POST" action="{{ route('facility.notifications.mark-read') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary">تعليم الكل كمقروء</button>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <div class="list-group-item">
                    <div class="small text-muted">{{ $notification->created_at?->diffForHumans() ?? '' }}</div>
                    <div>{{ data_get($notification->data ?? [], 'message', 'إشعار جديد') }}</div>
                </div>
            @empty
                <div class="list-group-item text-center text-muted py-4">
                    <i class="fas fa-bell-slash fs-4 d-block mb-2"></i>
                    لا توجد إشعارات حالياً
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
