@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" data-intro="{{ __('admin.tour.users_header_desc') }}" data-step="14">
            <h5 class="mb-0">{{ __('admin.users.title') }}</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('admin.users.add_new') }}
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4" data-intro="{{ __('admin.tour.users_filters_desc') }}" data-step="15">
                <div class="col-12 col-md-6 col-lg-4">
                    <select class="form-select" id="roleFilter" name="role_id">
                        <option value="">{{ __('admin.users.all_roles') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->getTranslatedDisplayName() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <select class="form-select" id="facilityFilter" name="facility_id">
                        <option value="">{{ __('admin.users.all_facilities') }}</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.users.search_placeholder') }}">
                        <button class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-secondary" id="resetFilters">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-responsive" data-intro="{{ __('admin.tour.users_table_desc') }}" data-step="16">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell">{{ __('admin.users.avatar') }}</th>
                            <th>{{ __('admin.users.name') }}</th>
                            <th class="d-none d-lg-table-cell">{{ __('admin.users.email') }}</th>
                            <th class="d-none d-md-table-cell">{{ __('admin.users.phone') }}</th>
                            <th class="d-none d-lg-table-cell">{{ __('admin.users.role') }}</th>
                            <th class="d-none d-lg-table-cell">{{ __('admin.users.facility') }}</th>
                            <th class="d-none d-md-table-cell">{{ __('admin.users.status') }}</th>
                            <th>{{ __('admin.users.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="d-none d-md-table-cell">
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded-circle" width="40">
                                @else
                                    <div class="avatar-placeholder rounded-circle" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2 d-md-none">
                                        @if($user->avatar)
                                            <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded-circle" width="32">
                                        @else
                                            <div class="avatar-placeholder rounded-circle" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="small text-muted d-md-none">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">{{ $user->email }}</td>
                            <td class="d-none d-md-table-cell">{{ $user->phone_number }}</td>
                            <td class="d-none d-lg-table-cell">
                                @foreach($user->roles as $role)
                                    <span class="badge" style="background-color: transparent !important; color: var(--brand-brown) !important;">{{ $role->getTranslatedDisplayName() }}</span>
                                @endforeach
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @foreach($user->facilities as $facility)
                                    <a href="{{ route('admin.facilities.show', $facility) }}" class="badge text-decoration-none" style="background-color: var(--brand-brown) !important; color: #ffffff !important;">
                                        {{ $facility->name }}
                                    </a>
                                @endforeach
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($user->is_active)
                                    <span class="badge" style="background-color: var(--brand-brown) !important; color: #ffffff !important;">{{ __('admin.status.active') }}</span>
                                @else
                                    <span class="badge" style="background-color: var(--brand-brown) !important; color: #ffffff !important;">{{ __('admin.status.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 action-buttons">
                                    <!-- Primary Actions Row -->
                                    <div class="d-flex gap-1 mb-1">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="btn btn-sm btn-outline-info"
                                           data-bs-toggle="tooltip"
                                           title="{{ __('admin.actions.view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           data-bs-toggle="tooltip"
                                           title="{{ __('admin.users.edit_user') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-confirm"
                                                data-bs-toggle="tooltip"
                                                title="{{ __('admin.users.delete_user') }}"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Status Toggle Row -->
                                    <div class="d-flex gap-1 mb-1">
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $user->is_active ? __('admin.users.deactivate') : __('admin.users.activate_user') }}">
                                                <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
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
            <div class="pagination-container">
                {{ $users->withQueryString()->links() }}
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
    background-color: var(--brand-brown);
    border-color: var(--brand-brown);
    color: white;
}

.action-buttons .btn-outline-warning:hover {
    background-color: var(--brand-brown);
    border-color: var(--brand-brown);
    color: white;
}

.action-buttons .btn-outline-danger:hover {
    background-color: var(--brand-brown);
    border-color: var(--brand-brown);
    color: white;
}

/* Status Toggle Row */
.action-buttons .btn-outline-success:hover {
    background-color: var(--brand-brown);
    border-color: var(--brand-brown);
    color: white;
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

/* Avatar placeholder styling */
.avatar-placeholder {
    background: linear-gradient(135deg, var(--brand-brown) 0%, rgba(var(--brand-brown-rgb), 0.7) 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder i {
    font-size: 16px;
}

/* Badge colors override to use brand-brown */
.badge.bg-primary,
.badge.bg-info,
.badge.bg-success,
.badge.bg-danger,
.badge.bg-warning {
    background-color: var(--brand-brown) !important;
    color: #ffffff !important;
}

/* DataTables export buttons override to use brand-brown */
.dt-buttons .btn-secondary {
    background-color: var(--brand-brown) !important;
    border-color: var(--brand-brown) !important;
    color: #ffffff !important;
}

.dt-buttons .btn-secondary:hover {
    background-color: var(--brand-brown) !important;
    border-color: var(--brand-brown) !important;
    color: #ffffff !important;
    opacity: 0.9;
}

/* DataTables export buttons in dark mode */
body.dark-mode .dt-buttons .btn-secondary,
html[data-theme="dark"] .dt-buttons .btn-secondary {
    background-color: #2d2d2d !important;
    border-color: #2d2d2d !important;
    color: #ffffff !important;
}

body.dark-mode .dt-buttons .btn-secondary:hover,
html[data-theme="dark"] .dt-buttons .btn-secondary:hover {
    background-color: #2d2d2d !important;
    border-color: #2d2d2d !important;
    color: #ffffff !important;
    opacity: 0.9;
}

/* DataTables wrapper background removal */
.dataTables_wrapper.dt-bootstrap5.no-footer {
    background-color: transparent !important;
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
    .form-select,
    .form-control {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .row.g-3 > [class*="col-"] {
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
    let table = $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        },
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 7] }
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

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle filters
    function applyFilters() {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        // Update parameters
        params.set('role_id', $('#roleFilter').val() || '');
        params.set('facility_id', $('#facilityFilter').val() || '');
        params.set('search', $('#searchInput').val() || '');

        // Redirect with new parameters
        window.location.href = `${url.pathname}?${params.toString()}`;
    }

    // Bind events
    $('#roleFilter, #facilityFilter').change(applyFilters);
    $('#searchBtn').click(applyFilters);

    // Reset filters
    $('#resetFilters').click(function() {
        window.location.href = window.location.pathname;
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let userId = $(this).data('user-id');
        let userName = $(this).data('user-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف المستخدم "${userName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0f172a',
            cancelButtonColor: '#0f172a',
            confirmButtonText: 'نعم، احذف المستخدم',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/users/${userId}`
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
