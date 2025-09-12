@extends('facility.layouts.app')

@section('title', 'عرض الحساب - ' . $account->account_name)

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">عرض الحساب: {{ $account->account_name }}</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.chart-of-accounts.edit', $account) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-edit"></i>
                                <span>تعديل</span>
                            </a>
                            <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-arrow-right"></i>
                                <span>العودة للقائمة</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- معلومات الحساب -->
                        <div class="lg:col-span-2">
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                    <h5 class="text-lg font-semibold text-gray-800 mb-0">معلومات الحساب</h5>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">كود الحساب:</label>
                                            <p class="text-gray-900 py-2">{{ $account->account_code }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم الحساب:</label>
                                            <p class="text-gray-900 py-2">{{ $account->account_name }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع الحساب:</label>
                                            <p class="py-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $account->account_type === 'asset' ? 'blue' : ($account->account_type === 'liability' ? 'yellow' : ($account->account_type === 'equity' ? 'cyan' : ($account->account_type === 'revenue' ? 'green' : 'red'))) }}-100 text-{{ $account->account_type === 'asset' ? 'blue' : ($account->account_type === 'liability' ? 'yellow' : ($account->account_type === 'equity' ? 'cyan' : ($account->account_type === 'revenue' ? 'green' : 'red'))) }}-800">
                                                    {{ $accountTypes[$account->account_type] ?? $account->account_type }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">فئة الحساب:</label>
                                            <p class="text-gray-900 py-2">{{ $accountCategories[$account->account_category] ?? $account->account_category }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">الرصيد الطبيعي:</label>
                                            <p class="py-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $account->normal_balance === 'debit' ? 'blue' : 'green' }}-100 text-{{ $account->normal_balance === 'debit' ? 'blue' : 'green' }}-800">
                                                    {{ $account->normal_balance === 'debit' ? 'مدين' : 'دائن' }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">المستوى:</label>
                                            <p class="py-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">المستوى {{ $account->level }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    @if($account->parentAccount)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الحساب الأب:</label>
                                                <p class="py-2">
                                                    <a href="{{ route('facility.accounting.chart-of-accounts.show', $account->parentAccount) }}" class="text-blue-600 hover:text-blue-900">
                                                        {{ $account->parentAccount->account_code }} - {{ $account->parentAccount->account_name }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($account->description)
                                        <div class="grid grid-cols-1 gap-6">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الوصف:</label>
                                                <p class="text-gray-900 py-2">{{ $account->description }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">الحالة:</label>
                                            <p class="py-2">
                                                @if($account->is_active)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">غير نشط</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع الحساب:</label>
                                            <p class="py-2">
                                                @if($account->is_system)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">حساب نظام</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">حساب عادي</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ملخص مالي -->
                        <div class="lg:col-span-1">
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                    <h5 class="text-lg font-semibold text-gray-800 mb-0">الملخص المالي</h5>
                                </div>
                                <div class="p-6">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الرصيد الافتتاحي:</label>
                                        <p class="text-xl font-semibold">
                                            <span class="{{ $account->opening_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $account->formatted_opening_balance }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الرصيد الحالي:</label>
                                        <p class="text-2xl font-bold">
                                            <span class="{{ $account->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $account->formatted_balance }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">إجمالي الحركات:</label>
                                        <p class="text-gray-900">
                                            <span class="text-blue-600">{{ $account->entries_count }} حركة</span>
                                        </p>
                                    </div>

                                    @if($account->children_count > 0)
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">الحسابات الفرعية:</label>
                                            <p class="text-gray-900">
                                                <span class="text-blue-600">{{ $account->children_count }} حساب فرعي</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الحسابات الفرعية -->
                    @if($account->children->count() > 0)
                        <div class="mt-6">
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                    <h5 class="text-lg font-semibold text-gray-800 mb-0">الحسابات الفرعية</h5>
                                </div>
                                <div class="p-6">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كود الحساب</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الحساب</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد الحالي</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($account->children as $child)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-6 py-4 whitespace-nowrap"><strong class="text-gray-900">{{ $child->account_code }}</strong></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $child->account_name }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $child->account_type === 'asset' ? 'blue' : ($child->account_type === 'liability' ? 'yellow' : ($child->account_type === 'equity' ? 'cyan' : ($child->account_type === 'revenue' ? 'green' : 'red'))) }}-100 text-{{ $child->account_type === 'asset' ? 'blue' : ($child->account_type === 'liability' ? 'yellow' : ($child->account_type === 'equity' ? 'cyan' : ($child->account_type === 'revenue' ? 'green' : 'red'))) }}-800">
                                                                    {{ $accountTypes[$child->account_type] ?? $child->account_type }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="{{ $child->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                    {{ $child->formatted_balance }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if($child->is_active)
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                                                @else
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">غير نشط</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                <a href="{{ route('facility.accounting.chart-of-accounts.show', $child) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- آخر الحركات -->
                    @if($account->entries->count() > 0)
                        <div class="mt-6">
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
                                    <h5 class="text-lg font-semibold text-gray-800 mb-0">آخر الحركات</h5>
                                    <a href="{{ route('facility.accounting.entries.index', ['account_id' => $account->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        عرض جميع الحركات
                                    </a>
                                </div>
                                <div class="p-6">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع القيد</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($account->entries()->latest()->limit(10)->get() as $entry)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->entry_date ? $entry->entry_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $entry->description ?? '' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $entry->entry_type === 'debit' ? 'blue' : 'green' }}-100 text-{{ $entry->entry_type === 'debit' ? 'blue' : 'green' }}-800">
                                                                    {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="{{ $entry->entry_type === 'debit' ? 'text-blue-600' : 'text-green-600' }}">
                                                                    {{ $entry->formatted_amount ?? '0.00 ر.س' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="{{ ($entry->running_balance ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                    {{ $entry->formatted_running_balance ?? '0.00 ر.س' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

