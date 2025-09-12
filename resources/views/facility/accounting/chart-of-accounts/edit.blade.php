@extends('facility.layouts.app')

@section('title', 'تعديل الحساب - ' . $account->account_name)

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">تعديل الحساب: {{ $account->account_name }}</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.chart-of-accounts.show', $account) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-eye"></i>
                                <span>عرض</span>
                            </a>
                            <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-arrow-right"></i>
                                <span>العودة للقائمة</span>
                            </a>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('facility.accounting.chart-of-accounts.update', $account) }}">
                    @csrf
                    @method('PUT')
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- معلومات أساسية -->
                            <div>
                                <div class="bg-white border border-gray-200 rounded-lg">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                        <h5 class="text-lg font-semibold text-gray-800 mb-0">المعلومات الأساسية</h5>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-4">
                                            <label for="account_code" class="block text-sm font-medium text-gray-700 mb-2">كود الحساب <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_code') border-red-500 @enderror" 
                                                   id="account_code" name="account_code" value="{{ old('account_code', $account->account_code) }}" required>
                                            @error('account_code')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm text-gray-500">يجب أن يكون فريداً وغير مكرر</p>
                                        </div>

                                        <div class="mb-4">
                                            <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الحساب <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_name') border-red-500 @enderror" 
                                                   id="account_name" name="account_name" value="{{ old('account_name', $account->account_name) }}" required>
                                            @error('account_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="account_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الحساب <span class="text-red-500">*</span></label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_type') border-red-500 @enderror" 
                                                    id="account_type" name="account_type" required>
                                                <option value="">اختر نوع الحساب</option>
                                                @foreach($accountTypes as $key => $value)
                                                    <option value="{{ $key }}" {{ old('account_type', $account->account_type) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('account_type')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="account_category" class="block text-sm font-medium text-gray-700 mb-2">فئة الحساب</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_category') border-red-500 @enderror" 
                                                    id="account_category" name="account_category">
                                                <option value="">اختر فئة الحساب</option>
                                                @foreach($accountCategories as $key => $value)
                                                    <option value="{{ $key }}" {{ old('account_category', $account->account_category) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('account_category')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">الحساب الأب</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('parent_id') border-red-500 @enderror" 
                                                    id="parent_id" name="parent_id">
                                                <option value="">بدون حساب أب</option>
                                                @foreach($parentAccounts as $parentAccount)
                                                    @if($parentAccount->id != $account->id)
                                                        <option value="{{ $parentAccount->id }}" {{ old('parent_id', $account->parent_id) == $parentAccount->id ? 'selected' : '' }}>
                                                            {{ $parentAccount->account_code }} - {{ $parentAccount->account_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إعدادات إضافية -->
                            <div>
                                <div class="bg-white border border-gray-200 rounded-lg">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                        <h5 class="text-lg font-semibold text-gray-800 mb-0">الإعدادات الإضافية</h5>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-4">
                                            <label for="normal_balance" class="block text-sm font-medium text-gray-700 mb-2">الرصيد الطبيعي <span class="text-red-500">*</span></label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('normal_balance') border-red-500 @enderror" 
                                                    id="normal_balance" name="normal_balance" required>
                                                <option value="">اختر الرصيد الطبيعي</option>
                                                <option value="debit" {{ old('normal_balance', $account->normal_balance) == 'debit' ? 'selected' : '' }}>مدين</option>
                                                <option value="credit" {{ old('normal_balance', $account->normal_balance) == 'credit' ? 'selected' : '' }}>دائن</option>
                                            </select>
                                            @error('normal_balance')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="opening_balance" class="block text-sm font-medium text-gray-700 mb-2">الرصيد الافتتاحي</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('opening_balance') border-red-500 @enderror" 
                                                       id="opening_balance" name="opening_balance" value="{{ old('opening_balance', $account->opening_balance) }}">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 text-sm">ر.س</span>
                                                </div>
                                            </div>
                                            @error('opening_balance')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm text-gray-500">تغيير الرصيد الافتتاحي سيؤثر على الرصيد الحالي</p>
                                        </div>

                                        <div class="mb-4">
                                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الحساب</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description', $account->description) }}</textarea>
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <div class="flex items-center">
                                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="is_active" name="is_active" value="1" 
                                                       {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                                                <label class="mr-2 block text-sm text-gray-900" for="is_active">
                                                    حساب نشط
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="flex items-center">
                                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="is_system" name="is_system" value="1" 
                                                       {{ old('is_system', $account->is_system) ? 'checked' : '' }}
                                                       {{ $account->is_system ? 'disabled' : '' }}>
                                                <label class="mr-2 block text-sm text-gray-900" for="is_system">
                                                    حساب نظام (لا يمكن حذفه)
                                                </label>
                                                @if($account->is_system)
                                                    <p class="mt-1 text-sm text-gray-500">هذا حساب نظام ولا يمكن تغيير نوعه</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تحذيرات -->
                        @if($account->entries_count > 0)
                            <div class="mt-4">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-1"></i>
                                        <div>
                                            <h6 class="text-sm font-medium text-yellow-800">تحذير</h6>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                هذا الحساب يحتوي على {{ $account->entries_count }} حركة محاسبية. 
                                                تغيير نوع الحساب أو الرصيد الطبيعي قد يؤثر على التقارير المالية.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($account->children_count > 0)
                            <div class="mt-2">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <i class="fas fa-info-circle text-blue-400 mr-3 mt-1"></i>
                                        <div>
                                            <h6 class="text-sm font-medium text-blue-800">ملاحظة</h6>
                                            <p class="text-sm text-blue-700 mt-1">
                                                هذا الحساب يحتوي على {{ $account->children_count }} حساب فرعي. 
                                                تغيير نوع الحساب قد يؤثر على الحسابات الفرعية.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- أزرار الإجراءات -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg mt-6">
                            <div class="flex justify-end space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.chart-of-accounts.show', $account) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-times"></i>
                                    <span>إلغاء</span>
                                </a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-save"></i>
                                    <span>حفظ التعديلات</span>
                                </button>
                            </div>
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
    // Auto-fill normal balance based on account type
    document.getElementById('account_type').addEventListener('change', function() {
        const accountType = this.value;
        const normalBalanceSelect = document.getElementById('normal_balance');
        
        // Clear previous selection
        normalBalanceSelect.value = '';
        
        // Set default normal balance based on account type
        switch(accountType) {
            case 'asset':
            case 'expense':
                normalBalanceSelect.value = 'debit';
                break;
            case 'liability':
            case 'equity':
            case 'revenue':
                normalBalanceSelect.value = 'credit';
                break;
        }
    });

    // Auto-generate account code based on parent and type
    document.getElementById('parent_id').addEventListener('change', function() {
        const parentId = this.value;
        const accountCodeInput = document.getElementById('account_code');
        
        if (parentId) {
            // If parent is selected, suggest a sub-account code
            const parentOption = this.options[this.selectedIndex];
            const parentCode = parentOption.text.split(' - ')[0];
            
            // Suggest next available sub-code
            accountCodeInput.placeholder = parentCode + '.XX';
        } else {
            accountCodeInput.placeholder = '';
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const accountCode = document.getElementById('account_code').value;
        const accountName = document.getElementById('account_name').value;
        const accountType = document.getElementById('account_type').value;
        const normalBalance = document.getElementById('normal_balance').value;
        
        if (!accountCode || !accountName || !accountType || !normalBalance) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }

        // Check if account type is being changed and there are entries
        const originalAccountType = '{{ $account->account_type }}';
        if (accountType !== originalAccountType && {{ $account->entries_count }} > 0) {
            if (!confirm('تغيير نوع الحساب قد يؤثر على التقارير المالية. هل أنت متأكد من المتابعة؟')) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endpush

