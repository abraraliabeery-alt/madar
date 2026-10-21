@extends('facility.layouts.app')

@section('title', 'الوحدات الشاغرة')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">الوحدات الشاغرة</h1>
            <a href="{{ route('facility.products.index') }}" class="text-muted small">← كل العقارات</a>
        </div>
        <span class="badge bg-warning text-dark fs-6">{{ $vacant->count() }} وحدة متاحة</span>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">الوحدة</th>
                        <th>الأصل</th>
                        <th>التصنيف</th>
                        <th>السعر</th>
                        <th>معروضة للإيجار؟</th>
                        <th class="pe-3">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vacant as $unit)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('facility.products.show', $unit) }}" class="fw-semibold text-decoration-none">
                                    {{ $unit->getTranslatedTitle() ?? '#'.$unit->id }}
                                </a>
                            </td>
                            <td class="text-muted small">
                                @if($unit->parent)
                                    {{ $unit->parent->getTranslatedTitle() ?? '#'.$unit->parent->id }}
                                    @if($unit->parent->parent)
                                        ← {{ $unit->parent->parent->getTranslatedTitle() ?? '#'.$unit->parent->parent->id }}
                                    @endif
                                @else
                                    <span class="text-muted">مستقل</span>
                                @endif
                            </td>
                            <td class="small">{{ $unit->category?->getTranslatedName() ?? '—' }}</td>
                            <td>{{ $unit->price ? number_format($unit->price) . ' ر.س' : '—' }}</td>
                            <td>
                                @if($offeredIds->contains($unit->id))
                                    <span class="badge bg-success">لها عرض نشط</span>
                                @else
                                    <span class="badge bg-secondary">لا يوجد عرض</span>
                                @endif
                            </td>
                            <td class="pe-3">
                                <a href="{{ route('facility.offers.create') }}" class="btn btn-sm btn-outline-primary">إنشاء عرض</a>
                                <a href="{{ route('facility.contracts.create') }}" class="btn btn-sm btn-outline-success">عقد</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">ممتاز — كل الوحدات مشغولة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
