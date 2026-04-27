@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">لوحة المنشأة</h1>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">إجمالي الإعلانات</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalProducts) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">المميزة</div>
                    <div class="fs-3 fw-bold">{{ number_format($featuredProducts) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">الموثقة</div>
                    <div class="fs-3 fw-bold">{{ number_format($verifiedProducts) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">عدد المنشآت</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalFacilities) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">أفضل الإعلانات</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>المنشأة</th>
                            <th>المشاهدات</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->address }}</td>
                                <td>{{ optional($p->facility)->name }}</td>
                                <td>{{ number_format($p->views_count ?? 0) }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('public.products.show', $p->id) }}" target="_blank">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
