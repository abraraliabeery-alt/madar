@extends('facility.layouts.app')

@section('title', __('facility.sidebar.reports_products'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1">{{ __('facility.sidebar.reports_products') }}</h1>
        <a href="{{ route('facility.reports') }}" class="text-muted small">← العودة للتقارير</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">إجمالي المنتجات</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">النشطة</div>
                <div class="h4 fw-bold mb-0">{{ $stats['active'] }}</div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>عدد الحجوزات</th>
                        <th>المشاهدات</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->getTranslatedTitle() ?: '—' }}</td>
                            <td>{{ $product->bookings_count }}</td>
                            <td>{{ $product->views_count ?? 0 }}</td>
                            <td>{{ $product->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد منتجات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="card-footer">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection
