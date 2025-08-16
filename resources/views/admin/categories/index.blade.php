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
            <div class="row g-4 mb-4 statistics-cards">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي الفئات</h6>
                                    <h3 class="mb-0">{{ $categories->total() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-tags"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الفئات النشطة</h6>
                                    <h3 class="mb-0">{{ $categories->where('is_active', true)->count() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">الفئات المميزة</h6>
                                    <h3 class="mb-0">{{ $categories->where('is_featured', true)->count() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي المنتجات</h6>
                                    <h3 class="mb-0">{{ $categories->sum('products_count') }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
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
                            <th class="d-none d-md-table-cell">#</th>
                            <th class="d-none d-md-table-cell">الأيقونة</th>
                            <th>الاسم</th>
                            <th class="d-none d-lg-table-cell">الفئة الأب</th>
                            <th class="d-none d-md-table-cell">عدد المنتجات</th>
                            <th class="d-none d-lg-table-cell">الترتيب</th>
                            <th class="d-none d-md-table-cell">الحالة</th>
                            <th class="d-none d-lg-table-cell">مميزة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $loop->iteration }}</td>
                            <td class="d-none d-md-table-cell">
                                @if($category->icon)
                                    @if(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'fa ') || Str::startsWith($category->icon, 'fab '))
                                        <!-- FontAwesome Icon -->
                                        <i class="{{ $category->icon }} fa-lg text-primary"></i>
                                    @else
                                        <!-- Image Icon -->
                                        <img src="{{ asset($category->icon) }}" alt="icon" width="30">
                                    @endif
                                @else
                                    <i class="fas fa-folder fa-2x text-muted"></i>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($category->image)
                                        <img src="{{ asset($category->image) }}" alt="category" class="rounded me-2" width="40">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                        @if($category->description)
                                            <small class="text-muted d-none d-md-block">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                        <div class="small text-muted d-md-none">
                                            @if($category->parent)
                                                <span class="badge bg-info">{{ $category->parent->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">فئة رئيسية</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($category->parent)
                                    <span class="badge bg-info">{{ $category->parent->name }}</span>
                                @else
                                    <span class="badge bg-secondary">فئة رئيسية</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-primary">{{ $category->products_count }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell">{{ $category->order ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">
                                @if($category->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($category->is_featured)
                                    <span class="badge bg-warning">مميزة</span>
                                @else
                                    <span class="badge bg-secondary">غير مميزة</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 action-buttons">
                                    <!-- Primary Actions Row -->
                                    <div class="d-flex gap-1 mb-1">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                           class="btn btn-sm btn-outline-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           data-bs-toggle="tooltip"
                                           title="تعديل الفئة">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-confirm"
                                                data-bs-toggle="tooltip"
                                                title="حذف الفئة"
                                                data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Status Toggle Row -->
                                    <div class="d-flex gap-1 mb-1">
                                        <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $category->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $category->is_active ? 'إلغاء التفعيل' : 'تفعيل الفئة' }}">
                                                <i class="fas {{ $category->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.categories.toggle-featured', $category) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $category->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $category->is_featured ? 'إلغاء التمييز' : 'تمييز الفئة' }}">
                                                <i class="fas fa-star {{ $category->is_featured ? 'text-warning' : '' }}"></i>
                                            </button>
                                        </form>
                                    </div>
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

/* Action Buttons Styling */
.action-buttons .btn {
    transition: all 0.2s ease-in-out;
    border-width: 1.5px;
    font-size: 0.875rem;
    min-width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-buttons .btn:active {
    transform: translateY(0);
}

/* Primary Actions Row */
.action-buttons .btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: white;
}

.action-buttons .btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: black;
}

.action-buttons .btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Status Toggle Row */
.action-buttons .btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.action-buttons .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Featured Star Button */
.action-buttons .btn .fa-star.text-warning {
    color: #ffc107 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-buttons .d-flex {
        flex-direction: column !important;
    }

    .action-buttons .btn {
        min-width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
}

/* Table cell padding for actions */
.datatable td:last-child {
    padding: 0.5rem;
    min-width: 120px;
}

/* Mobile-friendly table */
@media (max-width: 768px) {
    .datatable thead th,
    .datatable tbody td {
        padding: 0.5rem 0.25rem;
    }
    
    .datatable .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Filter improvements for mobile */
@media (max-width: 576px) {
    .row.g-4 > [class*="col-"] {
        margin-bottom: 1rem;
    }
    
    .statistics-cards .card {
        margin-bottom: 1rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .card-header .btn {
        width: 100%;
    }
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
        ],
        responsive: true,
        pageLength: window.innerWidth < 768 ? 10 : 15,
        scrollX: true,
        autoWidth: false
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
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

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let categoryId = $(this).data('category-id');
        let categoryName = $(this).data('category-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف الفئة "${categoryName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف الفئة',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/categories/${categoryId}`
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));

                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
