@extends('facility.layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة المستخدمين</h3>
                    <div>
                        <a href="{{ route('facility.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة مستخدم جديد
                        </a>
                        <a href="{{ route('facility.users.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </a>
                        <a href="{{ route('facility.users.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في المستخدمين..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="role_id" class="form-select">
                                <option value="">جميع الأدوار</option>
                                @foreach($availableRoles as $role)
                                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->getTranslatedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> مسح
                            </a>
                        </div>
                    </form>

                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>المستخدم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>رقم الهاتف</th>
                                        <th>الأدوار</th>
                                        <th>تاريخ الانضمام</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        @if($user->avatar)
                                                            <img src="{{ asset($user->avatar) }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                {{ substr($user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        @if($facility->owner_user_id == $user->id)
                                                            <small class="text-success">مالك المنشأة</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_number }}</td>
                                            <td>
                                                @foreach($user->roles as $role)
                                                    @if($role->facility_id == $facility->id)
                                                        <span class="badge bg-secondary me-1">{{ $role->getTranslatedName() }}</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">مفعل</span>
                                                @else
                                                    <span class="badge bg-warning">غير مفعل</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.users.show', $user) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($facility->owner_user_id != $user->id)
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="confirmRemove('{{ route('facility.users.remove', $user) }}', '{{ $user->name }}')">
                                                            <i class="fas fa-user-minus"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد مستخدمين</h5>
                            <p class="text-muted">ابدأ بإضافة مستخدمين للمنشأة</p>
                            <a href="{{ route('facility.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة مستخدم جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لتأكيد الإزالة -->
<div class="modal fade" id="confirmRemoveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الإزالة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من إزالة المستخدم <strong id="userName"></strong> من المنشأة؟</p>
                <p class="text-muted">سيتم إزالة جميع أدواره في هذه المنشأة.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="removeForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">إزالة</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmRemove(url, userName) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('removeForm').action = url;
        new bootstrap.Modal(document.getElementById('confirmRemoveModal')).show();
    }

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="role_id"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 0.25rem;
}

.avatar-sm img {
    object-fit: cover;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 0.125rem 0;
    }
}
</style>
@endpush
