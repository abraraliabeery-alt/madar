@extends('layouts.facility')

@section('title', 'إضافة عرض جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة عرض جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('facility.offers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <form action="{{ route('facility.offers.store') }}" method="POST">
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
                                                {{ $product->getTranslatedTitle() }} - {{ $product->address }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="offer_type" class="form-label">نوع العرض <span class="text-danger">*</span></label>
                                    <select name="offer_type" id="offer_type" class="form-select @error('offer_type') is-invalid @enderror" required>
                                        <option value="">اختر نوع العرض</option>
                                        @foreach($offerTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('offer_type') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('offer_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                                            <input type="number" name="price" id="price" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   value="{{ old('price') }}" step="0.01" min="0" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">العملة <span class="text-danger">*</span></label>
                                            <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                                <option value="SAR" {{ old('currency', 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو</option>
                                            </select>
                                            @error('currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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
                            </div>

                            <!-- معلومات العمولة والتواريخ -->
                            <div class="col-md-6">
                                <h5 class="mb-3">العمولة والتواريخ</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="commission_rate" class="form-label">نسبة العمولة (%)</label>
                                            <input type="number" name="commission_rate" id="commission_rate" 
                                                   class="form-control @error('commission_rate') is-invalid @enderror" 
                                                   value="{{ old('commission_rate') }}" step="0.0001" min="0" max="100">
                                            @error('commission_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">مبلغ العمولة</label>
                                            <input type="text" id="commission_amount_display" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="valid_from" class="form-label">صالح من</label>
                                            <input type="date" name="valid_from" id="valid_from" 
                                                   class="form-control @error('valid_from') is-invalid @enderror" 
                                                   value="{{ old('valid_from') }}">
                                            @error('valid_from')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="valid_to" class="form-label">صالح حتى</label>
                                            <input type="date" name="valid_to" id="valid_to" 
                                                   class="form-control @error('valid_to') is-invalid @enderror" 
                                                   value="{{ old('valid_to') }}">
                                            @error('valid_to')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" 
                                               class="form-check-input" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">عرض نشط</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_featured" id="is_featured" 
                                               class="form-check-input" value="1" 
                                               {{ old('is_featured') ? 'checked' : '' }}>
                                        <label for="is_featured" class="form-check-label">عرض مميز</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الشروط والأحكام -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">الشروط والأحكام</h5>
                                
                                <div class="mb-3">
                                    <label for="terms_conditions" class="form-label">الشروط والأحكام (إنجليزي)</label>
                                    <textarea name="terms_conditions" id="terms_conditions" 
                                              class="form-control @error('terms_conditions') is-invalid @enderror" 
                                              rows="4">{{ old('terms_conditions') }}</textarea>
                                    @error('terms_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="terms_conditions_ar" class="form-label">الشروط والأحكام (عربي)</label>
                                    <textarea name="terms_conditions_ar" id="terms_conditions_ar" 
                                              class="form-control @error('terms_conditions_ar') is-invalid @enderror" 
                                              rows="4">{{ old('terms_conditions_ar') }}</textarea>
                                    @error('terms_conditions_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="special_notes_ar" class="form-label">ملاحظات خاصة (عربي)</label>
                                    <textarea name="special_notes_ar" id="special_notes_ar" 
                                              class="form-control @error('special_notes_ar') is-invalid @enderror" 
                                              rows="3">{{ old('special_notes_ar') }}</textarea>
                                    @error('special_notes_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('facility.offers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ العرض
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
    // حساب العمولة تلقائياً
    function calculateCommission() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const rate = parseFloat(document.getElementById('commission_rate').value) || 0;
        const amount = price * (rate / 100);
        document.getElementById('commission_amount_display').value = amount.toFixed(2);
    }

    document.getElementById('price').addEventListener('input', calculateCommission);
    document.getElementById('commission_rate').addEventListener('input', calculateCommission);

    // تعيين التاريخ الحالي كافتراضي
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        if (!document.getElementById('valid_from').value) {
            document.getElementById('valid_from').value = today;
        }
    });
</script>
@endpush
