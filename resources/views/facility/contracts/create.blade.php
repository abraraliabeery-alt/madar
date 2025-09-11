@extends('facility.layouts.app')

@section('title', 'إضافة عقد جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة عقد جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('facility.contracts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('facility.contracts.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- معلومات أساسية -->
                            <div class="col-md-6">
                                <h5 class="mb-3">المعلومات الأساسية</h5>
                                
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">المنتج <span class="text-danger">*</span></label>
                                    <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->getTranslatedTitle() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="offer_id" class="form-label">العرض <span class="text-danger">*</span></label>
                                    <select name="offer_id" id="offer_id" class="form-select @error('offer_id') is-invalid @enderror" required>
                                        <option value="">اختر العرض</option>
                                        @foreach($offers as $offer)
                                            <option value="{{ $offer->id }}" {{ old('offer_id') == $offer->id ? 'selected' : '' }}>
                                                {{ $offer->getTranslatedTitle() }} - {{ $offer->product->getTranslatedTitle() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('offer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">نوع العقد <span class="text-danger">*</span></label>
                                    <select name="contract_type" id="contract_type" class="form-select @error('contract_type') is-invalid @enderror" required>
                                        <option value="">اختر نوع العقد</option>
                                        @foreach($contractTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('contract_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="user_id" class="form-label">العميل <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                        <option value="">اختر العميل</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="owner_id" class="form-label">المالك <span class="text-danger">*</span></label>
                                    <select name="owner_id" id="owner_id" class="form-select @error('owner_id') is-invalid @enderror" required>
                                        <option value="">اختر المالك</option>
                                        @foreach($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                                {{ $owner->name }} - {{ $owner->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('owner_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- التفاصيل المالية -->
                            <div class="col-md-6">
                                <h5 class="mb-3">التفاصيل المالية</h5>
                                
                                <div class="mb-3">
                                    <label for="total_amount" class="form-label">المبلغ الإجمالي <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="total_amount" id="total_amount" 
                                               class="form-control @error('total_amount') is-invalid @enderror" 
                                               value="{{ old('total_amount') }}" step="0.01" min="0" required>
                                        <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                            <option value="SAR" {{ old('currency', 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو</option>
                                        </select>
                                    </div>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deposit_amount" class="form-label">مبلغ العربون</label>
                                    <input type="number" name="deposit_amount" id="deposit_amount" 
                                           class="form-control @error('deposit_amount') is-invalid @enderror" 
                                           value="{{ old('deposit_amount') }}" step="0.01" min="0">
                                    @error('deposit_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="commission_rate" class="form-label">نسبة العمولة (%)</label>
                                    <input type="number" name="commission_rate" id="commission_rate" 
                                           class="form-control @error('commission_rate') is-invalid @enderror" 
                                           value="{{ old('commission_rate') }}" step="0.01" min="0" max="100">
                                    @error('commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="late_fee_rate" class="form-label">نسبة الرسوم المتأخرة (%)</label>
                                    <input type="number" name="late_fee_rate" id="late_fee_rate" 
                                           class="form-control @error('late_fee_rate') is-invalid @enderror" 
                                           value="{{ old('late_fee_rate') }}" step="0.01" min="0" max="100">
                                    @error('late_fee_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="early_payment_discount" class="form-label">خصم الدفع المبكر</label>
                                    <input type="number" name="early_payment_discount" id="early_payment_discount" 
                                           class="form-control @error('early_payment_discount') is-invalid @enderror" 
                                           value="{{ old('early_payment_discount') }}" step="0.01" min="0">
                                    @error('early_payment_discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- تواريخ العقد -->
                            <div class="col-md-6">
                                <h5 class="mb-3">تواريخ العقد</h5>
                                
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">تاريخ البداية <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="end_date" class="form-label">تاريخ النهاية</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contract_duration_months" class="form-label">مدة العقد (بالشهور)</label>
                                    <input type="number" name="contract_duration_months" id="contract_duration_months" 
                                           class="form-control @error('contract_duration_months') is-invalid @enderror" 
                                           value="{{ old('contract_duration_months') }}" min="1">
                                    @error('contract_duration_months')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- شروط الدفع -->
                            <div class="col-md-6">
                                <h5 class="mb-3">شروط الدفع</h5>
                                
                                <div class="mb-3">
                                    <label for="payment_frequency" class="form-label">تكرار الدفع</label>
                                    <select name="payment_frequency" id="payment_frequency" class="form-select @error('payment_frequency') is-invalid @enderror">
                                        <option value="">اختر تكرار الدفع</option>
                                        <option value="monthly" {{ old('payment_frequency') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                        <option value="quarterly" {{ old('payment_frequency') == 'quarterly' ? 'selected' : '' }}>ربعي</option>
                                        <option value="yearly" {{ old('payment_frequency') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                        <option value="custom" {{ old('payment_frequency') == 'custom' ? 'selected' : '' }}>مخصص</option>
                                    </select>
                                    @error('payment_frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="total_installments" class="form-label">إجمالي الأقساط</label>
                                    <input type="number" name="total_installments" id="total_installments" 
                                           class="form-control @error('total_installments') is-invalid @enderror" 
                                           value="{{ old('total_installments') }}" min="1">
                                    @error('total_installments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- الشروط والأحكام -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">الشروط والأحكام</h5>
                                
                                <div class="mb-3">
                                    <label for="terms_conditions" class="form-label">الشروط والأحكام (الإنجليزية)</label>
                                    <textarea name="terms_conditions" id="terms_conditions" rows="4" 
                                              class="form-control @error('terms_conditions') is-invalid @enderror">{{ old('terms_conditions') }}</textarea>
                                    @error('terms_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="terms_conditions_ar" class="form-label">الشروط والأحكام (العربية)</label>
                                    <textarea name="terms_conditions_ar" id="terms_conditions_ar" rows="4" 
                                              class="form-control @error('terms_conditions_ar') is-invalid @enderror">{{ old('terms_conditions_ar') }}</textarea>
                                    @error('terms_conditions_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="renewal_terms" class="form-label">شروط التجديد</label>
                                    <textarea name="renewal_terms" id="renewal_terms" rows="3" 
                                              class="form-control @error('renewal_terms') is-invalid @enderror">{{ old('renewal_terms') }}</textarea>
                                    @error('renewal_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="termination_terms" class="form-label">شروط الإنهاء</label>
                                    <textarea name="termination_terms" id="termination_terms" rows="3" 
                                              class="form-control @error('termination_terms') is-invalid @enderror">{{ old('termination_terms') }}</textarea>
                                    @error('termination_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('facility.contracts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ العقد
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-calculate end date based on duration
    document.getElementById('contract_duration_months').addEventListener('input', function() {
        const startDate = document.getElementById('start_date').value;
        const duration = parseInt(this.value);
        
        if (startDate && duration) {
            const start = new Date(startDate);
            const end = new Date(start);
            end.setMonth(end.getMonth() + duration);
            
            document.getElementById('end_date').value = end.toISOString().split('T')[0];
        }
    });

    // Auto-calculate installments based on payment frequency
    document.getElementById('payment_frequency').addEventListener('change', function() {
        const duration = parseInt(document.getElementById('contract_duration_months').value);
        const frequency = this.value;
        
        if (duration && frequency) {
            let installments = 0;
            switch(frequency) {
                case 'monthly':
                    installments = duration;
                    break;
                case 'quarterly':
                    installments = Math.ceil(duration / 3);
                    break;
                case 'yearly':
                    installments = Math.ceil(duration / 12);
                    break;
            }
            
            if (installments > 0) {
                document.getElementById('total_installments').value = installments;
            }
        }
    });

    // Auto-calculate commission amount
    document.getElementById('commission_rate').addEventListener('input', function() {
        const totalAmount = parseFloat(document.getElementById('total_amount').value);
        const rate = parseFloat(this.value);
        
        if (totalAmount && rate) {
            const commission = (totalAmount * rate) / 100;
            // You can display this somewhere if needed
            console.log('Commission amount:', commission);
        }
    });
</script>
@endpush
