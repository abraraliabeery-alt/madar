@extends('facility.layouts.app')

@section('title', 'إحصائيات العروض')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إحصائيات العروض</h4>
                        <a href="{{ route('facility.offers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            <span>العودة للقائمة</span>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- إحصائيات عامة -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-tags text-2xl text-blue-600"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-blue-600">إجمالي العروض</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total_offers'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-green-600">عروض نشطة</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $stats['active_offers'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-2xl text-red-600"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-red-600">عروض منتهية</p>
                                    <p class="text-2xl font-bold text-red-900">{{ $stats['expired_offers'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-dollar-sign text-2xl text-purple-600"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-purple-600">إجمالي القيمة</p>
                                    <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['total_value'], 2) }} ريال</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات مفصلة -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- أنواع العروض -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4">توزيع أنواع العروض</h5>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-700">عروض البيع</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $stats['sale_offers'] }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-700">عروض الإيجار</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $stats['rent_offers'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- حالة العروض -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4">حالة العروض</h5>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-700">نشطة</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $stats['active_offers'] }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-700">منتهية</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $stats['expired_offers'] }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-gray-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-700">غير نشطة</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $stats['total_offers'] - $stats['active_offers'] - $stats['expired_offers'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- رسوم بيانية -->
                    <div class="mt-8">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4">توزيع العروض</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- مخطط دائري للأنواع -->
                                <div>
                                    <h6 class="text-md font-medium text-gray-700 mb-3">أنواع العروض</h6>
                                    <div class="relative w-48 h-48 mx-auto">
                                        <canvas id="offerTypesChart"></canvas>
                                    </div>
                                </div>
                                
                                <!-- مخطط دائري للحالة -->
                                <div>
                                    <h6 class="text-md font-medium text-gray-700 mb-3">حالة العروض</h6>
                                    <div class="relative w-48 h-48 mx-auto">
                                        <canvas id="offerStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملخص الأداء -->
                    <div class="mt-8">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4">ملخص الأداء</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 mb-2">
                                        {{ $stats['total_offers'] > 0 ? round(($stats['active_offers'] / $stats['total_offers']) * 100, 1) : 0 }}%
                                    </div>
                                    <p class="text-sm text-gray-600">نسبة العروض النشطة</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-2">
                                        {{ $stats['sale_offers'] + $stats['rent_offers'] > 0 ? round(($stats['sale_offers'] / ($stats['sale_offers'] + $stats['rent_offers'])) * 100, 1) : 0 }}%
                                    </div>
                                    <p class="text-sm text-gray-600">نسبة عروض البيع</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600 mb-2">
                                        {{ number_format($stats['total_value'] / max($stats['total_offers'], 1), 2) }}
                                    </div>
                                    <p class="text-sm text-gray-600">متوسط قيمة العرض (ريال)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // مخطط أنواع العروض
    const offerTypesCtx = document.getElementById('offerTypesChart').getContext('2d');
    new Chart(offerTypesCtx, {
        type: 'doughnut',
        data: {
            labels: ['عروض البيع', 'عروض الإيجار'],
            datasets: [{
                data: [{{ $stats['sale_offers'] }}, {{ $stats['rent_offers'] }}],
                backgroundColor: ['#10B981', '#3B82F6'],
                borderWidth: 0
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

    // مخطط حالة العروض
    const offerStatusCtx = document.getElementById('offerStatusChart').getContext('2d');
    new Chart(offerStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['نشطة', 'منتهية', 'غير نشطة'],
            datasets: [{
                data: [
                    {{ $stats['active_offers'] }}, 
                    {{ $stats['expired_offers'] }}, 
                    {{ $stats['total_offers'] - $stats['active_offers'] - $stats['expired_offers'] }}
                ],
                backgroundColor: ['#10B981', '#EF4444', '#6B7280'],
                borderWidth: 0
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
</script>
@endpush
