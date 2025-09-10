@extends('layouts.app')

@section('title', 'إحصائيات المستخدم')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إحصائيات المستخدم</h1>
                    <p class="text-gray-600">تتبع نشاط {{ $user->name }} وإحصائياته</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('user.profile.show', $user) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-user ml-2"></i> الملف الشخصي
                    </a>
                    <a href="{{ route('user.profile.export', $user) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-download ml-2"></i> تصدير البيانات
                    </a>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي الحجوزات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-file-contract text-green-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي العقود</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_contracts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-heart text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المفضلة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_favorites'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-comment text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي التعليقات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_comments'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monthly Activity Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">النشاط الشهري</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Activity Distribution -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">توزيع النشاط</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">النشاط الأخير</h3>
            <div class="space-y-4">
                @if($stats['recent_activity']->count() > 0)
                    @foreach($stats['recent_activity'] as $activity)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }}-600"></i>
                                </div>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-activity text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">لا توجد أنشطة حديثة</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Role Statistics -->
        @if($stats['role_stats']->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات الأدوار</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($stats['role_stats'] as $role)
                        <div class="p-4 border rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">{{ $role['display_name'] ?: $role['name'] }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $role['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $role['is_active'] ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $role['users_count'] }} مستخدم</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Monthly Statistics Table -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الإحصائيات الشهرية</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الشهر</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحجوزات</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">العقود</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المواعيد</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المجموع</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats['monthly_stats'] as $month)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $month['month_name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $month['bookings'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $month['contracts'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $month['appointments'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                                    {{ $month['bookings'] + $month['contracts'] + $month['appointments'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Activity Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stats['monthly_stats']->pluck('month_name')->toArray()) !!},
            datasets: [{
                label: 'الحجوزات',
                data: {!! json_encode($stats['monthly_stats']->pluck('bookings')->toArray()) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }, {
                label: 'العقود',
                data: {!! json_encode($stats['monthly_stats']->pluck('contracts')->toArray()) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1
            }, {
                label: 'المواعيد',
                data: {!! json_encode($stats['monthly_stats']->pluck('appointments')->toArray()) !!},
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

    // Activity Distribution Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(activityCtx, {
        type: 'doughnut',
        data: {
            labels: ['الحجوزات', 'العقود', 'المنتجات', 'المفضلة', 'المواعيد', 'التعليقات'],
            datasets: [{
                data: [
                    {{ $stats['total_bookings'] }},
                    {{ $stats['total_contracts'] }},
                    {{ $stats['total_products'] }},
                    {{ $stats['total_favorites'] }},
                    {{ $stats['total_appointments'] }},
                    {{ $stats['total_comments'] }}
                ],
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#8B5CF6',
                    '#EF4444',
                    '#06B6D4'
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
</script>
@endpush
