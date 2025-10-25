@extends('layouts.app')

@section('title', 'الإحصائيات الشخصية')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">الإحصائيات الشخصية</h1>
            <p class="text-gray-600">تتبع نشاطك وإحصائياتك على المنصة</p>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-eye text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المشاهدات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_views'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-heart text-green-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">العناصر المفضلة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_favorites'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-search text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">عمليات البحث</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_searches'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">التقييمات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_reviews'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Activity Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">نشاطك الشهري</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Search Categories -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">فئات البحث المفضلة</h3>
                <div class="space-y-4">
                    @if(isset($stats['search_categories']) && count($stats['search_categories']) > 0)
                        @foreach($stats['search_categories'] as $category)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $category['name'] }}</span>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                        <div class="bg-primary-600 h-2 rounded-full" 
                                             style="width: {{ ($category['count'] / $stats['total_searches']) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $category['count'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">لا توجد بيانات</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">النشاط الأخير</h3>
                <div class="space-y-4">
                    @if(isset($stats['recent_activity']) && count($stats['recent_activity']) > 0)
                        @foreach($stats['recent_activity'] as $activity)
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-{{ $activity['icon'] }} text-gray-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">لا توجد أنشطة حديثة</p>
                    @endif
                </div>
            </div>

            <!-- Top Searches -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">أكثر عمليات البحث</h3>
                <div class="space-y-3">
                    @if(isset($stats['top_searches']) && count($stats['top_searches']) > 0)
                        @foreach($stats['top_searches'] as $search)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $search['query'] }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $search['count'] }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">لا توجد عمليات بحث</p>
                    @endif
                </div>
            </div>

            <!-- Favorite Types -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">أنواع المفضلة</h3>
                <div class="space-y-3">
                    @if(isset($stats['favorite_types']) && count($stats['favorite_types']) > 0)
                        @foreach($stats['favorite_types'] as $type)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $type['name'] }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $type['count'] }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">لا توجد مفضلة</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Report -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">التقرير الشهري</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['monthly_views'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">مشاهدات هذا الشهر</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['monthly_favorites'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">إضافات للمفضلة</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['monthly_searches'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">عمليات بحث</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['monthly_reviews'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">تقييمات جديدة</div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">تصدير البيانات</h3>
            <div class="flex flex-wrap gap-4">
                <button onclick="exportData('pdf')" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-file-pdf ml-2"></i> تصدير PDF
                </button>
                <button onclick="exportData('excel')" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-file-excel ml-2"></i> تصدير Excel
                </button>
                <button onclick="exportData('csv')" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-file-csv ml-2"></i> تصدير CSV
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Activity Chart
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'المشاهدات',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }, {
                label: 'عمليات البحث',
                data: [8, 15, 7, 12, 6, 9],
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function exportData(format) {
        // Implementation for exporting data
        alert('تصدير البيانات بصيغة ' + format.toUpperCase());
    }
</script>
@endpush

@push('styles')
<style>
.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}
</style>
@endpush
