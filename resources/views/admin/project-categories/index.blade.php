@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة تصنيفات المشاريع</h5>
            <a href="{{ route('admin.project-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة تصنيف جديد
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الحالة</th>
                            <th>مميز</th>
                            <th>عدد المشاريع</th>
                            <th class="text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projectCategories as $cat)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $cat->name }}</div>
                                    @if($cat->parent)
                                        <div class="text-muted small">تابع لـ: {{ $cat->parent->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($cat->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cat->is_featured)
                                        <span class="badge bg-warning">نعم</span>
                                    @else
                                        <span class="badge bg-secondary">لا</span>
                                    @endif
                                </td>
                                <td>{{ $cat->projects_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.project-categories.show', $cat) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                    <a href="{{ route('admin.project-categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning">تعديل</a>
                                    <form action="{{ route('admin.project-categories.toggle-status', $cat) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">تبديل الحالة</button>
                                    </form>
                                    <form action="{{ route('admin.project-categories.toggle-featured', $cat) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-info">تبديل الميزة</button>
                                    </form>
                                    <form action="{{ route('admin.project-categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف التصنيف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">لا يوجد تصنيفات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $projectCategories->links() }}</div>
        </div>
    </div>
</div>
@endsection
