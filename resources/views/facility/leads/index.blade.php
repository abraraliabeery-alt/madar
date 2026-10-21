@extends('facility.layouts.app')

@section('title', 'العملاء المحتملون')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h1 class="h4 fw-bold mb-0">العملاء المحتملون</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('facility.leads.report') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-chart-bar me-1"></i> تقرير التحويل
            </a>
            <a href="{{ route('facility.leads.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> عميل محتمل جديد
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">الإجمالي</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">جدد</div>
                <div class="h4 fw-bold mb-0 text-primary">{{ $stats['new'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3">
                <div class="text-muted small">مغلقون بنجاح</div>
                <div class="h4 fw-bold mb-0 text-success">{{ $stats['won'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-3 {{ $stats['due_followups'] ? 'border-danger' : '' }}">
                <div class="text-muted small">متابعات مستحقة</div>
                <div class="h4 fw-bold mb-0 {{ $stats['due_followups'] ? 'text-danger' : '' }}">{{ $stats['due_followups'] }}</div>
            </div>
        </div>
    </div>

    <form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الجوال" class="form-control form-control-sm" style="max-width:220px">
        <select name="assigned_to" class="form-select form-select-sm" style="max-width:180px">
            <option value="">كل الموظفين</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" @selected(request('assigned_to') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-sm btn-outline-secondary">تصفية</button>
        <a href="{{ route('facility.leads.index') }}" class="btn btn-sm btn-outline-secondary">تفريغ</a>
    </form>

    <div class="row g-3">
        @foreach(\App\Models\Lead::STAGES as $stageKey => $stageLabel)
            @php $stageLeads = $byStage->get($stageKey, collect()); @endphp
            <div class="col-12 col-md-4 col-xl-2">
                <div class="card shadow-sm h-100">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center
                        {{ $stageKey === 'won' ? 'bg-success-subtle' : ($stageKey === 'lost' ? 'bg-danger-subtle' : 'bg-light') }}">
                        <span class="fw-semibold small">{{ $stageLabel }}</span>
                        <span class="badge bg-secondary">{{ is_countable($stageLeads) ? count($stageLeads) : $stageLeads->total() }}</span>
                    </div>
                    <div class="card-body p-2 lead-column" data-stage="{{ $stageKey }}" style="min-height: 60px;">
                        @forelse($stageLeads as $lead)
                            <a href="{{ route('facility.leads.show', $lead) }}" draggable="true" data-lead-id="{{ $lead->id }}"
                               class="d-block border rounded p-2 mb-2 text-decoration-none text-dark bg-white lead-card"
                               style="cursor: grab;">
                                <div class="fw-semibold small">{{ $lead->name }}</div>
                                @if($lead->phone)
                                    <div class="text-muted" style="font-size:.75rem" dir="ltr">{{ $lead->phone }}</div>
                                @endif
                                @if($lead->product)
                                    <div class="text-muted text-truncate" style="font-size:.75rem">
                                        <i class="fas fa-building"></i> {{ $lead->product->getTranslatedTitle() ?? '#'.$lead->product->id }}
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    @if($lead->assignedTo)
                                        <span class="text-muted" style="font-size:.7rem"><i class="fas fa-user"></i> {{ $lead->assignedTo->name }}</span>
                                    @else
                                        <span></span>
                                    @endif
                                    @if($lead->next_follow_up)
                                        <span class="badge {{ $lead->next_follow_up <= now() ? 'bg-danger' : 'bg-warning text-dark' }}" style="font-size:.65rem">
                                            {{ \Carbon\Carbon::parse($lead->next_follow_up)->format('m-d H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="text-muted text-center small py-2 empty-hint">—</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($leads instanceof \Illuminate\Pagination\LengthAwarePaginator && $leads->hasPages())
        <div class="mt-3">{{ $leads->links() }}</div>
    @endif
</div>

<script>
(function () {
    const urlTemplate = @json(route('facility.leads.update-stage', ['lead' => 0]));
    const csrf = @json(csrf_token());
    let dragged = null;

    document.querySelectorAll('.lead-card').forEach(card => {
        card.addEventListener('dragstart', e => {
            dragged = card;
            card.style.opacity = '0.4';
            e.dataTransfer.effectAllowed = 'move';
        });
        card.addEventListener('dragend', () => { card.style.opacity = ''; });
    });

    document.querySelectorAll('.lead-column').forEach(col => {
        col.addEventListener('dragover', e => {
            e.preventDefault();
            col.classList.add('bg-light');
        });
        col.addEventListener('dragleave', () => col.classList.remove('bg-light'));
        col.addEventListener('drop', e => {
            e.preventDefault();
            col.classList.remove('bg-light');
            if (!dragged || dragged.closest('.lead-column') === col) return;

            const leadId = dragged.dataset.leadId;
            const stage = col.dataset.stage;
            const url = urlTemplate.replace('/0/stage', '/' + leadId + '/stage');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({_method: 'PATCH', stage: stage}),
            }).then(r => {
                if (!r.ok) throw new Error();
                col.querySelector('.empty-hint')?.remove();
                col.appendChild(dragged);
                const src = document.querySelectorAll('.lead-column');
                src.forEach(c => {
                    const headerBadge = c.closest('.card').querySelector('.badge');
                    const count = c.querySelectorAll('.lead-card').length;
                    headerBadge.textContent = count;
                    if (!c.querySelector('.empty-hint') && count === 0) {
                        const hint = document.createElement('div');
                        hint.className = 'text-muted text-center small py-2 empty-hint';
                        hint.textContent = '—';
                        c.appendChild(hint);
                    }
                });
            }).catch(() => alert('تعذر تحديث المرحلة'));
        });
    });
})();
</script>
@endsection
