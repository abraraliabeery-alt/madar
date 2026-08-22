@extends('admin.layouts.app')

@section('panel_title', 'تفاصيل طلب العقار #' . $productRequest->id)

@php
$statusColors = [
    'new' => 'bg-info',
    'contacted' => 'bg-primary',
    'in_progress' => 'bg-warning text-dark',
    'resolved' => 'bg-success',
    'closed' => 'bg-secondary',
    'rejected' => 'bg-danger',
];

$priorityColors = [
    'low' => 'bg-secondary',
    'normal' => 'bg-light text-dark border',
    'high' => 'bg-warning text-dark',
    'urgent' => 'bg-danger',
];
@endphp

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">معلومات الطلب</h5>
                    <div>
                        <span class="badge {{ $statusColors[$productRequest->status] ?? 'bg-light text-dark' }} me-1">{{ $productRequest->statusLabel() }}</span>
                        <span class="badge {{ $priorityColors[$productRequest->priority] ?? 'bg-light text-dark' }}">{{ $productRequest->priorityLabel() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">الاسم</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">رقم الجوال</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">المصدر</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->source }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ الطلب</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            @php($days = now()->diffInDays($productRequest->updated_at))
                            @php($stale = $productRequest->isOpen() && $days >= 3)
                            <label class="text-muted small">{{ __('admin.product_requests.last_update') }}</label>
                            <p class="mb-0 fw-bold {{ $stale ? 'text-danger' : '' }}">{{ $productRequest->updated_at->format('Y-m-d H:i') }}</p>
                            @if($stale)
                                <span class="badge bg-danger border border-danger">
                                    {{ __('admin.product_requests.stale_warning', ['days' => $days]) }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ التواصل</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->contacted_at?->format('Y-m-d H:i') ?? 'لم يتم' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ الإغلاق</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->closed_at?->format('Y-m-d H:i') ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">مسؤول الطلب</label>
                            <p class="mb-0 fw-bold">{{ $productRequest->assignedTo?->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>

                    <h6 class="fw-bold border-top pt-3">وصف الطلب</h6>
                    <p class="text-dark" style="white-space: pre-line;">{{ $productRequest->description }}</p>

                    <h6 class="fw-bold border-top pt-3 mt-4">المعلومات المستخرجة</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse(($productRequest->extracted ?? []) as $key => $value)
                            @if($value !== null && $value !== '')
                                <span class="badge bg-secondary fs-6">{{ $key }}: {{ is_array($value) ? implode(', ', $value) : $value }}</span>
                            @endif
                        @empty
                            <span class="text-muted">لا توجد معلومات مستخرجة.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4" id="ai-request-assistant">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 font-weight-bold">المطابقة والرد الذكي</h5>
                    <span class="badge bg-info">AI</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">يحلل طلب العميل ويعرض العقارات الأنسب، ثم يقترح ردًا للمراجعة والنسخ. لا يتم إرسال أي رسالة تلقائيًا.</p>
                    <button type="button" class="btn btn-outline-primary" id="ai-match-request">تحليل الطلب وإيجاد العقارات</button>
                    <div id="ai-match-results" class="mt-3" style="display:none;">
                        <div id="ai-match-products" class="mb-3"></div>
                        <label class="form-label fw-bold">الرد المقترح</label>
                        <textarea class="form-control" id="ai-suggested-reply" rows="6"></textarea>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="ai-copy-reply">نسخ الرد</button>
                            <a href="#" class="btn btn-sm btn-success" id="ai-whatsapp-reply" target="_blank">
                                <i class="fab fa-whatsapp"></i> إرسال واتساب
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 font-weight-bold">ملاحظات الإدارة</h5>
                </div>
                <div class="card-body">
                    @if($productRequest->admin_notes)
                        <p style="white-space: pre-line;">{{ $productRequest->admin_notes }}</p>
                    @else
                        <p class="text-muted">لا توجد ملاحظات.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 font-weight-bold">إدارة الطلب</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.product-requests.update', $productRequest) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $productRequest->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الأولوية</label>
                            <select name="priority" class="form-select" required>
                                @foreach($priorities as $key => $label)
                                    <option value="{{ $key }}" {{ $productRequest->priority === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تخصيص لموظف</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">—</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $productRequest->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="admin_notes" rows="5" class="form-control">{{ $productRequest->admin_notes }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">حفظ التحديثات</button>
                            <a href="{{ route('admin.product-requests.index') }}" class="btn btn-outline-secondary">العودة للقائمة</a>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.product-requests.destroy', $productRequest) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">حذف الطلب</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    var button = document.getElementById('ai-match-request');
    var results = document.getElementById('ai-match-results');
    var products = document.getElementById('ai-match-products');
    var reply = document.getElementById('ai-suggested-reply');
    var copy = document.getElementById('ai-copy-reply');
    var whatsapp = document.getElementById('ai-whatsapp-reply');
    var phone = @json($productRequest->phone ?? '');
    if(!button || !results || !products || !reply) return;

    button.addEventListener('click', function(){
        var original = button.textContent;
        button.disabled = true;
        button.textContent = 'جارٍ التحليل...';
        fetch(@json(route('admin.product-requests.ai-matches', $productRequest)), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        }).then(function(response){
            return response.json().then(function(data){ return {ok: response.ok, data: data}; });
        }).then(function(result){
            if(!result.ok || !result.data.success) throw new Error(result.data.message || 'تعذر تحليل الطلب');
            var data = result.data.data || {};
            products.innerHTML = '';
            (data.matches || []).forEach(function(match){
                var card = document.createElement('div');
                card.className = 'border rounded p-2 mb-2';
                var title = document.createElement('a');
                title.href = '{{ url('/admin/products') }}/' + String(match.product_id) + '/edit';
                title.target = '_blank';
                title.className = 'fw-bold text-decoration-none';
                title.textContent = match.title || ('العقار #' + match.product_id);
                var meta = document.createElement('div');
                meta.className = 'text-muted small mt-1';
                meta.textContent = 'الملاءمة ' + String(match.score || 0) + '% · ' + (match.reason || '');
                card.append(title, meta);
                products.appendChild(card);
            });
            if(!(data.matches || []).length){
                products.textContent = 'لم يتم العثور على عقارات مناسبة حاليًا.';
            }
            reply.value = data.reply || '';
            if(whatsapp && phone){
                var clean = String(phone).replace(/\D/g, '').replace(/^\+?/, '');
                var text = encodeURIComponent(reply.value || '');
                whatsapp.href = 'https://wa.me/' + clean + '?text=' + text;
                whatsapp.classList.remove('disabled');
            } else if(whatsapp){
                whatsapp.classList.add('disabled');
            }
            results.style.display = 'block';
        }).catch(function(error){
            window.alert(error.message || 'تعذر الاتصال بخدمة المطابقة.');
        }).finally(function(){
            button.disabled = false;
            button.textContent = original;
        });
    });

    if(copy){
        copy.addEventListener('click', function(){
            navigator.clipboard.writeText(reply.value || '');
        });
    }
});
</script>
@endpush
@endsection
