@extends('facility.layouts.app')

@section('title', 'تقرير العملاء المحتملين')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">تقرير تحويل العملاء المحتملين</h1>
            <a href="{{ route('facility.leads.index') }}" class="text-muted small">← العودة للعملاء</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">إجمالي العملاء</div>
                <div class="h4 fw-bold mb-0">{{ $total }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">مغلقون بنجاح</div>
                <div class="h4 fw-bold mb-0 text-success">{{ $won }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">مفقودون</div>
                <div class="h4 fw-bold mb-0 text-danger">{{ $lost }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">نسبة التحويل</div>
                <div class="h4 fw-bold mb-0 text-primary">{{ $total > 0 ? round($won / $total * 100) : 0 }}%</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">التوزيع حسب المرحلة</div>
                <div class="card-body">
                    @foreach(\App\Models\Lead::STAGES as $key => $label)
                        @php $count = $byStage[$key] ?? 0; $pct = $total > 0 ? round($count / $total * 100) : 0; @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $label }}</span>
                                <span class="text-muted">{{ $count }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress" style="height:6px">
                                <div class="progress-bar {{ $key === 'won' ? 'bg-success' : ($key === 'lost' ? 'bg-danger' : 'bg-primary') }}" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">الأداء حسب المصدر</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead><tr><th class="ps-3">المصدر</th><th>العملاء</th><th>مغلقون</th><th class="pe-3">التحويل</th></tr></thead>
                        <tbody>
                            @forelse($bySource as $row)
                                <tr>
                                    <td class="ps-3">{{ \App\Models\Lead::SOURCES[$row->source] ?? 'غير محدد' }}</td>
                                    <td>{{ $row->count }}</td>
                                    <td class="text-success">{{ $row->won }}</td>
                                    <td class="pe-3">{{ $row->count > 0 ? round($row->won / $row->count * 100) : 0 }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">لا بيانات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">الأداء حسب الموظف</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead><tr><th class="ps-3">الموظف</th><th>العملاء</th><th>مغلقون</th><th class="pe-3">التحويل</th></tr></thead>
                        <tbody>
                            @forelse($byUser as $row)
                                <tr>
                                    <td class="ps-3">{{ $userNames[$row->assigned_to] ?? '#'.$row->assigned_to }}</td>
                                    <td>{{ $row->count }}</td>
                                    <td class="text-success">{{ $row->won }}</td>
                                    <td class="pe-3">{{ $row->count > 0 ? round($row->won / $row->count * 100) : 0 }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">لا بيانات — أسند عملاء للموظفين</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
