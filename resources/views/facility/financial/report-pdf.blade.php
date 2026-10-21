<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: right; }
        th { background: #f3f4f6; }
        h1, h2 { color: #1f2937; }
    </style>
</head>
<body>
    <h1>{{ __('financial.facility_report') }} — {{ $facility->name }}</h1>
    <p>{{ __('financial.period') }}: {{ $dateFrom }} — {{ $dateTo }}</p>

    <h2>{{ __('financial.summary') }}</h2>
    <table>
        <tr><th>{{ __('financial.total_revenue') }}</th><td>{{ number_format($data['summary']['total_revenue'] ?? 0, 2) }}</td></tr>
        <tr><th>{{ __('financial.total_payments') }}</th><td>{{ number_format($data['summary']['total_payments'] ?? 0, 2) }}</td></tr>
        <tr><th>{{ __('financial.total_commissions') }}</th><td>{{ number_format($data['summary']['total_commissions'] ?? 0, 2) }}</td></tr>
        <tr><th>{{ __('financial.net_income') }}</th><td>{{ number_format($data['summary']['net_income'] ?? 0, 2) }}</td></tr>
    </table>

    <h2>{{ __('financial.contracts') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('financial.contract_number') }}</th>
                <th>{{ __('financial.client_name') }}</th>
                <th>{{ __('financial.total_amount') }}</th>
                <th>{{ __('financial.paid_amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['contracts'] ?? [] as $contract)
                <tr>
                    <td>{{ $contract->contract_number }}</td>
                    <td>{{ $contract->client?->name ?? '—' }}</td>
                    <td>{{ number_format($contract->total_amount ?? 0, 2) }}</td>
                    <td>{{ number_format($contract->getTotalPaidAmount(), 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('financial.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
