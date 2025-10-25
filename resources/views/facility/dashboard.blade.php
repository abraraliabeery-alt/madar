@extends('facility.layouts.app')

@section('title', __('facility.dashboard.title'))

@section('content')
<div class="w-full px-4 my-10">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">{{ __('facility.dashboard.title') }}</h1>
    </div>

    <!-- {{ __('facility.dashboard.stats_title') }} -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg border-r-4 border-blue-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-blue-600 uppercase mb-1">
                        {{ __('facility.dashboard.total_products') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['total_products'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-box text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-green-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-green-600 uppercase mb-1">
                        {{ __('facility.dashboard.total_bookings') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-cyan-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-cyan-600 uppercase mb-1">
                        {{ __('facility.dashboard.pending_bookings') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['pending_bookings'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-yellow-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-yellow-600 uppercase mb-1">
                        {{ __('facility.dashboard.total_tasks') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['total_tasks'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-tasks text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('facility.dashboard.recent_activity') }} -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0">{{ __('facility.dashboard.recent_bookings') }}</h6>
            </div>
            <div class="p-6">
                @if(isset($stats['recent_bookings']) && $stats['recent_bookings'] && $stats['recent_bookings']->count() > 0)
                    @foreach($stats['recent_bookings'] as $booking)
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="flex-shrink-0">
                            <img class="w-10 h-10 rounded-full object-cover"
                                 src="{{ $booking->user->avatar ?? asset('images/default-avatar.png') }}"
                                 alt="{{ __('facility.dashboard.user_avatar') }}">
                        </div>
                        <div class="flex-1 mr-3">
                            <h6 class="font-semibold text-gray-800 mb-1">{{ $booking->user->name ?? 'غير محدد' }}</h6>
                            <p class="text-sm text-gray-500 mb-1">{{ $booking->product->name ?? __('facility.dashboard.deleted_product') }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->created_at ? $booking->created_at->diffForHumans() : 'غير محدد' }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">{{ __('facility.dashboard.no_recent_bookings') }}</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0">{{ __('facility.dashboard.recent_tasks') }}</h6>
            </div>
            <div class="p-6">
                @if(isset($stats['recent_tasks']) && $stats['recent_tasks'] && $stats['recent_tasks']->count() > 0)
                    @foreach($stats['recent_tasks'] as $task)
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-tasks text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 mr-3">
                            <h6 class="font-semibold text-gray-800 mb-1">{{ $task->title }}</h6>
                            <p class="text-sm text-gray-500 mb-1">{{ __('facility.dashboard.assigned_to') }}: {{ $task->assignedTo->name ?? __('facility.dashboard.unassigned') }}</p>
                            <p class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">{{ __('facility.dashboard.no_recent_tasks') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Landing Page Customization Preview -->
    <div class="w-full mb-6">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-purple-600 m-0 flex items-center">
                    <i class="fas fa-palette mr-2"></i>
                    {{ __('facilities.dashboard.landing_customization') }}
                </h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                    <!-- Current Colors Preview -->
                    <div>
                        <h6 class="font-semibold text-gray-800 mb-3">{{ __('facilities.customization.current_colors') }}</h6>
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 shadow-sm"
                                     style="background-color: {{ $facility->primary_color ?? '#2563eb' }}"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ __('facilities.customization.primary') }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 shadow-sm"
                                     style="background-color: {{ $facility->secondary_color ?? '#1e40af' }}"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ __('facilities.customization.secondary') }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 shadow-sm"
                                     style="background-color: {{ $facility->accent_color ?? '#f59e0b' }}"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ __('facilities.customization.accent') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Settings -->
                    <div>
                        <h6 class="font-semibold text-gray-800 mb-3">{{ __('facilities.dashboard.current_settings') }}</h6>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('facilities.customization.font_family') }}:</span>
                                <span class="font-medium text-gray-800">{{ ucfirst($facility->font_family ?? 'figtree') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('facilities.customization.layout_style') }}:</span>
                                <span class="font-medium text-gray-800">{{ ucfirst($facility->layout_style ?? 'modern') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('facilities.customization.enable_animations') }}:</span>
                                <span class="font-medium {{ ($facility->enable_animations ?? true) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ($facility->enable_animations ?? true) ? __('general.yes') : __('general.no') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-left lg:text-right space-y-3">
                        <div class="space-y-2">
                            <a href="{{ route('facility.customization.edit', $facility) }}"
                               class="w-full lg:w-auto bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-palette mr-2"></i>
                                {{ __('facilities.dashboard.customize_now') }}
                            </a>
                            <a href="{{ route('public.facilities.show', $facility) }}"
                               target="_blank"
                               class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                {{ __('facilities.dashboard.view_landing') }}
                            </a>
                        </div>
                        @if($facility->hasCustomization())
                            <p class="text-xs text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ __('facilities.dashboard.customized') }}
                            </p>
                        @else
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('facilities.dashboard.using_defaults') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('facility.dashboard.facility_info') }} -->
    <div class="w-full">
        <div class="bg-white rounded-lg shadow-lg mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0">{{ __('facility.dashboard.facility_info') }}</h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-xl font-semibold text-gray-800 mb-2">{{ $facility->name }}</h5>
                        <p class="text-gray-600 mb-3">{{ $facility->description }}</p>
                        <div class="space-y-2">
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.address') }}:</span> {{ $facility->address }}</p>
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.phone') }}:</span> {{ $facility->phone_number }}</p>
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.email') }}:</span> {{ $facility->email }}</p>
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.facility_category') }}:</span> {{ $facility->facilityCategory ? $facility->facilityCategory->name : __('facility.dashboard.no_category') }}</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        @if($facility->logo)
                            <img src="{{ asset($facility->logo) }}"
                                 alt="{{ __('facility.form.logo') }}"
                                 class="max-h-24 w-auto mb-4 mx-auto md:mx-0 md:mr-0">
                        @endif
                        <div class="space-y-3 md:space-y-0 md:space-x-3 md:space-x-reverse flex flex-col md:flex-row md:justify-end">
                            <a href="{{ route('facility.edit') }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-edit ml-2"></i> {{ __('facility.dashboard.edit_facility') }}
                            </a>
                            <a href="{{ route('facility.products.index') }}"
                               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-box ml-2"></i> {{ __('facility.dashboard.manage_products') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إدارة الأنظمة الجديدة -->
    <div class="w-full mb-6">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0 flex items-center">
                    <i class="fas fa-cogs mr-2"></i>
                    إدارة الأنظمة
                </h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- إدارة العروض -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-tags text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">إدارة العروض</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">إدارة عروض البيع والإيجار للمنتجات</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.offers.index') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-list ml-2"></i> عرض العروض
                            </a>
                            <a href="{{ route('facility.offers.create') }}" 
                               class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-plus ml-2"></i> إضافة عرض
                            </a>
                        </div>
                    </div>

                    <!-- إدارة العقود -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-contract text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">إدارة العقود</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">إدارة عقود البيع والإيجار</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.contracts.index') }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-list ml-2"></i> عرض العقود
                            </a>
                            <a href="{{ route('facility.contracts.create') }}" 
                               class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-plus ml-2"></i> إضافة عقد
                            </a>
                        </div>
                    </div>

                    <!-- إدارة الفواتير -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-invoice text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">إدارة الفواتير</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">إدارة فواتير المبيعات والإيجار</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.invoices.index') }}" 
                               class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-list ml-2"></i> عرض الفواتير
                            </a>
                            <a href="{{ route('facility.invoices.create') }}" 
                               class="w-full bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-plus ml-2"></i> إضافة فاتورة
                            </a>
                        </div>
                    </div>

                    <!-- إدارة المدفوعات -->
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">إدارة المدفوعات</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">إدارة مدفوعات العملاء</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.payments.index') }}" 
                               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-list ml-2"></i> عرض المدفوعات
                            </a>
                            <a href="{{ route('facility.payments.create') }}" 
                               class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-plus ml-2"></i> إضافة دفعة
                            </a>
                        </div>
                    </div>

                    <!-- النظام المحاسبي -->
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calculator text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">النظام المحاسبي</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">إدارة الحسابات والتقارير المالية</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.accounting.dashboard') }}" 
                               class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-tachometer-alt ml-2"></i> لوحة التحكم
                            </a>
                            <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" 
                               class="w-full bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-list ml-2"></i> دليل الحسابات
                            </a>
                        </div>
                    </div>

                    <!-- التقارير المالية -->
                    <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">التقارير المالية</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">تقارير مالية شاملة ومفصلة</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.accounting.reports.index') }}" 
                               class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-chart-bar ml-2"></i> جميع التقارير
                            </a>
                            <a href="{{ route('facility.accounting.reports.income-statement') }}" 
                               class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-chart-pie ml-2"></i> قائمة الدخل
                            </a>
                        </div>
                    </div>

                    <!-- إدارة المستخدمين -->
                    <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-lg p-4 border border-teal-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <h6 class="font-semibold text-gray-800">إدارة المستخدمين</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">إدارة مستخدمي المنشأة وأدوارهم</p>
                        <div class="space-y-2">
                            <a href="{{ route('facility.users.index') }}" 
                               class="w-full bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-users ml-2"></i> عرض المستخدمين
                            </a>
                            <a href="{{ route('facility.users.create') }}" 
                               class="w-full bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-user-plus ml-2"></i> إضافة مستخدم
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="w-full mb-6">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    إحصائيات سريعة
                </h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_offers'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">إجمالي العروض</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['total_contracts'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">إجمالي العقود</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['total_invoices'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">إجمالي الفواتير</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_payments'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">إجمالي المدفوعات</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-teal-600">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">إجمالي المستخدمين</div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
