@extends('facility.financial.layout')

@section('title', __('facility_management.offers_management'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('facility_management.offers_management') }}</h1>
        <p class="text-muted mb-0">{{ __('facility_management.manage_property_offers') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="toggleViewMode()">
            <i class="bi bi-grid-3x3-gap" id="viewModeIcon"></i>
            <span id="viewModeText">{{ __('facility_management.grid_view') }}</span>
        </button>
        <a href="{{ route('facility.financial.create-offer') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            {{ __('facility_management.create_offer') }}
        </a>
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
                    <i class="bi bi-tags"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['total']) }}</div>
                <div class="stats-label">{{ __('facility_management.total_offers') }}</div>
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
                <div class="stats-label">{{ __('facility_management.active_offers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-secondary mb-2">
                    <i class="bi bi-pause-circle"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['inactive']) }}</div>
                <div class="stats-label">{{ __('facility_management.inactive_offers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-info mb-2">
                    <i class="bi bi-house"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['sale_offers']) }}</div>
                <div class="stats-label">{{ __('facility_management.sale_offers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-warning mb-2">
                    <i class="bi bi-calendar"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['rent_offers']) }}</div>
                <div class="stats-label">{{ __('facility_management.rent_offers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card stats-card-sm">
            <div class="card-body text-center">
                <div class="stats-icon text-dark mb-2">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['avg_price']) }}</div>
                <div class="stats-label">{{ __('facility_management.avg_price') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('facility.financial.offers') }}" id="filtersForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">{{ __('facility_management.search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="{{ __('facility_management.search_offers') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('facility_management.status') }}</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">{{ __('facility_management.all_statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            {{ __('facility_management.active') }}
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            {{ __('facility_management.inactive') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="offer_type" class="form-label">{{ __('facility_management.offer_type') }}</label>
                    <select class="form-select" id="offer_type" name="offer_type">
                        <option value="">{{ __('facility_management.all_types') }}</option>
                        <option value="sale" {{ request('offer_type') == 'sale' ? 'selected' : '' }}>
                            {{ __('facility_management.sale') }}
                        </option>
                        <option value="monthly_rent" {{ request('offer_type') == 'monthly_rent' ? 'selected' : '' }}>
                            {{ __('facility_management.monthly_rent') }}
                        </option>
                        <option value="yearly_rent" {{ request('offer_type') == 'yearly_rent' ? 'selected' : '' }}>
                            {{ __('facility_management.yearly_rent') }}
                        </option>
                        <option value="daily_rent" {{ request('offer_type') == 'daily_rent' ? 'selected' : '' }}>
                            {{ __('facility_management.daily_rent') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="price_min" class="form-label">{{ __('facility_management.min_price') }}</label>
                    <input type="number" class="form-control" id="price_min" name="price_min" 
                           value="{{ request('price_min') }}" min="0">
                </div>

                <div class="col-md-2">
                    <label for="price_max" class="form-label">{{ __('facility_management.max_price') }}</label>
                    <input type="number" class="form-control" id="price_max" name="price_max" 
                           value="{{ request('price_max') }}" min="0">
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
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>
                            {{ __('facility_management.price') }}
                        </option>
                        <option value="offer_type" {{ request('sort_by') == 'offer_type' ? 'selected' : '' }}>
                            {{ __('facility_management.offer_type') }}
                        </option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>
                            {{ __('facility_management.status') }}
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
                    <a href="{{ route('facility.financial.offers') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                        {{ __('facility_management.clear_filters') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Offers List/Grid -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-tags text-primary"></i>
            {{ __('facility_management.offers_list') }}
            <span class="badge bg-primary">{{ $offers->total() }}</span>
        </h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                <i class="bi bi-check-all"></i>
                {{ __('facility_management.select_all') }}
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('deactivate')" disabled id="bulkActionBtn">
                <i class="bi bi-pause-circle"></i>
                {{ __('facility_management.bulk_deactivate') }}
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($offers->count() > 0)
            <!-- Grid View -->
            <div id="gridView" class="row">
                @foreach($offers as $offer)
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4 offer-item">
                    <div class="card offer-card h-100">
                        <div class="position-relative">
                            @if($offer->product->images->count() > 0)
                                <img src="{{ $offer->product->images->first()->url }}" 
                                     class="card-img-top offer-image" alt="{{ $offer->product->name }}">
                            @else
                                <div class="card-img-top offer-image-placeholder d-flex align-items-center justify-content-center">
                                    <i class="bi bi-house text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <span class="position-absolute top-0 start-0 m-2 badge bg-{{ $offer->status == 'active' ? 'success' : 'secondary' }}">
                                {{ __('facility_management.status_' . $offer->status) }}
                            </span>
                            
                            <!-- Type Badge -->
                            <span class="position-absolute top-0 end-0 m-2 badge bg-info">
                                {{ __('facility_management.offer_type_' . $offer->offer_type) }}
                            </span>

                            <!-- Checkbox -->
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <input type="checkbox" class="form-check-input offer-checkbox" 
                                       value="{{ $offer->id }}" onchange="updateBulkActions()">
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title">{{ $offer->product->name }}</h6>
                            <p class="card-text text-muted small">
                                <i class="bi bi-geo-alt"></i>
                                {{ $offer->product->location }}
                            </p>
                            
                            <div class="offer-details">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">{{ __('facility_management.price') }}</small>
                                        <div class="fw-bold">{{ number_format($offer->price) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">{{ __('facility_management.commission') }}</small>
                                        <div class="fw-bold">{{ $offer->commission_rate }}%</div>
                                    </div>
                                </div>

                                @if($offer->deposit_amount)
                                <div class="mb-3">
                                    <small class="text-muted">{{ __('facility_management.deposit') }}</small>
                                    <div class="fw-bold text-warning">{{ number_format($offer->deposit_amount) }}</div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <small class="text-muted">{{ __('facility_management.valid_period') }}</small>
                                    <div class="small">
                                        {{ $offer->valid_from->format('d/m/Y') }}
                                        @if($offer->valid_to)
                                            - {{ $offer->valid_to->format('d/m/Y') }}
                                        @else
                                            - {{ __('facility_management.open_ended') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ __('facility_management.created') }}: {{ $offer->created_at->format('d/m/Y') }}
                                </small>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('facility.financial.edit-offer', $offer->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-{{ $offer->status == 'active' ? 'warning' : 'success' }} btn-sm"
                                            onclick="toggleOfferStatus({{ $offer->id }})">
                                        <i class="bi bi-{{ $offer->status == 'active' ? 'pause' : 'play' }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- List View (Hidden by default) -->
            <div id="listView" class="d-none">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onchange="selectAll()">
                                </th>
                                <th>{{ __('facility_management.product') }}</th>
                                <th>{{ __('facility_management.type') }}</th>
                                <th>{{ __('facility_management.price') }}</th>
                                <th>{{ __('facility_management.commission') }}</th>
                                <th>{{ __('facility_management.status') }}</th>
                                <th>{{ __('facility_management.valid_until') }}</th>
                                <th>{{ __('facility_management.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offers as $offer)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input offer-checkbox" 
                                           value="{{ $offer->id }}" onchange="updateBulkActions()">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($offer->product->images->count() > 0)
                                            <img src="{{ $offer->product->images->first()->url }}" 
                                                 class="offer-thumb me-3" alt="{{ $offer->product->name }}">
                                        @else
                                            <div class="offer-thumb-placeholder me-3">
                                                <i class="bi bi-house"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $offer->product->name }}</div>
                                            <small class="text-muted">{{ $offer->product->location }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ __('facility_management.offer_type_' . $offer->offer_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ number_format($offer->price) }}</span>
                                    @if($offer->deposit_amount)
                                        <br><small class="text-warning">
                                            + {{ number_format($offer->deposit_amount) }} {{ __('facility_management.deposit') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $offer->commission_rate }}%</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $offer->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ __('facility_management.status_' . $offer->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($offer->valid_to)
                                        {{ $offer->valid_to->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">{{ __('facility_management.open_ended') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('facility.financial.edit-offer', $offer->id) }}" 
                                           class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" 
                                           title="{{ __('facility_management.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-{{ $offer->status == 'active' ? 'warning' : 'success' }} btn-sm"
                                                onclick="toggleOfferStatus({{ $offer->id }})" data-bs-toggle="tooltip"
                                                title="{{ $offer->status == 'active' ? __('facility_management.deactivate') : __('facility_management.activate') }}">
                                            <i class="bi bi-{{ $offer->status == 'active' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $offers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">{{ __('facility_management.no_offers') }}</h4>
                <p class="text-muted">{{ __('facility_management.no_offers_message') }}</p>
                <a href="{{ route('facility.financial.create-offer') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    {{ __('facility_management.create_first_offer') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentView = 'grid';

// Toggle view mode
function toggleViewMode() {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const icon = document.getElementById('viewModeIcon');
    const text = document.getElementById('viewModeText');

    if (currentView === 'grid') {
        gridView.classList.add('d-none');
        listView.classList.remove('d-none');
        icon.className = 'bi bi-list';
        text.textContent = '{{ __('facility_management.list_view') }}';
        currentView = 'list';
    } else {
        gridView.classList.remove('d-none');
        listView.classList.add('d-none');
        icon.className = 'bi bi-grid-3x3-gap';
        text.textContent = '{{ __('facility_management.grid_view') }}';
        currentView = 'grid';
    }
}

// Select all offers
function selectAll() {
    const checkboxes = document.querySelectorAll('.offer-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const isChecked = selectAllCheckbox ? selectAllCheckbox.checked : true;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    
    updateBulkActions();
}

// Update bulk actions
function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.offer-checkbox:checked');
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    
    if (selectedCheckboxes.length > 0) {
        bulkActionBtn.disabled = false;
        bulkActionBtn.innerHTML = `<i class="bi bi-pause-circle"></i> ${selectedCheckboxes.length} {{ __('facility_management.selected') }}`;
    } else {
        bulkActionBtn.disabled = true;
        bulkActionBtn.innerHTML = '<i class="bi bi-pause-circle"></i> {{ __('facility_management.bulk_deactivate') }}';
    }
}

// Toggle offer status
function toggleOfferStatus(offerId) {
    if (!confirm('{{ __('facility_management.confirm_status_change') }}')) {
        return;
    }

    showLoading();
    
    fetch(`{{ route('facility.financial.toggle-offer-status', ':id') }}`.replace(':id', offerId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
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
    const selectedCheckboxes = document.querySelectorAll('.offer-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        showToast('warning', '{{ __('facility_management.select_offers_first') }}');
        return;
    }

    if (!confirm(`{{ __('facility_management.confirm_bulk_action') }} ${selectedCheckboxes.length} {{ __('facility_management.offers') }}?`)) {
        return;
    }

    const offerIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    showLoading();
    
    // For now, just toggle each one individually
    // In a real implementation, you'd want a bulk endpoint
    const promises = offerIds.map(id => 
        fetch(`{{ route('facility.financial.toggle-offer-status', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
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

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filtersForm select, #filtersForm input[type="number"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Auto-submit after a delay to allow for multiple selections
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

.offer-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.offer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.offer-image {
    height: 200px;
    object-fit: cover;
}

.offer-image-placeholder {
    height: 200px;
    background: #f8f9fa;
}

.offer-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.offer-thumb-placeholder {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.offer-details {
    font-size: 0.875rem;
}

.card-footer {
    border-top: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .offer-image {
        height: 150px;
    }
    
    .stats-value {
        font-size: 1.25rem;
    }
    
    .stats-label {
        font-size: 0.7rem;
    }
}
</style>
@endpush
