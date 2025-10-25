@extends('admin.layouts.app')

@section('title', 'سجل نشاط المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">سجل نشاط المستخدمين</li>
                    </ol>
                </div>
                <h4 class="page-title">سجل نشاط المستخدمين</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users.activity-logs') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="user_id" class="form-label">المستخدم</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">جميع المستخدمين</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="action" class="form-label">الإجراء</label>
                                <select class="form-select" id="action" name="action">
                                    <option value="">جميع الإجراءات</option>
                                    @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ $action }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-magnify"></i> بحث
                                    </button>
                                    <a href="{{ route('admin.users.activity-logs') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh"></i> إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">سجل النشاط</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-sm" id="exportLogsBtn">
                                <i class="mdi mdi-download"></i> تصدير
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="clearLogsBtn">
                                <i class="mdi mdi-delete"></i> مسح السجل
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الإجراء</th>
                                    <th>الوصف</th>
                                    <th>عنوان IP</th>
                                    <th>المتصفح</th>
                                    <th>نوع الجهاز</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-title bg-light text-primary rounded">
                                                    {{ substr($log->user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $log->user->name ?? 'غير محدد' }}</h6>
                                                <small class="text-muted">{{ $log->user->email ?? 'غير محدد' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $this->getActionColor($log->action) }}">{{ $log->action }}</span>
                                    </td>
                                    <td>{{ Str::limit($log->description, 50) }}</td>
                                    <td>
                                        <code>{{ $log->ip_address }}</code>
                                    </td>
                                    <td>{{ $log->browser ?? 'غير محدد' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $log->device_type ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
                                        </div>
                                        <div>
                                            <small class="text-info">{{ $log->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" onclick="viewLogDetails({{ $log->id }})">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="deleteLog({{ $log->id }})">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد سجلات نشاط</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($activityLogs->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $activityLogs->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل السجل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد مسح السجل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>تحذير!</strong> هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع سجلات النشاط.
                </div>
                <p>هل أنت متأكد من أنك تريد مسح جميع سجلات النشاط؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmClearLogs">مسح السجل</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewLogDetails(logId) {
        document.getElementById('logDetailsContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
            </div>
        `;
        
        fetch(`/admin/users/activity-logs/${logId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('logDetailsContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>معلومات المستخدم</h6>
                            <p><strong>الاسم:</strong> ${data.user.name || 'غير محدد'}</p>
                            <p><strong>البريد الإلكتروني:</strong> ${data.user.email || 'غير محدد'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>معلومات النشاط</h6>
                            <p><strong>الإجراء:</strong> <span class="badge bg-primary">${data.action}</span></p>
                            <p><strong>التاريخ:</strong> ${data.created_at}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>الوصف</h6>
                            <p>${data.description}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>معلومات الجهاز</h6>
                            <p><strong>عنوان IP:</strong> <code>${data.ip_address}</code></p>
                            <p><strong>المتصفح:</strong> ${data.browser || 'غير محدد'}</p>
                            <p><strong>نظام التشغيل:</strong> ${data.platform || 'غير محدد'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>معلومات إضافية</h6>
                            <p><strong>نوع الجهاز:</strong> ${data.device_type || 'غير محدد'}</p>
                            <p><strong>User Agent:</strong> <small class="text-muted">${data.user_agent || 'غير محدد'}</small></p>
                        </div>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('logDetailsContent').innerHTML = '<div class="alert alert-danger">خطأ في تحميل التفاصيل</div>';
            });
    }
    
    function deleteLog(logId) {
        if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
            fetch(`/admin/users/activity-logs/${logId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('خطأ في حذف السجل');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطأ في حذف السجل');
            });
        }
    }
    
    // Clear logs button
    document.getElementById('clearLogsBtn').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('clearLogsModal')).show();
    });
    
    // Confirm clear logs
    document.getElementById('confirmClearLogs').addEventListener('click', function() {
        fetch('/admin/users/activity-logs/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('خطأ في مسح السجل');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطأ في مسح السجل');
        });
    });
    
    // Export logs button
    document.getElementById('exportLogsBtn').addEventListener('click', function() {
        const params = new URLSearchParams(window.location.search);
        window.open(`/admin/users/activity-logs/export?${params.toString()}`, '_blank');
    });
</script>
@endpush

@php
function getActionColor($action) {
    $colors = [
        'login' => 'success',
        'logout' => 'secondary',
        'create' => 'primary',
        'update' => 'warning',
        'delete' => 'danger',
        'view' => 'info',
        'export' => 'dark',
        'import' => 'light',
    ];
    
    return $colors[$action] ?? 'secondary';
}
@endphp
