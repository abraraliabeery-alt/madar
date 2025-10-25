@extends('admin.layouts.app')

@section('title', 'إدارة الصلاحيات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إدارة الصلاحيات</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة صلاحية جديدة
                        </a>
                        <a href="{{ route('admin.permissions.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </a>
                    </div>
                </div>

                <!-- فلاتر البحث -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="is_active" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="group" class="form-select">
                                <option value="">جميع المجموعات</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في الصلاحيات..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </form>

                    @if($permissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>الوصف</th>
                                        <th>المجموعة</th>
                                        <th>الحالة</th>
                                        <th>عدد الأدوار</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>
                                                <strong>{{ $permission->name }}</strong>
                                                @if($permission->translations->count() > 0)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $permission->getTranslatedName() }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $permission->description ?: 'لا يوجد وصف' }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $permission->getGroupName() }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $permission->is_active ? 'success' : 'danger' }}">
                                                    {{ $permission->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $permission->roles->count() }}</span>
                                            </td>
                                            <td>{{ $permission->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.permissions.show', $permission) }}" 
                                                       class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.permissions.toggle-status', $permission) }}" 
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-{{ $permission->is_active ? 'secondary' : 'success' }}" 
                                                                title="{{ $permission->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                            <i class="fas fa-{{ $permission->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $permissions->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد صلاحيات</h5>
                            <p class="text-muted">ابدأ بإضافة صلاحيات جديدة</p>
                            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة صلاحية جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="is_active"], select[name="group"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
