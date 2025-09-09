@extends('facility.financial.layout')

@section('title', __('facility_management.contracts_management'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('facility_management.contracts_management') }}</h1>
        <p class="text-muted mb-0">{{ __('facility_management.manage_facility_contracts') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" onclick="exportContracts()">
            <i class="bi bi-download"></i>
            {{ __('facility_management.export') }}
        </button>
        <button class="btn btn-outline-secondary" onclick="refreshData()">
            <i class="bi bi-arrow-clockwise"></i>
            {{ __('facility_management.refresh') }}
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-primary mb-2">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['total']) }}</div>
                <div class="stats-label">{{ __('facility_management.total_contracts') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-warning mb-2">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['draft']) }}</div>
                <div class="stats-label">{{ __('facility_management.pending_approval') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-success mb-2">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['active']) }}</div>
                <div class="stats-label">{{ __('facility_management.active_contracts') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-info mb-2">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['completed']) }}</div>
                <div class="stats-label">{{ __('facility_management.completed') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-danger mb-2">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['cancelled']) }}</div>
                <div class="stats-label">{{ __('facility_management.cancelled') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-dark mb-2">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['total_value']) }}</div>
                <div class="stats-label">{{ __('facility_management.total_value') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-success">
                    <i class="bi bi-arrow-down-circle"></i>
                    {{ __('facility_management.received_amount') }}
                </h6>
                <h3 class="text-success">{{ number_format($stats['received_amount']) }}</h3>
                <div class="progress mb-2" style="height: 8px;">
                    @php
                        $percentage = $stats['total_value'] > 0 ? ($stats['received_amount'] / $stats['total_value']) * 100 : 0;
                    @endphp
                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                </div>
                <small class="text-muted">{{ number_format($percentage, 1) }}% {{ __('facility_management.of_total_value') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-warning">
                    <i class="bi bi-arrow-up-circle"></i>
                    {{ __('facility_management.remaining_amount') }}
                </h6>
                <h3 class="text-warning">{{ number_format($stats['total_value'] - $stats['received_amount']) }}</h3>
                <div class="progress mb-2" style="height: 8px;">
                    @php
                        $remainingPercentage = $stats['total_value'] > 0 ? (($stats['total_value'] - $stats['received_amount']) / $stats['total_value']) * 100 : 0;
                    @endphp
                    <div class="progress-bar bg-warning" style="width: {{ $remainingPercentage }}%"></div>
                </div>
                <small class="text-muted">{{ number_format($remainingPercentage, 1) }}% {{ __('facility_management.pending_collection') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('facility.financial.contracts') }}" id="filtersForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">{{ __('facility_management.search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="{{ __('facility_management.search_contracts') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('facility_management.status') }}</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">{{ __('facility_management.all_statuses') }}</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                            {{ __('facility_management.pending_approval') }}
                        </option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            {{ __('facility_management.active') }}
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            {{ __('facility_management.completed') }}
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            {{ __('facility_management.cancelled') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="contract_type" class="form-label">{{ __('facility_management.contract_type') }}</label>
                    <select class="form-select" id="contract_type" name="contract_type">
                        <option value="">{{ __('facility_management.all_types') }}</option>
                        <option value="sale" {{ request('contract_type') == 'sale' ? 'selected' : '' }}>
                            {{ __('facility_management.sale') }}
                        </option>
                        <option value="monthly_rent" {{ request('contract_type') == 'monthly_rent' ? 'selected' : '' }}>
                            {{ __('facility_management.monthly_rent') }}
                        </option>
                        <option value="yearly_rent" {{ request('contract_type') == 'yearly_rent' ? 'selected' : '' }}>
                            {{ __('facility_management.yearly_rent') }}
                        </option>
                        <option value="daily_rent" {{ request('contract_type') == 'daily_rent' ? 'selected' : '' }}>
                            {{ __('facility_management.daily_rent') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">{{ __('facility_management.date_from') }}</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">{{ __('facility_management.date_to') }}</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="sort_by" class="form-label">{{ __('facility_management.sort_by') }}</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                            {{ __('facility_management.created_date') }}
                        </option>
                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>
                            {{ __('facility_management.total_amount') }}
                        </option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>
                            {{ __('facility_management.status') }}
                        </option>
                        <option value="contract_number" {{ request('sort_by') == 'contract_number' ? 'selected' : '' }}>
                            {{ __('facility_management.contract_number') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="sort_direction" class="form-label">{{ __('facility_management.sort_direction') }}</label>
                    <select class="form-select" id="sort_direction" name="sort_direction">
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>
                            {{ __('facility_management.descending') }}
                        </option>
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>
                            {{ __('facility_management.ascending') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-7 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i>
                        {{ __('facility_management.apply_filters') }}
                    </button>
                    <a href="{{ route('facility.financial.contracts') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                        {{ __('facility_management.clear_filters') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Contracts List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-file-earmark-text text-primary"></i>
            {{ __('facility_management.contracts_list') }}
            <span class="badge bg-primary">{{ $contracts->total() }}</span>
        </h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                <i class="bi bi-check-all"></i>
                {{ __('facility_management.select_all') }}
            </button>
            <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('approve')" disabled id="bulkApproveBtn">
                <i class="bi bi-check-circle"></i>
                {{ __('facility_management.bulk_approve') }}
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($contracts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onchange="selectAll()">
                            </th>
                            <th>{{ __('facility_management.contract_number') }}</th>
                            <th>{{ __('facility_management.client') }}</th>
                            <th>{{ __('facility_management.product') }}</th>
                            <th>{{ __('facility_management.type') }}</th>
                            <th>{{ __('facility_management.total_amount') }}</th>
                            <th>{{ __('facility_management.payment_progress') }}</th>
                            <th>{{ __('facility_management.status') }}</th>
                            <th>{{ __('facility_management.created_date') }}</th>
                            <th>{{ __('facility_management.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contracts as $contract)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input contract-checkbox" 
                                       value="{{ $contract->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $contract->contract_number }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle me-2">
                                        {{ substr($contract->client->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $contract->client->name }}</div>
                                        <small class="text-muted">{{ $contract->client->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $contract->offer->product->name }}</div>
                                <small class="text-muted">{{ $contract->offer->product->location }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ __('facility_management.offer_type_' . $contract->offer->offer_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ number_format($contract->total_amount) }}</span>
                            </td>
                            <td>
                                @php
                                    $progress = $contract->total_amount > 0 ? ($contract->paid_amount / $contract->total_amount) * 100 : 0;
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $progress >= 100 ? 'success' : ($progress >= 50 ? 'warning' : 'info') }}" 
                                         style="width: {{ $progress }}%">
                                        {{ number_format($progress, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ number_format($contract->paid_amount) }} / {{ number_format($contract->total_amount) }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'draft' ? 'warning' : ($contract->status == 'completed' ? 'primary' : 'secondary')) }}">
                                    {{ __('facility_management.contract_status_' . $contract->status) }}
                                </span>
                                @if($contract->status == 'draft')
                                    <br><small class="text-warning">{{ __('facility_management.needs_approval') }}</small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $contract->created_at->format('d/m/Y') }}</small>
                                <br><small class="text-muted">{{ $contract->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('facility.financial.contract-details', $contract->id) }}" 
                                       class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" 
                                       title="{{ __('facility_management.view_details') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($contract->status == 'draft')
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="updateContractStatus({{ $contract->id }}, 'active')" 
                                                data-bs-toggle="tooltip" title="{{ __('facility_management.approve') }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="updateContractStatus({{ $contract->id }}, 'cancelled')" 
                                                data-bs-toggle="tooltip" title="{{ __('facility_management.reject') }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @endif
                                    @if($contract->status == 'active')
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="updateContractStatus({{ $contract->id }}, 'completed')" 
                                                data-bs-toggle="tooltip" title="{{ __('facility_management.mark_completed') }}">
                                            <i class="bi bi-check2-all"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $contracts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">{{ __('facility_management.no_contracts') }}</h4>
                <p class="text-muted">{{ __('facility_management.no_contracts_message') }}</p>
                <a href="{{ route('facility.financial.create-offer') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    {{ __('facility_management.create_offer_to_start') }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('facility_management.update_contract_status') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="contractId" name="contract_id">
                    <input type="hidden" id="newStatus" name="status">
                    
                    <div class="mb-3">
                        <label for="statusNotes" class="form-label">{{ __('facility_management.notes') }}</label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" 
                                  placeholder="{{ __('facility_management.optional_notes') }}"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <span id="statusWarning"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('facility_management.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">
                    {{ __('facility_management.confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select all contracts
function selectAll() {
    const checkboxes = document.querySelectorAll('.contract-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const isChecked = selectAllCheckbox ? selectAllCheckbox.checked : true;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    
    updateBulkActions();
}

// Update bulk actions
function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.contract-checkbox:checked');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    
    if (selectedCheckboxes.length > 0) {
        bulkApproveBtn.disabled = false;
        bulkApproveBtn.innerHTML = `<i class="bi bi-check-circle"></i> ${selectedCheckboxes.length} {{ __('facility_management.selected') }}`;
    } else {
        bulkApproveBtn.disabled = true;
        bulkApproveBtn.innerHTML = '<i class="bi bi-check-circle"></i> {{ __('facility_management.bulk_approve') }}';
    }
}

// Update contract status
function updateContractStatus(contractId, status) {
    // Set modal data
    document.getElementById('contractId').value = contractId;
    document.getElementById('newStatus').value = status;
    
    // Set warning message
    const warningElement = document.getElementById('statusWarning');
    let warningMessage = '';
    
    switch(status) {
        case 'active':
            warningMessage = '{{ __('facility_management.approve_contract_warning') }}';
            break;
        case 'cancelled':
            warningMessage = '{{ __('facility_management.cancel_contract_warning') }}';
            break;
        case 'completed':
            warningMessage = '{{ __('facility_management.complete_contract_warning') }}';
            break;
    }
    
    warningElement.textContent = warningMessage;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
    modal.show();
}

// Confirm status update
function confirmStatusUpdate() {
    const contractId = document.getElementById('contractId').value;
    const status = document.getElementById('newStatus').value;
    const notes = document.getElementById('statusNotes').value;
    
    showLoading();
    
    fetch(`{{ route('facility.financial.update-contract-status', ':id') }}`.replace(':id', contractId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        // Hide modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal'));
        modal.hide();
        
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', '{{ __('facility_management.error_occurred') }}');
    });
}

// Bulk action
function bulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.contract-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        showToast('warning', '{{ __('facility_management.select_contracts_first') }}');
        return;
    }

    if (!confirm(`{{ __('facility_management.confirm_bulk_action') }} ${selectedCheckboxes.length} {{ __('facility_management.contracts') }}?`)) {
        return;
    }

    const contractIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    showLoading();
    
    // For now, just update each one individually
    const promises = contractIds.map(id => 
        fetch(`{{ route('facility.financial.update-contract-status', ':id') }}`.replace(':id', id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: 'active',
                notes: 'Bulk approval'
            })
        })
    );

    Promise.all(promises)
        .then(() => {
            hideLoading();
            showToast('success', '{{ __('facility_management.bulk_action_completed') }}');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(error => {
            hideLoading();
            showToast('error', '{{ __('facility_management.error_occurred') }}');
        });
}

// Export contracts
function exportContracts() {
    showLoading();
    
    // Get current filters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'true');
    
    const url = '{{ route('facility.financial.contracts') }}?' + params.toString();
    
    // Create temporary link and click it
    const link = document.createElement('a');
    link.href = url;
    link.download = 'contracts-export.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    hideLoading();
    showToast('success', '{{ __('facility_management.export_started') }}');
}

// Refresh data
function refreshData() {
    showLoading();
    location.reload();
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filtersForm select, #filtersForm input[type="date"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            setTimeout(() => {
                document.getElementById('filtersForm').submit();
            }, 300);
        });
    });

    // Real-time search
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filtersForm').submit();
        }, 500);
    });
});
</script>
@endpush

@push('styles')
<style>
.stats-card-sm {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card-sm:hover {
    transform: translateY(-2px);
}

.stats-icon {
    font-size: 1.5rem;
}

.stats-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.stats-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 0.25rem;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

@media (max-width: 768px) {
    .stats-value {
        font-size: 1.25rem;
    }
    
    .stats-label {
        font-size: 0.7rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 0.125rem 0;
    }
}
</style>
@endpush
