@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الأدوار</h5>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة دور جديد
            </a>
        </div>
        <div class="card-body">
            <!-- Roles Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell">#</th>
                            <th>الاسم</th>
                            <th class="d-none d-lg-table-cell">الوصف</th>
                            <th class="d-none d-md-table-cell">عدد المستخدمين</th>
                            <th class="d-none d-lg-table-cell">عدد الصلاحيات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $role->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $role->name }}</div>
                                        <div class="small text-muted d-md-none">
                                            @if($role->description)
                                                {{ Str::limit($role->description, 30) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">{{ Str::limit($role->description, 50) }}</td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-primary">{{ $role->users_count }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge bg-info">{{ $role->permissions_count }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Mobile View: Compact Horizontal Layout -->
                                    <div class="d-flex d-md-none gap-1 flex-wrap">
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-info action-btn-mobile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-warning action-btn-mobile">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($role->users_count == 0)
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    
                                    <!-- Desktop View: Vertical Layout -->
                                    <div class="d-none d-md-flex flex-column gap-1">
                                        <div class="d-flex gap-1 mb-1">
                                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($role->users_count == 0)
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-confirm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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
                {{ $roles->links() }}
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
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .card-header .btn {
        width: 100%;
    }
}

/* Table cell padding for actions */
.datatable td:last-child {
    padding: 0.5rem;
    min-width: 120px;
}

@media (max-width: 767px) {
    .datatable td:last-child {
        min-width: auto;
        padding: 0.25rem;
        text-align: center;
    }
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
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [5] }
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
            confirmButtonText: 'نعم، احذف الدور',
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
