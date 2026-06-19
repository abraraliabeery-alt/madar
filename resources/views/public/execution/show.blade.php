@extends('layouts.app')

@section('title', 'تفاصيل طلب التنفيذ #' . $executionRequest->id)

@section('content')
    <header class="bg-slate-900 text-white py-3 shadow-md">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <a href="{{ route('public.execution.marketplace') }}" class="flex items-center gap-2 text-xs text-slate-200 hover:text-white">
                <i class="fas fa-arrow-right"></i>
                العودة لسوق التنفيذ
            </a>
            <div class="flex items-center gap-2 text-xs">
                @auth
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-800 text-[11px]">
                        <i class="fas fa-user ml-1 text-[9px]"></i>
                        {{ auth()->user()->name ?? 'حساب مستخدم' }}
                    </span>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-900 text-white text-[11px] font-semibold hover:bg-slate-800">
                        <i class="fas fa-sign-in-alt ml-1 text-[9px]"></i>
                        تسجيل الدخول لتقديم عرض
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @php
        $t = $executionRequest->translations->firstWhere('locale', app()->getLocale());
        $budgetRange = null;
        if(!is_null($executionRequest->budget_min) || !is_null($executionRequest->budget_max)) {
            $min = $executionRequest->budget_min ? number_format($executionRequest->budget_min) : null;
            $max = $executionRequest->budget_max ? number_format($executionRequest->budget_max) : null;
            $budgetRange = trim(($min ? $min : '') . ' - ' . ($max ? $max : ''));
        }

        $myFacility = null;
        $myBid = null;
        if (auth()->check()) {
            $myFacility = auth()->user()->facilities()->first();
            if ($myFacility) {
                $myBid = $executionRequest->bids->firstWhere('executor_facility_id', $myFacility->id);
            }
        }

        $isPastDue = false;
        if (!empty($executionRequest->due_date)) {
            try {
                $due = $executionRequest->due_date instanceof \Carbon\CarbonInterface
                    ? $executionRequest->due_date
                    : \Carbon\Carbon::parse($executionRequest->due_date);
                $isPastDue = now()->startOfDay()->gt($due->endOfDay());
            } catch (\Throwable $e) {
                $isPastDue = false;
            }
        }

        $isLockedBid = $myBid && in_array($myBid->status, ['accepted', 'rejected'], true);
        $isFinalSubmitted = $myBid && !empty($myBid->data['submitted_at']);
    @endphp

    <main class="flex-1 bg-slate-50">
        <section class="max-w-5xl mx-auto px-4 py-6 space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-3">
                    <div class="space-y-2">
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-50 text-slate-800 text-[11px] border border-slate-200">
                            <i class="fas fa-circle text-[7px] ml-1"></i>
                            طلب تنفيذ مفتوح
                        </div>
                        <h1 class="text-xl md:text-2xl font-bold text-slate-900">
                            {{ $t->title ?? ('طلب تنفيذ #' . $executionRequest->id) }}
                        </h1>
                        <p class="text-xs text-slate-500 flex flex-wrap gap-3">
                            <span><i class="fas fa-clock ml-1 text-amber-500"></i> {{ $executionRequest->created_at->diffForHumans() }}</span>
                            @if($executionRequest->due_date)
                                <span><i class="fas fa-calendar ml-1 text-indigo-500"></i> حتى {{ $executionRequest->due_date->format('Y-m-d') }}</span>
                            @endif
                            @if($executionRequest->type)
                                <span><i class="fas fa-tag ml-1 text-slate-500"></i> {{ $executionRequest->type }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-xs text-slate-600 bg-slate-50 rounded-xl p-3 min-w-[180px]">
                        <div class="flex items-center justify-between mb-1">
                            <span>العروض المستلمة</span>
                            <span class="font-semibold text-slate-900"><i class="fas fa-gavel ml-1 text-amber-500"></i> {{ $executionRequest->bids->count() }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            @if($budgetRange)
                                <span><i class="fas fa-wallet ml-1 text-slate-700"></i> {{ $budgetRange }} ريال</span>
                            @else
                                <span><i class="fas fa-wallet ml-1 text-slate-400"></i> الميزانية غير محددة</span>
                            @endif
                            <span><i class="fas fa-signal ml-1 text-slate-400"></i> الحالة: {{ $executionRequest->status }}</span>
                        </div>
                    </div>
                </div>

                @if($t && $t->description)
                    <div class="border-t border-slate-100 pt-3 mt-2">
                        <h2 class="text-sm font-semibold text-slate-800 mb-1">وصف الطلب</h2>
                        <p class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ $t->description }}</p>
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-3 mt-3">
                    <h2 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-timeline text-indigo-500"></i>
                        الخط الزمني للطلب
                    </h2>
                    <div class="flex items-center gap-3 text-[11px] text-slate-600 flex-wrap">
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-slate-900"></span>
                            <span>تم إنشاء الطلب {{ $executionRequest->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <span class="h-px w-6 bg-slate-200"></span>
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full {{ $executionRequest->bids->count() ? 'bg-slate-900' : 'bg-slate-300' }}"></span>
                            <span>
                                @if($executionRequest->bids->count())
                                    استلام {{ $executionRequest->bids->count() }} عرض/عروض
                                @else
                                    بانتظار أول عرض من المنفِّذين
                                @endif
                            </span>
                        </div>
                        <span class="h-px w-6 bg-slate-200"></span>
                        <div class="flex items-center gap-1">
                            @php
                                $isClosed = in_array($executionRequest->status, ['completed','closed','cancelled']);
                            @endphp
                            <span class="w-2 h-2 rounded-full {{ $isClosed ? 'bg-slate-900' : 'bg-slate-300' }}"></span>
                            <span>
                                @if($isClosed)
                                    الطلب منتهٍ بالحالة: {{ $executionRequest->status }}
                                @else
                                    الطلب ما زال قيد المتابعة
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                        <h2 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-gavel text-amber-500"></i>
                            العروض المقدَّمة
                        </h2>
                        <div class="space-y-3 text-xs">
                            @forelse($executionRequest->bids as $bid)
                                <div class="border border-slate-100 rounded-xl p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="text-slate-800 font-semibold">
                                            {{ optional($bid->executorUser)->name ?? 'منفِّذ غير معرّف' }}
                                        </div>
                                        <div class="text-slate-600 flex flex-wrap gap-3">
                                            <span><i class="fas fa-wallet ml-1 text-slate-700"></i> {{ $bid->price_total ? number_format($bid->price_total) . ' ' . ($bid->currency ?? 'SAR') : 'لم يحدد السعر' }}</span>
                                            <span><i class="fas fa-calendar-day ml-1 text-indigo-500"></i> {{ $bid->duration_days ? $bid->duration_days . ' يوم' : 'مدة غير محددة' }}</span>
                                            <span><i class="fas fa-shield-halved ml-1 text-slate-500"></i> {{ $bid->warranty_months ? $bid->warranty_months . ' شهر ضمان' : 'بدون ضمان معلَن' }}</span>
                                        </div>
                                        @if(isset($bid->data['notes']) && $bid->data['notes'])
                                            <p class="text-slate-600 mt-1 whitespace-pre-line">{{ $bid->data['notes'] }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-1 text-[11px]">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                            @if($bid->status === 'accepted') bg-slate-200 text-slate-800
                                            @elseif($bid->status === 'rejected') bg-red-100 text-red-700
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ $bid->status }}
                                        </span>
                                        <span class="text-slate-400">{{ $bid->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 text-xs">لا توجد عروض حتى الآن على هذا الطلب.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                        <h2 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-handshake text-slate-700"></i>
                            قدّم عرضك على هذا الطلب
                        </h2>

                        @auth
                            @error('execution')
                                <p class="mb-2 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror

                            @if($isPastDue)
                                <p class="text-xs text-slate-600">انتهى وقت التقديم على هذا الطلب.</p>
                            @elseif($isLockedBid)
                                <p class="text-xs text-slate-600">تم اتخاذ قرار بشأن عرضك، ولا يمكن تعديله.</p>
                            @elseif($isFinalSubmitted)
                                <p class="text-xs text-slate-600">تم إرسال العرض بشكل نهائي ولا يمكن تعديله.</p>
                            @endif

                            @php
                                $facilityProfileDocs = [];
                                if (!empty($myFacility)) {
                                    $candidates = [
                                        ['path' => (string) ($myFacility->License ?? ''), 'label' => 'ترخيص المنشأة'],
                                        ['path' => (string) ($myFacility->license ?? ''), 'label' => 'ترخيص المنشأة'],
                                        ['path' => (string) ($myFacility->license_path ?? ''), 'label' => 'ترخيص المنشأة'],
                                        ['path' => (string) data_get($myFacility->customization_settings ?? [], 'qualification_docs.commercial_register.path', ''), 'label' => 'السجل التجاري'],
                                        ['path' => (string) ($myFacility->logo_path ?? ''), 'label' => 'شعار المنشأة'],
                                        ['path' => (string) ($myFacility->logo ?? ''), 'label' => 'شعار المنشأة'],
                                        ['path' => (string) ($myFacility->cover_image ?? ''), 'label' => 'غلاف المنشأة'],
                                        ['path' => (string) ($myFacility->header ?? ''), 'label' => 'غلاف المنشأة'],
                                    ];
                                    foreach ($candidates as $row) {
                                        $p = trim((string) ($row['path'] ?? ''));
                                        if ($p === '') {
                                            continue;
                                        }
                                        if (!Storage::disk('public')->exists($p)) {
                                            continue;
                                        }
                                        $facilityProfileDocs[] = [
                                            'disk' => 'public',
                                            'path' => $p,
                                            'label' => (string) ($row['label'] ?? 'مستند منشأة'),
                                        ];
                                    }
                                    $facilityProfileDocs = array_values(collect($facilityProfileDocs)->unique('path')->all());
                                }
                            @endphp

                            <a href="{{ route('public.execution.bids.form', $executionRequest) }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
                                <i class="fas fa-file-signature ml-1 text-[10px]"></i>
                                فتح نموذج تقديم العرض (صفحة كاملة)
                            </a>
                            <p class="mt-2 text-[11px] text-slate-500">تم نقل نموذج التقديم إلى صفحة مستقلة لأن النموذج هنا كان ضيق.</p>

                            <form
                                method="POST"
                                action="{{ route('public.execution.bids.store', $executionRequest) }}"
                                class="hidden space-y-3 text-xs"
                                enctype="multipart/form-data"
                                x-data="{
                                    step: 1,
                                    maxStep: 4,
                                    saving: false,
                                    autosaveUrl: @js(route('public.execution.bids.store', $executionRequest)),
                                    items: (() => {
                                        try {
                                            const raw = @js(old('financial_items_json', json_encode($myBid?->data['financial']['items'] ?? [])));
                                            const parsed = typeof raw === 'string' ? JSON.parse(raw || '[]') : (raw || []);
                                            return Array.isArray(parsed) ? parsed : [];
                                        } catch (e) {
                                            return [];
                                        }
                                    })(),
                                    req: (() => {
                                        const r = @js(data_get($executionRequest->data ?? [], 'bid_requirements', []));
                                        const minAtt = Number(r?.required_attachments_min ?? 1);
                                        const requiredFields = Array.isArray(r?.required_fields) ? r.required_fields : ['price_total','message','technical_plan','declaration'];
                                        const requiredAttachments = Array.isArray(r?.required_attachments) ? r.required_attachments : [];
                                        return {
                                            required_attachments_min: Math.max(0, Math.min(10, isFinite(minAtt) ? minAtt : 1)),
                                            required_fields: requiredFields,
                                            required_attachments: requiredAttachments
                                        };
                                    })(),
                                    addItem() {
                                        this.items.push({ name: '', qty: 1, unit_price: 0, total: 0 });
                                        this.recalc();
                                    },
                                    removeItem(i) {
                                        this.items.splice(i, 1);
                                        this.recalc();
                                    },
                                    recalc() {
                                        this.items = (this.items || []).map((r) => {
                                            const qty = Number(r.qty || 0);
                                            const unit = Number(r.unit_price || 0);
                                            const total = Math.round((qty * unit) * 100) / 100;
                                            return { ...r, qty, unit_price: unit, total };
                                        });
                                        const el = this.$refs.financialItems;
                                        if (el) {
                                            el.value = JSON.stringify(this.items || []);
                                        }
                                    },
                                    totalSum() {
                                        return (this.items || []).reduce((s, r) => s + Number(r.total || 0), 0);
                                    },
                                    attachmentsCount() {
                                        const existing = Number(this.$refs.existingAttachmentsCount?.value || 0);
                                        const removed = (this.$el.querySelectorAll('input[name="remove_attachments[]"]:checked') || []).length;
                                        const add = Number(this.$refs.newAttachments?.files?.length || 0);
                                        return Math.max(0, existing - removed) + add;
                                    },
                                    attachmentTypeCount(key) {
                                        const existing = Number(this.$el.querySelector(`input[type=hidden][data-existing-type='${key}']`)?.value || 0);
                                        const add = Number(this.$el.querySelector(`input[type=file][data-attach-key='${key}']`)?.files?.length || 0);
                                        return existing + add;
                                    },
                                    missingForFinal() {
                                        const missing = [];
                                        const requiredFields = (this.req?.required_fields || []);

                                        if (requiredFields.includes('price_total')) {
                                            const v = this.$el.querySelector('input[name="price_total"]')?.value;
                                            if (v === null || v === '' || Number(v) < 0) missing.push('قيمة العرض');
                                        }
                                        if (requiredFields.includes('message')) {
                                            const v = this.$el.querySelector('textarea[name="message"]')?.value;
                                            if (!v || String(v).trim() === '') missing.push('ملخص العرض');
                                        }
                                        if (requiredFields.includes('technical_plan')) {
                                            const v = this.$el.querySelector('textarea[name="technical_plan"]')?.value;
                                            if (!v || String(v).trim() === '') missing.push('العرض الفني');
                                        }
                                        if (requiredFields.includes('declaration')) {
                                            const checked = !!this.$el.querySelector('input[name="declaration"]')?.checked;
                                            if (!checked) missing.push('الإقرار والتعهد');
                                        }

                                        const minAtt = Number(this.req?.required_attachments_min || 0);
                                        if (minAtt > 0 && this.attachmentsCount() < minAtt) {
                                            missing.push('المرفقات');
                                        }

                                        const requiredTypes = Array.isArray(this.req?.required_attachments) ? this.req.required_attachments : [];
                                        for (const t of requiredTypes) {
                                            if (!t || !t.required || !t.key) continue;
                                            const key = String(t.key);
                                            const label = String(t.label || t.key);
                                            const has = (this.$el.querySelector(`input[type=file][data-attach-key='${key}']`)?.files?.length || 0) > 0
                                                || (Number(this.$el.querySelector(`input[type=hidden][data-existing-type='${key}']`)?.value || 0) > 0);
                                            if (!has) {
                                                missing.push(label);
                                            }
                                        }

                                        return missing;
                                    },
                                    async autosaveDraft() {
                                        if (this.saving) return;
                                        const form = this.$el;
                                        if (!form) return;
                                        this.recalc();
                                        this.saving = true;

                                        try {
                                            const raw = new FormData(form);
                                            const fd = new FormData();
                                            for (const [k, v] of raw.entries()) {
                                                if (v instanceof File) {
                                                    continue;
                                                }
                                                fd.append(k, v);
                                            }
                                            fd.set('action', 'save_draft');

                                            const res = await fetch(this.autosaveUrl, {
                                                method: 'POST',
                                                headers: {
                                                    'Accept': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                },
                                                body: fd,
                                            });
                                            await res.json().catch(() => null);
                                        } catch (e) {
                                        } finally {
                                            this.saving = false;
                                        }
                                    },
                                    next() {
                                        this.recalc();
                                        this.autosaveDraft();
                                        this.step = Math.min(this.maxStep, this.step + 1);
                                    },
                                    prev() {
                                        this.step = Math.max(1, this.step - 1);
                                    }
                                }"
                                x-init="recalc()"
                            >
                                @csrf

                                <input type="hidden" name="financial_items_json" x-ref="financialItems" value="">

                                @if(!empty($myFacility))
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <div class="text-[11px] font-semibold text-slate-800">بيانات المنشأة (تُستخدم تلقائيًا)</div>
                                                <div class="text-[11px] text-slate-500 mt-0.5">لن تحتاج لإعادة تعبئة أو رفع مستندات المنشأة الثابتة إلا عند الحاجة.</div>
                                            </div>
                                            <div class="text-[11px] text-slate-500">{{ $myFacility->name ?? '' }}</div>
                                        </div>
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                                            <div class="flex items-center justify-between"><span class="text-slate-500">الهاتف</span><span class="font-semibold text-slate-900">{{ $myFacility->phone ?? '—' }}</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-500">البريد</span><span class="font-semibold text-slate-900">{{ $myFacility->email ?? '—' }}</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-500">رقم الترخيص</span><span class="font-semibold text-slate-900">{{ $myFacility->license_number ?? '—' }}</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-500">انتهاء الترخيص</span><span class="font-semibold text-slate-900">{{ $myFacility->license_expiry?->format('Y-m-d') ?? '—' }}</span></div>
                                        </div>
                                        @if(!empty($facilityProfileDocs))
                                            <div class="mt-2">
                                                <div class="text-[11px] font-semibold text-slate-800 mb-1">مستندات منشأتك المتاحة</div>
                                                <div class="space-y-1">
                                                    @foreach($facilityProfileDocs as $doc)
                                                        <a class="block text-[11px] text-slate-800 hover:underline" href="{{ Storage::disk('public')->url($doc['path']) }}" target="_blank">
                                                            {{ $doc['label'] }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="text-[11px] text-slate-500">خطوات التقديم</div>
                                        <div class="mt-1 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-2 bg-slate-900" :style="`width: ${(step / maxStep) * 100}%`"></div>
                                        </div>
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        <span x-text="step"></span>/<span x-text="maxStep"></span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 text-[11px]">
                                    <button type="button" class="px-2.5 py-1 rounded-full border" :class="step===1 ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-white border-slate-200 text-slate-600'" @click="step=1">بيانات العرض</button>
                                    <button type="button" class="px-2.5 py-1 rounded-full border" :class="step===2 ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-white border-slate-200 text-slate-600'" @click="step=2">الظرف الفني</button>
                                    <button type="button" class="px-2.5 py-1 rounded-full border" :class="step===3 ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-white border-slate-200 text-slate-600'" @click="step=3">الظرف المالي</button>
                                    <button type="button" class="px-2.5 py-1 rounded-full border" :class="step===4 ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-white border-slate-200 text-slate-600'" @click="step=4">مراجعة وإرسال</button>
                                </div>

                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-[11px] font-semibold text-slate-800">قائمة التحقق قبل الإرسال النهائي</div>
                                            <div class="text-[11px] text-slate-500 mt-0.5">هذه قائمة متابعة وليست حقول إدخال. لن يسمح النظام بالإرسال النهائي حتى تكتمل العناصر المطلوبة.</div>
                                        </div>
                                        <div class="text-[11px] text-slate-500" x-show="saving">جارٍ حفظ المسودة...</div>
                                    </div>

                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                                        <div class="flex items-center justify-between bg-white border border-slate-200 rounded-lg px-2 py-1">
                                            <span class="text-slate-700">قيمة العرض</span>
                                            <span class="inline-flex items-center gap-1 font-semibold" :class="missingForFinal().includes('قيمة العرض') ? 'text-red-600' : 'text-slate-900'">
                                                <i class="fas fa-times-circle" x-show="missingForFinal().includes('قيمة العرض')"></i>
                                                <i class="fas fa-check-circle" x-show="!missingForFinal().includes('قيمة العرض')"></i>
                                                <span x-text="missingForFinal().includes('قيمة العرض') ? 'ناقص' : 'مكتمل'"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white border border-slate-200 rounded-lg px-2 py-1">
                                            <span class="text-slate-700">ملخص العرض</span>
                                            <span class="inline-flex items-center gap-1 font-semibold" :class="missingForFinal().includes('ملخص العرض') ? 'text-red-600' : 'text-slate-900'">
                                                <i class="fas fa-times-circle" x-show="missingForFinal().includes('ملخص العرض')"></i>
                                                <i class="fas fa-check-circle" x-show="!missingForFinal().includes('ملخص العرض')"></i>
                                                <span x-text="missingForFinal().includes('ملخص العرض') ? 'ناقص' : 'مكتمل'"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white border border-slate-200 rounded-lg px-2 py-1">
                                            <span class="text-slate-700">العرض الفني</span>
                                            <span class="inline-flex items-center gap-1 font-semibold" :class="missingForFinal().includes('العرض الفني') ? 'text-red-600' : 'text-slate-900'">
                                                <i class="fas fa-times-circle" x-show="missingForFinal().includes('العرض الفني')"></i>
                                                <i class="fas fa-check-circle" x-show="!missingForFinal().includes('العرض الفني')"></i>
                                                <span x-text="missingForFinal().includes('العرض الفني') ? 'ناقص' : 'مكتمل'"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white border border-slate-200 rounded-lg px-2 py-1">
                                            <span class="text-slate-700">الإقرار</span>
                                            <span class="inline-flex items-center gap-1 font-semibold" :class="missingForFinal().includes('الإقرار والتعهد') ? 'text-red-600' : 'text-slate-900'">
                                                <i class="fas fa-times-circle" x-show="missingForFinal().includes('الإقرار والتعهد')"></i>
                                                <i class="fas fa-check-circle" x-show="!missingForFinal().includes('الإقرار والتعهد')"></i>
                                                <span x-text="missingForFinal().includes('الإقرار والتعهد') ? 'ناقص' : 'مكتمل'"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between bg-white border border-slate-200 rounded-lg px-2 py-1 sm:col-span-2">
                                            <span class="text-slate-700">المرفقات (الحد الأدنى)</span>
                                            <span class="inline-flex items-center gap-1 font-semibold" :class="missingForFinal().includes('المرفقات') ? 'text-red-600' : 'text-slate-900'">
                                                <i class="fas fa-times-circle" x-show="missingForFinal().includes('المرفقات')"></i>
                                                <i class="fas fa-check-circle" x-show="!missingForFinal().includes('المرفقات')"></i>
                                                <span>
                                                    <span x-text="attachmentsCount()"></span>
                                                    /
                                                    <span x-text="req.required_attachments_min"></span>
                                                </span>
                                            </span>
                                        </div>

                                        <template x-for="t in (Array.isArray(req.required_attachments) ? req.required_attachments.filter(x => x && x.required && x.key) : [])" :key="t.key">
                                            <div class="flex items-center justify-between bg-white border border-slate-200 rounded-lg px-2 py-1 sm:col-span-2">
                                                <span class="text-slate-700" x-text="t.label || t.key"></span>
                                                <span class="inline-flex items-center gap-1 font-semibold" :class="attachmentTypeCount(String(t.key)) > 0 ? 'text-slate-900' : 'text-red-600'">
                                                    <i class="fas fa-check-circle" x-show="attachmentTypeCount(String(t.key)) > 0"></i>
                                                    <i class="fas fa-times-circle" x-show="!(attachmentTypeCount(String(t.key)) > 0)"></i>
                                                    <span x-text="attachmentTypeCount(String(t.key)) > 0 ? 'مكتمل' : 'ناقص'"></span>
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="step===1" class="space-y-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-700 mb-1">قيمة العرض الإجمالية (بالريال)</label>
                                        <input type="number" step="0.01" name="price_total" value="{{ old('price_total', $myBid?->price_total) }}" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-700 mb-1">مدة التنفيذ (بالأيام)</label>
                                            <input type="number" name="duration_days" value="{{ old('duration_days', $myBid?->duration_days) }}" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-700 mb-1">الضمان (بالأشهر)</label>
                                            <input type="number" name="warranty_months" value="{{ old('warranty_months', $myBid?->warranty_months) }}" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-700 mb-1">رسالة العرض / ملخص</label>
                                        <textarea name="message" rows="3" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" placeholder="ملخص سريع..." {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>{{ old('message', $myBid?->data['notes'] ?? null) }}</textarea>
                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                        <div class="text-[11px] font-semibold text-slate-800 mb-2">المرفقات</div>
                                        <input type="hidden" x-ref="existingAttachmentsCount" value="{{ is_array($myBid?->data['attachments'] ?? null) ? count($myBid->data['attachments']) : 0 }}">
                                        @php
                                            $reqAttachments = (array) data_get($executionRequest->data ?? [], 'bid_requirements.required_attachments', []);
                                            $reqAttachments = array_values(array_filter($reqAttachments, fn ($a) => is_array($a) && !empty($a['key'])));
                                            $existingByType = [];
                                            foreach ((array) ($myBid?->data['attachments'] ?? []) as $a) {
                                                if (!is_array($a)) {
                                                    continue;
                                                }
                                                $t = (string) ($a['type'] ?? 'general');
                                                $existingByType[$t] = ($existingByType[$t] ?? 0) + 1;
                                            }
                                        @endphp

                                        @if(!empty($reqAttachments))
                                            <div class="space-y-2">
                                                @foreach(array_slice($reqAttachments, 0, 6) as $ra)
                                                    @php
                                                        $key = (string) ($ra['key'] ?? '');
                                                        $label = (string) ($ra['label'] ?? $key);
                                                        $isRequired = !empty($ra['required']);
                                                        $count = (int) ($existingByType[$key] ?? 0);
                                                    @endphp
                                                    <div class="bg-white border border-slate-200 rounded-lg p-2">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <div class="text-[11px] font-semibold text-slate-800">
                                                                {{ $label }}
                                                                @if($isRequired)
                                                                    <span class="text-red-600">*</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-[11px] text-slate-500">مرفقات حالية: {{ $count }}</div>
                                                        </div>
                                                        <input type="hidden" data-existing-type="{{ $key }}" value="{{ $count }}">
                                                        <input type="file" data-attach-key="{{ $key }}" name="attachments_files[{{ $key }}][]" multiple class="block w-full text-[11px] mt-1" accept="application/pdf,image/png,image/jpeg" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }} @change="recalc()">
                                                        <div class="text-[11px] text-slate-500 mt-1">PDF أو صور. يمكنك رفع أكثر من ملف.</div>

                                                        @if(!empty($facilityProfileDocs))
                                                            <div class="mt-2">
                                                                <div class="text-[11px] font-semibold text-slate-800 mb-1">إرفاق من مستندات المنشأة</div>
                                                                <div class="space-y-1">
                                                                    @foreach($facilityProfileDocs as $doc)
                                                                        <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                                                            <input type="checkbox" name="profile_attachments[{{ $key }}][]" value="{{ $doc['path'] }}" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                                                            <span>{{ $doc['label'] }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                                <div class="text-[11px] text-slate-500 mt-1">سيتم إرفاق المستندات مباشرة دون إعادة رفع.</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <input type="file" x-ref="newAttachments" name="attachments_files[]" multiple class="block w-full text-[11px]" accept="application/pdf,image/png,image/jpeg" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }} @change="recalc()">
                                        @endif
                                        <p class="mt-1 text-[11px] text-slate-500">مسموح PDF أو صور. الحد الأعلى 10 ملفات، 8MB لكل ملف.</p>

                                        @php
                                            $attachmentsList = (array) ($myBid?->data['attachments'] ?? []);
                                            $attachmentsGrouped = [];
                                            foreach ($attachmentsList as $a) {
                                                if (!is_array($a)) {
                                                    continue;
                                                }
                                                $t = (string) ($a['type'] ?? 'general');
                                                $attachmentsGrouped[$t] = $attachmentsGrouped[$t] ?? [];
                                                $attachmentsGrouped[$t][] = $a;
                                            }
                                            $typeLabels = [];
                                            foreach ((array) data_get($executionRequest->data ?? [], 'bid_requirements.required_attachments', []) as $ra) {
                                                if (!is_array($ra) || empty($ra['key'])) {
                                                    continue;
                                                }
                                                $typeLabels[(string) $ra['key']] = (string) ($ra['label'] ?? $ra['key']);
                                            }
                                        @endphp

                                        @if(!empty($attachmentsGrouped))
                                            <div class="mt-2 space-y-3">
                                                @foreach($attachmentsGrouped as $typeKey => $rows)
                                                    <div class="bg-white border border-slate-200 rounded-xl p-2">
                                                        <div class="text-[11px] font-semibold text-slate-800 mb-2">
                                                            {{ $typeLabels[$typeKey] ?? $typeKey }}
                                                        </div>
                                                        <div class="space-y-2">
                                                            @foreach($rows as $a)
                                                                <label class="flex items-center justify-between gap-2 text-[11px] bg-white border border-slate-200 rounded-lg px-2 py-1">
                                                                    <a class="text-slate-800 hover:underline" href="{{ Storage::disk('public')->url($a['path']) }}" target="_blank">
                                                                        {{ $a['original_name'] ?? $a['path'] }}
                                                                    </a>
                                                                    <span class="inline-flex items-center gap-2">
                                                                        <span class="text-slate-400">{{ isset($a['size_bytes']) ? number_format(($a['size_bytes'] ?? 0)/1024, 1) . ' KB' : '' }}</span>
                                                                        <input type="checkbox" name="remove_attachments[]" value="{{ $a['path'] }}" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                                                    </span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <p class="text-[11px] text-slate-500">حدد الملفات التي تريد حذفها ثم احفظ كمسودة.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div x-show="step===2" class="space-y-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-700 mb-1">العرض الفني (منهجية وخطة التنفيذ)</label>
                                        <textarea name="technical_plan" rows="8" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" placeholder="المراحل، الفريق، المواد، إدارة المخاطر..." {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>{{ old('technical_plan', $myBid?->data['technical']['plan'] ?? null) }}</textarea>
                                        <p class="mt-1 text-[11px] text-slate-500">عند الإرسال النهائي: العرض الفني إلزامي.</p>
                                    </div>
                                </div>

                                <div x-show="step===3" class="space-y-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-700 mb-1">العرض المالي (تفصيل نصي اختياري)</label>
                                        <textarea name="financial_breakdown" rows="4" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" placeholder="تفصيل مختصر..." {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>{{ old('financial_breakdown', $myBid?->data['financial']['breakdown'] ?? null) }}</textarea>
                                    </div>

                                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                                        <div class="flex items-center justify-between bg-slate-50 px-3 py-2">
                                            <div class="text-[11px] font-semibold text-slate-800">بنود العرض المالي</div>
                                            <button type="button" class="text-[11px] px-2 py-1 rounded-lg bg-white border border-slate-200 hover:bg-slate-100" @click="addItem()" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>إضافة بند</button>
                                        </div>
                                        <div class="p-3 overflow-x-auto">
                                            <table class="min-w-[720px] w-full text-[11px]">
                                                <thead>
                                                    <tr class="text-slate-600">
                                                        <th class="text-right pb-2">البند</th>
                                                        <th class="text-right pb-2">الكمية</th>
                                                        <th class="text-right pb-2">سعر الوحدة</th>
                                                        <th class="text-right pb-2">الإجمالي</th>
                                                        <th class="pb-2"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(row, i) in items" :key="i">
                                                        <tr class="border-t border-slate-100">
                                                            <td class="py-2">
                                                                <input type="text" class="w-full border border-slate-200 rounded-md px-2 py-1" x-model="row.name" @input="recalc()" :disabled="{{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'true' : 'false' }}">
                                                            </td>
                                                            <td class="py-2">
                                                                <input type="number" class="w-28 border border-slate-200 rounded-md px-2 py-1" x-model="row.qty" @input="recalc()" :disabled="{{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'true' : 'false' }}">
                                                            </td>
                                                            <td class="py-2">
                                                                <input type="number" step="0.01" class="w-32 border border-slate-200 rounded-md px-2 py-1" x-model="row.unit_price" @input="recalc()" :disabled="{{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'true' : 'false' }}">
                                                            </td>
                                                            <td class="py-2 text-slate-700" x-text="row.total.toFixed(2)"></td>
                                                            <td class="py-2">
                                                                <button type="button" class="text-red-600" @click="removeItem(i)" :disabled="{{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'true' : 'false' }}">حذف</button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <tr class="border-t border-slate-200">
                                                        <td colspan="3" class="py-2 text-right font-semibold text-slate-700">الإجمالي التقديري</td>
                                                        <td class="py-2 font-semibold text-slate-900" x-text="totalSum().toFixed(2)"></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p class="mt-2 text-[11px] text-slate-500">يتم حفظ البنود داخل العرض وتظهر في PDF.</p>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="step===4" class="space-y-3">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <div class="text-[11px] font-semibold text-slate-800">المراجعة النهائية (ملخص مباشر)</div>
                                                <div class="text-[11px] text-slate-500 mt-0.5">راجع الظرف الفني والمالي والمرفقات قبل الإرسال النهائي.</div>
                                            </div>
                                            <div class="text-[11px]" :class="missingForFinal().length ? 'text-red-600' : 'text-slate-900'" x-text="missingForFinal().length ? 'غير مكتمل' : 'جاهز للإرسال'"></div>
                                        </div>

                                        <div class="mt-2" x-show="missingForFinal().length">
                                            <div class="text-[11px] font-semibold text-red-600">عناصر ناقصة قبل الإرسال:</div>
                                            <div class="text-[11px] text-red-600" x-text="missingForFinal().join('، ')"></div>
                                        </div>

                                        <div class="mt-3 grid grid-cols-1 gap-2">
                                            <div class="bg-white border border-slate-200 rounded-xl p-2">
                                                <div class="text-[11px] font-semibold text-slate-800 mb-2">بيانات العرض</div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                                                    <div class="flex items-center justify-between"><span class="text-slate-500">قيمة العرض الإجمالية</span><span class="font-semibold text-slate-900" x-text="($el.closest('form').querySelector('input[name=price_total]')?.value || '—') + ' SAR'"></span></div>
                                                    <div class="flex items-center justify-between"><span class="text-slate-500">مدة التنفيذ (يوم)</span><span class="font-semibold text-slate-900" x-text="$el.closest('form').querySelector('input[name=duration_days]')?.value || '—'"></span></div>
                                                    <div class="flex items-center justify-between"><span class="text-slate-500">الضمان (شهر)</span><span class="font-semibold text-slate-900" x-text="$el.closest('form').querySelector('input[name=warranty_months]')?.value || '—'"></span></div>
                                                    <div class="flex items-center justify-between"><span class="text-slate-500">إجمالي بنود العرض المالي</span><span class="font-semibold text-slate-900" x-text="totalSum().toFixed(2) + ' SAR'"></span></div>
                                                </div>
                                                <div class="mt-2 text-[11px]">
                                                    <div class="text-slate-500 mb-1">ملخص العرض</div>
                                                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-slate-800 whitespace-pre-line" x-text="$el.closest('form').querySelector('textarea[name=message]')?.value || '—'"></div>
                                                </div>
                                            </div>

                                            <div class="bg-white border border-slate-200 rounded-xl p-2">
                                                <div class="text-[11px] font-semibold text-slate-800 mb-2">الظرف الفني</div>
                                                <div class="text-[11px] text-slate-500 mb-1">خطة التنفيذ</div>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-slate-800 whitespace-pre-line" x-text="$el.closest('form').querySelector('textarea[name=technical_plan]')?.value || '—'"></div>
                                            </div>

                                            <div class="bg-white border border-slate-200 rounded-xl p-2">
                                                <div class="text-[11px] font-semibold text-slate-800 mb-2">الظرف المالي</div>
                                                <div class="text-[11px] text-slate-500 mb-1">تفصيل نصي</div>
                                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-slate-800 whitespace-pre-line" x-text="$el.closest('form').querySelector('textarea[name=financial_breakdown]')?.value || '—'"></div>

                                                <div class="mt-2">
                                                    <div class="text-[11px] text-slate-500 mb-1">بنود العرض المالي</div>
                                                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                                                        <table class="w-full text-[11px] bg-white">
                                                            <thead class="bg-slate-50 text-slate-600">
                                                                <tr>
                                                                    <th class="text-right px-2 py-1">البند</th>
                                                                    <th class="text-right px-2 py-1">الكمية</th>
                                                                    <th class="text-right px-2 py-1">سعر الوحدة</th>
                                                                    <th class="text-right px-2 py-1">الإجمالي</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <template x-if="!items || items.length === 0">
                                                                    <tr>
                                                                        <td colspan="4" class="px-2 py-2 text-slate-500">—</td>
                                                                    </tr>
                                                                </template>
                                                                <template x-for="(row, i) in (items || [])" :key="i">
                                                                    <tr class="border-t border-slate-100">
                                                                        <td class="px-2 py-1 text-slate-800" x-text="row.name || '—'"></td>
                                                                        <td class="px-2 py-1 text-slate-800" x-text="Number(row.qty || 0)"></td>
                                                                        <td class="px-2 py-1 text-slate-800" x-text="Number(row.unit_price || 0).toFixed(2)"></td>
                                                                        <td class="px-2 py-1 text-slate-800" x-text="Number(row.total || 0).toFixed(2)"></td>
                                                                    </tr>
                                                                </template>
                                                                <tr class="border-t border-slate-200 bg-slate-50">
                                                                    <td colspan="3" class="px-2 py-1 font-semibold text-slate-700">الإجمالي</td>
                                                                    <td class="px-2 py-1 font-semibold text-slate-900" x-text="totalSum().toFixed(2)"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-white border border-slate-200 rounded-xl p-2">
                                                <div class="text-[11px] font-semibold text-slate-800 mb-2">المرفقات</div>
                                                <div class="text-[11px] text-slate-500 mb-2">المستندات المرفوعة حسب النوع.</div>

                                                <div class="space-y-2">
                                                    <template x-if="Array.isArray(req.required_attachments) && req.required_attachments.filter(x => x && x.key).length">
                                                        <div class="space-y-2">
                                                            <template x-for="t in req.required_attachments.filter(x => x && x.key)" :key="t.key">
                                                                <div class="flex items-center justify-between border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                                                    <div class="text-slate-700">
                                                                        <span x-text="t.label || t.key"></span>
                                                                        <span class="text-red-600" x-show="t.required">*</span>
                                                                    </div>
                                                                    <div class="font-semibold" :class="attachmentTypeCount(String(t.key)) > 0 ? 'text-slate-900' : (t.required ? 'text-red-600' : 'text-slate-500')" x-text="attachmentTypeCount(String(t.key))"></div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                    <template x-if="!(Array.isArray(req.required_attachments) && req.required_attachments.filter(x => x && x.key).length)">
                                                        <div class="flex items-center justify-between border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                                            <span class="text-slate-600">إجمالي المرفقات</span>
                                                            <span class="font-semibold text-slate-900" x-text="attachmentsCount()"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2 text-[11px] text-slate-600">يمكنك أيضًا معاينة PDF من الأزرار أدناه.</div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <a href="{{ route('public.execution.bids.pdf.preview', $executionRequest) }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-800 text-xs font-semibold hover:bg-slate-50">
                                            <i class="fas fa-file-pdf ml-1 text-[10px]"></i>
                                            معاينة PDF
                                        </a>
                                        <a href="{{ route('public.execution.bids.pdf.download', $executionRequest) }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-800 text-xs font-semibold hover:bg-slate-50">
                                            <i class="fas fa-download ml-1 text-[10px]"></i>
                                            تنزيل PDF
                                        </a>
                                    </div>

                                    <label class="flex items-start gap-2 text-[11px] text-slate-700">
                                        <input type="checkbox" name="declaration" value="1" class="mt-0.5" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                        <span>أقر بصحة البيانات المقدمة وأتعهد بالالتزام بشروط الطلب عند الترسية.</span>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
                                    <button type="button" class="px-3 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50" @click="prev()" :disabled="step===1">السابق</button>

                                    <div class="flex items-center gap-2">
                                        <button type="submit" name="action" value="save_draft" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold hover:bg-slate-200" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                                            <i class="fas fa-floppy-disk ml-1 text-[10px]"></i>
                                            حفظ كمسودة
                                        </button>
                                        <button type="button" class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-800 text-xs font-semibold hover:bg-slate-50" @click="next()" :disabled="step===maxStep">التالي</button>
                                        <button type="submit" name="action" value="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }} :disabled="missingForFinal().length > 0">
                                            <i class="fas fa-paper-plane ml-1 text-[10px]"></i>
                                            إرسال نهائي
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p class="text-xs text-slate-600 mb-3">لتقديم عرض على هذا الطلب، تحتاج لتسجيل الدخول بحسابك.</p>
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
                                <i class="fas fa-sign-in-alt ml-1 text-[10px]"></i>
                                تسجيل الدخول وتقديم عرض
                            </a>
                        @endauth
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3 text-[11px] text-slate-800">
                        <p class="font-semibold mb-1 flex items-center gap-1">
                            <i class="fas fa-circle-info"></i>
                            تذكير مهم
                        </p>
                        <p>هذه الصفحة للعرض العام والتقديم، إدارة الطلبات والعروض بشكل كامل (قبول، رفض، تتبع تنفيذ) تتم داخل لوحة المنشأة الخاصة بصاحب الطلب.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
