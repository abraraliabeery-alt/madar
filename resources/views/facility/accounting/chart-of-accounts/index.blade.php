@extends('facility.layouts.app')

@section('title', 'دليل الحسابات')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">دليل الحسابات</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.chart-of-accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة حساب جديد</span>
                            </a>
                            <a href="{{ route('facility.accounting.chart-of-accounts.create-default') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-magic"></i>
                                <span>إنشاء دليل افتراضي</span>
                            </a>
                            <a href="{{ route('facility.accounting.chart-of-accounts.export') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-download"></i>
                                <span>تصدير</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- فلترة وبحث -->
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الحسابات..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="account_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الأنواع</option>
                                @foreach($accountTypes as $key => $value)
                                    <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="account_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الفئات</option>
                                @foreach($accountCategories as $key => $value)
                                    <option value="{{ $key }}" {{ request('account_category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-search"></i>
                                <span>بحث</span>
                            </button>
                            <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-times"></i>
                                <span>مسح</span>
                            </a>
                        </div>
                    </form>

                    @if($accounts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كود الحساب</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الحساب</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد الطبيعي</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد الحالي</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستوى</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($accounts as $account)
                                        <tr class="{{ $account->level > 1 ? 'bg-gray-50' : 'hover:bg-gray-50' }}" style="padding-left: {{ ($account->level - 1) * 20 }}px;">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $account->account_code }}</div>
                                                @if($account->is_system)
                                                    <div class="text-sm text-gray-500">حساب نظام</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $account->account_name }}</div>
                                                    @if($account->parentAccount)
                                                        <div class="text-sm text-gray-500">تحت: {{ $account->parentAccount->account_name }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $account->account_type === 'asset' ? 'blue' : ($account->account_type === 'liability' ? 'yellow' : ($account->account_type === 'equity' ? 'cyan' : ($account->account_type === 'revenue' ? 'green' : 'red'))) }}-100 text-{{ $account->account_type === 'asset' ? 'blue' : ($account->account_type === 'liability' ? 'yellow' : ($account->account_type === 'equity' ? 'cyan' : ($account->account_type === 'revenue' ? 'green' : 'red'))) }}-800">
                                                    {{ $accountTypes[$account->account_type] ?? $account->account_type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $accountCategories[$account->account_category] ?? $account->account_category }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $account->normal_balance === 'debit' ? 'blue' : 'green' }}-100 text-{{ $account->normal_balance === 'debit' ? 'blue' : 'green' }}-800">
                                                    {{ $account->normal_balance === 'debit' ? 'مدين' : 'دائن' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium {{ $account->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $account->formatted_balance }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">المستوى {{ $account->level }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($account->is_active)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">غير نشط</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.accounting.chart-of-accounts.show', $account) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.chart-of-accounts.edit', $account) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($account->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.chart-of-accounts.destroy', $account) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            {{ $accounts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-list-alt text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد حسابات</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإنشاء دليل الحسابات</p>
                            <div class="flex justify-center space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.chart-of-accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-plus"></i>
                                    <span>إضافة حساب جديد</span>
                                </a>
                                <a href="{{ route('facility.accounting.chart-of-accounts.create-default') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-magic"></i>
                                    <span>إنشاء دليل افتراضي</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="account_type"], select[name="account_category"], select[name="is_active"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

