@extends('facility.layouts.app')

@section('title', 'الميزانيات')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">الميزانيات</h4>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('facility.accounting.budgets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>إضافة ميزانية جديدة</span>
                            </a>
                            <a href="{{ route('facility.accounting.budgets.create-year') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-calendar-plus"></i>
                                <span>إنشاء ميزانية سنوية</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div>
                            <input type="text" name="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الميزانيات..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمدة</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                            </select>
                        </div>
                        <div>
                            <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="من تاريخ" value="{{ request('start_date') }}">
                        </div>
                        <div>
                            <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="إلى تاريخ" value="{{ request('end_date') }}">
                        </div>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-search"></i>
                                <span>بحث</span>
                            </button>
                            <a href="{{ route('facility.accounting.budgets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                <i class="fas fa-times"></i>
                                <span>مسح</span>
                            </a>
                        </div>
                    </form>

                    @if($budgets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الميزانية</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفترة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المخصص</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المنفق</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المتبقي</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نسبة الاستهلاك</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($budgets as $budget)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $budget->name }}</div>
                                                    @if($budget->is_current)
                                                        <div class="text-sm text-green-600">ميزانية حالية</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $budget->start_date->format('Y-m-d') }}</div>
                                                <div class="text-sm text-gray-500">{{ $budget->end_date->format('Y-m-d') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-blue-600">{{ $budget->formatted_amount }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-yellow-600">{{ $budget->formatted_spent_amount ?? '0.00 ر.س' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium {{ ($budget->remaining_amount ?? $budget->amount) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $budget->formatted_remaining_amount ?? $budget->formatted_amount }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $usagePercentage = $budget->amount > 0 ? (($budget->spent_amount ?? 0) / $budget->amount) * 100 : 0;
                                                @endphp
                                                <div class="w-full bg-gray-200 rounded-full h-5">
                                                    <div class="h-5 rounded-full flex items-center justify-center text-xs font-medium text-white {{ $usagePercentage > 90 ? 'bg-red-600' : ($usagePercentage > 75 ? 'bg-yellow-600' : 'bg-green-600') }}" 
                                                         style="width: {{ min(100, $usagePercentage) }}%">
                                                        {{ number_format($usagePercentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($budget->status === 'pending')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">معلقة</span>
                                                @elseif($budget->status === 'active')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشطة</span>
                                                @elseif($budget->status === 'approved')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">معتمدة</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مكتملة</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse">
                                                    <a href="{{ route('facility.accounting.budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.budgets.edit', $budget) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($budget->status === 'pending')
                                                        <a href="{{ route('facility.accounting.budgets.approve', $budget) }}" 
                                                           class="text-green-600 hover:text-green-900 p-1 rounded" 
                                                           onclick="return confirm('هل أنت متأكد من اعتماد هذه الميزانية؟')">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                    @if($budget->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.budgets.destroy', $budget) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الميزانية؟')">
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
                            {{ $budgets->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-chart-pie text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد ميزانيات</h5>
                            <p class="text-gray-500 mb-6">ابدأ بإنشاء ميزانية جديدة</p>
                            <div class="flex justify-center space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.budgets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-plus"></i>
                                    <span>إضافة ميزانية جديدة</span>
                                </a>
                                <a href="{{ route('facility.accounting.budgets.create-year') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>إنشاء ميزانية سنوية</span>
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
    document.querySelectorAll('select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

