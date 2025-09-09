@extends('facility.financial.layout')

@section('title', __('facility_management.financial_dashboard'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('facility_management.financial_dashboard') }}</h1>
        <p class="text-muted mb-0">{{ __('facility_management.manage_facility_finances') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" onclick="refreshData()">
            <i class="bi bi-arrow-clockwise"></i>
            {{ __('facility_management.refresh') }}
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
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-value">{{ number_format($stats['total_offers']) }}</div>
                        <div class="stats-label">{{ __('facility_management.total_offers') }}</div>
                    </div>
                    <div class="stats-icon">
                        <i class="bi bi-tags"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small>
                        <i class="bi bi-check-circle"></i>
                        {{ number_format($stats['active_offers']) }} {{ __('facility_management.active') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-value">{{ number_format($stats['total_contracts']) }}</div>
                        <div class="stats-label">{{ __('facility_management.total_contracts') }}</div>
                    </div>
                    <div class="stats-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small>
                        <i class="bi bi-play-circle"></i>
                        {{ number_format($stats['active_contracts']) }} {{ __('facility_management.active') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-value">{{ number_format($stats['total_revenue']) }}</div>
                        <div class="stats-label">{{ __('facility_management.total_revenue') }}</div>
                    </div>
                    <div class="stats-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small>
                        <i class="bi bi-graph-up"></i>
                        {{ __('facility_management.total_expected') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-value">{{ number_format($stats['received_payments']) }}</div>
                        <div class="stats-label">{{ __('facility_management.received_payments') }}</div>
                    </div>
                    <div class="stats-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small>
                        <i class="bi bi-clock"></i>
                        {{ number_format($stats['pending_payments']) }} {{ __('facility_management.pending') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts Section -->
@if(count($alerts) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                    {{ __('facility_management.important_alerts') }}
                </h5>
            </div>
            <div class="card-body">
                @foreach($alerts as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                    {{ $alert['message'] }}
                    @if(isset($alert['action_url']))
                        <a href="{{ $alert['action_url'] }}" class="btn btn-sm btn-outline-{{ $alert['type'] }} ms-2">
                            {{ $alert['action_text'] }}
                        </a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Charts and Analytics -->
<div class="row mb-4">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up text-primary"></i>
                    {{ __('facility_management.monthly_revenue') }}
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Success Rate -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-target text-success"></i>
                    {{ __('facility_management.success_rate') }}
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="progress-circle mb-3" data-percentage="{{ $successRate }}">
                    <div class="progress-value">{{ $successRate }}%</div>
                </div>
                <p class="text-muted mb-0">{{ __('facility_management.offers_to_contracts') }}</p>
                <small class="text-muted">
                    {{ number_format($stats['active_contracts']) }} / {{ number_format($stats['total_offers']) }}
                    {{ __('facility_management.contracts_from_offers') }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables -->
<div class="row">
    <!-- Pending Contracts -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock text-warning"></i>
                    {{ __('facility_management.pending_contracts') }}
                </h5>
                <a href="{{ route('facility.financial.contracts', ['status' => 'draft']) }}" class="btn btn-sm btn-outline-primary">
                    {{ __('facility_management.view_all') }}
                </a>
            </div>
            <div class="card-body">
                @if($pendingContracts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('facility_management.client') }}</th>
                                    <th>{{ __('facility_management.product') }}</th>
                                    <th>{{ __('facility_management.amount') }}</th>
                                    <th>{{ __('facility_management.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingContracts as $contract)
                                <tr>
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
                                        <small class="text-muted">{{ $contract->offer->offer_type }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ number_format($contract->total_amount) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('facility.financial.contract-details', $contract->id) }}" 
                                               class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                               title="{{ __('facility_management.view_details') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="updateContractStatus({{ $contract->id }}, 'active')"
                                                    data-bs-toggle="tooltip" title="{{ __('facility_management.approve') }}">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">{{ __('facility_management.no_pending_contracts') }}</h6>
                        <p class="text-muted">{{ __('facility_management.all_contracts_processed') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-credit-card text-success"></i>
                    {{ __('facility_management.recent_payments') }}
                </h5>
                <a href="{{ route('facility.financial.payments') }}" class="btn btn-sm btn-outline-primary">
                    {{ __('facility_management.view_all') }}
                </a>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('facility_management.client') }}</th>
                                    <th>{{ __('facility_management.amount') }}</th>
                                    <th>{{ __('facility_management.status') }}</th>
                                    <th>{{ __('facility_management.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-secondary text-white rounded-circle me-2">
                                                {{ substr($payment->contract->client->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $payment->contract->client->name }}</div>
                                                <small class="text-muted">{{ $payment->payment_method }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ number_format($payment->amount) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status == 'confirmed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ __('facility_management.payment_status_' . $payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $payment->payment_date->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">{{ __('facility_management.no_recent_payments') }}</h6>
                        <p class="text-muted">{{ __('facility_management.payments_will_appear_here') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Contracts -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-file-earmark-text text-primary"></i>
                    {{ __('facility_management.recent_contracts') }}
                </h5>
                <a href="{{ route('facility.financial.contracts') }}" class="btn btn-sm btn-outline-primary">
                    {{ __('facility_management.view_all') }}
                </a>
            </div>
            <div class="card-body">
                @if($recentContracts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('facility_management.contract_number') }}</th>
                                    <th>{{ __('facility_management.client') }}</th>
                                    <th>{{ __('facility_management.product') }}</th>
                                    <th>{{ __('facility_management.type') }}</th>
                                    <th>{{ __('facility_management.amount') }}</th>
                                    <th>{{ __('facility_management.status') }}</th>
                                    <th>{{ __('facility_management.created_date') }}</th>
                                    <th>{{ __('facility_management.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentContracts as $contract)
                                <tr>
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
                                        <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ __('facility_management.contract_status_' . $contract->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $contract->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('facility.financial.contract-details', $contract->id) }}" 
                                           class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                           title="{{ __('facility_management.view_details') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">{{ __('facility_management.no_contracts') }}</h6>
                        <p class="text-muted">{{ __('facility_management.contracts_will_appear_here') }}</p>
                        <a href="{{ route('facility.financial.create-offer') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            {{ __('facility_management.create_first_offer') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: '{{ __('facility_management.revenue') }}',
                data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('{{ app()->getLocale() }}').format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Progress Circle Animation
    const progressCircle = document.querySelector('.progress-circle');
    if (progressCircle) {
        const percentage = progressCircle.dataset.percentage;
        const circumference = 2 * Math.PI * 45; // radius = 45
        const offset = circumference - (percentage / 100) * circumference;
        
        const svg = `
            <svg width="120" height="120" class="progress-ring">
                <circle cx="60" cy="60" r="45" fill="transparent" stroke="#e9ecef" stroke-width="8"/>
                <circle cx="60" cy="60" r="45" fill="transparent" stroke="#198754" stroke-width="8"
                        stroke-dasharray="${circumference}" stroke-dashoffset="${offset}"
                        stroke-linecap="round" transform="rotate(-90 60 60)"/>
            </svg>
        `;
        
        progressCircle.innerHTML = svg + progressCircle.innerHTML;
    }
});

// Refresh data function
function refreshData() {
    showLoading();
    location.reload();
}

// Update contract status function
function updateContractStatus(contractId, status) {
    if (!confirm('{{ __('facility_management.confirm_status_change') }}')) {
        return;
    }

    showLoading();
    
    fetch(`{{ route('facility.financial.update-contract-status', ':id') }}`.replace(':id', contractId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: ''
        })
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
</script>
@endpush

@push('styles')
<style>
.stats-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.stats-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-top: 0.25rem;
}

.stats-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}

.stats-footer {
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.2);
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

.progress-circle {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.progress-value {
    position: absolute;
    font-size: 1.5rem;
    font-weight: 700;
    color: #198754;
}

.progress-ring circle {
    transition: stroke-dashoffset 0.5s ease-in-out;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
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
</style>
@endpush
