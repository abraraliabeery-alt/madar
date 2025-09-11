@extends('facility.layouts.app')

@section('title', 'التقارير المالية')

@section('content')
<div class="w-full px-4 my-6">
    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-3xl font-bold">{{ number_format($quickStats['total_revenue'], 2) }}</h4>
                    <p class="text-blue-100">إجمالي الإيرادات</p>
                </div>
                <div class="bg-blue-400 rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-3xl font-bold">{{ number_format($quickStats['total_payments'], 2) }}</h4>
                    <p class="text-green-100">إجمالي المدفوعات</p>
                </div>
                <div class="bg-green-400 rounded-full p-3">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-3xl font-bold">{{ $quickStats['total_contracts'] }}</h4>
                    <p class="text-cyan-100">إجمالي العقود</p>
                </div>
                <div class="bg-cyan-400 rounded-full p-3">
                    <i class="fas fa-file-contract text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-3xl font-bold">{{ $quickStats['pending_payments'] }}</h4>
                    <p class="text-yellow-100">المدفوعات المعلقة</p>
                </div>
                <div class="bg-yellow-400 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- التقارير الرئيسية -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- تقرير الإيرادات -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    تقرير الإيرادات
                </h5>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="text-sm font-medium text-gray-600 mb-2">الفترة المحددة</h6>
                    <p class="text-gray-800">{{ $revenueReport['period'] ?? 'جميع الفترات' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-green-600">{{ number_format($revenueReport['total_revenue'], 2) }}</h4>
                        <p class="text-sm text-gray-600">إجمالي الإيرادات</p>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-blue-600">{{ number_format($revenueReport['monthly_average'], 2) }}</h4>
                        <p class="text-sm text-gray-600">متوسط شهري</p>
                    </div>
                </div>
                <a href="{{ route('facility.financial.revenue') }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-center block transition-colors">
                    عرض التقرير
                </a>
            </div>
        </div>

        <!-- تقرير المدفوعات -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    تقرير المدفوعات
                </h5>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="text-sm font-medium text-gray-600 mb-2">آخر تحديث</h6>
                    <p class="text-gray-800">{{ $paymentsReport['last_updated'] ?? 'اليوم' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-green-600">{{ number_format($paymentsReport['total_paid'], 2) }}</h4>
                        <p class="text-sm text-gray-600">المدفوع</p>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-yellow-600">{{ number_format($paymentsReport['pending_amount'], 2) }}</h4>
                        <p class="text-sm text-gray-600">المعلق</p>
                    </div>
                </div>
                <a href="{{ route('facility.financial.payments') }}" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-center block transition-colors">
                    عرض التقرير
                </a>
            </div>
        </div>

        <!-- تقرير الفواتير -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i>
                    تقرير الفواتير
                </h5>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="text-sm font-medium text-gray-600 mb-2">حالة الفواتير</h6>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">مرسلة</span>
                            <span class="text-sm font-medium">{{ $invoicesReport['sent_count'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">مدفوعة</span>
                            <span class="text-sm font-medium text-green-600">{{ $invoicesReport['paid_count'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">متأخرة</span>
                            <span class="text-sm font-medium text-red-600">{{ $invoicesReport['overdue_count'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('facility.financial.invoices') }}" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-center block transition-colors">
                    عرض التقرير
                </a>
            </div>
        </div>

        <!-- تقرير العقود -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-file-contract mr-2"></i>
                    تقرير العقود
                </h5>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="text-sm font-medium text-gray-600 mb-2">نشاط العقود</h6>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">نشطة</span>
                            <span class="text-sm font-medium text-green-600">{{ $contractsReport['active_count'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">منتهية</span>
                            <span class="text-sm font-medium text-gray-600">{{ $contractsReport['expired_count'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">قريب الانتهاء</span>
                            <span class="text-sm font-medium text-yellow-600">{{ $contractsReport['expiring_soon'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('facility.financial.contracts') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-center block transition-colors">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <!-- تقارير إضافية -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-8">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-4 rounded-t-lg">
            <h5 class="text-lg font-semibold flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                تقارير إضافية
            </h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- تقرير شهري -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h6 class="font-semibold text-gray-800 mb-2">تقرير شهري</h6>
                    <p class="text-sm text-gray-600 mb-4">عرض الإحصائيات المالية للشهر الحالي</p>
                    <form method="GET" action="{{ route('facility.financial.monthly') }}" class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الشهر</label>
                            <select name="month" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                            عرض التقرير
                        </button>
                    </form>
                </div>

                <!-- تقرير سنوي -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h6 class="font-semibold text-gray-800 mb-2">تقرير سنوي</h6>
                    <p class="text-sm text-gray-600 mb-4">عرض الإحصائيات المالية للسنة الحالية</p>
                    <form method="GET" action="{{ route('facility.financial.yearly') }}" class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">السنة</label>
                            <select name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                            عرض التقرير
                        </button>
                    </form>
                </div>

                <!-- تصدير البيانات -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h6 class="font-semibold text-gray-800 mb-2">تصدير البيانات</h6>
                    <p class="text-sm text-gray-600 mb-4">تصدير التقارير المالية بصيغ مختلفة</p>
                    <div class="space-y-2">
                        <a href="{{ route('facility.financial.export') }}" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-center block transition-colors">
                            <i class="fas fa-download mr-2"></i>تصدير Excel
                        </a>
                        <a href="{{ route('facility.financial.export') }}?format=pdf" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-center block transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>تصدير PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- روابط سريعة -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-4 rounded-t-lg">
            <h5 class="text-lg font-semibold flex items-center">
                <i class="fas fa-link mr-2"></i>
                روابط سريعة
            </h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('facility.financial.monthly') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-center transition-colors">
                    <i class="fas fa-calendar-alt text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">تقرير شهري</span>
                </a>
                <a href="{{ route('facility.financial.yearly') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-center transition-colors">
                    <i class="fas fa-calendar text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">تقرير سنوي</span>
                </a>
                <a href="{{ route('facility.payments.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-center transition-colors">
                    <i class="fas fa-credit-card text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">المدفوعات</span>
                </a>
                <a href="{{ route('facility.invoices.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-center transition-colors">
                    <i class="fas fa-file-invoice text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">الفواتير</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection