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

            <!-- Categories Hierarchy View -->
            <div class="row g-4">
                <!-- Main Categories -->
                <div class="col-12">
                    <h5 class="mb-3">
                        <i class="fas fa-folder-open text-primary me-2"></i>
                        الفئات الرئيسية
                    </h5>
                    <div class="row g-3">
                        @foreach($categories->where('parent_id', null) as $mainCategory)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 category-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($mainCategory->icon)
                                            @if(Str::startsWith($mainCategory->icon, 'fas ') || Str::startsWith($mainCategory->icon, 'fa ') || Str::startsWith($mainCategory->icon, 'fab '))
                                                <i class="{{ $mainCategory->icon }} fa-2x text-primary me-3"></i>
                                            @else
                                                <img src="{{ asset($mainCategory->icon) }}" alt="icon" width="40" class="me-3">
                                            @endif
                                        @else
                                            <i class="fas fa-folder fa-2x text-muted me-3"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $mainCategory->name }}</h6>
                                            <div class="d-flex gap-2">
                                                @if($mainCategory->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                                @if($mainCategory->is_featured)
                                                    <span class="badge bg-warning">مميزة</span>
                                                @endif
                                                @if($mainCategory->translations->count() > 0)
                                                    <span class="badge bg-info" title="لدى هذه الفئة {{ $mainCategory->translations->count() }} ترجمة">
                                                        <i class="fas fa-language me-1"></i>{{ $mainCategory->translations->count() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($mainCategory->description)
                                        <p class="text-muted small mb-3">{{ Str::limit($mainCategory->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">
                                            <i class="fas fa-box me-1"></i>
                                            {{ $mainCategory->products_count }} منتج
                                        </span>
                                        <span class="text-muted">
                                            <i class="fas fa-sitemap me-1"></i>
                                            {{ $mainCategory->children->count() }} فئة فرعية
                                        </span>
                                    </div>
                                    
                                    <!-- Subcategories Preview -->
                                    @if($mainCategory->children->count() > 0)
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-2">الفئات الفرعية:</small>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($mainCategory->children->take(3) as $child)
                                                    <span class="badge bg-light text-dark">{{ $child->name }}</span>
                                                @endforeach
                                                @if($mainCategory->children->count() > 3)
                                                    <span class="badge bg-secondary">+{{ $mainCategory->children->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.categories.show', $mainCategory) }}" 
                                           class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-eye me-1"></i>عرض
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $mainCategory) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($mainCategory->products_count == 0 && $mainCategory->children->count() == 0)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-confirm"
                                                    data-category-id="{{ $mainCategory->id }}"
                                                    data-category-name="{{ $mainCategory->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Subcategories -->
                @if($categories->where('parent_id', '!=', null)->count() > 0)
                <div class="col-12">
                    <h5 class="mb-3 mt-4">
                        <i class="fas fa-folder text-info me-2"></i>
                        الفئات الفرعية
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الأيقونة</th>
                                    <th>الاسم</th>
                                    <th>الفئة الأب</th>
                                    <th>عدد المنتجات</th>
                                    <th>الحالة</th>
                                    <th>الترجمات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories->where('parent_id', '!=', null) as $subCategory)
                                <tr>
                                    <td>
                                        @if($subCategory->icon)
                                            @if(Str::startsWith($subCategory->icon, 'fas ') || Str::startsWith($subCategory->icon, 'fa ') || Str::startsWith($subCategory->icon, 'fab '))
                                                <i class="{{ $subCategory->icon }} fa-lg text-primary"></i>
                                            @else
                                                <img src="{{ asset($subCategory->icon) }}" alt="icon" width="30">
                                            @endif
                                        @else
                                            <i class="fas fa-folder text-muted"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($subCategory->image)
                                                <img src="{{ asset($subCategory->image) }}" alt="category" class="rounded me-2" width="40">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $subCategory->name }}</h6>
                                                @if($subCategory->description)
                                                    <small class="text-muted">{{ Str::limit($subCategory->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.categories.show', $subCategory->parent) }}" 
                                           class="badge bg-info text-decoration-none">
                                            {{ $subCategory->parent->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $subCategory->products_count }}</span>
                                    </td>
                                    <td>
                                        @if($subCategory->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subCategory->translations->count() > 0)
                                            <span class="badge bg-info" title="لدى هذه الفئة {{ $subCategory->translations->count() }} ترجمة">
                                                <i class="fas fa-language me-1"></i>{{ $subCategory->translations->count() }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">لا توجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.categories.show', $subCategory) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $subCategory) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($subCategory->products_count == 0)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger delete-confirm"
                                                        data-category-id="{{ $subCategory->id }}"
                                                        data-category-name="{{ $subCategory->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
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
/* Category Cards Styling */
.category-card {
    transition: all 0.3s ease-in-out;
    border: 1px solid #e9ecef;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.category-card .card-body {
    padding: 1.25rem;
}

.category-card h6 {
    color: #2c3e50;
    font-weight: 600;
}

.category-card .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

/* Statistics Cards */
.statistics-cards .card {
    transition: all 0.3s ease-in-out;
}

.statistics-cards .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Table Styling */
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

/* Badge Styling */
.badge.bg-light {
    background-color: #e9ecef !important;
    color: #495057 !important;
    border: 1px solid #dee2e6;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
}

/* Translation badge styling */
.badge.bg-info[title] {
    cursor: help;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-card .card-body {
        padding: 1rem;
    }
    
    .category-card h6 {
        font-size: 1rem;
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

@media (max-width: 576px) {
    .row.g-4 > [class*="col-"] {
        margin-bottom: 1rem;
    }
    
    .statistics-cards .card {
        margin-bottom: 1rem;
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
