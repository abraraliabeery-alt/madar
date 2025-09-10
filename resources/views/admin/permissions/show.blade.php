@extends('admin.layouts.app')

@section('title', 'تفاصيل الصلاحية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الصلاحية</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- المعلومات الأساسية -->
                        <div class="col-md-6">
                            <h5 class="mb-3">المعلومات الأساسية</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">اسم الصلاحية:</td>
                                    <td>
                                        <code>{{ $permission->name }}</code>
                                        @if($permission->translations->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                {{ $permission->getTranslatedName() }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">الوصف:</td>
                                    <td>{{ $permission->description ?: 'لا يوجد وصف' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">المجموعة:</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $permission->getGroupName() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">اسم الحارس:</td>
                                    <td>{{ $permission->guard_name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">الحالة:</td>
                                    <td>
                                        <span class="badge bg-{{ $permission->is_active ? 'success' : 'danger' }}">
                                            {{ $permission->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">تاريخ الإنشاء:</td>
                                    <td>{{ $permission->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">آخر تحديث:</td>
                                    <td>{{ $permission->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- الترجمات -->
                        <div class="col-md-6">
                            <h5 class="mb-3">الترجمات</h5>
                            
                            @if($permission->translations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>اللغة</th>
                                                <th>الاسم</th>
                                                <th>اسم العرض</th>
                                                <th>الوصف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permission->translations as $translation)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ $translation->locale == 'ar' ? 'العربية' : 'English' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $translation->name }}</td>
                                                    <td>{{ $translation->display_name ?: $translation->name }}</td>
                                                    <td>{{ $translation->description ?: 'لا يوجد وصف' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    لا توجد ترجمات لهذه الصلاحية
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- الأدوار المرتبطة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">الأدوار المرتبطة</h5>
                            
                            @if($permission->roles->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>اسم الدور</th>
                                                <th>الوصف</th>
                                                <th>الحالة</th>
                                                <th>تاريخ الارتباط</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permission->roles as $role)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $role->name }}</strong>
                                                        @if($role->translations->count() > 0)
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ $role->getTranslatedName() }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $role->description ?: 'لا يوجد وصف' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }}">
                                                            {{ $role->is_active ? 'نشط' : 'غير نشط' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $role->pivot->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.roles.show', $role) }}" 
                                                           class="btn btn-sm btn-info" title="عرض الدور">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    لا توجد أدوار مرتبطة بهذه الصلاحية
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- الإحصائيات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">الإحصائيات</h5>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3 class="card-title">{{ $permission->roles->count() }}</h3>
                                            <p class="card-text">عدد الأدوار المرتبطة</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h3 class="card-title">{{ $permission->translations->count() }}</h3>
                                            <p class="card-text">عدد الترجمات</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h3 class="card-title">{{ $permission->is_active ? 'نشط' : 'غير نشط' }}</h3>
                                            <p class="card-text">الحالة</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h3 class="card-title">{{ $permission->created_at->diffForHumans() }}</h3>
                                            <p class="card-text">منذ الإنشاء</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                        <div>
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                                  class="d-inline" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
