@extends('facility.layouts.app')

@section('title', 'تعديل العرض')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">تعديل العرض</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.offers.show', $offer) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-eye"></i>
                    <span>عرض</span>
                </a>
                <a href="{{ route('facility.offers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة للقائمة</span>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('facility.offers.update', $offer) }}" enctype="multipart/form-data">
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
                                    <option value="{{ $product->id }}" {{ old('product_id', $offer->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->getTranslatedTitle() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="offer_type" class="block text-sm font-medium text-gray-700 mb-2">نوع العرض <span class="text-red-500">*</span></label>
                            <select name="offer_type" id="offer_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('offer_type') border-red-500 @enderror" required>
                                <option value="">اختر نوع العرض</option>
                                @foreach($offerTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('offer_type', $offer->offer_type) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('offer_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="offer_title" class="block text-sm font-medium text-gray-700 mb-2">عنوان العرض</label>
                            <input type="text" name="offer_title" id="offer_title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('offer_title') border-red-500 @enderror" 
                                   value="{{ old('offer_title', $offer->offer_title) }}" placeholder="مثال: شقة فاخرة للإيجار">
                            @error('offer_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="offer_description" class="block text-sm font-medium text-gray-700 mb-2">وصف العرض</label>
                            <textarea name="offer_description" id="offer_description" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('offer_description') border-red-500 @enderror" 
                                      rows="3" placeholder="وصف مفصل للعرض...">{{ old('offer_description', $offer->offer_description) }}</textarea>
                            @error('offer_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- التفاصيل المالية -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">التفاصيل المالية</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">السعر <span class="text-red-500">*</span></label>
                                <input type="number" name="price" id="price" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('price') border-red-500 @enderror" 
                                       value="{{ old('price', $offer->price) }}" step="0.01" min="0" required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">العملة <span class="text-red-500">*</span></label>
                                <select name="currency" id="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('currency') border-red-500 @enderror" required>
                                    <option value="SAR" {{ old('currency', $offer->currency) == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                    <option value="USD" {{ old('currency', $offer->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                                    <option value="EUR" {{ old('currency', $offer->currency) == 'EUR' ? 'selected' : '' }}>يورو</option>
                                </select>
                                @error('currency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ العربون</label>
                            <input type="number" name="deposit_amount" id="deposit_amount" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('deposit_amount') border-red-500 @enderror" 
                                   value="{{ old('deposit_amount', $offer->deposit_amount) }}" step="0.01" min="0">
                            @error('deposit_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">نسبة العمولة</label>
                                <div class="relative">
                                    <input type="number" name="commission_rate" id="commission_rate" class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('commission_rate') border-red-500 @enderror" 
                                           value="{{ old('commission_rate', $offer->commission_rate) }}" step="0.0001" min="0" max="1">
                                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                                </div>
                                @error('commission_rate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="commission_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ العمولة</label>
                                <input type="number" name="commission_amount" id="commission_amount" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('commission_amount') border-red-500 @enderror" 
                                       value="{{ old('commission_amount', $offer->commission_amount) }}" step="0.01" min="0">
                                @error('commission_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                    <!-- إعدادات العرض -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">إعدادات العرض</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                                <select name="priority" id="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('priority') border-red-500 @enderror">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('priority', $offer->priority) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                                <input type="date" name="valid_from" id="valid_from" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('valid_from') border-red-500 @enderror" 
                                       value="{{ old('valid_from', $offer->valid_from ? $offer->valid_from->format('Y-m-d') : '') }}">
                                @error('valid_from')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="valid_to" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                            <input type="date" name="valid_to" id="valid_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('valid_to') border-red-500 @enderror" 
                                   value="{{ old('valid_to', $offer->valid_to ? $offer->valid_to->format('Y-m-d') : '') }}">
                            @error('valid_to')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="min_contract_duration" class="block text-sm font-medium text-gray-700 mb-2">مدة العقد الأدنى (أشهر)</label>
                                <input type="number" name="min_contract_duration" id="min_contract_duration" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('min_contract_duration') border-red-500 @enderror" 
                                       value="{{ old('min_contract_duration', $offer->min_contract_duration) }}" min="1">
                                @error('min_contract_duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="max_contract_duration" class="block text-sm font-medium text-gray-700 mb-2">مدة العقد القصوى (أشهر)</label>
                                <input type="number" name="max_contract_duration" id="max_contract_duration" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('max_contract_duration') border-red-500 @enderror" 
                                       value="{{ old('max_contract_duration', $offer->max_contract_duration) }}" min="1">
                                @error('max_contract_duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" value="1" {{ old('is_active', $offer->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="mr-2 block text-sm text-gray-900">عرض نشط</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" value="1" {{ old('is_featured', $offer->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="mr-2 block text-sm text-gray-900">عرض مميز</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="auto_renew" id="auto_renew" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" value="1" {{ old('auto_renew', $offer->auto_renew) ? 'checked' : '' }}>
                                <label for="auto_renew" class="mr-2 block text-sm text-gray-900">تجديد تلقائي</label>
                            </div>
                        </div>
                    </div>

                    <!-- الشروط والملاحظات -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">الشروط والملاحظات</h5>
                        
                        <div class="mb-4">
                            <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">الشروط والأحكام</label>
                            <textarea name="terms_conditions" id="terms_conditions" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('terms_conditions') border-red-500 @enderror" 
                                      rows="4" placeholder="الشروط والأحكام العامة للعرض...">{{ old('terms_conditions', $offer->terms_conditions) }}</textarea>
                            @error('terms_conditions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="special_conditions" class="block text-sm font-medium text-gray-700 mb-2">شروط خاصة</label>
                            <textarea name="special_conditions" id="special_conditions" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('special_conditions') border-red-500 @enderror" 
                                      rows="3" placeholder="شروط خاصة بالعرض...">{{ old('special_conditions', $offer->special_conditions) }}</textarea>
                            @error('special_conditions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="marketing_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات تسويقية</label>
                            <textarea name="marketing_notes" id="marketing_notes" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('marketing_notes') border-red-500 @enderror" 
                                      rows="3" placeholder="ملاحظات للفريق التسويقي...">{{ old('marketing_notes', $offer->marketing_notes) }}</textarea>
                            @error('marketing_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('facility.offers.show', $offer) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
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

