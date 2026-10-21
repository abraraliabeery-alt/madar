@extends('admin.financial.layout')

@section('title', __('financial.accounting_entries'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.accounting_entries') }}</h2>
    <a href="{{ route('admin.financial.accounting-entries.export') }}" class="btn btn-outline-secondary">{{ __('financial.export') }}</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-2"><select name="account_type" class="form-select"><option value="">{{ __('financial.all_types') }}</option>@foreach(['revenue','receivable','commission','liability','expense'] as $t)<option value="{{ $t }}" {{ request('account_type') == $t ? 'selected' : '' }}>{{ $t }}</option>@endforeach</select></div>
    <div class="col-md-2"><select name="entry_type" class="form-select"><option value="">{{ __('financial.entry_type') }}</option><option value="debit" {{ request('entry_type') == 'debit' ? 'selected' : '' }}>{{ __('financial.debit') }}</option><option value="credit" {{ request('entry_type') == 'credit' ? 'selected' : '' }}>{{ __('financial.credit') }}</option></select></div>
    <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
</form>

<div class="card mb-4">
    <div class="card-header">{{ __('financial.new_entry') }}</div>
    <form method="POST" action="{{ route('admin.financial.accounting-entries.create') }}" class="card-body row g-2">
        @csrf
        <div class="col-md-2"><select name="entry_type" class="form-select" required><option value="debit">{{ __('financial.debit') }}</option><option value="credit">{{ __('financial.credit') }}</option></select></div>
        <div class="col-md-2"><select name="account_type" class="form-select" required>@foreach(['revenue','receivable','commission','liability','expense'] as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach</select></div>
        <div class="col-md-2"><input type="number" step="0.01" name="amount" class="form-control" placeholder="{{ __('financial.amount') }}" required></div>
        <div class="col-md-3"><input type="text" name="description" class="form-control" placeholder="{{ __('financial.description') }}" required></div>
        <div class="col-md-2"><input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
        <div class="col-md-1"><button class="btn btn-success w-100">+</button></div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('financial.entry_type') }}</th><th>{{ __('financial.account_type') }}</th><th>{{ __('financial.amount') }}</th><th>{{ __('financial.description') }}</th><th>{{ __('financial.date') }}</th></tr></thead>
            <tbody>
                @forelse($entries as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td><span class="badge bg-{{ $e->entry_type === 'debit' ? 'danger' : 'success' }}">{{ $e->entry_type }}</span></td>
                        <td>{{ $e->account_type }}</td>
                        <td>{{ number_format($e->amount ?? 0, 2) }}</td>
                        <td>{{ $e->description }}</td>
                        <td>{{ $e->entry_date }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($entries, 'links'))<div class="card-footer">{{ $entries->links() }}</div>@endif
</div>
@endsection
