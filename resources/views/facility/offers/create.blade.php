@extends('facility.layouts.app')

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
                            <i class="fas fa-arrow-left"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('facility.offers.store') }}" enctype="multipart/form-data">
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
                                    <label for="offer_type" class="form-label">نوع العرض <span class="text-danger">*</span></label>
                                    <select name="offer_type" id="offer_type" class="form-select @error('offer_type') is-invalid @enderror" required>
                                        <option value="">اختر نوع العرض</option>
                                        @foreach($offerTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('offer_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('offer_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="offer_title" class="form-label">عنوان العرض</label>
                                    <input type="text" name="offer_title" id="offer_title" class="form-control @error('offer_title') is-invalid @enderror" 
                                           value="{{ old('offer_title') }}" placeholder="مثال: شقة فاخرة للإيجار">
                                    @error('offer_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="offer_description" class="form-label">وصف العرض</label>
                                    <textarea name="offer_description" id="offer_description" class="form-control @error('offer_description') is-invalid @enderror" 
                                              rows="3" placeholder="وصف مفصل للعرض...">{{ old('offer_description') }}</textarea>
                                    @error('offer_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- التفاصيل المالية -->
                            <div class="col-md-6">
                                <h5 class="mb-3">التفاصيل المالية</h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
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
                                                <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
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
                                    <input type="number" name="deposit_amount" id="deposit_amount" class="form-control @error('deposit_amount') is-invalid @enderror" 
                                           value="{{ old('deposit_amount') }}" step="0.01" min="0">
                                    @error('deposit_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="commission_rate" class="form-label">نسبة العمولة</label>
                                            <div class="input-group">
                                                <input type="number" name="commission_rate" id="commission_rate" class="form-control @error('commission_rate') is-invalid @enderror" 
                                                       value="{{ old('commission_rate') }}" step="0.0001" min="0" max="1">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            @error('commission_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="commission_amount" class="form-label">مبلغ العمولة</label>
                                            <input type="number" name="commission_amount" id="commission_amount" class="form-control @error('commission_amount') is-invalid @enderror" 
                                                   value="{{ old('commission_amount') }}" step="0.01" min="0">
                                            @error('commission_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- إعدادات العرض -->
                            <div class="col-md-6">
                                <h5 class="mb-3">إعدادات العرض</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="priority" class="form-label">الأولوية</label>
                                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror">
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" {{ old('priority', 5) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="valid_from" class="form-label">تاريخ البداية</label>
                                            <input type="date" name="valid_from" id="valid_from" class="form-control @error('valid_from') is-invalid @enderror" 
                                                   value="{{ old('valid_from', now()->format('Y-m-d')) }}">
                                            @error('valid_from')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="valid_to" class="form-label">تاريخ النهاية</label>
                                    <input type="date" name="valid_to" id="valid_to" class="form-control @error('valid_to') is-invalid @enderror" 
                                           value="{{ old('valid_to') }}">
                                    @error('valid_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="min_contract_duration" class="form-label">مدة العقد الأدنى (أشهر)</label>
                                            <input type="number" name="min_contract_duration" id="min_contract_duration" class="form-control @error('min_contract_duration') is-invalid @enderror" 
                                                   value="{{ old('min_contract_duration') }}" min="1">
                                            @error('min_contract_duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_contract_duration" class="form-label">مدة العقد القصوى (أشهر)</label>
                                            <input type="number" name="max_contract_duration" id="max_contract_duration" class="form-control @error('max_contract_duration') is-invalid @enderror" 
                                                   value="{{ old('max_contract_duration') }}" min="1">
                                            @error('max_contract_duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">عرض نشط</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label for="is_featured" class="form-check-label">عرض مميز</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="auto_renew" id="auto_renew" class="form-check-input" value="1" {{ old('auto_renew') ? 'checked' : '' }}>
                                        <label for="auto_renew" class="form-check-label">تجديد تلقائي</label>
                                    </div>
                                </div>
                            </div>

                            <!-- الشروط والملاحظات -->
                            <div class="col-md-6">
                                <h5 class="mb-3">الشروط والملاحظات</h5>
                                
                                <div class="mb-3">
                                    <label for="terms_conditions" class="form-label">الشروط والأحكام</label>
                                    <textarea name="terms_conditions" id="terms_conditions" class="form-control @error('terms_conditions') is-invalid @enderror" 
                                              rows="4" placeholder="الشروط والأحكام العامة للعرض...">{{ old('terms_conditions') }}</textarea>
                                    @error('terms_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="special_conditions" class="form-label">شروط خاصة</label>
                                    <textarea name="special_conditions" id="special_conditions" class="form-control @error('special_conditions') is-invalid @enderror" 
                                              rows="3" placeholder="شروط خاصة بالعرض...">{{ old('special_conditions') }}</textarea>
                                    @error('special_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="marketing_notes" class="form-label">ملاحظات تسويقية</label>
                                    <textarea name="marketing_notes" id="marketing_notes" class="form-control @error('marketing_notes') is-invalid @enderror" 
                                              rows="3" placeholder="ملاحظات للفريق التسويقي...">{{ old('marketing_notes') }}</textarea>
                                    @error('marketing_notes')
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
    // Calculate commission amount when rate changes
    document.getElementById('commission_rate').addEventListener('input', function() {
        const rate = parseFloat(this.value);
        const price = parseFloat(document.getElementById('price').value);
        if (rate && price) {
            document.getElementById('commission_amount').value = (rate * price).toFixed(2);
        }
    });

    // Calculate commission rate when amount changes
    document.getElementById('commission_amount').addEventListener('input', function() {
        const amount = parseFloat(this.value);
        const price = parseFloat(document.getElementById('price').value);
        if (amount && price) {
            document.getElementById('commission_rate').value = (amount / price).toFixed(4);
        }
    });

    // Set max contract duration based on min
    document.getElementById('min_contract_duration').addEventListener('input', function() {
        const min = parseInt(this.value);
        const maxInput = document.getElementById('max_contract_duration');
        if (min) {
            maxInput.min = min;
        }
    });
</script>
@endpush