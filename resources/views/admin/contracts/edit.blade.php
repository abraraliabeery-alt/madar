@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.contracts.edit') }} - {{ $contract->contract_number }}</h5>
            <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('admin.contracts.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.contracts.update', $contract) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.contracts.basic_info') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">{{ __('admin.contracts.user') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">{{ __('admin.contracts.select_user') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $contract->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="product_id" class="form-label">{{ __('admin.contracts.product') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                        <option value="">{{ __('admin.contracts.select_product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-facility="{{ $product->facility_id }}" data-price="{{ $product->price }}" {{ old('product_id', $contract->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - {{ number_format($product->price, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="facility_id" id="facility_id" value="{{ old('facility_id', $contract->facility_id) }}">

                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">{{ __('admin.contracts.contract_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('contract_type') is-invalid @enderror" id="contract_type" name="contract_type" required>
                                        <option value="">{{ __('admin.contracts.select_contract_type') }}</option>
                                        <option value="sale" {{ old('contract_type', $contract->contract_type) == 'sale' ? 'selected' : '' }}>{{ __('admin.contracts.type_sale') }}</option>
                                        <option value="rent" {{ old('contract_type', $contract->contract_type) == 'rent' ? 'selected' : '' }}>{{ __('admin.contracts.type_rent') }}</option>
                                        <option value="lease" {{ old('contract_type', $contract->contract_type) == 'lease' ? 'selected' : '' }}>{{ __('admin.contracts.type_lease') }}</option>
                                    </select>
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status_id" class="form-label">{{ __('admin.contracts.status') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                                        <option value="">{{ __('admin.contracts.select_status') }}</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id', $contract->status_id) == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Details -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.contracts.contract_details') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">{{ __('admin.contracts.start_date') }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $contract->start_date) }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">{{ __('admin.contracts.end_date') }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $contract->end_date) }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="total_amount" class="form-label">{{ __('admin.contracts.total_amount') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount', $contract->total_amount) }}" required min="0" step="0.01">
                                                <span class="input-group-text">{!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</span>
                                            </div>
                                            @error('total_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="down_payment" class="form-label">{{ __('admin.contracts.down_payment') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('down_payment') is-invalid @enderror" id="down_payment" name="down_payment" value="{{ old('down_payment', $contract->down_payment) }}" required min="0" step="0.01">
                                                <span class="input-group-text">{!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</span>
                                            </div>
                                            @error('down_payment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="monthly_payment" class="form-label">{{ __('admin.contracts.monthly_payment') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('monthly_payment') is-invalid @enderror" id="monthly_payment" name="monthly_payment" value="{{ old('monthly_payment', $contract->monthly_payment) }}" required min="0" step="0.01">
                                                <span class="input-group-text">{!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</span>
                                            </div>
                                            @error('monthly_payment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="col-md-6">
                        <div class="card" id="bankDetails" style="{{ $contract->contract_type !== 'sale' ? 'display: none;' : '' }}">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.contracts.bank_details') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="bank_id" class="form-label">{{ __('admin.contracts.bank') }}</label>
                                    <select class="form-select @error('bank_id') is-invalid @enderror" id="bank_id" name="bank_id">
                                        <option value="">{{ __('admin.contracts.select_bank') }}</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ old('bank_id', $contract->bank_id) == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="loan_amount" class="form-label">{{ __('admin.contracts.loan_amount') }}</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('loan_amount') is-invalid @enderror" id="loan_amount" name="loan_amount" value="{{ old('loan_amount', $contract->loan_amount) }}" min="0" step="0.01">
                                                <span class="input-group-text">{!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</span>
                                            </div>
                                            @error('loan_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="interest_rate" class="form-label">{{ __('admin.contracts.interest_rate') }}</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('interest_rate') is-invalid @enderror" id="interest_rate" name="interest_rate" value="{{ old('interest_rate', $contract->interest_rate) }}" min="0" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            @error('interest_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="loan_term" class="form-label">{{ __('admin.contracts.loan_term') }}</label>
                                            <input type="number" class="form-control @error('loan_term') is-invalid @enderror" id="loan_term" name="loan_term" value="{{ old('loan_term', $contract->loan_term) }}" min="1">
                                            @error('loan_term')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.contracts.additional_info') }}</h6>
                            </div>
                            <div class="card-body">
                                @include('components.translations-repeater', [
                                    'locales' => $locales ?? config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'items' => $contract->translations->map(function ($t) {
                                        return [
                                            'locale' => $t->locale,
                                            'title' => $t->title,
                                            'content' => $t->content,
                                        ];
                                    })->values()->toArray(),
                                    'fields' => [
                                        [
                                            'type' => 'input',
                                            'key' => 'title',
                                            'label' => __('admin.contracts.title'),
                                            'requiredFirst' => false,
                                        ],
                                        [
                                            'type' => 'textarea',
                                            'key' => 'content',
                                            'label' => __('admin.contracts.content'),
                                            'rows' => 4,
                                        ],
                                    ],
                                    'addLabel' => __('admin.ui.layout.add_new'),
                                    'removeLabel' => __('admin.actions.delete'),
                                    'minItems' => 0,
                                    'maxItems' => is_array($locales ?? null) ? count($locales) : null,
                                ])

                                <div class="mb-3 mt-3">
                                    <label for="notes" class="form-label">{{ __('admin.contracts.notes') }}</label>
                                    <textarea class="form-control summernote @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $contract->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $contract->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">{{ __('admin.contracts.is_active') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" {{ old('is_verified', $contract->is_verified) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">{{ __('admin.contracts.is_verified') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('admin.contracts.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize summernote
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol']],
        ]
    });

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle product selection
    $('#product_id').change(function() {
        let selectedOption = $(this).find('option:selected');
        let facilityId = selectedOption.data('facility');
        let price = selectedOption.data('price');

        $('#facility_id').val(facilityId);
        if (!$('#total_amount').val()) {
            $('#total_amount').val(price);
            calculatePayments();
        }
    });

    // Handle contract type change
    $('#contract_type').change(function() {
        let type = $(this).val();
        if (type === 'sale') {
            $('#bankDetails').show();
        } else {
            $('#bankDetails').hide();
            $('#bank_id').val('').trigger('change');
            $('#loan_amount, #interest_rate, #loan_term').val('');
        }
    });

    // Calculate payments
    function calculatePayments() {
        let totalAmount = parseFloat($('#total_amount').val()) || 0;
        let downPayment = parseFloat($('#down_payment').val()) || 0;
        let loanTerm = parseInt($('#loan_term').val()) || 1;

        if (totalAmount > 0 && downPayment >= 0 && loanTerm > 0) {
            let remainingAmount = totalAmount - downPayment;
            let monthlyPayment = remainingAmount / loanTerm;
            $('#monthly_payment').val(monthlyPayment.toFixed(2));
        }
    }

    // Bind calculation events
    $('#total_amount, #down_payment, #loan_term').change(calculatePayments);

    // Set minimum end date based on start date
    $('#start_date').change(function() {
        $('#end_date').attr('min', $(this).val());
    });
});
</script>
@endpush
