@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة المدن</h5>
            <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة مدينة
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الترتيب</th>
                        <th>مميز</th>
                        <th>نشط</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->sort_order }}</td>
                            <td>{!! $city->is_featured ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}</td>
                            <td>{!! $city->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $cities->links() }}
        </div>
    </div>
</div>
@endsection
