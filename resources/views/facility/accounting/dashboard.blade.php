@extends('facility.layouts.app')

@section('title', 'لوحة التحكم المحاسبية')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">لوحة التحكم المحاسبية</h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('facility.accounting.entries.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>قيد محاسبي جديد</span>
                </a>
                <a href="{{ route('facility.accounting.reports.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-chart-bar"></i>
                    <span>التقارير المالية</span>
                </a>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">إجمالي الحسابات</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_accounts'] ?? 0) }}</p>
                    </div>
                    <div class="bg-blue-400 rounded-full p-3">
                        <i class="fas fa-list-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">الفترات النشطة</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['active_periods'] ?? 0) }}</p>
                    </div>
                    <div class="bg-green-400 rounded-full p-3">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-sm font-medium">إجمالي القيود</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_entries'] ?? 0) }}</p>
                    </div>
                    <div class="bg-cyan-400 rounded-full p-3">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">القيود المعلقة</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['pending_entries'] ?? 0) }}</p>
                    </div>
                    <div class="bg-yellow-400 rounded-full p-3">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- الفترة المحاسبية الحالية -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-calendar mr-2"></i>
                        الفترة المحاسبية الحالية
                    </h5>
                </div>
                <div class="p-6">
                    @if($currentPeriod)
                        <div class="flex justify-between items-center mb-4">
                            <h6 class="text-lg font-semibold text-gray-800">{{ $currentPeriod->period_name ?? $currentPeriod->name ?? 'غير محدد' }}</h6>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $currentPeriod->status ?? 'غير محدد' }}</span>
                        </div>
                        <p class="text-gray-600 mb-4">
                            من {{ $currentPeriod->start_date ? $currentPeriod->start_date->format('Y-m-d') : 'غير محدد' }} 
                            إلى {{ $currentPeriod->end_date ? $currentPeriod->end_date->format('Y-m-d') : 'غير محدد' }}
                        </p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            @php
                                try {
                                    $duration = $currentPeriod->duration ?? 0;
                                    $startDate = $currentPeriod->start_date;
                                    $progress = $duration > 0 && $startDate ? 
                                        (now()->diffInDays($startDate) / $duration) * 100 : 0;
                                } catch (Exception $e) {
                                    $progress = 0;
                                }
                            @endphp
                            <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: {{ min($progress, 100) }}%"></div>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ round(min($progress, 100)) }}% من الفترة المحاسبية
                        </p>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-calendar-times text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500 mb-4">لا توجد فترة محاسبية نشطة</p>
                            <a href="{{ route('facility.accounting.periods.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                إنشاء فترة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ملخص مالي -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200">
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>
                        الملخص المالي
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <h4 class="text-2xl font-bold text-green-600">{{ number_format($financialSummary['total_revenue'] ?? 0, 2) }}</h4>
                            <p class="text-sm text-gray-600">الإيرادات</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-2xl font-bold text-red-600">{{ number_format($financialSummary['total_expenses'] ?? 0, 2) }}</h4>
                            <p class="text-sm text-gray-600">المصروفات</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-2xl font-bold text-blue-600">{{ number_format($financialSummary['total_assets'] ?? 0, 2) }}</h4>
                            <p class="text-sm text-gray-600">الأصول</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-2xl font-bold text-yellow-600">{{ number_format($financialSummary['total_liabilities'] ?? 0, 2) }}</h4>
                            <p class="text-sm text-gray-600">الخصوم</p>
                        </div>
                    </div>
                    <div class="border-t pt-4 text-center">
                        <h5 class="text-xl font-bold {{ ($financialSummary['net_income'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($financialSummary['net_income'] ?? 0, 2) }} ريال
                        </h5>
                        <p class="text-sm text-gray-600">صافي الدخل</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- آخر القيود المحاسبية -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i>
                    آخر القيود المحاسبية
                </h5>
                <a href="{{ route('facility.accounting.entries.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    عرض الكل
                </a>
            </div>
            <div class="p-6">
                @if($recentEntries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع القيد</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحساب</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">منشئ القيد</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentEntries as $entry)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->entry_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $entry->entry_type === 'debit' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->account->account_name ?? 'غير محدد' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->formatted_amount }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($entry->description, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry->createdBy->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        <!-- الميزانيات النشطة -->
        @if($activeBudgets->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    الميزانيات النشطة
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activeBudgets as $budget)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-center mb-3">
                                <h6 class="font-semibold text-gray-800">{{ $budget->budget_name }}</h6>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $budget->status }}</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">{{ $budget->description }}</p>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-{{ $budget->utilization_percentage > 100 ? 'red' : ($budget->utilization_percentage > 80 ? 'yellow' : 'green') }}-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ min($budget->utilization_percentage, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>{{ number_format($budget->spent_amount, 2) }} / {{ number_format($budget->total_budget, 2) }} ريال</span>
                                <span>{{ round($budget->utilization_percentage, 1) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection