@extends('admin.layouts.app')

@section('panel_title', 'تفاصيل طلب التسويق #' . $marketingProductRequest->id)

@php
$statusColors = [
    'new' => 'bg-info',
    'contacted' => 'bg-primary',
    'in_progress' => 'bg-warning text-dark',
    'resolved' => 'bg-success',
    'closed' => 'bg-secondary',
    'rejected' => 'bg-danger',
];

$priorityColors = [
    'low' => 'bg-secondary',
    'normal' => 'bg-light text-dark border',
    'high' => 'bg-warning text-dark',
    'urgent' => 'bg-danger',
];
@endphp

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">معلومات طلب التسويق</h5>
                    <div>
                        <span class="badge {{ $statusColors[$marketingProductRequest->status] ?? 'bg-light text-dark' }} me-1">{{ $marketingProductRequest->statusLabel() }}</span>
                        <span class="badge {{ $priorityColors[$marketingProductRequest->priority] ?? 'bg-light text-dark' }}">{{ $marketingProductRequest->priorityLabel() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">الاسم</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">رقم الجوال</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">المصدر</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->source }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ الطلب</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            @php($days = now()->diffInDays($marketingProductRequest->updated_at))
                            @php($stale = $marketingProductRequest->isOpen() && $days >= 3)
                            <label class="text-muted small">{{ __('admin.product_requests.last_update') }}</label>
                            <p class="mb-0 fw-bold {{ $stale ? 'text-danger' : '' }}">{{ $marketingProductRequest->updated_at->format('Y-m-d H:i') }}</p>
                            @if($stale)
                                <span class="badge bg-danger border border-danger">
                                    {{ __('admin.product_requests.stale_warning', ['days' => $days]) }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ التواصل</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->contacted_at?->format('Y-m-d H:i') ?? 'لم يتم' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ الإغلاق</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->closed_at?->format('Y-m-d H:i') ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">مسؤول الطلب</label>
                            <p class="mb-0 fw-bold">{{ $marketingProductRequest->assignedTo?->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>

                    <h6 class="fw-bold border-top pt-3">وصف الطلب</h6>
                    <p class="text-dark" style="white-space: pre-line;">{{ $marketingProductRequest->description }}</p>

                    <h6 class="fw-bold border-top pt-3 mt-4">المعلومات المستخرجة</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse(($marketingProductRequest->extracted ?? []) as $key => $value)
                            @if($value !== null && $value !== '')
                                <span class="badge bg-secondary fs-6">{{ $key }}: {{ is_array($value) ? implode(', ', $value) : $value }}</span>
                            @endif
                        @empty
                            <span class="text-muted">لا توجد معلومات مستخرجة.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 font-weight-bold">ملاحظات الإدارة</h5>
                </div>
                <div class="card-body">
                    @if($marketingProductRequest->admin_notes)
                        <p style="white-space: pre-line;">{{ $marketingProductRequest->admin_notes }}</p>
                    @else
                        <p class="text-muted">لا توجد ملاحظات.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 font-weight-bold">إدارة الطلب</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.marketing-product-requests.update', $marketingProductRequest) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $marketingProductRequest->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الأولوية</label>
                            <select name="priority" class="form-select" required>
                                @foreach($priorities as $key => $label)
                                    <option value="{{ $key }}" {{ $marketingProductRequest->priority === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تخصيص لموظف</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">—</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $marketingProductRequest->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="admin_notes" rows="5" class="form-control">{{ $marketingProductRequest->admin_notes }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">حفظ التحديثات</button>
                            <a href="{{ route('admin.marketing-product-requests.index') }}" class="btn btn-outline-secondary">العودة للقائمة</a>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.marketing-product-requests.destroy', $marketingProductRequest) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">حذف الطلب</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
