@extends('facility.layouts.app')

@section('title', 'عميل محتمل جديد')

@section('content')
<div class="container-fluid" style="max-width: 860px;">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">عميل محتمل جديد</h1>
        <a href="{{ route('facility.leads.index') }}" class="text-muted small">← العودة للعملاء المحتملين</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.leads.store') }}">
                @csrf
                @include('facility.leads._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
