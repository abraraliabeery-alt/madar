@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">عرض تصنيف مشاريع - {{ $projectCategory->name }}</h5>
            <a href="{{ route('admin.project-categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="fw-semibold mb-1">الاسم</div>
                    <div>{{ $projectCategory->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold mb-1">الأب</div>
                    <div>{{ $projectCategory->parent ? $projectCategory->parent->name : '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold mb-1">الحالة</div>
                    <div>{{ $projectCategory->is_active ? 'نشط' : 'غير نشط' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold mb-1">مميز</div>
                    <div>{{ $projectCategory->is_featured ? 'نعم' : 'لا' }}</div>
                </div>
                <div class="col-md-12">
                    <div class="fw-semibold mb-1">الوصف</div>
                    <div>{{ $projectCategory->description ?: '—' }}</div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.project-categories.edit', $projectCategory) }}" class="btn btn-outline-warning">تعديل</a>
                <form action="{{ route('admin.project-categories.destroy', $projectCategory) }}" method="POST" onsubmit="return confirm('حذف التصنيف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
