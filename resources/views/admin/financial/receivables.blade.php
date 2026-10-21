@extends('admin.financial.layout')

@section('title', __('financial.receivables_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.receivables_report') }}</h2>
</div>

<div class="card p-3 mb-4"><small class="text-muted">{{ __('financial.total_receivables') }}</small><h3>{{ number_format($report['total_receivables'] ?? 0, 2) }}</h3></div>

<div class="card">
    <div class="card-header">{{ __('financial.by_contract') }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('financial.contract') }}</th><th>{{ __('financial.amount') }}</th></tr></thead>
            <tbody>
                @forelse(($report['by_contract'] ?? []) as $row)
                    <tr>
                        <td>{{ $row['contract_id'] }}</td>
                        <td>{{ optional($row['contract'])->contract_number ?? $row['contract_id'] }}</td>
                        <td>{{ number_format($row['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
