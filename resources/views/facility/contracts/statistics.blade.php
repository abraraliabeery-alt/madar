@extends('facility.layouts.app')

@section('title', 'إحصائيات العقود')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-7xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إحصائيات العقود</h4>
                        <a href="{{ route('facility.contracts.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-arrow-right"></i>
                            <span>رجوع</span>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Contracts -->
                        <div class="bg-blue-600 text-white rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="text-sm font-medium mb-1">إجمالي العقود</h6>
                                    <h3 class="text-2xl font-bold">{{ $stats['total_contracts'] }}</h3>
                                </div>
                                <div class="text-4xl opacity-80">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Active Contracts -->
                        <div class="bg-green-600 text-white rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="text-sm font-medium mb-1">العقود النشطة</h6>
                                    <h3 class="text-2xl font-bold">{{ $stats['active_contracts'] }}</h3>
                                </div>
                                <div class="text-4xl opacity-80">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Verified Contracts -->
                        <div class="bg-cyan-600 text-white rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="text-sm font-medium mb-1">العقود المتحقق منها</h6>
                                    <h3 class="text-2xl font-bold">{{ $stats['verified_contracts'] }}</h3>
                                </div>
                                <div class="text-4xl opacity-80">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Value -->
                        <div class="bg-yellow-600 text-white rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="text-sm font-medium mb-1">إجمالي القيمة</h6>
                                    <h3 class="text-2xl font-bold">{{ number_format($stats['total_value'], 2) }} ر.س</h3>
                                </div>
                                <div class="text-4xl opacity-80">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Draft Contracts -->
                        <div class="bg-gray-100 rounded-lg p-4">
                            <div class="text-center">
                                <h6 class="text-sm font-medium text-gray-600 mb-1">المسودات</h6>
                                <h4 class="text-xl font-bold text-gray-800">{{ $stats['draft_contracts'] }}</h4>
                            </div>
                        </div>

                        <!-- Completed Contracts -->
                        <div class="bg-blue-100 rounded-lg p-4">
                            <div class="text-center">
                                <h6 class="text-sm font-medium text-blue-600 mb-1">المكتملة</h6>
                                <h4 class="text-xl font-bold text-blue-800">{{ $stats['completed_contracts'] }}</h4>
                            </div>
                        </div>

                        <!-- Cancelled Contracts -->
                        <div class="bg-red-100 rounded-lg p-4">
                            <div class="text-center">
                                <h6 class="text-sm font-medium text-red-600 mb-1">الملغية</h6>
                                <h4 class="text-xl font-bold text-red-800">{{ $stats['cancelled_contracts'] }}</h4>
                            </div>
                        </div>

                        <!-- Monthly Revenue -->
                        <div class="bg-green-100 rounded-lg p-4">
                            <div class="text-center">
                                <h6 class="text-sm font-medium text-green-600 mb-1">إيرادات الشهر</h6>
                                <h4 class="text-xl font-bold text-green-800">{{ number_format($stats['monthly_revenue'], 2) }} ر.س</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Contract Types Distribution -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h6 class="text-lg font-semibold text-gray-800 mb-4">توزيع أنواع العقود</h6>
                            <div class="h-80">
                                <canvas id="contractTypesChart"></canvas>
                            </div>
                        </div>

                        <!-- Monthly Revenue Chart -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h6 class="text-lg font-semibold text-gray-800 mb-4">الإيرادات الشهرية</h6>
                            <div class="h-80">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Contracts -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h6 class="text-lg font-semibold text-gray-800 mb-0">أحدث العقود</h6>
                        </div>
                        <div class="p-6">
                            @if($stats['recent_contracts']->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم العقد</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع العقد</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($stats['recent_contracts'] as $contract)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $contract->contract_number ?: 'CON-' . $contract->id }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $contract->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $contract->user->email }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <a href="{{ route('facility.products.show', $contract->product) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                                            {{ $contract->product->getTranslatedTitle() }}
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $contract->contract_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ $contract->contract_type == 'sale' ? 'بيع' : 'إيجار' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ number_format($contract->total_amount, 2) }} ر.س
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $contract->created_at->format('Y-m-d') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @switch($contract->status)
                                                            @case('draft')
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">مسودة</span>
                                                                @break
                                                            @case('active')
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                                                @break
                                                            @case('completed')
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">مكتمل</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ملغي</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('facility.contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-file-contract text-4xl text-gray-400 mb-4"></i>
                                    <h5 class="text-lg font-medium text-gray-900 mb-2">لا توجد عقود</h5>
                                    <p class="text-gray-500">لم يتم إنشاء أي عقود بعد</p>
                                </div>
                            @endif
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
document.addEventListener('DOMContentLoaded', function() {
    // Contract Types Distribution Chart
    const typesCtx = document.getElementById('contractTypesChart').getContext('2d');
    new Chart(typesCtx, {
        type: 'pie',
        data: {
            labels: ['بيع', 'إيجار'],
            datasets: [{
                data: [
                    {{ $stats['sale_contracts'] }},
                    {{ $stats['rent_contracts'] }}
                ],
                backgroundColor: [
                    'rgb(34, 197, 94)', // green-500
                    'rgb(59, 130, 246)'  // blue-500
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            datasets: [{
                label: 'الإيرادات الشهرية',
                data: @json($stats['monthly_data']),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ر.س';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
