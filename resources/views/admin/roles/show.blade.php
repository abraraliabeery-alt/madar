@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل الدور - {{ $role->name }}</h5>
            <div>
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Role Details -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات الدور</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الاسم</label>
                                    <p class="fs-5">{{ $role->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">عدد المستخدمين</label>
                                    <p class="fs-5">
                                        <span class="badge bg-primary">{{ $role->users->count() }}</span>
                                    </p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">الوصف</label>
                                    <p class="fs-5">{{ $role->description ?? 'لا يوجد وصف' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">الصلاحيات ({{ $role->permissions->count() }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($role->permissions->groupBy('pages') as $page => $pagePermissions)
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">{{ $page }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="list-group">
                                                    @foreach($pagePermissions as $permission)
                                                        <div class="list-group-item">
                                                            <h6 class="mb-1">{{ $permission->name }}</h6>
                                                            @if($permission->description)
                                                                <small class="text-muted">{{ $permission->description }}</small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">المستخدمين ({{ $role->users->count() }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>الصورة</th>
                                            <th>الاسم</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>رقم الهاتف</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($role->users as $user)
                                        <tr>
                                            <td>
                                                @if($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}" alt="avatar" class="rounded-circle" width="40">
                                                @else
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_number ?? '-' }}</td>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <p class="mb-0">لا يوجد مستخدمين لهذا الدور</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Button -->
                @if($role->users->isEmpty())
                <div class="col-12">
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-confirm">
                            <i class="fas fa-trash me-2"></i>حذف الدور
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكن التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف الدور',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
