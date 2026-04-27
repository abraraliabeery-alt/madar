@extends('facility.financial.layout')

@section('title', __('facility_management.financial_dashboard'))

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-0">{{ __('facility_management.financial_dashboard') }}</h1>
        <p class="text-gray-600 mb-0">{{ __('facility_management.manage_facility_finances') }}</p>
    </div>
    <div class="flex gap-2">
        <button class="bg-white border border-blue-500 text-blue-500 hover:bg-blue-50 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors" onclick="refreshData()">
            <i class="fas fa-sync-alt"></i>
            <span>{{ __('facility_management.refresh') }}</span>
        </button>
        <a href="{{ route('facility.financial.create-offer') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
            <i class="fas fa-plus"></i>
            <span>{{ __('facility_management.create_offer') }}</span>
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-3xl font-bold">{{ number_format($stats['total_offers']) }}</div>
                <div class="text-blue-100 text-sm mt-1">{{ __('facility_management.total_offers') }}</div>
            </div>
            <div class="text-4xl opacity-30">
                <i class="fas fa-tags"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-blue-400">
            <small class="text-blue-100">
                <i class="fas fa-check-circle mr-1"></i>
                {{ number_format($stats['active_offers']) }} {{ __('facility_management.active') }}
            </small>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-3xl font-bold">{{ number_format($stats['total_contracts']) }}</div>
                <div class="text-green-100 text-sm mt-1">{{ __('facility_management.total_contracts') }}</div>
            </div>
            <div class="text-4xl opacity-30">
                <i class="fas fa-file-contract"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-green-400">
            <small class="text-green-100">
                <i class="fas fa-play-circle mr-1"></i>
                {{ number_format($stats['active_contracts']) }} {{ __('facility_management.active') }}
            </small>
        </div>
    </div>

    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-3xl font-bold">{{ number_format($stats['total_revenue']) }}</div>
                <div class="text-cyan-100 text-sm mt-1">{{ __('facility_management.total_revenue') }}</div>
            </div>
            <div class="text-4xl opacity-30">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-cyan-400">
            <small class="text-cyan-100">
                <i class="fas fa-chart-line mr-1"></i>
                {{ __('facility_management.total_expected') }}
            </small>
        </div>
    </div>

    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-3xl font-bold">{{ number_format($stats['received_payments']) }}</div>
                <div class="text-yellow-100 text-sm mt-1">{{ __('facility_management.received_payments') }}</div>
            </div>
            <div class="text-4xl opacity-30">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-yellow-400">
            <small class="text-yellow-100">
                <i class="fas fa-clock mr-1"></i>
                {{ number_format($stats['pending_payments']) }} {{ __('facility_management.pending') }}
            </small>
        </div>
    </div>
</div>

<!-- Alerts Section -->
@if(count($alerts) > 0)
<div class="mb-6">
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
            <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                {{ __('facility_management.important_alerts') }}
            </h5>
        </div>
        <div class="p-6">
            @foreach($alerts as $alert)
            <div class="bg-{{ $alert['type'] === 'danger' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-50 border border-{{ $alert['type'] === 'danger' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-200 text-{{ $alert['type'] === 'danger' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-800 px-4 py-3 rounded-lg mb-3 flex justify-between items-center">
                <span>{{ $alert['message'] }}</span>
                @if(isset($alert['action_url']))
                    <a href="{{ $alert['action_url'] }}" class="bg-{{ $alert['type'] === 'danger' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-600 hover:bg-{{ $alert['type'] === 'danger' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-700 text-white px-3 py-1 rounded text-sm transition-colors">
                        {{ $alert['action_text'] }}
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Receivables & Collections KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">إجمالي الذمم</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($receivablesTotal ?? 0) }}</div>
            </div>
            <i class="fas fa-file-invoice-dollar text-gray-300 text-3xl"></i>
        </div>
        <div class="mt-3">
            <a href="{{ route('facility.invoices.index') }}" class="text-blue-600 text-sm hover:underline">عرض الفواتير</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">عدد الفواتير المتأخرة</div>
                <div class="text-2xl font-bold text-amber-600">{{ number_format($overdueInvoicesCount ?? 0) }}</div>
            </div>
            <i class="fas fa-exclamation-circle text-amber-300 text-3xl"></i>
        </div>
        <div class="mt-3 text-sm text-gray-600">قيمة المتأخرات: <span class="font-semibold">{{ number_format($overdueAmount ?? 0) }}</span></div>
        <div class="mt-2">
            <a href="{{ route('facility.invoices.index', ['status' => 'sent']) }}" class="text-blue-600 text-sm hover:underline">عرض المتأخرات</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">معدل التحصيل (30 يوم)</div>
                <div class="text-2xl font-bold {{ ($collectionRate ?? 0) >= 75 ? 'text-green-600' : (($collectionRate ?? 0) >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ number_format($collectionRate ?? 0, 1) }}%</div>
            </div>
            <i class="fas fa-bullseye text-gray-300 text-3xl"></i>
        </div>
        <div class="mt-3">
            <a href="{{ route('facility.financial.payments') }}" class="text-blue-600 text-sm hover:underline">دفعات المنشأة</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">أعمار الديون (متأخر)</div>
                <div class="text-sm text-gray-700">
                    <span class="inline-block mr-2">0–30: <span class="font-semibold">{{ number_format(($aging['d0_30'] ?? 0)) }}</span></span>
                    <span class="inline-block mr-2">31–60: <span class="font-semibold">{{ number_format(($aging['d31_60'] ?? 0)) }}</span></span>
                </div>
                <div class="text-sm text-gray-700">
                    <span class="inline-block mr-2">61–90: <span class="font-semibold">{{ number_format(($aging['d61_90'] ?? 0)) }}</span></span>
                    <span class="inline-block mr-2">90+: <span class="font-semibold">{{ number_format(($aging['d90_plus'] ?? 0)) }}</span></span>
                </div>
            </div>
            <i class="fas fa-layer-group text-gray-300 text-3xl"></i>
        </div>
    </div>
</div>

<!-- Charts and Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 h-full">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    {{ __('facility_management.monthly_revenue') }}
                </h5>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Success Rate -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 h-full">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                    <i class="fas fa-target text-green-500 mr-2"></i>
                    {{ __('facility_management.success_rate') }}
                </h5>
            </div>
            <div class="p-6 text-center">
                <div class="progress-circle mb-3" data-percentage="{{ $successRate }}">
                    <div class="progress-value">{{ $successRate }}%</div>
                </div>
                <p class="text-gray-600 mb-0">{{ __('facility_management.offers_to_contracts') }}</p>
                <small class="text-gray-500">
                    {{ number_format($stats['active_contracts']) }} / {{ number_format($stats['total_offers']) }}
                    {{ __('facility_management.contracts_from_offers') }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pending Contracts -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
            <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                <i class="fas fa-clock text-yellow-500 mr-2"></i>
                {{ __('facility_management.pending_contracts') }}
            </h5>
            <a href="{{ route('facility.financial.contracts', ['status' => 'draft']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                {{ __('facility_management.view_all') }}
            </a>
        </div>
        <div class="p-6">
                @if($pendingContracts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.client') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.product') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.amount') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingContracts as $contract)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                                {{ substr($contract->client->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $contract->client->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $contract->client->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $contract->offer->product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $contract->offer->offer_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($contract->total_amount) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2 rtl:space-x-reverse">
                                            <a href="{{ route('facility.financial.contract-details', $contract->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                               title="{{ __('facility_management.view_details') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="text-green-600 hover:text-green-900 p-1 rounded" 
                                                    onclick="updateContractStatus({{ $contract->id }}, 'active')"
                                                    title="{{ __('facility_management.approve') }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
                        <h6 class="text-lg font-medium text-gray-900 mb-2">{{ __('facility_management.no_pending_contracts') }}</h6>
                        <p class="text-gray-500">{{ __('facility_management.all_contracts_processed') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
            <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                <i class="fas fa-credit-card text-green-500 mr-2"></i>
                {{ __('facility_management.recent_payments') }}
            </h5>
            <a href="{{ route('facility.financial.payments') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                {{ __('facility_management.view_all') }}
            </a>
        </div>
        <div class="p-6">
                @if($recentPayments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.client') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.amount') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentPayments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-500 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                                {{ substr($payment->contract->client->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $payment->contract->client->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $payment->payment_method }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($payment->amount) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $payment->status == 'confirmed' ? 'green' : ($payment->status == 'pending' ? 'yellow' : 'red') }}-100 text-{{ $payment->status == 'confirmed' ? 'green' : ($payment->status == 'pending' ? 'yellow' : 'red') }}-800">
                                            {{ __('facility_management.payment_status_' . $payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $payment->payment_date->format('d/m/Y') }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-credit-card text-gray-400 text-5xl mb-4"></i>
                        <h6 class="text-lg font-medium text-gray-900 mb-2">{{ __('facility_management.no_recent_payments') }}</h6>
                        <p class="text-gray-500">{{ __('facility_management.payments_will_appear_here') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Contracts -->
<div class="mt-6">
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
            <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                <i class="fas fa-file-contract text-blue-500 mr-2"></i>
                {{ __('facility_management.recent_contracts') }}
            </h5>
            <a href="{{ route('facility.financial.contracts') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                {{ __('facility_management.view_all') }}
            </a>
        </div>
        <div class="p-6">
                @if($recentContracts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.contract_number') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.client') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.product') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.type') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.amount') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.created_date') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('facility_management.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentContracts as $contract)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $contract->contract_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                                {{ substr($contract->client->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $contract->client->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $contract->client->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $contract->offer->product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $contract->offer->product->location }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ __('facility_management.offer_type_' . $contract->offer->offer_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($contract->total_amount) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $contract->status == 'active' ? 'green' : ($contract->status == 'draft' ? 'yellow' : 'gray') }}-100 text-{{ $contract->status == 'active' ? 'green' : ($contract->status == 'draft' ? 'yellow' : 'gray') }}-800">
                                            {{ __('facility_management.contract_status_' . $contract->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $contract->created_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('facility.financial.contract-details', $contract->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                           title="{{ __('facility_management.view_details') }}">
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
                        <i class="fas fa-file-contract text-gray-400 text-5xl mb-4"></i>
                        <h6 class="text-lg font-medium text-gray-900 mb-2">{{ __('facility_management.no_contracts') }}</h6>
                        <p class="text-gray-500 mb-4">{{ __('facility_management.contracts_will_appear_here') }}</p>
                        <a href="{{ route('facility.financial.create-offer') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('facility_management.create_first_offer') }}</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: '{{ __('facility_management.revenue') }}',
                data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
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
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('{{ app()->getLocale() }}').format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Progress Circle Animation
    const progressCircle = document.querySelector('.progress-circle');
    if (progressCircle) {
        const percentage = progressCircle.dataset.percentage;
        const circumference = 2 * Math.PI * 45; // radius = 45
        const offset = circumference - (percentage / 100) * circumference;
        
        const svg = `
            <svg width="120" height="120" class="progress-ring">
                <circle cx="60" cy="60" r="45" fill="transparent" stroke="#e9ecef" stroke-width="8"/>
                <circle cx="60" cy="60" r="45" fill="transparent" stroke="#198754" stroke-width="8"
                        stroke-dasharray="${circumference}" stroke-dashoffset="${offset}"
                        stroke-linecap="round" transform="rotate(-90 60 60)"/>
            </svg>
        `;
        
        progressCircle.innerHTML = svg + progressCircle.innerHTML;
    }
});

// Refresh data function
function refreshData() {
    showLoading();
    location.reload();
}

// Update contract status function
function updateContractStatus(contractId, status) {
    if (!confirm('{{ __('facility_management.confirm_status_change') }}')) {
        return;
    }

    showLoading();
    
    fetch(`{{ route('facility.financial.update-contract-status', ':id') }}`.replace(':id', contractId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: ''
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', '{{ __('facility_management.error_occurred') }}');
    });
}
</script>
@endpush

@push('styles')
<style>
.progress-circle {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.progress-value {
    position: absolute;
    font-size: 1.5rem;
    font-weight: 700;
    color: #10b981;
}

.progress-ring circle {
    transition: stroke-dashoffset 0.5s ease-in-out;
}
</style>
@endpush
