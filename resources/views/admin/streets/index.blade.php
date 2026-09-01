@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الشوارع</h5>
            <a href="{{ route('admin.streets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة شارع
            </a>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="neighborhood_id" class="form-select" onchange="this.form.submit()">
                        <option value="">كل الأحياء</option>
                        @foreach($neighborhoods as $neighborhood)
                            <option value="{{ $neighborhood->id }}" {{ request('neighborhood_id') == $neighborhood->id ? 'selected' : '' }}>{{ $neighborhood->city->name ?? '' }} - {{ $neighborhood->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الحي</th>
                        <th>المدينة</th>
                        <th>نشط</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($streets as $street)
                        <tr>
                            <td>{{ $street->id }}</td>
                            <td>{{ $street->name }}</td>
                            <td>{{ $street->neighborhood->name ?? '-' }}</td>
                            <td>{{ $street->neighborhood->city->name ?? '-' }}</td>
                            <td>{!! $street->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.streets.edit', $street) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.streets.destroy', $street) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
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
            {{ $streets->links() }}
        </div>
    </div>
</div>
@endsection
