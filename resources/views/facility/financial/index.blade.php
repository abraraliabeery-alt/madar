@extends('facility.layouts.app')

@section('title', __('financial.financial_reports'))

@section('content')
<div class="w-full px-4 my-6">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-3xl font-bold">{{ number_format($quickStats['total_revenue'], 2) }}</h4>
                    <p class="text-blue-100">{{ __('financial.total_revenue') }}</p>
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
                    <p class="text-green-100">{{ __('financial.total_payments') }}</p>
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
                    <p class="text-cyan-100">{{ __('financial.contracts') }}</p>
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
                    <p class="text-yellow-100">{{ __('financial.pending') }}</p>
                </div>
                <div class="bg-yellow-400 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('facility.financial.revenue') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-blue-100 text-blue-600 rounded-lg p-3 ml-3"><i class="fas fa-chart-line text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.revenue_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.revenue_report_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.receivables') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-yellow-100 text-yellow-600 rounded-lg p-3 ml-3"><i class="fas fa-hand-holding-usd text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.receivables_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.receivables_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.commissions') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-purple-100 text-purple-600 rounded-lg p-3 ml-3"><i class="fas fa-percentage text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.commissions_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.commissions_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.payments-report') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-green-100 text-green-600 rounded-lg p-3 ml-3"><i class="fas fa-credit-card text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.payments_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.payments_report_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.invoices') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-cyan-100 text-cyan-600 rounded-lg p-3 ml-3"><i class="fas fa-file-invoice text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.invoices_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.invoices_report_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.contracts-report') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-gray-100 text-gray-600 rounded-lg p-3 ml-3"><i class="fas fa-file-contract text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.contracts_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.contracts_report_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.customer') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-indigo-100 text-indigo-600 rounded-lg p-3 ml-3"><i class="fas fa-user text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.customer_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.customer_report_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.owner') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-pink-100 text-pink-600 rounded-lg p-3 ml-3"><i class="fas fa-user-tie text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.owner_report') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.owner_report_desc') }}</p>
        </a>

        <a href="{{ route('facility.financial.export') }}" class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow block">
            <div class="flex items-center mb-3">
                <div class="bg-emerald-100 text-emerald-600 rounded-lg p-3 ml-3"><i class="fas fa-download text-xl"></i></div>
                <h5 class="font-semibold text-gray-800">{{ __('financial.export') }}</h5>
            </div>
            <p class="text-sm text-gray-600">{{ __('financial.financial_summary') }} CSV</p>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-4 rounded-t-lg">
            <h5 class="text-lg font-semibold flex items-center">
                <i class="fas fa-calendar-alt ml-2"></i>
                {{ __('financial.monthly_report') }} / {{ __('financial.yearly_report') }}
            </h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <form method="GET" action="{{ route('facility.financial.monthly') }}" class="bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.year') }}</label>
                            <select name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                @for($y = date('Y') + 1; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.month') }}</label>
                            <select name="month" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>{{ __('financial.month_' . $m) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">{{ __('financial.view_report') }}</button>
                </form>

                <form method="GET" action="{{ route('facility.financial.yearly') }}" class="bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('financial.year') }}</label>
                        <select name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @for($y = date('Y') + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">{{ __('financial.view_report') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
