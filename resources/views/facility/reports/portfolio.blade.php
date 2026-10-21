@extends('facility.layouts.app')

@section('title', 'محفظة الأصول')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">محفظة الأصول</h1>
            <span class="text-muted small">كل أصولك في نظرة واحدة — إشغال، تحصيل، ربحية</span>
        </div>
        <a href="{{ route('facility.reports.portfolio.pdf') }}" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> تصدير PDF
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">الأصل</th>
                        <th>الوحدات</th>
                        <th>الإشغال</th>
                        <th>عقود نشطة</th>
                        <th>المحصّل</th>
                        <th>متأخرات</th>
                        <th class="pe-3">صافي الربح</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('facility.products.manage', $row->asset) }}" class="fw-semibold text-decoration-none">
                                    {{ $row->asset->getTranslatedTitle() ?? '#'.$row->asset->id }}
                                </a>
                            </td>
                            <td>{{ $row->units }}</td>
                            <td style="min-width:140px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:8px">
                                        <div class="progress-bar {{ $row->occupancy >= 70 ? 'bg-success' : ($row->occupancy >= 40 ? 'bg-warning' : 'bg-danger') }}"
                                             style="width:{{ $row->occupancy }}%"></div>
                                    </div>
                                    <span class="small text-muted">{{ $row->occupancy }}%</span>
                                </div>
                            </td>
                            <td>{{ $row->contracts }}</td>
                            <td class="text-success fw-semibold">{{ number_format($row->collected, 0) }}</td>
                            <td class="{{ $row->arrears > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">{{ number_format($row->arrears, 0) }}</td>
                            <td class="pe-3 fw-bold {{ $row->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($row->profit, 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">لا أصول بعد — أضف عقاراً واربط وحداته به</td></tr>
                    @endforelse
                </tbody>
                @if($rows->count() > 1)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td class="ps-3">الإجمالي</td>
                            <td>{{ $rows->sum('units') }}</td>
                            <td>{{ $rows->sum('units') ? round($rows->sum('occupied') / $rows->sum('units') * 100) : 0 }}%</td>
                            <td>{{ $rows->sum('contracts') }}</td>
                            <td class="text-success">{{ number_format($rows->sum('collected'), 0) }}</td>
                            <td class="text-danger">{{ number_format($rows->sum('arrears'), 0) }}</td>
                            <td class="pe-3">{{ number_format($rows->sum('profit'), 0) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
