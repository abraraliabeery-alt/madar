@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الفئات</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة فئة جديدة
            </a>
        </div>
        <div class="card-body">
            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي الفئات</h6>
                                    <h3 class="mb-0">{{ $categories->total() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-tags"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الفئات النشطة</h6>
                                    <h3 class="mb-0">{{ $categories->where('is_active', true)->count() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الفئات المميزة</h6>
                                    <h3 class="mb-0">{{ $categories->where('is_featured', true)->count() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي المنتجات</h6>
                                    <h3 class="mb-0">{{ $categories->sum('products_count') }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الأيقونة</th>
                            <th>الاسم</th>
                            <th>الفئة الأب</th>
                            <th>عدد المنتجات</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th>مميزة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($category->icon)
                                    <img src="{{ Storage::url($category->icon) }}" alt="icon" width="30">
                                @else
                                    <i class="fas fa-folder fa-2x text-muted"></i>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($category->image)
                                        <img src="{{ Storage::url($category->image) }}" alt="category" class="rounded me-2" width="40">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                        @if($category->description)
                                            <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($category->parent)
                                    <span class="badge bg-info">{{ $category->parent->name }}</span>
                                @else
                                    <span class="badge bg-secondary">فئة رئيسية</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products_count }}</span>
                            </td>
                            <td>{{ $category->sort_order ?? '-' }}</td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @if($category->is_featured)
                                    <span class="badge bg-warning">مميزة</span>
                                @else
                                    <span class="badge bg-secondary">غير مميزة</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas {{ $category->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.categories.toggle-featured', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $category->is_featured ? 'btn-secondary' : 'btn-warning' }}">
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
                {{ $categories->links() }}
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
    $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        },
        order: [[5, 'asc']],
        columnDefs: [
            { orderable: false, targets: [1, 8] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Initialize Sortable for categories reordering
    let sortable = new Sortable(document.querySelector('.datatable tbody'), {
        handle: '.sort-handle',
        animation: 150,
        onEnd: function(evt) {
            let categories = [];
            $('.datatable tbody tr').each(function(index) {
                categories.push({
                    id: $(this).data('id'),
                    sort_order: index
                });
            });

            // Update sort order via AJAX
            $.ajax({
                url: '{{ route("admin.categories.reorder") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    categories: categories
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
    });
});
</script>
@endpush
