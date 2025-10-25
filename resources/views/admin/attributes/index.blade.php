@extends('admin.layouts.app')

@section('title', 'إدارة الخصائص')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إدارة الخصائص</h4>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة خاصية جديدة
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.attributes.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="q" class="form-control" placeholder="البحث في الخصائص..." value="{{ request('q') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="category_id" class="form-control">
                                        <option value="">جميع الفئات</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="type" class="form-control">
                                        <option value="">جميع الأنواع</option>
                                        <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>نص</option>
                                        <option value="number" {{ request('type') == 'number' ? 'selected' : '' }}>رقم</option>
                                        <option value="boolean" {{ request('type') == 'boolean' ? 'selected' : '' }}>نعم/لا</option>
                                        <option value="select" {{ request('type') == 'select' ? 'selected' : '' }}>قائمة</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="required" class="form-control">
                                        <option value="">جميع الخصائص</option>
                                        <option value="1" {{ request('required') == '1' ? 'selected' : '' }}>إلزامية</option>
                                        <option value="0" {{ request('required') == '0' ? 'selected' : '' }}>اختيارية</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-secondary me-2">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> مسح
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Attributes Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>النوع</th>
                                    <th>الفئة</th>
                                    <th>الحالة</th>
                                    <th>الرمز</th>
                                    <th>عدد المنتجات</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attributes as $attribute)
                                <tr>
                                    <td>{{ $attribute->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                                                                    @if($attribute->icon)
                                            <img src="{{ asset($attribute->icon) }}" alt="{{ $attribute->getTranslatedName() ?? 'N/A' }}" class="me-2" style="width: 20px; height: 20px;">
                                        @else
                                            <div class="avatar-placeholder me-2" style="width: 20px; height: 20px;">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                        @endif
                                            <span>{{ $attribute->getTranslatedName() ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $attribute->type }}</span>
                                    </td>
                                    <td>
                                        @if($attribute->category)
                                            <span class="badge bg-secondary">{{ $attribute->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->required)
                                            <span class="badge bg-danger">إلزامية</span>
                                        @else
                                            <span class="badge bg-warning">اختيارية</span>
                                        @endif
                                    </td>
                                    <td>{{ $attribute->Symbol ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $attribute->products_count }}</span>
                                    </td>
                                    <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                                                <td>
                                <div class="action-buttons">
                                    <!-- Mobile View: Compact Horizontal Layout -->
                                    <div class="d-flex d-md-none gap-1 flex-wrap">
                                        <a href="{{ route('admin.attributes.show', $attribute) }}"
                                           class="btn btn-sm btn-outline-info action-btn-mobile"
                                           data-bs-toggle="tooltip"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.attributes.edit', $attribute) }}"
                                           class="btn btn-sm btn-outline-warning action-btn-mobile"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm"
                                                data-bs-toggle="tooltip"
                                                title="حذف"
                                                data-attribute-id="{{ $attribute->id }}"
                                                data-attribute-name="{{ $attribute->getTranslatedName() ?? 'N/A' }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Toggle Required -->
                                        <form method="POST" action="{{ route('admin.attributes.toggle-required', $attribute) }}" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-secondary action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $attribute->required ? 'اختيارية' : 'إلزامية' }}">
                                                <i class="fas fa-toggle-{{ $attribute->required ? 'on' : 'off' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Desktop View: Vertical Layout -->
                                    <div class="d-none d-md-flex flex-column gap-1">
                                        <!-- Primary Actions Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <a href="{{ route('admin.attributes.show', $attribute) }}"
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip"
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.attributes.edit', $attribute) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               data-bs-toggle="tooltip"
                                               title="تعديل الخاصية">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-confirm"
                                                    data-bs-toggle="tooltip"
                                                    title="حذف الخاصية"
                                                    data-attribute-id="{{ $attribute->id }}"
                                                    data-attribute-name="{{ $attribute->getTranslatedName() ?? 'N/A' }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Toggle Required Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <form method="POST" action="{{ route('admin.attributes.toggle-required', $attribute) }}" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $attribute->required ? 'جعل اختيارية' : 'جعل إلزامية' }}">
                                                    <i class="fas fa-toggle-{{ $attribute->required ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد خصائص</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        {{ $attributes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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

/* Toggle Required Row */
.action-buttons .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Mobile Action Buttons */
.action-btn-mobile {
    min-width: 36px !important;
    height: 36px !important;
    padding: 0.375rem !important;
    font-size: 0.875rem !important;
    border-radius: 8px !important;
    margin: 1px !important;
}

.action-btn-mobile:hover {
    transform: scale(1.05) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
}

/* Mobile action buttons container */
@media (max-width: 767px) {
    .action-buttons {
        min-width: auto;
        padding: 0.25rem;
    }
    
    .action-buttons .d-flex {
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.25rem !important;
    }
    
    /* Ensure buttons don't wrap awkwardly */
    .action-btn-mobile {
        flex-shrink: 0;
    }
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
.table td:last-child {
    padding: 0.5rem;
    min-width: 120px;
}

@media (max-width: 767px) {
    .table td:last-child {
        min-width: auto;
        padding: 0.25rem;
        text-align: center;
    }
}

/* Avatar placeholder styling */
.avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder i {
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let attributeId = $(this).data('attribute-id');
        let attributeName = $(this).data('attribute-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف الخاصية "${attributeName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف الخاصية',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/attributes/${attributeId}`
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
