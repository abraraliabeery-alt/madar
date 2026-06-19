@extends('layouts.dashboard-bs')

@section('title', 'إنشاء مشروع')

@section('content')
<x-bs.card title="إنشاء مشروع">
    <x-slot name="actions">
        <a href="{{ route('client.dashboard') }}" class="btn btn-light btn-sm">رجوع</a>
    </x-slot>
    <form method="POST" action="{{ route('client.projects.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">البيانات الأساسية</div>
            </div>
            <div class="card-body">
                <div class="text-muted small mb-3">
                    <div>المصدر: جدول ترجمات المشاريع ⟶ مرتبط بجدول المشاريع</div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">صورة المشروع</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">الحفظ في جدول المشاريع (حقل الصورة)</div>
                    <div id="image-preview-wrap" class="mt-2" style="display:none;">
                        <img id="image-preview" src="" alt="معاينة صورة المشروع" class="border" style="width: 120px; height: 120px; border-radius: 50%; object-fit: contain; background: #fff;">
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">المساعد الصوتي</div>
                        <div class="text-muted small mb-2">تحدث بحرية عن مشروعك، ثم اضغط تحليل وتعبئة لتعبئة الحقول تلقائياً.</div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" id="voice-start" class="btn btn-danger btn-sm">🎙️ ابدأ التحدث</button>
                            <button type="button" id="voice-stop" class="btn btn-outline-secondary btn-sm" disabled>■ إيقاف</button>
                            <span id="voice-status" class="text-muted small"></span>
                        </div>

                        <div class="mt-3">
                            <label for="voice-transcript" class="form-label">النص المحوّل من الصوت</label>
                            <textarea id="voice-transcript" rows="3" class="form-control" placeholder="مثال: أبغى بناء فيلا سكنية دورين، مساحة الأرض 400، العظم والسباكة والكهرباء... ميزانيتي بين 600 إلى 800 ألف خلال 5 شهور"></textarea>
                        </div>

                        <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" id="voice-analyze" class="btn btn-dark btn-sm">تحليل وتعبئة</button>
                            <button type="button" id="voice-clear" class="btn btn-outline-secondary btn-sm">مسح</button>
                            <button type="button" id="voice-undo" class="btn btn-warning btn-sm" disabled>تراجع</button>
                            <span id="voice-analyze-status" class="text-muted small"></span>
                        </div>
                    </div>
                </div>

                @include('components.translations-repeater', [
                    'locales' => $locales,
                    'namePrefix' => 'translations',
                    'fields' => [
                        [
                            'type' => 'input',
                            'key' => 'name',
                            'label' => 'اسم المشروع',
                            'requiredFirst' => true,
                        ],
                        [
                            'type' => 'textarea',
                            'key' => 'description',
                            'label' => 'وصف المشروع',
                            'rows' => 4,
                        ],
                    ],
                    'addLabel' => 'إضافة ترجمة',
                    'removeLabel' => 'حذف',
                    'minItems' => 1,
                    'maxItems' => is_array($locales) ? count($locales) : null,
                ])
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">الموقع</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="city_id"
                            label="المدينة"
                            :options="$cities"
                            option-label="localized_name"
                            placeholder="اختر المدينة"
                        />
                        <div class="form-text">المصدر: جدول المدن ⟶ الحفظ في جدول المشاريع (حقل المدينة)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="neighborhood_id"
                            label="الحي"
                            :options="$neighborhoods"
                            placeholder="اختر الحي"
                        />
                        <div class="form-text">المصدر: جدول الأحياء ⟶ الحفظ في جدول المشاريع (حقل الحي)</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="street_id"
                            label="الشارع"
                            :options="$streets"
                            placeholder="اختر الشارع"
                        />
                        <div class="form-text">المصدر: جدول الشوارع ⟶ الحفظ في جدول المشاريع (حقل الشارع)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input name="address" label="العنوان" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل العنوان)</div>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="fw-semibold mb-2">الموقع على الخريطة</div>
                    <div class="text-muted small mb-3">ابحث عن الموقع أو حرّك المؤشر على الخريطة لتحديث الإحداثيات تلقائياً.</div>

                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-12 col-lg">
                            <input id="project-map-search" type="text" class="form-control" placeholder="ابحث عن المدينة / الحي / اسم الشارع" autocomplete="off" />
                        </div>
                        <div class="col-12 col-lg-auto d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="project-map-search-btn">بحث</button>
                            <button type="button" class="btn btn-outline-secondary" id="project-map-locate-btn">استخدم موقعي</button>
                            <a href="#" class="btn btn-outline-secondary" id="project-map-open-gmaps" target="_blank" rel="noopener">فتح في خرائط Google</a>
                        </div>
                    </div>

                    <div id="project-map" class="border rounded" style="height: 340px;"></div>

                    <div class="row g-3 mt-3">
                        <div class="col-12 col-md-6">
                            <label for="latitude" class="form-label">خط العرض</label>
                            <input
                                type="number"
                                step="any"
                                class="form-control @error('latitude') is-invalid @enderror"
                                id="latitude"
                                name="latitude"
                                value="{{ old('latitude') }}"
                                readonly
                            >
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">الحفظ في جدول المشاريع (حقل خط العرض)</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="longitude" class="form-label">خط الطول</label>
                            <input
                                type="number"
                                step="any"
                                class="form-control @error('longitude') is-invalid @enderror"
                                id="longitude"
                                name="longitude"
                                value="{{ old('longitude') }}"
                                readonly
                            >
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">الحفظ في جدول المشاريع (حقل خط الطول)</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="google_maps_url" class="form-label">رابط خرائط جوجل</label>
                        <input
                            type="url"
                            class="form-control @error('google_maps_url') is-invalid @enderror"
                            id="google_maps_url"
                            name="google_maps_url"
                            value="{{ old('google_maps_url') }}"
                            readonly
                        >
                        @error('google_maps_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">الحفظ في جدول المشاريع (حقل رابط خرائط جوجل)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">تفاصيل الطلب</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="project_type"
                            label="نوع المشروع"
                            :options="[
                                ['id' => 'residential', 'name' => 'سكني'],
                                ['id' => 'commercial', 'name' => 'تجاري'],
                                ['id' => 'industrial', 'name' => 'صناعي'],
                                ['id' => 'government', 'name' => 'حكومي/مؤسسي'],
                                ['id' => 'other', 'name' => 'أخرى'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="اختر"
                        />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل نوع المشروع)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="request_type"
                            label="نوع الطلب"
                            :options="[
                                ['id' => 'build', 'name' => 'بناء جديد'],
                                ['id' => 'renovation', 'name' => 'ترميم'],
                                ['id' => 'finishing', 'name' => 'تشطيب'],
                                ['id' => 'extension', 'name' => 'إضافة/ملحق'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="اختر"
                        />
                        <div class="form-text">قائمة ثابتة ⟶ الحفظ في جدول المشاريع (حقل نوع الطلب)</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="scope_of_work"
                            label="نطاق العمل"
                            :options="[
                                ['id' => 'full', 'name' => 'كامل'],
                                ['id' => 'structure', 'name' => 'عظم'],
                                ['id' => 'finishing', 'name' => 'تشطيب'],
                                ['id' => 'mep', 'name' => 'كهرباء/سباكة'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="اختر"
                        />
                        <div class="form-text">قائمة ثابتة ⟶ الحفظ في جدول المشاريع (حقل نطاق العمل)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="finishing_level"
                            label="مستوى التشطيب"
                            :options="[
                                ['id' => 'economic', 'name' => 'اقتصادي'],
                                ['id' => 'standard', 'name' => 'متوسط'],
                                ['id' => 'luxury', 'name' => 'فاخر'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="اختر"
                        />
                        <div class="form-text">قائمة ثابتة ⟶ الحفظ في جدول المشاريع (حقل مستوى التشطيب)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">مواصفات المشروع</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="land_area" label="مساحة الأرض (م²)" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل مساحة الأرض)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="built_area" label="المساحة المبنية (م²)" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل المساحة المبنية)</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <x-bs.input type="number" name="floors_count" label="عدد الأدوار" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل عدد الأدوار)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="number" name="rooms_count" label="عدد الغرف" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل عدد الغرف)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="number" name="bathrooms_count" label="عدد دورات المياه" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل عدد دورات المياه)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">الميزانية والجدول الزمني</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            id="project_status"
                            name="status"
                            label="حالة النشر"
                            :options="[
                                ['id' => 'draft', 'name' => 'مسودة'],
                                ['id' => 'open_for_bids', 'name' => 'مفتوح للعروض'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="اختر"
                        />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل حالة النشر). وعند فتحه للعروض يتم إنشاء سجل في جدول طلبات التنفيذ.</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="budget_min" label="الميزانية الدنيا" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل الميزانية الدنيا) + نسخة للسوق في جدول طلبات التنفيذ (حقل الميزانية الدنيا)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="budget_max" label="الميزانية القصوى" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل الميزانية القصوى) + نسخة للسوق في جدول طلبات التنفيذ (حقل الميزانية القصوى)</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <x-bs.input type="date" name="start_date" label="تاريخ البدء المتوقع" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل تاريخ البدء المتوقع)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" name="duration_days" label="مدة التنفيذ (بالأيام)" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل مدة التنفيذ)</div>
                    </div>
                </div>

                <div id="tender-deadlines" class="row g-3 mt-1">
                    <div class="col-12 col-md-4">
                        <x-bs.input type="date" name="bid_deadline" label="آخر موعد لاستلام العروض" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل آخر موعد للعروض) + للسوق في جدول طلبات التنفيذ (حقل موعد الإغلاق)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="date" name="qa_deadline" label="آخر موعد للاستفسارات" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل آخر موعد للاستفسارات)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="date" name="site_visit_date" label="موعد المعاينة (اختياري)" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل موعد المعاينة)</div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($projectAttributes) && $projectAttributes->count())
            <div class="card mb-4">
                <div class="card-header">
                    <div class="fw-semibold">خصائص المشروع (عام)</div>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-3">المصدر: جدول الخصائص + جدول ترجمات الخصائص ⟶ الحفظ في جدول ربط مرتبط بجدول المشاريع</div>
                    <div class="vstack gap-3">
                        @foreach($projectAttributes as $attribute)
                            @php
                                $attrName = optional($attribute->translations->firstWhere('locale', app()->getLocale()))->name
                                    ?? optional($attribute->translations->first())->name
                                    ?? ('Attribute #'.$attribute->id);
                                $fieldName = 'attributes['.$attribute->id.'][value]';
                                $oldValue = old('attributes.'.$attribute->id.'.value');
                            @endphp
                            <div>
                                <label class="form-label" for="project-attr-{{ $attribute->id }}">
                                    {{ $attrName }}@if($attribute->required) <span class="text-danger">*</span>@endif
                                </label>
                                <input id="project-attr-{{ $attribute->id }}" type="text" name="{{ $fieldName }}" value="{{ $oldValue }}" class="form-control" />
                                @error('attributes.'.$attribute->id.'.value')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(isset($ideaStageAttributes) && $ideaStageAttributes->count())
            <div class="card mb-4">
                <div class="card-header">
                    <div class="fw-semibold">خصائص مرحلة الفكرة</div>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-3">المصدر: جدول خصائص المراحل + جدول ترجمات خصائص المراحل ⟶ الحفظ في جدول ربط مرتبط بجدول مراحل المشروع</div>
                    <div class="vstack gap-3">
                        @foreach($ideaStageAttributes as $attribute)
                            @php
                                $attrName = optional($attribute->translations->firstWhere('locale', app()->getLocale()))->name
                                    ?? optional($attribute->translations->first())->name
                                    ?? ('Attribute #'.$attribute->id);
                                $fieldName = 'stage_attributes['.$attribute->id.']';
                                $oldValue = old('stage_attributes.'.$attribute->id);
                            @endphp
                            <div>
                                <label class="form-label" for="stage-attr-{{ $attribute->id }}">
                                    {{ $attrName }}@if($attribute->required) <span class="text-danger">*</span>@endif
                                </label>
                                @if($attribute->type === 'text')
                                    <textarea id="stage-attr-{{ $attribute->id }}" name="{{ $fieldName }}" rows="3" class="form-control">{{ $oldValue }}</textarea>
                                @else
                                    <input id="stage-attr-{{ $attribute->id }}" type="text" name="{{ $fieldName }}" value="{{ $oldValue }}" class="form-control" />
                                @endif
                                @error('stage_attributes.'.$attribute->id)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">متطلبات ومرفقات</div>
            </div>
            <div class="card-body">
                <div id="requirements-section" class="mb-3">
                    <div class="alert alert-light border mb-3">
                        <div class="fw-semibold mb-1">مساعدة لكتابة متطلبات واضحة</div>
                        <div class="text-muted small">عبّئ الحقول التالية وسيتم تكوين نص مرتب تلقائيًا، ويمكنك تعديله قبل الحفظ.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="req_scope" class="form-label">ما الذي تريد تنفيذه؟</label>
                            <input type="text" id="req_scope" class="form-control" placeholder="مثال: بناء دورين وملحق + تشطيب كامل" />
                            <div class="form-text">يساعد المنفذ على فهم نطاق العمل بسرعة.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="req_materials" class="form-label">المواد/الماركات المطلوبة (إن وجدت)</label>
                            <input type="text" id="req_materials" class="form-control" placeholder="مثال: عزل مائي نوع كذا، دهان جوتن، سيراميك مقاس..." />
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="req_schedule" class="form-label">الجدول المتوقع</label>
                            <input type="text" id="req_schedule" class="form-control" placeholder="مثال: بدء خلال شهر، مدة التنفيذ 120 يوم" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">معلومات تساعد على تسعير أدق</label>
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_has_plans">
                                        <label class="form-check-label" for="req_has_plans">يوجد مخططات/رسومات جاهزة</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_needs_site_visit">
                                        <label class="form-check-label" for="req_needs_site_visit">أفضل معاينة قبل التسعير</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_include_materials">
                                        <label class="form-check-label" for="req_include_materials">العرض يشمل المواد والتوريد</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_include_labor">
                                        <label class="form-check-label" for="req_include_labor">العرض يشمل العمالة والتنفيذ</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="req_notes" class="form-label">شروط/ملاحظات خاصة</label>
                            <textarea id="req_notes" class="form-control" rows="3" placeholder="مثال: الالتزام بالسلامة، تنظيف الموقع، ضمان... "></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-bs.textarea name="requirements" label="نص المتطلبات (سيُحفظ)" rows="6" />
                        <div class="form-text">الحفظ في جدول المشاريع (حقل المتطلبات)</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="attachments_files" class="form-label">مرفقات (رفع ملفات)</label>
                    <input type="file" class="form-control @error('attachments_files') is-invalid @enderror" id="attachments_files" name="attachments_files[]" multiple>
                    @error('attachments_files')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('attachments_files.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="form-text">يتم حفظ الملفات في جدول مرفقات المشاريع وربطها بالمشروع</div>
                    <div id="attachments-files-list" class="mt-2"></div>
                </div>

                <div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#legacy-attachments" aria-expanded="false" aria-controls="legacy-attachments">
                        عندي روابط بدل رفع ملفات
                    </button>

                    <div id="legacy-attachments" class="collapse mt-3">
                        <label class="form-label">مرفقات (روابط/أسماء ملفات)</label>
                        <div class="vstack gap-2">
                            @for($i = 0; $i < 3; $i++)
                                <input type="text" name="attachments[]" value="{{ old('attachments.'.$i) }}" class="form-control" placeholder="مثال: رابط Google Drive أو اسم ملف مثل مخطط.pdf">
                            @endfor
                        </div>
                        <div class="form-text">الحفظ في جدول المشاريع (حقل المرفقات) بصيغة قائمة</div>
                        @error('attachments')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('attachments.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</x-bs.card>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    (function () {
        const statusEl = document.getElementById('project_status');
        const tenderEl = document.getElementById('tender-deadlines');
        const requirementsSectionEl = document.getElementById('requirements-section');

        const imageInput = document.getElementById('image');
        const imagePreviewWrap = document.getElementById('image-preview-wrap');
        const imagePreview = document.getElementById('image-preview');

        const attachmentsInput = document.getElementById('attachments_files');
        const attachmentsList = document.getElementById('attachments-files-list');

        let attachmentsDraft = [];

        const mapEl = document.getElementById('project-map');
        const mapSearchEl = document.getElementById('project-map-search');
        const mapSearchBtn = document.getElementById('project-map-search-btn');
        const mapLocateBtn = document.getElementById('project-map-locate-btn');
        const mapOpenGmaps = document.getElementById('project-map-open-gmaps');

        const latEl = document.getElementById('latitude');
        const lngEl = document.getElementById('longitude');
        const gmapsEl = document.getElementById('google_maps_url');

        const reqScope = document.getElementById('req_scope');
        const reqMaterials = document.getElementById('req_materials');
        const reqSchedule = document.getElementById('req_schedule');
        const reqHasPlans = document.getElementById('req_has_plans');
        const reqNeedsSiteVisit = document.getElementById('req_needs_site_visit');
        const reqIncludeMaterials = document.getElementById('req_include_materials');
        const reqIncludeLabor = document.getElementById('req_include_labor');
        const reqNotes = document.getElementById('req_notes');

        const requirementsTextarea = document.querySelector('textarea[name="requirements"]');

        const voiceStartBtn = document.getElementById('voice-start');
        const voiceStopBtn = document.getElementById('voice-stop');
        const voiceStatusEl = document.getElementById('voice-status');
        const voiceTranscriptEl = document.getElementById('voice-transcript');
        const voiceAnalyzeBtn = document.getElementById('voice-analyze');
        const voiceClearBtn = document.getElementById('voice-clear');
        const voiceUndoBtn = document.getElementById('voice-undo');
        const voiceAnalyzeStatusEl = document.getElementById('voice-analyze-status');

        const projectTypeEl = document.querySelector('select[name="project_type"]');
        const requestTypeEl = document.querySelector('select[name="request_type"]');
        const scopeOfWorkEl = document.querySelector('select[name="scope_of_work"]');
        const finishingLevelEl = document.querySelector('select[name="finishing_level"]');

        const budgetMinEl = document.querySelector('input[name="budget_min"]');
        const budgetMaxEl = document.querySelector('input[name="budget_max"]');
        const durationDaysEl = document.querySelector('input[name="duration_days"]');
        const landAreaEl = document.querySelector('input[name="land_area"]');
        const builtAreaEl = document.querySelector('input[name="built_area"]');
        const floorsCountEl = document.querySelector('input[name="floors_count"]');
        const roomsCountEl = document.querySelector('input[name="rooms_count"]');
        const bathroomsCountEl = document.querySelector('input[name="bathrooms_count"]');

        function syncTenderVisibility() {
            if (!statusEl || !tenderEl) {
                return;
            }

            const value = statusEl.value;
            const isOpen = value === 'open_for_bids';
            tenderEl.style.display = isOpen ? '' : 'none';

            if (requirementsSectionEl) {
                requirementsSectionEl.style.display = isOpen ? '' : 'none';
            }
        }

        function buildRequirementsText() {
            if (!requirementsTextarea) {
                return;
            }

            const lines = [];

            const scopeVal = (reqScope && reqScope.value ? reqScope.value.trim() : '');
            const materialsVal = (reqMaterials && reqMaterials.value ? reqMaterials.value.trim() : '');
            const scheduleVal = (reqSchedule && reqSchedule.value ? reqSchedule.value.trim() : '');
            const notesVal = (reqNotes && reqNotes.value ? reqNotes.value.trim() : '');

            lines.push('ملخص الطلب:');
            lines.push(scopeVal ? ('- المطلوب: ' + scopeVal) : '- المطلوب:');

            if (scheduleVal) {
                lines.push('- الجدول المتوقع: ' + scheduleVal);
            }

            if (materialsVal) {
                lines.push('- المواد/الماركات المطلوبة: ' + materialsVal);
            }

            const info = [];
            if (reqHasPlans && reqHasPlans.checked) info.push('يوجد مخططات/رسومات جاهزة');
            if (reqNeedsSiteVisit && reqNeedsSiteVisit.checked) info.push('يفضل معاينة قبل التسعير');
            if (reqIncludeMaterials && reqIncludeMaterials.checked) info.push('العرض يشمل المواد والتوريد');
            if (reqIncludeLabor && reqIncludeLabor.checked) info.push('العرض يشمل العمالة والتنفيذ');

            if (info.length) {
                lines.push('معلومات إضافية:');
                info.forEach((item) => lines.push('- ' + item));
            }

            if (notesVal) {
                lines.push('ملاحظات وشروط:');
                lines.push(notesVal);
            }

            requirementsTextarea.value = lines.join('\n');
        }

        function bindRequirements() {
            const els = [reqScope, reqMaterials, reqSchedule, reqHasPlans, reqNeedsSiteVisit, reqIncludeMaterials, reqIncludeLabor, reqNotes];
            els.forEach((el) => {
                if (!el) return;
                el.addEventListener('input', buildRequirementsText);
                el.addEventListener('change', buildRequirementsText);
            });
            buildRequirementsText();
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function renderAttachmentsList() {
            if (!attachmentsList) {
                return;
            }

            if (!attachmentsDraft.length) {
                attachmentsList.innerHTML = '';
                return;
            }

            const items = attachmentsDraft.map((f, idx) => {
                const name = f.name || 'ملف';
                const size = typeof f.size === 'number' ? formatBytes(f.size) : '';
                return `
                    <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2">
                        <div class="text-truncate">
                            <div class="fw-semibold text-truncate">${name}</div>
                            <div class="text-muted small">${size}</div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-remove-attachment="${idx}">إزالة</button>
                    </div>
                `;
            }).join('');

            attachmentsList.innerHTML = items;
        }

        function syncAttachmentsInput() {
            if (!attachmentsInput) {
                return;
            }

            const dt = new DataTransfer();
            attachmentsDraft.forEach((f) => dt.items.add(f));
            attachmentsInput.files = dt.files;
        }

        function bindImagePreview() {
            if (!imageInput || !imagePreviewWrap || !imagePreview) {
                return;
            }

            imageInput.addEventListener('change', function () {
                const file = imageInput.files && imageInput.files[0] ? imageInput.files[0] : null;
                if (!file) {
                    imagePreviewWrap.style.display = 'none';
                    imagePreview.src = '';
                    return;
                }

                const url = URL.createObjectURL(file);
                imagePreview.src = url;
                imagePreviewWrap.style.display = '';
            });
        }

        function bindAttachmentsPreview() {
            if (!attachmentsInput || !attachmentsList) {
                return;
            }

            attachmentsInput.addEventListener('change', function () {
                attachmentsDraft = Array.from(attachmentsInput.files || []);
                renderAttachmentsList();
            });

            attachmentsList.addEventListener('click', function (e) {
                const btn = e.target && e.target.closest ? e.target.closest('[data-remove-attachment]') : null;
                if (!btn) {
                    return;
                }

                const idx = parseInt(btn.getAttribute('data-remove-attachment'), 10);
                if (Number.isNaN(idx)) {
                    return;
                }

                attachmentsDraft.splice(idx, 1);
                syncAttachmentsInput();
                renderAttachmentsList();
            });

            attachmentsDraft = Array.from(attachmentsInput.files || []);
            renderAttachmentsList();
        }

        function bindVoiceAssist() {
            if (!voiceStartBtn || !voiceTranscriptEl) {
                return;
            }

            const supported = ('webkitSpeechRecognition' in window) || ('SpeechRecognition' in window);
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;

            const setVoiceStatus = (msg) => {
                if (voiceStatusEl) {
                    voiceStatusEl.textContent = msg || '';
                }
            };

            const setAnalyzeStatus = (msg) => {
                if (voiceAnalyzeStatusEl) {
                    voiceAnalyzeStatusEl.textContent = msg || '';
                }
            };

            let rec = null;
            let listening = false;
            let wantStop = false;
            let lastSnapshot = null;

            const strip = (s) => (s || '').replace(/[\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06ED]/g, '');
            const norm = (s) => strip(s)
                .replace(/[إأآ]/g, 'ا')
                .replace(/ى/g, 'ي')
                .replace(/ؤ/g, 'و')
                .replace(/ئ/g, 'ي')
                .toLowerCase();

            const toLatinDigits = (s) => (s || '')
                .replace(/[٠-٩]/g, (d) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)))
                .replace(/[۰-۹]/g, (d) => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d)));

            const numWordsMap = {
                'صفر': 0,
                'واحد': 1,
                'واحدة': 1,
                'اثنين': 2,
                'اثنان': 2,
                'اثنتين': 2,
                'اثنتان': 2,
                'ثلاث': 3,
                'ثلاثه': 3,
                'ثلاثة': 3,
                'اربع': 4,
                'اربعه': 4,
                'اربعة': 4,
                'أربع': 4,
                'أربعة': 4,
                'خمس': 5,
                'خمسه': 5,
                'خمسة': 5,
                'ست': 6,
                'سته': 6,
                'ستة': 6,
                'سبع': 7,
                'سبعه': 7,
                'سبعة': 7,
                'ثمان': 8,
                'ثمانيه': 8,
                'ثمانية': 8,
                'تسع': 9,
                'تسعه': 9,
                'تسعة': 9,
                'عشر': 10,
                'عشره': 10,
                'عشرة': 10,
            };

            function parseSimpleNumberToken(token) {
                const t = norm(toLatinDigits(token)).trim();
                if (!t) return null;
                if (Object.prototype.hasOwnProperty.call(numWordsMap, t)) {
                    return numWordsMap[t];
                }
                const m = t.match(/-?\d+(?:\.\d+)?/);
                if (m) {
                    const v = parseFloat(m[0]);
                    return Number.isFinite(v) ? v : null;
                }
                return null;
            }

            function multiplierFromText(s) {
                const n = norm(s);
                if (/(مليون|ملايين)/.test(n)) return 1000000;
                if (/(الف|ألف|الاف|آلاف|الآف)/.test(n)) return 1000;
                if (/(مليار)/.test(n)) return 1000000000;
                return 1;
            }

            function parseMoneyValue(text) {
                const raw = toLatinDigits(text);
                const n = norm(raw);

                if (/نص\s*مليون/.test(n) || /نصف\s*مليون/.test(n)) {
                    return 500000;
                }

                const mult = multiplierFromText(n);
                const m = n.match(/(-?\d+(?:\.\d+)?)/);
                if (m) {
                    const base = parseFloat(m[1]);
                    if (Number.isFinite(base)) {
                        return Math.round(base * mult);
                    }
                }

                const parts = n.split(/\s+/).filter(Boolean);
                for (const p of parts) {
                    const v = parseSimpleNumberToken(p);
                    if (v !== null) {
                        return Math.round(v * mult);
                    }
                }

                return null;
            }

            function extractMoneyRange(text) {
                const s = norm(toLatinDigits(text));
                const isBudgetContext = /(ميزان|ميزانيه|ميزانية|ميزانيتي|تكلف|سعر|قيمة|بحدود|حدود|تقريبا|تقريباً)/.test(s);
                if (!isBudgetContext) {
                    return null;
                }

                const between = s.match(/(?:بين|من)\s+([^\n]+?)\s+(?:الى|إلى|و)\s+([^\n]+)/);
                if (between) {
                    const a = parseMoneyValue(between[1]);
                    const b = parseMoneyValue(between[2]);
                    if (a !== null && b !== null) {
                        return { min: Math.min(a, b), max: Math.max(a, b) };
                    }
                    if (a !== null) return { min: a, max: null };
                    if (b !== null) return { min: null, max: b };
                }

                const upTo = s.match(/(?:الى|إلى|حتى|حدود)\s+([^\n]+)/);
                if (upTo) {
                    const v = parseMoneyValue(upTo[1]);
                    if (v !== null) return { min: null, max: v };
                }

                const from = s.match(/(?:من)\s+([^\n]+)/);
                if (from) {
                    const v = parseMoneyValue(from[1]);
                    if (v !== null) return { min: v, max: null };
                }

                const vals = [];
                const moneyTokens = s.match(/(?:\d+(?:\.\d+)?)\s*(?:مليون|ملايين|الف|ألف|آلاف|الاف)?/g) || [];
                moneyTokens.forEach((t) => {
                    const v = parseMoneyValue(t);
                    if (v !== null) vals.push(v);
                });
                if (vals.length >= 2) {
                    return { min: Math.min(...vals), max: Math.max(...vals) };
                }
                if (vals.length === 1) {
                    return { min: vals[0], max: null };
                }

                return null;
            }

            function extractDurationDays(text) {
                const s = norm(toLatinDigits(text));
                const m = s.match(/(\d+|[\p{L}]+)\s*(يوم|ايام|أيام|اسبوع|أسبوع|اسابيع|أسابيع|شهر|شهور|سنه|سنة|سنوات)/u);
                if (!m) return null;
                const count = parseSimpleNumberToken(m[1]);
                if (count === null) return null;

                const unit = m[2];
                if (/اسبوع|أسبوع|اسابيع|أسابيع/.test(unit)) return Math.round(count * 7);
                if (/شهر|شهور/.test(unit)) return Math.round(count * 30);
                if (/سنه|سنة|سنوات/.test(unit)) return Math.round(count * 365);
                return Math.round(count);
            }

            function extractArea(text, which) {
                const s = norm(toLatinDigits(text));
                const patterns = which === 'land'
                    ? [/(?:مساحة\s*الارض|مساحه\s*الارض|الارض|الأرض)\s*(\d{2,7}(?:\.\d+)?)/]
                    : [/(?:مساحة\s*البناء|مساحه\s*البناء|المبني|المبنى|البناء)\s*(\d{2,7}(?:\.\d+)?)/];
                for (const re of patterns) {
                    const m = s.match(re);
                    if (m) {
                        const v = parseFloat(m[1]);
                        if (Number.isFinite(v)) return v;
                    }
                }
                return null;
            }

            function extractCount(text, type) {
                const s = norm(toLatinDigits(text));
                const re = type === 'floors'
                    ? /(\d+|[\p{L}]+)\s*(دور|ادوار|أدوار)/u
                    : type === 'rooms'
                        ? /(\d+|[\p{L}]+)\s*(غرف|غرفة)/u
                        : /(\d+|[\p{L}]+)\s*(حمام|حمامات)/u;
                const m = s.match(re);
                if (!m) return null;
                const v = parseSimpleNumberToken(m[1]);
                return v !== null ? Math.round(v) : null;
            }

            function snapshot() {
                lastSnapshot = {
                    scope: reqScope ? (reqScope.value || '') : '',
                    notes: reqNotes ? (reqNotes.value || '') : '',
                    requirements: requirementsTextarea ? (requirementsTextarea.value || '') : '',
                    desc0: document.querySelector('textarea[name="translations[0][description]"]')?.value || '',
                    name0: document.querySelector('input[name="translations[0][name]"]')?.value || '',
                    projectType: projectTypeEl ? (projectTypeEl.value || '') : '',
                    requestType: requestTypeEl ? (requestTypeEl.value || '') : '',
                    scopeOfWork: scopeOfWorkEl ? (scopeOfWorkEl.value || '') : '',
                    finishingLevel: finishingLevelEl ? (finishingLevelEl.value || '') : '',
                    budgetMin: budgetMinEl ? (budgetMinEl.value || '') : '',
                    budgetMax: budgetMaxEl ? (budgetMaxEl.value || '') : '',
                    durationDays: durationDaysEl ? (durationDaysEl.value || '') : '',
                    landArea: landAreaEl ? (landAreaEl.value || '') : '',
                    builtArea: builtAreaEl ? (builtAreaEl.value || '') : '',
                    floorsCount: floorsCountEl ? (floorsCountEl.value || '') : '',
                    roomsCount: roomsCountEl ? (roomsCountEl.value || '') : '',
                    bathroomsCount: bathroomsCountEl ? (bathroomsCountEl.value || '') : '',
                };
                if (voiceUndoBtn) {
                    voiceUndoBtn.disabled = false;
                }
            }

            function restoreSnapshot() {
                if (!lastSnapshot) {
                    return;
                }
                if (reqScope) reqScope.value = lastSnapshot.scope;
                if (reqNotes) reqNotes.value = lastSnapshot.notes;
                if (requirementsTextarea) requirementsTextarea.value = lastSnapshot.requirements;
                const desc0 = document.querySelector('textarea[name="translations[0][description]"]');
                if (desc0) desc0.value = lastSnapshot.desc0;
                const name0 = document.querySelector('input[name="translations[0][name]"]');
                if (name0) name0.value = lastSnapshot.name0;

                if (projectTypeEl) projectTypeEl.value = lastSnapshot.projectType;
                if (requestTypeEl) requestTypeEl.value = lastSnapshot.requestType;
                if (scopeOfWorkEl) scopeOfWorkEl.value = lastSnapshot.scopeOfWork;
                if (finishingLevelEl) finishingLevelEl.value = lastSnapshot.finishingLevel;

                if (budgetMinEl) budgetMinEl.value = lastSnapshot.budgetMin;
                if (budgetMaxEl) budgetMaxEl.value = lastSnapshot.budgetMax;
                if (durationDaysEl) durationDaysEl.value = lastSnapshot.durationDays;
                if (landAreaEl) landAreaEl.value = lastSnapshot.landArea;
                if (builtAreaEl) builtAreaEl.value = lastSnapshot.builtArea;
                if (floorsCountEl) floorsCountEl.value = lastSnapshot.floorsCount;
                if (roomsCountEl) roomsCountEl.value = lastSnapshot.roomsCount;
                if (bathroomsCountEl) bathroomsCountEl.value = lastSnapshot.bathroomsCount;

                if (voiceUndoBtn) voiceUndoBtn.disabled = true;
                lastSnapshot = null;

                buildRequirementsText();
            }

            function makeRec() {
                if (!SR) return null;
                const r = new SR();
                r.lang = 'ar-SA';
                r.interimResults = true;
                r.continuous = true;
                return r;
            }

            function start() {
                if (!supported) {
                    setVoiceStatus('متصفحك لا يدعم الإملاء الصوتي.');
                    return;
                }
                if (listening) return;

                rec = makeRec();
                if (!rec) {
                    setVoiceStatus('تعذر تشغيل الإملاء الصوتي.');
                    return;
                }

                listening = true;
                wantStop = false;
                setVoiceStatus('يتم الاستماع...');
                setAnalyzeStatus('');

                rec.onresult = (e) => {
                    let final = '';
                    for (let i = e.resultIndex; i < e.results.length; i++) {
                        const rs = e.results[i];
                        if (rs.isFinal) {
                            final += (rs[0]?.transcript || '') + ' ';
                        }
                    }
                    final = final.trim();
                    if (final) {
                        voiceTranscriptEl.value = (voiceTranscriptEl.value ? (voiceTranscriptEl.value + ' ') : '') + final;
                        setVoiceStatus('تم التحويل إلى نص.');
                    }
                };

                rec.onerror = () => {
                    setVoiceStatus('حدث خطأ أثناء الاستماع.');
                };

                rec.onend = () => {
                    if (wantStop) {
                        listening = false;
                        setVoiceStatus('');
                        voiceStartBtn.disabled = false;
                        if (voiceStopBtn) voiceStopBtn.disabled = true;
                        if ((voiceTranscriptEl.value || '').trim()) {
                            voiceAnalyzeBtn && voiceAnalyzeBtn.click();
                        }
                        return;
                    }

                    if (listening) {
                        try {
                            rec.start();
                        } catch (_) {
                            listening = false;
                        }
                    }
                };

                try {
                    rec.start();
                } catch (_) {
                    listening = false;
                    setVoiceStatus('تعذر بدء الاستماع.');
                    return;
                }

                voiceStartBtn.disabled = true;
                if (voiceStopBtn) voiceStopBtn.disabled = false;
            }

            function stop() {
                wantStop = true;
                try {
                    rec && rec.stop();
                } catch (_) {}
            }

            function analyzeAndFill() {
                const text = (voiceTranscriptEl.value || '').trim();
                if (!text) {
                    setAnalyzeStatus('اكتب/سجّل نصاً أولاً.');
                    return;
                }

                snapshot();
                setAnalyzeStatus('جاري التعبئة...');

                const normalized = toLatinDigits(text).replace(/\s+/g, ' ').trim();
                const n2 = norm(normalized);
                const firstLine = normalized.split(/\n|\.|\!|\؟|\?/).map(s => s.trim()).filter(Boolean)[0] || '';
                const rest = normalized.replace(firstLine, '').trim();

                const name0 = document.querySelector('input[name="translations[0][name]"]');
                if (name0 && !name0.value && firstLine) {
                    name0.value = firstLine;
                }

                if (reqScope && !reqScope.value) {
                    reqScope.value = firstLine;
                } else if (reqNotes && !reqNotes.value) {
                    reqNotes.value = firstLine;
                }

                if (reqNotes && rest && !reqNotes.value) {
                    reqNotes.value = rest;
                } else if (reqNotes && rest && reqNotes.value && reqNotes.value.length < 40) {
                    reqNotes.value = (reqNotes.value + '\n' + rest).trim();
                }

                const desc0 = document.querySelector('textarea[name="translations[0][description]"]');
                if (desc0 && !desc0.value) {
                    desc0.value = text;
                }

                const has = (re) => re.test(n2);

                if (projectTypeEl && !projectTypeEl.value) {
                    if (has(/سكني|فيلا|شقه|شقة|دوبلكس/)) projectTypeEl.value = 'residential';
                    else if (has(/تجاري|محل|مكتب|معرض/)) projectTypeEl.value = 'commercial';
                    else if (has(/صناعي|مستودع|مصنع/)) projectTypeEl.value = 'industrial';
                    else if (has(/حكومي|مدرسه|مدرسة|مستشفى|جامعه|جامعة/)) projectTypeEl.value = 'government';
                }

                if (requestTypeEl && !requestTypeEl.value) {
                    if (has(/ترميم|تجديد|إعادة/)) requestTypeEl.value = 'renovation';
                    else if (has(/تشطيب/)) requestTypeEl.value = 'finishing';
                    else if (has(/إضافة|ملحق|توسعة/)) requestTypeEl.value = 'extension';
                    else if (has(/بناء|انشاء|إنشاء|عظم/)) requestTypeEl.value = 'build';
                }

                if (scopeOfWorkEl && !scopeOfWorkEl.value) {
                    if (has(/كامل|تسليم مفتاح/)) scopeOfWorkEl.value = 'full';
                    else if (has(/عظم/)) scopeOfWorkEl.value = 'structure';
                    else if (has(/تشطيب/)) scopeOfWorkEl.value = 'finishing';
                }

                if (finishingLevelEl && !finishingLevelEl.value) {
                    if (has(/فاخر|vip|فخم/)) finishingLevelEl.value = 'luxury';
                    else if (has(/متوسط/)) finishingLevelEl.value = 'standard';
                    else if (has(/اقتصادي/)) finishingLevelEl.value = 'economic';
                }

                const moneyRange = extractMoneyRange(normalized);
                if (moneyRange) {
                    if (budgetMinEl && !budgetMinEl.value && moneyRange.min !== null) budgetMinEl.value = moneyRange.min;
                    if (budgetMaxEl && !budgetMaxEl.value && moneyRange.max !== null) budgetMaxEl.value = moneyRange.max;
                }

                const dDays = extractDurationDays(normalized);
                if (durationDaysEl && !durationDaysEl.value && dDays !== null) {
                    durationDaysEl.value = dDays;
                }

                const landA = extractArea(normalized, 'land');
                if (landAreaEl && !landAreaEl.value && landA !== null) {
                    landAreaEl.value = landA;
                }
                const builtA = extractArea(normalized, 'built');
                if (builtAreaEl && !builtAreaEl.value && builtA !== null) {
                    builtAreaEl.value = builtA;
                }

                const floors = extractCount(normalized, 'floors');
                if (floorsCountEl && !floorsCountEl.value && floors !== null) {
                    floorsCountEl.value = floors;
                }
                const rooms = extractCount(normalized, 'rooms');
                if (roomsCountEl && !roomsCountEl.value && rooms !== null) {
                    roomsCountEl.value = rooms;
                }
                const baths = extractCount(normalized, 'baths');
                if (bathroomsCountEl && !bathroomsCountEl.value && baths !== null) {
                    bathroomsCountEl.value = baths;
                }

                buildRequirementsText();
                setAnalyzeStatus('تمت التعبئة.');
            }

            voiceStartBtn.addEventListener('click', start);
            voiceStopBtn && voiceStopBtn.addEventListener('click', stop);

            voiceAnalyzeBtn && voiceAnalyzeBtn.addEventListener('click', analyzeAndFill);

            voiceClearBtn && voiceClearBtn.addEventListener('click', function () {
                voiceTranscriptEl.value = '';
                setVoiceStatus('');
                setAnalyzeStatus('');
            });

            voiceUndoBtn && voiceUndoBtn.addEventListener('click', function () {
                restoreSnapshot();
                setAnalyzeStatus('تم التراجع.');
            });
        }

        function bindMapPicker() {
            if (!mapEl || typeof L === 'undefined') {
                return;
            }

            const defaultLat = 24.7136;
            const defaultLng = 46.6753;

            const initialLat = latEl && latEl.value !== '' ? parseFloat(latEl.value) : defaultLat;
            const initialLng = lngEl && lngEl.value !== '' ? parseFloat(lngEl.value) : defaultLng;

            const map = L.map(mapEl, {
                zoomControl: true,
            }).setView([initialLat, initialLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(map);

            const marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

            function setLatLng(lat, lng, updateView) {
                const fixedLat = Number.isFinite(lat) ? lat : defaultLat;
                const fixedLng = Number.isFinite(lng) ? lng : defaultLng;

                marker.setLatLng([fixedLat, fixedLng]);
                if (updateView) {
                    map.setView([fixedLat, fixedLng], Math.max(map.getZoom(), 14));
                }

                if (latEl) latEl.value = fixedLat;
                if (lngEl) lngEl.value = fixedLng;

                const gUrl = `https://www.google.com/maps?q=${fixedLat},${fixedLng}`;
                if (gmapsEl) gmapsEl.value = gUrl;
                if (mapOpenGmaps) mapOpenGmaps.href = gUrl;
            }

            setLatLng(initialLat, initialLng, false);

            map.on('click', function (e) {
                if (!e || !e.latlng) return;
                setLatLng(e.latlng.lat, e.latlng.lng, true);
            });

            marker.on('dragend', function (e) {
                const pos = e && e.target ? e.target.getLatLng() : null;
                if (!pos) return;
                setLatLng(pos.lat, pos.lng, false);
            });

            async function doSearch() {
                if (!mapSearchEl || !mapSearchEl.value) {
                    return;
                }

                const q = mapSearchEl.value.trim();
                if (!q) return;

                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!res.ok) {
                    return;
                }

                const data = await res.json();
                const first = Array.isArray(data) && data.length ? data[0] : null;
                if (!first) {
                    return;
                }

                const lat = parseFloat(first.lat);
                const lng = parseFloat(first.lon);
                setLatLng(lat, lng, true);
            }

            if (mapSearchBtn) {
                mapSearchBtn.addEventListener('click', function () {
                    doSearch();
                });
            }

            if (mapSearchEl) {
                mapSearchEl.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        doSearch();
                    }
                });
            }

            if (mapLocateBtn && navigator.geolocation) {
                mapLocateBtn.addEventListener('click', function () {
                    navigator.geolocation.getCurrentPosition(function (pos) {
                        const lat = pos && pos.coords ? pos.coords.latitude : null;
                        const lng = pos && pos.coords ? pos.coords.longitude : null;
                        if (lat === null || lng === null) return;
                        setLatLng(lat, lng, true);
                    });
                });
            }
        }

        if (statusEl) {
            statusEl.addEventListener('change', syncTenderVisibility);
            syncTenderVisibility();
        }

        bindRequirements();
        bindImagePreview();
        bindAttachmentsPreview();
        bindMapPicker();
        bindVoiceAssist();
    })();
</script>
@endpush
@endsection
