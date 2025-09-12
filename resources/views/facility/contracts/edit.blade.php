@extends('facility.layouts.app')

@section('title', 'تعديل العقد')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">تعديل العقد</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.contracts.show', $contract) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-eye"></i>
                    <span>عرض</span>
                </a>
                <a href="{{ route('facility.contracts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة للقائمة</span>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('facility.contracts.update', $contract) }}">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- معلومات أساسية -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">المعلومات الأساسية</h5>
                        
                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">المنتج <span class="text-red-500">*</span></label>
                            <select name="product_id" id="product_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('product_id') border-red-500 @enderror" required>
                                <option value="">اختر المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $contract->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->getTranslatedTitle() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="offer_id" class="block text-sm font-medium text-gray-700 mb-2">العرض <span class="text-red-500">*</span></label>
                            <select name="offer_id" id="offer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('offer_id') border-red-500 @enderror" required>
                                <option value="">اختر العرض</option>
                                @foreach($offers as $offer)
                                    <option value="{{ $offer->id }}" {{ old('offer_id', $contract->offer_id) == $offer->id ? 'selected' : '' }}>
                                        {{ $offer->getTranslatedTitle() }} - {{ $offer->product->getTranslatedTitle() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('offer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="contract_type" class="block text-sm font-medium text-gray-700 mb-2">نوع العقد <span class="text-red-500">*</span></label>
                            <select name="contract_type" id="contract_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('contract_type') border-red-500 @enderror" required>
                                <option value="">اختر نوع العقد</option>
                                @foreach($contractTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('contract_type', $contract->contract_type) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">العميل <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('user_id') border-red-500 @enderror" required>
                                <option value="">اختر العميل</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $contract->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-2">المالك <span class="text-red-500">*</span></label>
                            <select name="owner_id" id="owner_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('owner_id') border-red-500 @enderror" required>
                                <option value="">اختر المالك</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id', $contract->owner_id) == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} - {{ $owner->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- التفاصيل المالية -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">التفاصيل المالية</h5>
                        
                        <div class="mb-4">
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">المبلغ الإجمالي <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <input type="number" name="total_amount" id="total_amount" 
                                       class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('total_amount') border-red-500 @enderror" 
                                       value="{{ old('total_amount', $contract->total_amount) }}" step="0.01" min="0" required>
                                <select name="currency" id="currency" class="border border-gray-300 rounded-r-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('currency') border-red-500 @enderror" required>
                                    <option value="SAR" {{ old('currency', $contract->currency) == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                    <option value="USD" {{ old('currency', $contract->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                                    <option value="EUR" {{ old('currency', $contract->currency) == 'EUR' ? 'selected' : '' }}>يورو</option>
                                </select>
                            </div>
                            @error('total_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ العربون</label>
                            <input type="number" name="deposit_amount" id="deposit_amount" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('deposit_amount') border-red-500 @enderror" 
                                   value="{{ old('deposit_amount', $contract->deposit_amount) }}" step="0.01" min="0">
                            @error('deposit_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">نسبة العمولة (%)</label>
                            <input type="number" name="commission_rate" id="commission_rate" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('commission_rate') border-red-500 @enderror" 
                                   value="{{ old('commission_rate', $contract->commission_rate) }}" step="0.01" min="0" max="100">
                            @error('commission_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="late_fee_rate" class="block text-sm font-medium text-gray-700 mb-2">نسبة الرسوم المتأخرة (%)</label>
                            <input type="number" name="late_fee_rate" id="late_fee_rate" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('late_fee_rate') border-red-500 @enderror" 
                                   value="{{ old('late_fee_rate', $contract->late_fee_rate) }}" step="0.01" min="0" max="100">
                            @error('late_fee_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="early_payment_discount" class="block text-sm font-medium text-gray-700 mb-2">خصم الدفع المبكر</label>
                            <input type="number" name="early_payment_discount" id="early_payment_discount" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('early_payment_discount') border-red-500 @enderror" 
                                   value="{{ old('early_payment_discount', $contract->early_payment_discount) }}" step="0.01" min="0">
                            @error('early_payment_discount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                    <!-- تواريخ العقد -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">تواريخ العقد</h5>
                        
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('start_date') border-red-500 @enderror" 
                                   value="{{ old('start_date', $contract->start_date ? $contract->start_date->format('Y-m-d') : '') }}" required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('end_date') border-red-500 @enderror" 
                                   value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="contract_duration_months" class="block text-sm font-medium text-gray-700 mb-2">مدة العقد (بالشهور)</label>
                            <input type="number" name="contract_duration_months" id="contract_duration_months" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('contract_duration_months') border-red-500 @enderror" 
                                   value="{{ old('contract_duration_months', $contract->contract_duration_months) }}" min="1">
                            @error('contract_duration_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- شروط الدفع -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">شروط الدفع</h5>
                        
                        <div class="mb-4">
                            <label for="payment_frequency" class="block text-sm font-medium text-gray-700 mb-2">تكرار الدفع</label>
                            <select name="payment_frequency" id="payment_frequency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('payment_frequency') border-red-500 @enderror">
                                <option value="">اختر تكرار الدفع</option>
                                <option value="monthly" {{ old('payment_frequency', $contract->payment_frequency) == 'monthly' ? 'selected' : '' }}>شهري</option>
                                <option value="quarterly" {{ old('payment_frequency', $contract->payment_frequency) == 'quarterly' ? 'selected' : '' }}>ربعي</option>
                                <option value="yearly" {{ old('payment_frequency', $contract->payment_frequency) == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                <option value="custom" {{ old('payment_frequency', $contract->payment_frequency) == 'custom' ? 'selected' : '' }}>مخصص</option>
                            </select>
                            @error('payment_frequency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="total_installments" class="block text-sm font-medium text-gray-700 mb-2">إجمالي الأقساط</label>
                            <input type="number" name="total_installments" id="total_installments" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('total_installments') border-red-500 @enderror" 
                                   value="{{ old('total_installments', $contract->total_installments) }}" min="1">
                            @error('total_installments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الشروط والأحكام -->
                <div class="mt-8">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">الشروط والأحكام</h5>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">الشروط والأحكام (الإنجليزية)</label>
                            <textarea name="terms_conditions" id="terms_conditions" rows="4" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('terms_conditions') border-red-500 @enderror">{{ old('terms_conditions', $contract->terms_conditions) }}</textarea>
                            @error('terms_conditions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="terms_conditions_ar" class="block text-sm font-medium text-gray-700 mb-2">الشروط والأحكام (العربية)</label>
                            <textarea name="terms_conditions_ar" id="terms_conditions_ar" rows="4" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('terms_conditions_ar') border-red-500 @enderror">{{ old('terms_conditions_ar', $contract->terms_conditions_ar) }}</textarea>
                            @error('terms_conditions_ar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="renewal_terms" class="block text-sm font-medium text-gray-700 mb-2">شروط التجديد</label>
                            <textarea name="renewal_terms" id="renewal_terms" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('renewal_terms') border-red-500 @enderror">{{ old('renewal_terms', $contract->renewal_terms) }}</textarea>
                            @error('renewal_terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="termination_terms" class="block text-sm font-medium text-gray-700 mb-2">شروط الإنهاء</label>
                            <textarea name="termination_terms" id="termination_terms" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('termination_terms') border-red-500 @enderror">{{ old('termination_terms', $contract->termination_terms) }}</textarea>
                            @error('termination_terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('facility.contracts.show', $contract) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-times"></i>
                    <span>إلغاء</span>
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-save"></i>
                    <span>حفظ التغييرات</span>
                </button>
            </div>
        </form>
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

