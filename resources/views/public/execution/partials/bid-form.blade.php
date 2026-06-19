@php
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

    <form
        method="POST"
        action="{{ route('public.execution.bids.store', $executionRequest) }}"
        class="space-y-3 text-xs"
        enctype="multipart/form-data"
        data-autosave-url="{{ route('public.execution.bids.store', $executionRequest) }}"
        x-data="{
            step: 1,
            maxStep: 4,
            saving: false,
            autosaveUrl: null,
            items: [],
            req: {
                required_attachments_min: 1,
                required_fields: ['price_total','message','technical_plan','declaration'],
                required_attachments: []
            },
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
                const removed = (this.$el.querySelectorAll('input[name=\'remove_attachments[]\']:checked') || []).length;
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
                    const v = this.$el.querySelector('input[name=\'price_total\']')?.value;
                    if (v === null || v === '' || Number(v) < 0) missing.push('قيمة العرض');
                }
                if (requiredFields.includes('message')) {
                    const v = this.$el.querySelector('textarea[name=\'message\']')?.value;
                    if (!v || String(v).trim() === '') missing.push('ملخص العرض');
                }
                if (requiredFields.includes('technical_plan')) {
                    const notes = this.$el.querySelector('textarea[name=\'technical_plan\']')?.value;
                    const pdfCount = Number(this.$el.querySelector('input[type=hidden][data-existing-type=\'technical_plan\']')?.value || 0)
                        + Number(this.$el.querySelector('input[type=file][name=\'technical_plan_file\']')?.files?.length || 0);
                    if ((!notes || String(notes).trim() === '') && pdfCount <= 0) missing.push('العرض الفني');
                }
                if (requiredFields.includes('declaration')) {
                    const checked = !!this.$el.querySelector('input[name=\'declaration\']')?.checked;
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
        x-init="
            autosaveUrl = $el.dataset.autosaveUrl || null;
            try {
                const txt = document.getElementById('bid-items-json')?.textContent || '[]';
                const parsed = JSON.parse(txt || '[]');
                items = Array.isArray(parsed) ? parsed : [];
            } catch (e) {
                items = [];
            }
            try {
                const txt = document.getElementById('bid-req-json')?.textContent || '{}';
                const r = JSON.parse(txt || '{}') || {};
                const minAtt = Number(r?.required_attachments_min ?? 1);
                const requiredFields = Array.isArray(r?.required_fields) ? r.required_fields : ['price_total','message','technical_plan','declaration'];
                const requiredAttachments = Array.isArray(r?.required_attachments) ? r.required_attachments : [];
                req = {
                    required_attachments_min: Math.max(0, Math.min(10, isFinite(minAtt) ? minAtt : 1)),
                    required_fields: requiredFields,
                    required_attachments: requiredAttachments
                };
            } catch (e) {
                req = {
                    required_attachments_min: 1,
                    required_fields: ['price_total','message','technical_plan','declaration'],
                    required_attachments: []
                };
            }
            recalc();
        "
    >
        @csrf

        <script type="application/json" id="bid-items-json">@json(old('financial_items_json', json_encode($myBid?->data['financial']['items'] ?? [])))</script>
        <script type="application/json" id="bid-req-json">@json(data_get($executionRequest->data ?? [], 'bid_requirements', []))</script>

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
                <label class="block text-[11px] font-medium text-slate-700 mb-1">العرض الفني (PDF)</label>
                @php
                    $existingTechPdf = 0;
                    foreach ((array) ($myBid?->data['attachments'] ?? []) as $a) {
                        if (is_array($a) && (($a['type'] ?? 'general') === 'technical_plan')) {
                            $existingTechPdf++;
                        }
                    }
                @endphp
                <input type="hidden" data-existing-type="technical_plan" value="{{ $existingTechPdf }}">
                <input type="file" name="technical_plan_file" accept="application/pdf" class="block w-full text-[11px]" {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>
                <p class="mt-1 text-[11px] text-slate-500">المطلوب رفع ملف PDF للعـرض الفني (ظرف فني). الحد الأعلى 8MB.</p>

                <div class="mt-3">
                    <label class="block text-[11px] font-medium text-slate-700 mb-1">ملاحظات فنية (اختياري)</label>
                    <textarea name="technical_plan" rows="6" class="w-full border border-slate-200 rounded-md px-3 py-1.5 focus:ring-1 focus:ring-slate-400 focus:border-slate-400" placeholder="أي ملاحظات إضافية..." {{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'disabled' : '' }}>{{ old('technical_plan', $myBid?->data['technical']['plan'] ?? null) }}</textarea>
                </div>
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
                    <table class="min-w-[900px] w-full text-[11px]">
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
                                        <input type="number" class="w-40 border border-slate-200 rounded-md px-2 py-1" x-model="row.qty" @input="recalc()" :disabled="{{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'true' : 'false' }}">
                                    </td>
                                    <td class="py-2">
                                        <input type="number" step="0.01" class="w-48 border border-slate-200 rounded-md px-2 py-1" x-model="row.unit_price" @input="recalc()" :disabled="{{ ($isPastDue || $isLockedBid || $isFinalSubmitted) ? 'true' : 'false' }}">
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
