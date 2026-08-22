@extends('admin.layouts.app')

@section('panel_title', 'طلبات تسويق العقارات')

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

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">إجمالي الطلبات</h6>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">قيد المعالجة</h6>
                    <h3 class="mb-0 text-primary">{{ $stats['open'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">جديدة</h6>
                    <h3 class="mb-0 text-info">{{ $stats['new'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">محلولة</h6>
                    <h3 class="mb-0 text-success">{{ $stats['resolved'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 font-weight-bold">فلترة طلبات التسويق</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.marketing-product-requests.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: الاسم، الجوال، الوصف">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="priority" class="form-select">
                        <option value="">كل الأولويات</option>
                        @foreach($priorities as $key => $label)
                            <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="assigned_to" class="form-select">
                        <option value="">كل الموظفين</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" placeholder="من">
                </div>
                <div class="col-md-1">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" placeholder="إلى">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">فلتر</button>
                </div>
            </form>
        </div>
    </div>

    <form id="bulk-form" method="POST" action="{{ route('admin.marketing-product-requests.bulk-action') }}">
        @csrf
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2">
                <select name="action" class="form-select form-select-sm w-auto" required>
                    <option value="">إجراء جماعي...</option>
                    <option value="close">إغلاق</option>
                    <option value="resolve">حل</option>
                    <option value="delete">حذف</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('تأكيد تنفيذ الإجراء؟')">تطبيق</button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" class="form-check-input" id="select-all"></th>
                            <th>#</th>
                            <th>الوصف</th>
                            <th>المعلومات المستخرجة</th>
                            <th>التواصل</th>
                            <th>الحالة</th>
                            <th>الأولوية</th>
                            <th>مسؤول</th>
                            <th>{{ __('admin.product_requests.last_update') }}</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $req->id }}" form="bulk-form" class="form-check-input row-checkbox"></td>
                            <td>{{ $req->id }}</td>
                            <td class="text-wrap" style="max-width: 250px;">{{ Str::limit($req->description, 100) }}</td>
                            <td>
                                @php($extracted = $req->extracted ?? [])
                                @foreach($extracted as $key => $value)
                                    @if($value !== null && $value !== '' && $key !== 'notes')
                                        <span class="badge bg-secondary me-1">{{ $key }}: {{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if($req->name)<div>{{ $req->name }}</div>@endif
                                @if($req->phone)<div class="text-muted small">{{ $req->phone }}</div>@endif
                            </td>
                            <td>
                                <span class="badge {{ $statusColors[$req->status] ?? 'bg-light text-dark' }}">{{ $req->statusLabel() }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $priorityColors[$req->priority] ?? 'bg-light text-dark' }}">{{ $req->priorityLabel() }}</span>
                            </td>
                            <td>
                                {{ $req->assignedTo?->name ?? '—' }}
                            </td>
                            <td>
                                @php
                                    $daysSinceUpdate = now()->diffInDays($req->updated_at);
                                    $isStale = $req->isOpen() && $daysSinceUpdate >= 3;
                                @endphp
                                <div class="small {{ $isStale ? 'text-danger fw-bold' : 'text-muted' }}">
                                    {{ $req->updated_at->format('Y-m-d H:i') }}
                                </div>
                                @if($isStale)
                                    <span class="badge bg-danger-subtle text-danger border border-danger">
                                        {{ __('admin.product_requests.stale_warning', ['days' => $daysSinceUpdate]) }}
                                    </span>
                                @else
                                    <span class="text-muted small">{{ __('admin.product_requests.days_ago', ['days' => $daysSinceUpdate]) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.marketing-product-requests.show', $req) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                    <form method="POST" action="{{ route('admin.marketing-product-requests.destroy', $req) }}" onsubmit="return confirm('هل تريد حذف هذا الطلب؟')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">لا توجد طلبات تسويق حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($requests->hasPages())
        <div class="card-footer bg-white">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('select-all')?.addEventListener('change', function (e) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection
