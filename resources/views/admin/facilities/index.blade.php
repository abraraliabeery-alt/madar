@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة المنشآت</h5>
            <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة منشأة جديدة
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter" name="category_id">
                        <option value="">كل الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" name="search" value="{{ request('search') }}" placeholder="بحث...">
                        <button class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-secondary" id="resetFilters">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Facilities Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>الشعار</th>
                            <th>الاسم</th>
                            <th>المالك</th>
                            <th>الفئة</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>التفعيل</th>
                            <th>التحقق</th>
                            <th>مميزة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facilities as $facility)
                        <tr>
                            <td>
                                @if($facility->logo)
                                    <img src="{{ Storage::url($facility->logo) }}" alt="logo" width="40" class="rounded">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $facility->name }}</h6>
                                    <small class="text-muted">{{ $facility->email }}</small>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $facility->owner) }}">
                                    {{ $facility->owner->name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $facility->category->name }}</span>
                            </td>
                            <td>{{ Str::limit($facility->address, 30) }}</td>
                            <td>
                                @if($facility->status)
                                    <span class="badge bg-{{ $facility->status->color }}">
                                        {{ $facility->status->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->is_verified)
                                    <span class="badge bg-success">متحقق منه</span>
                                @else
                                    <span class="badge bg-warning">غير متحقق منه</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->is_featured)
                                    <span class="badge bg-warning">مميزة</span>
                                @else
                                    <span class="badge bg-secondary">غير مميزة</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.facilities.show', $facility) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.facilities.toggle-status', $facility) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $facility->is_active ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas {{ $facility->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.facilities.toggle-verification', $facility) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $facility->is_verified ? 'btn-warning' : 'btn-info' }}">
                                            <i class="fas {{ $facility->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.facilities.toggle-featured', $facility) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $facility->is_featured ? 'btn-secondary' : 'btn-warning' }}">
                                            <i class="fas fa-star"></i>
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
            <div class="mt-4">
                {{ $facilities->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.datatable th, .datatable td {
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        },
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 9] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle filters
    function applyFilters() {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        // Update parameters
        params.set('category_id', $('#categoryFilter').val() || '');
        params.set('search', $('#searchInput').val() || '');

        // Redirect with new parameters
        window.location.href = `${url.pathname}?${params.toString()}`;
    }

    // Bind events
    $('#categoryFilter').change(applyFilters);
    $('#searchBtn').click(applyFilters);

    // Reset filters
    $('#resetFilters').click(function() {
        window.location.href = window.location.pathname;
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكن التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف المنشأة',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
