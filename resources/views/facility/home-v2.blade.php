@extends('facility.layouts.app')

@section('title', 'لوحة المنشأة - إصدار')

@section('content')
<div class="w-full px-4 my-10">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">لوحة المنشأة (تجريبية)</h1>
        <div class="space-x-2 space-x-reverse">
            <a href="{{ route('facility.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium py-2 px-4 rounded-md inline-flex items-center justify-center">
                العودة للوحة الحالية
            </a>
        </div>
    </div>

    @if(isset($stats['appointments']))
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <p class="text-xs text-gray-500 mb-1">مواعيد اليوم</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['appointments']['today'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <p class="text-xs text-gray-500 mb-1">مواعيد قادمة (7 أيام)</p>
            <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['appointments']['upcoming'] ?? 0) }}</p>
        </div>
    </div>
    @endif

    @if(isset($filters['enabled']) && $filters['enabled'])
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-2">
            @php($currentRange = $filters['range'] ?? '30d')
            @foreach([
                'today' => 'اليوم',
                '7d' => 'آخر 7 أيام',
                '30d' => 'آخر 30 يوم',
                'month' => 'هذا الشهر',
            ] as $key => $label)
                <a href="{{ route('facility.home-v2', ['range' => $key]) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium border {{ $currentRange === $key ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="text-xs text-gray-500">
            الفترة الحالية:
            @if($filters['from'] && $filters['to'])
                {{ $filters['from'] }} → {{ $filters['to'] }}
            @else
                تلقائيًا حسب آخر 30 يوم
            @endif
        </div>
    </div>
    @endif

    @if(config('features.facility_lifecycle_widgets') && ($stats['lifecycle'] ?? null))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3">قمع دورة الحياة (Funnel+)</h3>
            <div class="grid grid-cols-3 md:grid-cols-7 gap-4 text-center">
                @php($fp = $stats['lifecycle']['funnel_plus'])
                <div><div class="text-xs text-gray-500 mb-1">مشاهدات</div><div class="text-xl font-bold">{{ number_format($fp['views'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">مفضلة</div><div class="text-xl font-bold">{{ number_format($fp['favorites'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">حجوزات</div><div class="text-xl font-bold">{{ number_format($fp['bookings'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">عروض</div><div class="text-xl font-bold">{{ number_format($fp['offers'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">عقود</div><div class="text-xl font-bold">{{ number_format($fp['contracts'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">فواتير</div><div class="text-xl font-bold">{{ number_format($fp['invoices'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">مدفوعات</div><div class="text-xl font-bold">{{ number_format($fp['payments'] ?? 0) }}</div></div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3">مراحل العميل</h3>
            @php($cs = $stats['lifecycle']['customer_stages'])
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                <div><div class="text-xs text-gray-500 mb-1">Leads</div><div class="text-xl font-bold">{{ number_format($cs['leads'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">Negotiation</div><div class="text-xl font-bold">{{ number_format($cs['negotiation'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">Customers</div><div class="text-xl font-bold">{{ number_format($cs['customers'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">Payers</div><div class="text-xl font-bold">{{ number_format($cs['active_payers'] ?? 0) }}</div></div>
                <div><div class="text-xs text-gray-500 mb-1">Overdue</div><div class="text-xl font-bold text-red-600">{{ number_format($cs['overdue_customers'] ?? 0) }}</div></div>
            </div>
        </div>
    </div>
    @endif

    @if(config('features.facility_nba_widget') && ($stats['nba'] ?? null))
    @php($n = $stats['nba'])
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
            <h3 class="font-semibold text-gray-800">أفضل إجراء تالي</h3>
            <div class="text-xs text-gray-500">
                اليوم لديك
                <span class="font-semibold text-red-600">{{ number_format($n['invoices_overdue'] ?? 0) }} فواتير متأخرة</span>,
                <span class="font-semibold">{{ number_format($n['invoices_due_today'] ?? 0) }} فواتير مستحقة اليوم</span>.
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <a href="{{ route('facility.invoices.index', ['status'=>'overdue']) }}" class="border rounded p-3 hover:bg-gray-50">
                <div class="text-xs text-gray-500">فواتير متأخرة</div>
                <div class="text-xl font-bold text-red-600">{{ number_format($n['invoices_overdue'] ?? 0) }}</div>
            </a>
            <a href="{{ route('facility.invoices.index', ['due'=>'today']) }}" class="border rounded p-3 hover:bg-gray-50">
                <div class="text-xs text-gray-500">مستحقة اليوم</div>
                <div class="text-xl font-bold text-amber-600">{{ number_format($n['invoices_due_today'] ?? 0) }}</div>
            </a>
            <a href="{{ route('facility.execution-requests.index', ['status'=>'open']) }}" class="border rounded p-3 hover:bg-gray-50">
                <div class="text-xs text-gray-500">طلبات تنفيذ مفتوحة</div>
                <div class="text-xl font-bold">{{ number_format($stats['open_execution_requests'] ?? 0) }}</div>
            </a>
            <a href="{{ route('facility.execution-requests.workspace') }}" class="border rounded p-3 hover:bg-gray-50">
                <div class="text-xs text-gray-500">مساحة العمل</div>
                <div class="text-xl font-bold">{{ number_format($stats['total_execution_bids_received'] ?? 0) }}</div>
            </a>
        </div>
        <div class="mt-3">
            <form method="POST" action="{{ route('facility.tasks.generate-reminders') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                    توليد التذكيرات الآن
                </button>
            </form>
        </div>
    </div>
    @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg border-r-4 border-blue-500 p-4">
            <div class="text-xs font-bold text-blue-600 uppercase mb-1">طلبات التنفيذ</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_execution_requests'] ?? 0 }}</div>
            <div class="mt-3">
                <a href="{{ route('facility.execution-requests.index') }}" class="text-sm text-blue-600 hover:underline">إدارة الطلبات</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-green-500 p-4">
            <div class="text-xs font-bold text-green-600 uppercase mb-1">العقود</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_contracts'] }}</div>
            <div class="mt-3">
                <a href="{{ route('facility.contracts.index') }}" class="text-sm text-green-600 hover:underline">إدارة العقود</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-purple-500 p-4">
            <div class="text-xs font-bold text-purple-600 uppercase mb-1">فواتير هذا الشهر</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_invoices_month'] }}</div>
            <div class="mt-3">
                <a href="{{ route('facility.invoices.index') }}" class="text-sm text-purple-600 hover:underline">عرض الفواتير</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-red-500 p-4">
            <div class="text-xs font-bold text-red-600 uppercase mb-1">فواتير متأخرة</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['overdue_invoices'] }}</div>
            <div class="mt-3">
                <a href="{{ route('facility.invoices.index', ['status' => 'overdue']) }}" class="text-sm text-red-600 hover:underline">عرض المتأخر</a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg border-r-4 border-indigo-500 p-4">
            <div class="text-xs font-bold text-indigo-600 uppercase mb-1">الإشغال</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['occupancy_percent'] ?? 0 }}%</div>
            <div class="mt-3 text-sm text-gray-600">العقود النشطة: {{ $stats['active_contracts'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-lg border-r-4 border-orange-500 p-4">
            <div class="text-xs font-bold text-orange-600 uppercase mb-1">مهام مفتوحة</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['open_tasks'] ?? 0 }}</div>
            <div class="mt-3">
                <a href="{{ route('facility.tasks.index', ['status' => 'open']) }}" class="text-sm text-orange-600 hover:underline">عرض المهام</a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">التحصيل المدفوع هذا الشهر</h2>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['paid_amount_month'], 2) }} ر.س</div>
        <div class="mt-4">
            <a href="{{ route('facility.payments.index') }}" class="text-sm text-gray-600 hover:underline">تفاصيل المدفوعات</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('facility.execution-requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-4 shadow flex items-center justify-between">
            <span>إنشاء طلب تنفيذ</span>
            <i class="fas fa-plus-circle"></i>
        </a>
        <a href="{{ route('facility.contracts.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-4 shadow flex items-center justify-between">
            <span>إنشاء عقد</span>
            <i class="fas fa-file-signature"></i>
        </a>
        <a href="{{ route('facility.tasks.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg p-4 shadow flex items-center justify-between">
            <span>إنشاء مهمة</span>
            <i class="fas fa-list-check"></i>
        </a>
        <a href="{{ route('facility.execution-requests.workspace') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-4 shadow flex items-center justify-between">
            <span>مساحة العمل</span>
            <i class="fas fa-briefcase"></i>
        </a>
    </div>

    <!-- Reminders Panel -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">تذكيرات اليوم</h2>
            <form method="POST" action="{{ route('facility.tasks.generate-reminders') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                    توليد التذكيرات الآن
                </button>
            </form>
        </div>
        <p class="text-sm text-gray-500">تذكيرات الفواتير المتأخرة/المستحقة وحجوزات اليوم.</p>
    </div>

    <!-- Recent Items -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3">أحدث طلبات التنفيذ</h3>
            <ul class="space-y-2">
                @foreach(($stats['recent_execution_requests'] ?? []) as $r)
                    <li class="flex items-center justify-between text-sm text-gray-700">
                        <span>#{{ $r->id }} - {{ $r->status }}</span>
                        <span class="text-gray-500">{{ optional($r->created_at)->format('Y/m/d') }}</span>
                    </li>
                @endforeach
                @if(empty($stats['recent_execution_requests']) || count($stats['recent_execution_requests']) === 0)
                    <li class="text-sm text-gray-500">لا توجد بيانات</li>
                @endif
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3">أحدث الفواتير</h3>
            <ul class="space-y-2">
                @foreach(($stats['recent_invoices'] ?? []) as $inv)
                    <li class="flex items-center justify-between text-sm text-gray-700">
                        <span>#{{ $inv->id }} - {{ $inv->status }}</span>
                        <span class="text-gray-500">{{ optional($inv->created_at)->format('Y/m/d') }}</span>
                    </li>
                @endforeach
                @if(empty($stats['recent_invoices']) || count($stats['recent_invoices']) === 0)
                    <li class="text-sm text-gray-500">لا توجد بيانات</li>
                @endif
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3">أحدث المهام</h3>
            <ul class="space-y-2">
                @foreach(($stats['recent_tasks'] ?? []) as $t)
                    <li class="flex items-center justify-between text-sm text-gray-700">
                        <span>#{{ $t->id }} - {{ $t->type }} ({{ $t->status }})</span>
                        <span class="text-gray-500">{{ optional($t->created_at)->format('Y/m/d') }}</span>
                    </li>
                @endforeach
                @if(empty($stats['recent_tasks']) || count($stats['recent_tasks']) === 0)
                    <li class="text-sm text-gray-500">لا توجد بيانات</li>
                @endif
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-800 mb-3">المسار التحويلي (Funnel)</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-xs text-gray-500 mb-1">مشاهدات</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['funnel']['views'] ?? 0) }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 mb-1">مفضلة</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['funnel']['favorites'] ?? 0) }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 mb-1">حجوزات</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['funnel']['bookings'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-3">تنبيهات جودة المحتوى</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="border rounded-lg p-3">
                    <div class="text-xs text-gray-500">صور مفقودة</div>
                    <div class="text-xl font-bold text-red-600">{{ number_format($stats['quality']['missing_image'] ?? 0) }}</div>
                    <a href="{{ route('facility.products.index', ['quality' => 'attention']) }}" class="mt-1 inline-block text-xs text-blue-600 hover:underline">عرض العقارات المتأثرة</a>
                </div>
                <div class="border rounded-lg p-3">
                    <div class="text-xs text-gray-500">موقع مفقود</div>
                    <div class="text-xl font-bold text-red-600">{{ number_format($stats['quality']['missing_location'] ?? 0) }}</div>
                    <a href="{{ route('facility.products.index', ['quality' => 'attention']) }}" class="mt-1 inline-block text-xs text-blue-600 hover:underline">عرض العقارات المتأثرة</a>
                </div>
                <div class="border rounded-lg p-3">
                    <div class="text-xs text-gray-500">سعر مفقود/صفر</div>
                    <div class="text-xl font-bold text-amber-600">{{ number_format($stats['quality']['missing_price'] ?? 0) }}</div>
                    <a href="{{ route('facility.products.index', ['quality' => 'attention']) }}" class="mt-1 inline-block text-xs text-blue-600 hover:underline">عرض العقارات المتأثرة</a>
                </div>
                <div class="border rounded-lg p-3">
                    <div class="text-xs text-gray-500">ترجمات مفقودة</div>
                    <div class="text-xl font-bold text-amber-600">{{ number_format($stats['quality']['missing_translations'] ?? 0) }}</div>
                    <a href="{{ route('facility.products.index', ['quality' => 'attention']) }}" class="mt-1 inline-block text-xs text-blue-600 hover:underline">عرض العقارات المتأثرة</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-3">أفضل الإعلانات</h3>
            <div class="table-responsive">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="text-right p-2">#</th>
                            <th class="text-right p-2">العنوان</th>
                            <th class="text-right p-2">المشاهدات</th>
                            <th class="text-right p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($stats['top_products'] ?? []) as $p)
                            <tr class="border-t">
                                <td class="p-2">{{ $p->id }}</td>
                                <td class="p-2">{{ $p->address }}</td>
                                <td class="p-2">{{ number_format($p->views_count ?? 0) }}</td>
                                <td class="p-2">
                                    <a class="text-blue-600 hover:underline" href="{{ route('facility.products.show', $p->id) }}" target="_blank">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4 text-gray-500">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-3">أضعف الإعلانات</h3>
            <div class="table-responsive">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="text-right p-2">#</th>
                            <th class="text-right p-2">العنوان</th>
                            <th class="text-right p-2">المشاهدات</th>
                            <th class="text-right p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($stats['bottom_products'] ?? []) as $p)
                            <tr class="border-t">
                                <td class="p-2">{{ $p->id }}</td>
                                <td class="p-2">{{ $p->address }}</td>
                                <td class="p-2">{{ number_format($p->views_count ?? 0) }}</td>
                                <td class="p-2">
                                    <a class="text-blue-600 hover:underline" href="{{ route('facility.products.show', $p->id) }}" target="_blank">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4 text-gray-500">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-sm text-gray-500">هذه النسخة اختيارية وتجريبية ويمكن إيقافها في أي وقت.</div>
</div>
@endsection
