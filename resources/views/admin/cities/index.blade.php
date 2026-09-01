@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.cities.title') }}</h5>
            <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('admin.cities.create') }}
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin.cities.name') }}</th>
                        <th>{{ __('admin.cities.sort_order') }}</th>
                        <th>{{ __('admin.cities.is_featured') }}</th>
                        <th>{{ __('admin.cities.is_active') }}</th>
                        <th>{{ __('admin.cities.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->sort_order }}</td>
                            <td>{!! $city->is_featured ? '<span class="badge bg-success">' . __('admin.cities.is_featured') . '</span>' : '<span class="badge bg-secondary">-</span>' !!}</td>
                            <td>{!! $city->is_active ? '<span class="badge bg-success">' . __('admin.cities.is_active') . '</span>' : '<span class="badge bg-secondary">-</span>' !!}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="return confirm('{{ __('admin.common.confirm_delete') }}');">
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
