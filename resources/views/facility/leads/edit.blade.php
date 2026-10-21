@extends('facility.layouts.app')

@section('title', 'تعديل: ' . $lead->name)

@section('content')
<div class="container-fluid" style="max-width: 860px;">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">تعديل: {{ $lead->name }}</h1>
        <a href="{{ route('facility.leads.show', $lead) }}" class="text-muted small">← العودة لصفحة العميل</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.leads.update', $lead) }}">
                @csrf
                @method('PUT')
                @include('facility.leads._form')
                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> حفظ التعديلات</button>
                </div>
            </form>
            <form method="POST" action="{{ route('facility.leads.destroy', $lead) }}" class="mt-2"
                  onsubmit="return confirm('حذف العميل المحتمل نهائياً؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
            </form>
        </div>
    </div>
</div>
@endsection
