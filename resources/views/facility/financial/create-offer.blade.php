@extends('facility.financial.layout')

@section('title', __('facility_management.create_offer'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('facility_management.create_offer') }}</h1>
        <p class="text-muted mb-0">{{ __('facility_management.create_new_property_offer') }}</p>
    </div>
    <div>
        <a href="{{ route('facility.financial.offers') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            {{ __('facility_management.back_to_offers') }}
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <form method="POST" action="{{ route('facility.financial.store-offer') }}" id="createOfferForm">
            @csrf
            
            <!-- Product Selection -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-house text-primary"></i>
                        {{ __('facility_management.select_product') }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="mb-3">
                            <label for="product_id" class="form-label required">{{ __('facility_management.product') }}</label>
                            <select class="form-select @error('product_id') is-invalid @enderror" 
                                    id="product_id" name="product_id" required onchange="loadProductDetails()">
                                <option value="">{{ __('facility_management.select_product') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->location }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product Preview -->
                        <div id="productPreview" class="d-none">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img id="productImage" src="" class="img-fluid rounded" alt="Product Image">
                                            <div id="noImagePlaceholder" class="bg-secondary rounded d-flex align-items-center justify-content-center d-none" style="height: 150px;">
                                                <i class="bi bi-house text-white" style="font-size: 3rem;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 id="productName" class="mb-2"></h6>
                                            <p id="productLocation" class="text-muted mb-2"></p>
                                            <p id="productDescription" class="small text-muted"></p>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">{{ __('facility_management.category') }}</small>
                                                    <div id="productCategory" class="fw-semibold"></div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">{{ __('facility_management.features') }}</small>
                                                    <div id="productFeatures" class="fw-semibold"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            {{ __('facility_management.no_products_available') }}
                            <br>
                            <small>{{ __('facility_management.create_product_first_message') }}</small>
                        </div>
                        <a href="{{ route('facility.products.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            {{ __('facility_management.create_product') }}
                        </a>
                    @endif
                </div>
            </div>

            @if($products->count() > 0)
            <!-- Offer Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-tag text-success"></i>
                        {{ __('facility_management.offer_details') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="offer_type" class="form-label required">{{ __('facility_management.offer_type') }}</label>
                            <select class="form-select @error('offer_type') is-invalid @enderror" 
                                    id="offer_type" name="offer_type" required onchange="updateOfferTypeFields()">
                                <option value="">{{ __('facility_management.select_offer_type') }}</option>
                                <option value="sale" {{ old('offer_type') == 'sale' ? 'selected' : '' }}>
                                    {{ __('facility_management.sale') }}
                                </option>
                                <option value="monthly_rent" {{ old('offer_type') == 'monthly_rent' ? 'selected' : '' }}>
                                    {{ __('facility_management.monthly_rent') }}
                                </option>
                                <option value="yearly_rent" {{ old('offer_type') == 'yearly_rent' ? 'selected' : '' }}>
                                    {{ __('facility_management.yearly_rent') }}
                                </option>
                                <option value="daily_rent" {{ old('offer_type') == 'daily_rent' ? 'selected' : '' }}>
                                    {{ __('facility_management.daily_rent') }}
                                </option>
                            </select>
                            @error('offer_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label required">{{ __('facility_management.status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                    {{ __('facility_management.active') }}
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                    {{ __('facility_management.inactive') }}
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="price" class="form-label required">
                                <span id="priceLabel">{{ __('facility_management.price') }}</span>
                                <small class="text-muted" id="priceHint"></small>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">{{ __('facility_management.currency') }}</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       min="0" step="0.01" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="commission_rate" class="form-label required">{{ __('facility_management.commission_rate') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                       id="commission_rate" name="commission_rate" value="{{ old('commission_rate', '5') }}" 
                                       min="0" max="100" step="0.1" required onchange="calculateCommission()">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                {{ __('facility_management.commission_amount') }}: 
                                <span id="commissionAmount">0</span> {{ __('facility_management.currency') }}
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="deposit_amount" class="form-label">{{ __('facility_management.deposit_amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ __('facility_management.currency') }}</span>
                                <input type="number" class="form-control @error('deposit_amount') is-invalid @enderror" 
                                       id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount') }}" 
                                       min="0" step="0.01" onchange="updateSummary()">
                            </div>
                            @error('deposit_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('facility_management.deposit_optional') }}</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="valid_from" class="form-label required">{{ __('facility_management.valid_from') }}</label>
                            <input type="date" class="form-control @error('valid_from') is-invalid @enderror" 
                                   id="valid_from" name="valid_from" value="{{ old('valid_from', date('Y-m-d')) }}" required>
                            @error('valid_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="valid_to" class="form-label">{{ __('facility_management.valid_to') }}</label>
                            <input type="date" class="form-control @error('valid_to') is-invalid @enderror" 
                                   id="valid_to" name="valid_to" value="{{ old('valid_to') }}">
                            @error('valid_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('facility_management.leave_empty_open_ended') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-text text-info"></i>
                        {{ __('facility_management.terms_conditions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="terms_conditions" class="form-label">{{ __('facility_management.terms_conditions') }}</label>
                        <textarea class="form-control @error('terms_conditions') is-invalid @enderror" 
                                  id="terms_conditions" name="terms_conditions" rows="6" 
                                  placeholder="{{ __('facility_management.enter_terms_conditions') }}">{{ old('terms_conditions') }}</textarea>
                        @error('terms_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">{{ __('facility_management.terms_conditions_hint') }}</small>
                    </div>

                    <!-- Terms Templates -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('facility_management.quick_terms_templates') }}</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addQuickTerm('payment_terms')">
                                {{ __('facility_management.payment_terms') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addQuickTerm('cancellation_policy')">
                                {{ __('facility_management.cancellation_policy') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addQuickTerm('maintenance')">
                                {{ __('facility_management.maintenance_terms') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addQuickTerm('insurance')">
                                {{ __('facility_management.insurance_terms') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offer Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calculator text-warning"></i>
                        {{ __('facility_management.offer_summary') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td>{{ __('facility_management.base_price') }}:</td>
                                    <td class="text-end fw-bold" id="summaryPrice">0 {{ __('facility_management.currency') }}</td>
                                </tr>
                                <tr id="summaryDepositRow" class="d-none">
                                    <td>{{ __('facility_management.deposit') }}:</td>
                                    <td class="text-end fw-bold" id="summaryDeposit">0 {{ __('facility_management.currency') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('facility_management.commission') }} (<span id="summaryCommissionRate">0</span>%):</td>
                                    <td class="text-end fw-bold text-warning" id="summaryCommission">0 {{ __('facility_management.currency') }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-bold">{{ __('facility_management.total_expected') }}:</td>
                                    <td class="text-end fw-bold text-primary fs-5" id="summaryTotal">0 {{ __('facility_management.currency') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6>{{ __('facility_management.note') }}:</h6>
                                <ul class="mb-0 small">
                                    <li>{{ __('facility_management.commission_calculated_on_base_price') }}</li>
                                    <li>{{ __('facility_management.deposit_separate_from_price') }}</li>
                                    <li>{{ __('facility_management.client_pays_total_amount') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('facility.financial.offers') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                            {{ __('facility_management.cancel') }}
                        </a>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="save_draft" class="btn btn-outline-primary">
                                <i class="bi bi-file-earmark"></i>
                                {{ __('facility_management.save_as_draft') }}
                            </button>
                            <button type="submit" name="action" value="save_active" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i>
                                {{ __('facility_management.create_and_activate') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Quick terms templates
const quickTerms = {
    payment_terms: '{{ __('facility_management.default_payment_terms') }}',
    cancellation_policy: '{{ __('facility_management.default_cancellation_policy') }}',
    maintenance: '{{ __('facility_management.default_maintenance_terms') }}',
    insurance: '{{ __('facility_management.default_insurance_terms') }}'
};

// Load product details
function loadProductDetails() {
    const productId = document.getElementById('product_id').value;
    const preview = document.getElementById('productPreview');
    
    if (!productId) {
        preview.classList.add('d-none');
        return;
    }

    // Show loading
    showLoading();
    
    // Fetch product details (in a real implementation, you'd make an AJAX call)
    // For now, we'll simulate with the selected option text
    const option = document.querySelector(`#product_id option[value="${productId}"]`);
    if (option) {
        document.getElementById('productName').textContent = option.textContent.split(' - ')[0];
        document.getElementById('productLocation').textContent = option.textContent.split(' - ')[1] || '';
        
        // Show preview
        preview.classList.remove('d-none');
        
        // Hide loading
        hideLoading();
    }
}

// Update offer type fields
function updateOfferTypeFields() {
    const offerType = document.getElementById('offer_type').value;
    const priceLabel = document.getElementById('priceLabel');
    const priceHint = document.getElementById('priceHint');
    
    switch(offerType) {
        case 'sale':
            priceLabel.textContent = '{{ __('facility_management.sale_price') }}';
            priceHint.textContent = '{{ __('facility_management.total_sale_amount') }}';
            break;
        case 'monthly_rent':
            priceLabel.textContent = '{{ __('facility_management.monthly_rent') }}';
            priceHint.textContent = '{{ __('facility_management.amount_per_month') }}';
            break;
        case 'yearly_rent':
            priceLabel.textContent = '{{ __('facility_management.yearly_rent') }}';
            priceHint.textContent = '{{ __('facility_management.amount_per_year') }}';
            break;
        case 'daily_rent':
            priceLabel.textContent = '{{ __('facility_management.daily_rent') }}';
            priceHint.textContent = '{{ __('facility_management.amount_per_day') }}';
            break;
        default:
            priceLabel.textContent = '{{ __('facility_management.price') }}';
            priceHint.textContent = '';
    }
    
    updateSummary();
}

// Calculate commission
function calculateCommission() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    const commissionAmount = (price * commissionRate) / 100;
    
    document.getElementById('commissionAmount').textContent = new Intl.NumberFormat('{{ app()->getLocale() }}').format(commissionAmount);
    
    updateSummary();
}

// Update summary
function updateSummary() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const deposit = parseFloat(document.getElementById('deposit_amount').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    const commission = (price * commissionRate) / 100;
    const total = price + deposit;
    
    // Update summary display
    document.getElementById('summaryPrice').textContent = new Intl.NumberFormat('{{ app()->getLocale() }}').format(price) + ' {{ __('facility_management.currency') }}';
    document.getElementById('summaryCommissionRate').textContent = commissionRate;
    document.getElementById('summaryCommission').textContent = new Intl.NumberFormat('{{ app()->getLocale() }}').format(commission) + ' {{ __('facility_management.currency') }}';
    document.getElementById('summaryTotal').textContent = new Intl.NumberFormat('{{ app()->getLocale() }}').format(total) + ' {{ __('facility_management.currency') }}';
    
    // Show/hide deposit row
    const depositRow = document.getElementById('summaryDepositRow');
    if (deposit > 0) {
        document.getElementById('summaryDeposit').textContent = new Intl.NumberFormat('{{ app()->getLocale() }}').format(deposit) + ' {{ __('facility_management.currency') }}';
        depositRow.classList.remove('d-none');
    } else {
        depositRow.classList.add('d-none');
    }
}

// Add quick term
function addQuickTerm(type) {
    const textarea = document.getElementById('terms_conditions');
    const currentText = textarea.value;
    const newTerm = quickTerms[type];
    
    if (newTerm) {
        const separator = currentText ? '\n\n' : '';
        textarea.value = currentText + separator + newTerm;
        textarea.focus();
    }
}

// Form validation
document.getElementById('createOfferForm').addEventListener('submit', function(e) {
    const productId = document.getElementById('product_id').value;
    const offerType = document.getElementById('offer_type').value;
    const price = document.getElementById('price').value;
    const commissionRate = document.getElementById('commission_rate').value;
    const validFrom = document.getElementById('valid_from').value;
    
    if (!productId || !offerType || !price || !commissionRate || !validFrom) {
        e.preventDefault();
        showToast('error', '{{ __('facility_management.please_fill_required_fields') }}');
        return false;
    }
    
    // Check if valid_to is before valid_from
    const validTo = document.getElementById('valid_to').value;
    if (validTo && validTo <= validFrom) {
        e.preventDefault();
        showToast('error', '{{ __('facility_management.valid_to_must_be_after_valid_from') }}');
        return false;
    }
    
    // Show loading
    showLoading();
    return true;
});

// Auto-calculate on input changes
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('price').addEventListener('input', calculateCommission);
    document.getElementById('commission_rate').addEventListener('input', calculateCommission);
    document.getElementById('deposit_amount').addEventListener('input', updateSummary);
    
    // Set minimum date for valid_from to today
    document.getElementById('valid_from').min = new Date().toISOString().split('T')[0];
    
    // Update valid_to minimum when valid_from changes
    document.getElementById('valid_from').addEventListener('change', function() {
        const validTo = document.getElementById('valid_to');
        validTo.min = this.value;
    });
});
</script>
@endpush

@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.input-group-text {
    background: #f8f9fa;
    border-color: #ced4da;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 0.25rem;
}

.alert-info {
    background-color: #e7f3ff;
    border-color: #b8daff;
    color: #0c5460;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
