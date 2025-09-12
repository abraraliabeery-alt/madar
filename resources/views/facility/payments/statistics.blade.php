@extends('facility.layouts.app')

@section('title', 'إحصائيات المدفوعات')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إحصائيات المدفوعات</h4>
                        <a href="{{ route('facility.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            <span>العودة للقائمة</span>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- إحصائيات رئيسية -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-credit-card text-2xl text-blue-600"></i>
                                </div>
                                <div class="mr-4 rtl:ml-4">
                                    <p class="text-sm font-medium text-blue-600">إجمالي المدفوعات</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total_payments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                                </div>
                                <div class="mr-4 rtl:ml-4">
                                    <p class="text-sm font-medium text-green-600">مدفوعات مؤكدة</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $stats['confirmed_payments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                                </div>
                                <div class="mr-4 rtl:ml-4">
                                    <p class="text-sm font-medium text-yellow-600">مدفوعات معلقة</p>
                                    <p class="text-2xl font-bold text-yellow-900">{{ $stats['pending_payments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                                </div>
                                <div class="mr-4 rtl:ml-4">
                                    <p class="text-sm font-medium text-red-600">مدفوعات فاشلة</p>
                                    <p class="text-2xl font-bold text-red-900">{{ $stats['failed_payments'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات مالية -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                <h5 class="text-lg font-semibold text-gray-800 mb-0">الإحصائيات المالية</h5>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">إجمالي المبلغ المؤكد</span>
                                        <span class="text-lg font-bold text-green-600">{{ number_format($stats['total_amount'], 2) }} ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">إجمالي الرسوم</span>
                                        <span class="text-lg font-bold text-orange-600">{{ number_format($stats['total_processing_fees'], 2) }} ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">المدفوعات المستردة</span>
                                        <span class="text-lg font-bold text-red-600">{{ $stats['refunded_payments'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                <h5 class="text-lg font-semibold text-gray-800 mb-0">توزيع المدفوعات</h5>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @php
                                        $total = $stats['total_payments'];
                                        $confirmedPercentage = $total > 0 ? ($stats['confirmed_payments'] / $total) * 100 : 0;
                                        $pendingPercentage = $total > 0 ? ($stats['pending_payments'] / $total) * 100 : 0;
                                        $failedPercentage = $total > 0 ? ($stats['failed_payments'] / $total) * 100 : 0;
                                        $refundedPercentage = $total > 0 ? ($stats['refunded_payments'] / $total) * 100 : 0;
                                    @endphp
                                    
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-green-600">مؤكدة</span>
                                            <span class="text-gray-600">{{ number_format($confirmedPercentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $confirmedPercentage }}%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-yellow-600">معلقة</span>
                                            <span class="text-gray-600">{{ number_format($pendingPercentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $pendingPercentage }}%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-red-600">فاشلة</span>
                                            <span class="text-gray-600">{{ number_format($failedPercentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $failedPercentage }}%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">مستردة</span>
                                            <span class="text-gray-600">{{ number_format($refundedPercentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $refundedPercentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملخص الأداء -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                            <h5 class="text-lg font-semibold text-gray-800 mb-0">ملخص الأداء</h5>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 mb-2">
                                        {{ $total > 0 ? number_format(($stats['confirmed_payments'] / $total) * 100, 1) : 0 }}%
                                    </div>
                                    <div class="text-sm text-gray-600">معدل النجاح</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-2">
                                        {{ number_format($stats['total_amount'] - $stats['total_processing_fees'], 2) }} ر.س
                                    </div>
                                    <div class="text-sm text-gray-600">صافي الإيرادات</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-orange-600 mb-2">
                                        {{ $stats['total_processing_fees'] > 0 ? number_format(($stats['total_processing_fees'] / $stats['total_amount']) * 100, 2) : 0 }}%
                                    </div>
                                    <div class="text-sm text-gray-600">نسبة الرسوم</div>
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
<script>
    // يمكن إضافة JavaScript للتفاعل مع الرسوم البيانية هنا
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تأثيرات تفاعلية للبطاقات
        const cards = document.querySelectorAll('.bg-blue-50, .bg-green-50, .bg-yellow-50, .bg-red-50');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg', 'transform', 'scale-105');
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg', 'transform', 'scale-105');
            });
        });
    });
</script>
@endpush
