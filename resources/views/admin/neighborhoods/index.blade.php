@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الأحياء</h5>
            <a href="{{ route('admin.neighborhoods.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة حي
            </a>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="city_id" class="form-select" onchange="this.form.submit()">
                        <option value="">كل المدن</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>المدينة</th>
                        <th>نشط</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($neighborhoods as $neighborhood)
                        <tr>
                            <td>{{ $neighborhood->id }}</td>
                            <td>{{ $neighborhood->name }}</td>
                            <td>{{ $neighborhood->city->name ?? '-' }}</td>
                            <td>{!! $neighborhood->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.neighborhoods.edit', $neighborhood) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.neighborhoods.destroy', $neighborhood) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
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
            {{ $neighborhoods->links() }}
        </div>
    </div>
</div>
@endsection
