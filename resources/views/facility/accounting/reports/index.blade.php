@extends('facility.layouts.app')

@section('title', 'التقارير المالية')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">التقارير المالية</h3>
            <a href="{{ route('facility.accounting.reports.export-all') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                <i class="fas fa-download"></i>
                <span>تصدير جميع التقارير</span>
            </a>
        </div>

        <div class="p-6">
            <!-- فلترة التقارير -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">الفترة المحاسبية</label>
                        <select name="period_id" id="period_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">جميع الفترات</option>
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                    {{ $period->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                        <input type="date" name="start_date" id="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                        <input type="date" name="end_date" id="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ request('end_date') }}">
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">العملة</label>
                        <select name="currency" id="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="SAR" {{ request('currency') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                            <option value="USD" {{ request('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                            <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>يورو</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <div class="flex space-x-2 space-x-reverse w-full">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                                <i class="fas fa-search"></i>
                                <span>تطبيق</span>
                            </button>
                            <a href="{{ route('facility.accounting.reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                                <i class="fas fa-times"></i>
                                <span>مسح</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- تقارير أساسية -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-chart-line text-primary-600 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">قائمة الدخل</h5>
                    <p class="text-gray-600 text-sm mb-4">عرض الإيرادات والمصروفات والأرباح</p>
                    <a href="{{ route('facility.accounting.reports.income-statement', request()->query()) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-eye"></i>
                        <span>عرض التقرير</span>
                    </a>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-balance-scale text-blue-600 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">الميزانية العمومية</h5>
                    <p class="text-gray-600 text-sm mb-4">عرض الأصول والخصوم وحقوق الملكية</p>
                    <a href="{{ route('facility.accounting.reports.balance-sheet', request()->query()) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-eye"></i>
                        <span>عرض التقرير</span>
                    </a>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-coins text-green-600 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">التدفق النقدي</h5>
                    <p class="text-gray-600 text-sm mb-4">عرض التدفقات النقدية الداخلة والخارجة</p>
                    <a href="{{ route('facility.accounting.reports.cash-flow', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-eye"></i>
                        <span>عرض التقرير</span>
                    </a>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-calculator text-yellow-600 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">ميزان المراجعة</h5>
                    <p class="text-gray-600 text-sm mb-4">عرض جميع الحسابات وأرصدتها</p>
                    <a href="{{ route('facility.accounting.reports.trial-balance', request()->query()) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-eye"></i>
                        <span>عرض التقرير</span>
                    </a>
                </div>
            </div>

            <!-- تقارير إضافية -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-chart-pie text-gray-600 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">تقرير الميزانية</h5>
                    <p class="text-gray-600 text-sm mb-4">مقارنة الميزانية المخططة مع الفعلية</p>
                    <a href="{{ route('facility.accounting.reports.budget-report', request()->query()) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-eye"></i>
                        <span>عرض التقرير</span>
                    </a>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-chart-bar text-gray-800 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">تقرير الحسابات</h5>
                    <p class="text-gray-600 text-sm mb-4">تفاصيل حركات الحسابات</p>
                    <a href="{{ route('facility.accounting.reports.account-details', request()->query()) }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-eye"></i>
                        <span>عرض التقرير</span>
                    </a>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                    <i class="fas fa-file-alt text-primary-600 text-4xl mb-4"></i>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">تقرير مخصص</h5>
                    <p class="text-gray-600 text-sm mb-4">إنشاء تقرير مخصص حسب الحاجة</p>
                    <a href="{{ route('facility.accounting.reports.custom', request()->query()) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>إنشاء تقرير</span>
                    </a>
                </div>
            </div>

            <!-- ملخص سريع -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800">ملخص سريع</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <h4 class="text-green-600 text-2xl font-bold">{{ number_format($summary['total_revenue'] ?? 0, 2) }} ر.س</h4>
                            <p class="text-gray-600 mt-1">إجمالي الإيرادات</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-red-600 text-2xl font-bold">{{ number_format($summary['total_expenses'] ?? 0, 2) }} ر.س</h4>
                            <p class="text-gray-600 mt-1">إجمالي المصروفات</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-{{ ($summary['net_profit'] ?? 0) >= 0 ? 'green' : 'red' }}-600 text-2xl font-bold">
                                {{ number_format($summary['net_profit'] ?? 0, 2) }} ر.س
                            </h4>
                            <p class="text-gray-600 mt-1">صافي الربح</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-blue-600 text-2xl font-bold">{{ number_format($summary['total_assets'] ?? 0, 2) }} ر.س</h4>
                            <p class="text-gray-600 mt-1">إجمالي الأصول</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on period change
    document.getElementById('period_id').addEventListener('change', function() {
        if (this.value) {
            // Get period dates and fill start/end date fields
            const periodOption = this.options[this.selectedIndex];
            const periodText = periodOption.text;
            
            // You can implement logic to extract dates from period name
            // For now, just submit the form
            this.form.submit();
        }
    });

    // Set default date range to current month
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        if (!document.getElementById('start_date').value) {
            document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
        }
        if (!document.getElementById('end_date').value) {
            document.getElementById('end_date').value = lastDay.toISOString().split('T')[0];
        }
    });
</script>
@endpush