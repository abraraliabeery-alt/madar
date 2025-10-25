@extends('facility.layouts.app')

@section('title', 'القيود المحاسبية')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">القيود المحاسبية</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.accounting.entries.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>قيد محاسبي جديد</span>
                </a>
                <a href="{{ route('facility.accounting.entries.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-download"></i>
                    <span>تصدير</span>
                </a>
            </div>
        </div>

        <!-- فلترة وبحث -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <select name="period_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">جميع الفترات</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->period_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="account_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">جميع أنواع الحسابات</option>
                        @foreach($accountTypes as $key => $value)
                            <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" name="date_from" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="من تاريخ" value="{{ request('date_from') }}">
                </div>
                <div>
                    <input type="date" name="date_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="إلى تاريخ" value="{{ request('date_to') }}">
                </div>
                <div>
                    <select name="is_reversed" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">جميع الحالات</option>
                        <option value="0" {{ request('is_reversed') === '0' ? 'selected' : '' }}>نشط</option>
                        <option value="1" {{ request('is_reversed') === '1' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-search"></i>
                        <span>بحث</span>
                    </button>
                    <a href="{{ route('facility.accounting.entries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-times"></i>
                        <span>مسح</span>
                    </a>
                </div>
            </form>
        </div>

        @if($entries->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع القيد</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحساب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفترة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">منشئ القيد</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($entries as $entry)
                            <tr class="{{ $entry->is_reversed ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->entry_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $entry->entry_type === 'debit' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $entry->account->account_name ?? 'غير محدد' }}</div>
                                        @if($entry->account)
                                            <div class="text-sm text-gray-500">{{ $entry->account->account_code }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $entry->formatted_amount }}</div>
                                    @if($entry->tax_amount > 0)
                                        <div class="text-sm text-gray-500">ضريبة: {{ $entry->formatted_tax_amount }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($entry->description, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->period->period_name ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->createdBy->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($entry->is_reversed)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ملغي</span>
                                        @if($entry->reversed_at)
                                            <div class="text-xs text-gray-500">{{ $entry->reversed_at->format('Y-m-d H:i') }}</div>
                                        @endif
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('facility.accounting.entries.show', $entry) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($entry->canBeReversed())
                                            <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition-colors" onclick="openReverseModal({{ $entry->id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>
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
                {{ $entries->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-file-invoice text-gray-400 text-6xl mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-500 mb-2">لا توجد قيود محاسبية</h5>
                <p class="text-gray-400 mb-6">ابدأ بإنشاء قيد محاسبي جديد</p>
                <a href="{{ route('facility.accounting.entries.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>إنشاء قيد جديد
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal for reversing entries -->
<div id="reverseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">إلغاء القيد المحاسبي</h3>
                <button onclick="closeReverseModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="reverseForm" method="POST">
                @csrf
                <div class="mb-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800">تحذير</h4>
                                <p class="text-sm text-yellow-700 mt-1">هل أنت متأكد من إلغاء هذا القيد المحاسبي؟ سيتم إنشاء قيد معكوس تلقائياً.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">سبب الإلغاء *</label>
                    <textarea id="reason" name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeReverseModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                        تأكيد الإلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openReverseModal(entryId) {
        document.getElementById('reverseForm').action = `/facility/accounting/entries/${entryId}/reverse`;
        document.getElementById('reverseModal').classList.remove('hidden');
    }

    function closeReverseModal() {
        document.getElementById('reverseModal').classList.add('hidden');
        document.getElementById('reason').value = '';
    }

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="period_id"], select[name="account_type"], select[name="is_reversed"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush