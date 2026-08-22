@extends('admin.layouts.app')

@section('panel_title', 'إضافة طلب عقار ذكي')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 font-weight-bold">إضافة طلب عقار جديد</h5>
        <a href="{{ route('admin.product-requests.index') }}" class="btn btn-sm btn-outline-secondary">رجوع للقائمة</a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0">الإدخال الذكي</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">صورة واتساب / لقطة شاشة</label>
                    <div id="screenshot-drop" class="border border-2 border-dashed border-secondary rounded p-3 text-center position-relative" style="min-height: 100px; transition: background .2s;">
                        <input type="file" id="screenshot-input" accept="image/jpeg,image/png,image/jpg,image/webp" class="d-none">
                        <div id="screenshot-hint" class="text-muted small mb-2">اسحب صورة هنا أو اضغط للاختيار أو Ctrl+V للصق</div>
                        <img id="screenshot-preview" class="d-none rounded border" style="max-height: 80px;">
                        <div id="screenshot-actions" class="d-none justify-content-center gap-2 mt-2">
                            <button type="button" id="screenshot-choose" class="btn btn-outline-secondary btn-sm">اختيار ملف آخر</button>
                            <button type="button" id="analyze-image" class="btn btn-info btn-sm" style="color:#fff !important;">تحليل الصورة</button>
                        </div>
                        <div id="screenshot-empty-actions" class="d-flex justify-content-center gap-2 mt-2">
                            <button type="button" id="screenshot-choose-empty" class="btn btn-outline-secondary btn-sm">اختيار ملف</button>
                        </div>
                    </div>
                    <div id="image-status" class="text-muted small mt-1"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">التسجيل الصوتي</label>
                    <div class="d-flex gap-2">
                        <button type="button" id="voice-start" class="btn btn-danger btn-sm" style="color:#fff !important;"><i class="fas fa-microphone"></i> تسجيل</button>
                        <button type="button" id="voice-stop" class="btn btn-secondary btn-sm" style="color:#fff !important;" disabled>إيقاف</button>
                        <span id="voice-status" class="text-muted small align-self-center"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4" id="whatsapp-import-card">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0">استيراد تصدير واتساب</h6>
            <span class="badge bg-info">AI دفعة</span>
        </div>
        <div class="card-body">
            <p class="text-muted small">ارفع ملف <code>.txt</code> المصدّر من محادثة واتساب (دردشة أو قروب). الذكاء يستخرج رسائل اليوم التي تخص العقارات ويحفظها كطلبات جديدة، ثم يحذف الملف مباشرة.</p>
            <div class="input-group mb-2">
                <input type="file" id="whatsapp-file" accept=".txt" class="form-control form-control-sm">
                <button type="button" id="import-whatsapp" class="btn btn-success btn-sm" style="color:#fff !important;">استيراد رسائل اليوم</button>
            </div>
            <div id="import-status" class="text-muted small"></div>
        </div>
    </div>

    <form id="request-form" method="POST" action="{{ route('admin.product-requests.store') }}">
        @csrf
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الجوال <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="phone" class="form-control" required value="{{ old('phone') }}">
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">وصف الطلب <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                        <div class="text-end mt-1">
                            <button type="button" id="analyze-text" class="btn btn-sm btn-outline-primary">تحليل الوصف</button>
                        </div>
                    </div>
                </div>

                <div id="extracted-preview" class="d-none mt-4">
                    <h6 class="font-weight-bold">المعلومات المستخرجة</h6>
                    <div id="extracted-chips" class="d-flex flex-wrap gap-2 mb-2"></div>
                    <p class="text-muted small mb-0">ملاحظات: <span id="extracted-notes"></span></p>
                    <input type="hidden" name="extracted" id="extracted-json">
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-select">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $key === 'new' ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="priority" class="form-label">الأولوية</label>
                        <select name="priority" id="priority" class="form-select">
                            @foreach($priorities as $key => $label)
                                <option value="{{ $key }}" {{ $key === 'normal' ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="assigned_to" class="form-label">مسؤول</label>
                        <select name="assigned_to" id="assigned_to" class="form-select">
                            <option value="">—</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="admin_notes" class="form-label">ملاحظات الإدارة</label>
                        <textarea name="admin_notes" id="admin_notes" rows="2" class="form-control">{{ old('admin_notes') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.product-requests.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ الطلب</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const analyzeTextUrl = @json(route('admin.product-requests.analyze-text'));
    const analyzeImageUrl = @json(route('admin.product-requests.analyze-image'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const description = document.getElementById('description');
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const extractedPreview = document.getElementById('extracted-preview');
    const extractedChips = document.getElementById('extracted-chips');
    const extractedNotes = document.getElementById('extracted-notes');
    const extractedJson = document.getElementById('extracted-json');
    const imageStatus = document.getElementById('image-status');
    const voiceStatus = document.getElementById('voice-status');
    let extracted = {};

    const labels = {
        type: 'نوع العقار', city: 'المدينة', neighborhood: 'الحي',
        min_price: 'أقل سعر', max_price: 'أعلى سعر', rooms: 'الغرف',
        bathrooms: 'الحمامات', area: 'المساحة', purpose: 'الغرض',
        budget: 'الميزانية', phone: 'الجوال', notes: 'ملاحظات'
    };

    function renderExtracted(obj) {
        extracted = obj || {};
        extractedChips.innerHTML = '';
        Object.keys(labels).forEach(k => {
            if (k === 'notes' || extracted[k] === null || extracted[k] === '') return;
            const chip = document.createElement('span');
            chip.className = 'badge bg-secondary';
            chip.textContent = (labels[k] || k) + ': ' + extracted[k];
            extractedChips.appendChild(chip);
        });
        extractedNotes.textContent = extracted.notes || '';
        extractedPreview.classList.remove('d-none');
        extractedJson.value = JSON.stringify(extracted);
        if (extracted.phone && !phoneInput.value) phoneInput.value = extracted.phone;
    }

    async function analyzeText() {
        const text = description.value.trim();
        if (!text) return;
        const btn = document.getElementById('analyze-text');
        const original = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'جارٍ التحليل...';
        try {
            const res = await fetch(analyzeTextUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ description: text })
            });
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'تعذر التحليل');
            renderExtracted(data.extracted);
        } catch (e) {
            alert(e.message || 'حدث خطأ في التحليل.');
        } finally {
            btn.disabled = false;
            btn.textContent = original;
        }
    }

    document.getElementById('analyze-text')?.addEventListener('click', analyzeText);

    // Screenshot: drag & drop + paste + preview
    const screenshotInput = document.getElementById('screenshot-input');
    const screenshotDrop = document.getElementById('screenshot-drop');
    const screenshotHint = document.getElementById('screenshot-hint');
    const screenshotPreview = document.getElementById('screenshot-preview');
    const screenshotActions = document.getElementById('screenshot-actions');
    const screenshotEmptyActions = document.getElementById('screenshot-empty-actions');

    function setScreenshotFile(file) {
        if (!file) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        screenshotInput.files = dt.files;
        screenshotPreview.src = URL.createObjectURL(file);
        screenshotPreview.classList.remove('d-none');
        screenshotHint.classList.add('d-none');
        screenshotEmptyActions.classList.add('d-none');
        screenshotActions.classList.remove('d-none');
        screenshotActions.classList.add('d-flex');
    }

    document.getElementById('screenshot-choose')?.addEventListener('click', () => screenshotInput.click());
    document.getElementById('screenshot-choose-empty')?.addEventListener('click', () => screenshotInput.click());
    screenshotInput?.addEventListener('change', () => setScreenshotFile(screenshotInput.files[0]));

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
        screenshotDrop?.addEventListener(evt, (e) => { e.preventDefault(); e.stopPropagation(); });
    });
    ['dragenter', 'dragover'].forEach(evt => {
        screenshotDrop?.addEventListener(evt, () => screenshotDrop.classList.add('bg-light'));
    });
    ['dragleave', 'drop'].forEach(evt => {
        screenshotDrop?.addEventListener(evt, () => screenshotDrop.classList.remove('bg-light'));
    });
    screenshotDrop?.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) setScreenshotFile(file);
    });
    screenshotDrop?.addEventListener('click', (e) => {
        if (e.target === screenshotDrop || e.target === screenshotHint) screenshotInput.click();
    });

    document.addEventListener('paste', (e) => {
        if (!screenshotDrop || !screenshotInput) return;
        const active = document.activeElement;
        if (active && ['input', 'textarea', 'select'].includes(active.tagName.toLowerCase()) && active !== screenshotInput) return;
        const items = e.clipboardData?.items;
        if (!items) return;
        for (const item of items) {
            if (item.type.startsWith('image/')) {
                const file = item.getAsFile();
                if (file) setScreenshotFile(file);
                e.preventDefault();
                break;
            }
        }
    });

    document.getElementById('analyze-image')?.addEventListener('click', async () => {
        const input = document.getElementById('screenshot-input');
        const file = input?.files[0];
        if (!file) { alert('اختر صورة أولاً.'); return; }
        const btn = document.getElementById('analyze-image');
        const original = btn.textContent;
        btn.disabled = true;
        imageStatus.textContent = 'جارٍ قراءة الصورة...';
        try {
            const formData = new FormData();
            formData.append('image', file);
            const res = await fetch(analyzeImageUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: formData
            });
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'تعذر قراءة الصورة');
            if (data.extracted?.notes) description.value = data.extracted.notes;
            renderExtracted(data.extracted);
        } catch (e) {
            alert(e.message || 'حدث خطأ أثناء قراءة الصورة.');
        } finally {
            btn.disabled = false;
            imageStatus.textContent = '';
            btn.textContent = original;
        }
    });

    // Voice
    const voiceStart = document.getElementById('voice-start');
    const voiceStop = document.getElementById('voice-stop');
    const Speech = window.SpeechRecognition || window.webkitSpeechRecognition;
    let rec = null, listening = false;

    if (Speech && voiceStart && voiceStop) {
        voiceStart.addEventListener('click', () => {
            if (listening) return;
            rec = new Speech();
            rec.lang = 'ar-SA';
            rec.interimResults = true;
            rec.continuous = true;
            rec.onresult = (e) => {
                const text = Array.from(e.results).map(r => r[0].transcript).join('');
                description.value = (description.value ? description.value + ' ' : '') + text;
            };
            rec.onerror = () => { voiceStatus.textContent = 'خطأ في التسجيل'; stop(); };
            rec.onend = () => { listening = false; voiceStart.disabled = false; voiceStop.disabled = true; voiceStatus.textContent = ''; };
            rec.start();
            listening = true;
            voiceStart.disabled = true;
            voiceStop.disabled = false;
            voiceStatus.textContent = 'جارٍ الاستماع...';
        });

        voiceStop.addEventListener('click', stop);
    } else if (voiceStart) {
        voiceStart.disabled = true;
        voiceStart.textContent = 'غير مدعوم';
    }

    function stop() {
        if (rec) { try { rec.stop(); } catch (_) {} }
    }

    // WhatsApp chat import
    const importBtn = document.getElementById('import-whatsapp');
    const importFile = document.getElementById('whatsapp-file');
    const importStatus = document.getElementById('import-status');
    const importUrl = @json(route('admin.product-requests.import-whatsapp'));

    if (importBtn && importFile) {
        importBtn.addEventListener('click', async () => {
            const file = importFile.files[0];
            if (!file) { alert('اختر ملف .txt أولاُ.'); return; }

            const formData = new FormData();
            formData.append('chat_file', file);

            importBtn.disabled = true;
            importStatus.textContent = 'جارٍ قراءة وتحليل الملف...';

            try {
                const res = await fetch(importUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: formData
                });
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'تعذر الاستيراد');
                importStatus.innerHTML = '<span class="text-success">' + (data.message || 'تم الاستيراد.') + '</span>';
                importFile.value = '';
            } catch (e) {
                importStatus.innerHTML = '<span class="text-danger">' + (e.message || 'حدث خطأ أثناء الاستيراد.') + '</span>';
            } finally {
                importBtn.disabled = false;
            }
        });
    }
});
</script>
@endsection