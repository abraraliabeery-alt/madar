@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إحصائيات تصنيفات المشاريع</h5>
            <a href="{{ route('admin.project-categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="fw-semibold">إجمالي التصنيفات</div>
                            <div class="fs-2">{{ $stats['total_categories'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="fw-semibold">النشطة</div>
                            <div class="fs-2">{{ $stats['active_categories'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <div class="fw-semibold">المميزة</div>
                            <div class="fs-2">{{ $stats['featured_categories'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="fw-semibold mb-2">الأكثر استخدامًا</div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>التصنيف</th>
                                <th>عدد المشاريع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($stats['top_categories'] ?? []) as $row)
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->projects_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
