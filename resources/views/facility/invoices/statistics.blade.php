@extends('facility.layouts.app')

@section('title', 'إحصائيات الفواتير')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('facility.invoices.index') }}">الفواتير</a></li>
    <li class="breadcrumb-item active">الإحصائيات</li>
@endsection

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
            <h3 class="text-xl font-semibold">إحصائيات الفواتير</h3>
        </div>

        <div class="p-6">
            <!-- إحصائيات سريعة -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-invoice text-blue-600 text-2xl"></i>
                        </div>
                        <div class="mr-4">
                            <p class="text-sm font-medium text-blue-600">إجمالي الفواتير</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($stats['total_invoices']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="mr-4">
                            <p class="text-sm font-medium text-green-600">الفواتير المدفوعة</p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($stats['paid_invoices']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="mr-4">
                            <p class="text-sm font-medium text-yellow-600">الفواتير المعلقة</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ number_format($stats['pending_invoices']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <div class="mr-4">
                            <p class="text-sm font-medium text-red-600">الفواتير المتأخرة</p>
                            <p class="text-2xl font-bold text-red-900">{{ number_format($stats['overdue_invoices']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- إحصائيات مالية -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                        الإجمالي المالي
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">إجمالي المبلغ:</span>
                            <span class="font-semibold text-lg">{{ number_format($stats['total_amount'], 2) }} SAR</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ المدفوع:</span>
                            <span class="font-semibold text-green-600">{{ number_format($stats['paid_amount'], 2) }} SAR</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المبلغ المتبقي:</span>
                            <span class="font-semibold text-red-600">{{ number_format($stats['remaining_amount'], 2) }} SAR</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-percentage text-blue-600 mr-2"></i>
                        معدلات التحصيل
                    </h4>
                    <div class="space-y-3">
                        @php
                            $collectionRate = $stats['total_amount'] > 0 ? ($stats['paid_amount'] / $stats['total_amount']) * 100 : 0;
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-gray-600">معدل التحصيل:</span>
                            <span class="font-semibold">{{ number_format($collectionRate, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $collectionRate }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        توزيع الحالات
                    </h4>
                    <div class="space-y-2">
                        @php
                            $total = $stats['total_invoices'];
                            $paidPercent = $total > 0 ? ($stats['paid_invoices'] / $total) * 100 : 0;
                            $pendingPercent = $total > 0 ? ($stats['pending_invoices'] / $total) * 100 : 0;
                            $overduePercent = $total > 0 ? ($stats['overdue_invoices'] / $total) * 100 : 0;
                        @endphp
                        <div class="flex justify-between text-sm">
                            <span class="text-green-600">مدفوعة</span>
                            <span>{{ number_format($paidPercent, 1) }}%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-600">معلقة</span>
                            <span>{{ number_format($pendingPercent, 1) }}%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-red-600">متأخرة</span>
                            <span>{{ number_format($overduePercent, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- رسوم بيانية -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">توزيع الفواتير حسب الحالة</h4>
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>

                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">التوزيع المالي</h4>
                    <canvas id="amountChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- ملخص الأداء -->
            <div class="bg-gradient-to-r from-primary-50 to-primary-100 p-6 rounded-lg border border-primary-200">
                <h4 class="text-lg font-semibold text-primary-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-primary-600 mr-2"></i>
                    ملخص الأداء
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-800">{{ number_format($collectionRate, 1) }}%</div>
                        <div class="text-sm text-primary-600">معدل التحصيل</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-800">{{ number_format($stats['total_invoices']) }}</div>
                        <div class="text-sm text-primary-600">إجمالي الفواتير</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-800">{{ number_format($stats['total_amount'], 2) }}</div>
                        <div class="text-sm text-primary-600">إجمالي المبلغ (SAR)</div>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('facility.invoices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    العودة للقائمة
                </a>
                <a href="{{ route('facility.invoices.export') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    تصدير البيانات
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // توزيع الفواتير حسب الحالة
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['مدفوعة', 'معلقة', 'متأخرة'],
            datasets: [{
                data: [{{ $stats['paid_invoices'] }}, {{ $stats['pending_invoices'] }}, {{ $stats['overdue_invoices'] }}],
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // التوزيع المالي
    const amountCtx = document.getElementById('amountChart').getContext('2d');
    new Chart(amountCtx, {
        type: 'bar',
        data: {
            labels: ['إجمالي المبلغ', 'المبلغ المدفوع', 'المبلغ المتبقي'],
            datasets: [{
                data: [{{ $stats['total_amount'] }}, {{ $stats['paid_amount'] }}, {{ $stats['remaining_amount'] }}],
                backgroundColor: ['#3B82F6', '#10B981', '#EF4444'],
                borderWidth: 1,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' SAR';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection


