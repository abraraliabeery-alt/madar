@extends('facility.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-start justify-content-between mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">طلب تنفيذ جديد</h1>
            <p class="text-muted mb-0">أنشئ طلباً عاماً لاستقبال عروض من منفِّذين (مقاولات، صيانة، تصميم، وغيرها) مع دعم كامل لتعدد اللغات.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.execution-requests.store') }}" class="vstack gap-4">
                @csrf

                @include('components.translations-repeater', [
                    'locales' => $locales,
                    'namePrefix' => 'translations',
                    'fields' => [
                        [
                            'type' => 'input',
                            'key' => 'title',
                            'label' => 'العنوان',
                            'required' => true,
                        ],
                        [
                            'type' => 'textarea',
                            'key' => 'description',
                            'label' => 'الوصف',
                            'rows' => 4,
                        ],
                    ],
                    'addLabel' => 'إضافة ترجمة',
                    'removeLabel' => 'حذف',
                    'minItems' => 1,
                    'maxItems' => is_array($locales) ? count($locales) : null,
                ])

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label">النوع (اختياري)</label>
                        <input type="text" name="type" value="{{ old('type') }}" class="form-control" placeholder="مثال: مقاولات، صيانة، تصميم">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">الأولوية</label>
                        <select name="priority" class="form-select">
                            <option value="normal" {{ old('priority','normal') === 'normal' ? 'selected' : '' }}>عادية</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>مرتفعة</option>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">تاريخ مستهدف (اختياري)</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">الميزانية الدنيا (اختياري)</label>
                        <input type="number" step="0.01" name="budget_min" value="{{ old('budget_min') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">الميزانية القصوى (اختياري)</label>
                        <input type="number" step="0.01" name="budget_max" value="{{ old('budget_max') }}" class="form-control">
                    </div>
                </div>

                <div class="border rounded-3 p-3">
                    <div>
                        <div class="fw-bold">متطلبات تقديم العروض (اعتماد-ستايل)</div>
                        <div class="text-muted small">تحدد ما يجب على المنفِّذ إكماله قبل الإرسال النهائي.</div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12 col-md-4">
                            <label class="form-label">الحد الأدنى للمرفقات المطلوبة</label>
                            <input type="number" min="0" max="10" name="bid_requirements[required_attachments_min]" value="{{ old('bid_requirements.required_attachments_min', 1) }}" class="form-control">
                            <div class="form-text">0 = بدون مرفقات إلزامية.</div>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label">الحقول الإلزامية للإرسال النهائي</label>
                            @php
                                $rf = (array) old('bid_requirements.required_fields', ['price_total','message','technical_plan','declaration']);
                            @endphp
                            <div class="row g-2">
                                <div class="col-12 col-sm-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox" name="bid_requirements[required_fields][]" value="price_total" {{ in_array('price_total', $rf, true) ? 'checked' : '' }}>
                                        <span>قيمة العرض الإجمالية</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox" name="bid_requirements[required_fields][]" value="message" {{ in_array('message', $rf, true) ? 'checked' : '' }}>
                                        <span>ملخص/رسالة العرض</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox" name="bid_requirements[required_fields][]" value="technical_plan" {{ in_array('technical_plan', $rf, true) ? 'checked' : '' }}>
                                        <span>العرض الفني</span>
                                    </label>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox" name="bid_requirements[required_fields][]" value="declaration" {{ in_array('declaration', $rf, true) ? 'checked' : '' }}>
                                        <span>الإقرار والتعهد</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-text">إذا لم تحدد شيئًا سيتم تطبيق الإعدادات الافتراضية.</div>
                        </div>
                    </div>

                    @php
                        $ra = (array) old('bid_requirements.required_attachments', []);
                        if (empty($ra)) {
                            $ra = [
                                ['key' => 'technical', 'label' => 'مرفقات العرض الفني', 'category' => 'technical', 'required' => true],
                                ['key' => 'financial', 'label' => 'مرفقات العرض المالي', 'category' => 'financial', 'required' => false],
                            ];
                        }
                    @endphp

                    <hr class="my-3">

                    <div class="fw-bold mb-1">أنواع المرفقات المطلوبة</div>
                    <div class="text-muted small mb-2">اكتب "مفتاح" نوع المرفق (مثال: technical) ليستخدمه النظام في تصنيف المرفقات والتحقق من الإلزامي.</div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 22%">المفتاح</th>
                                    <th style="width: 38%">الاسم الظاهر</th>
                                    <th style="width: 20%">التصنيف</th>
                                    <th style="width: 20%">إلزامي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($ra, 0, 6) as $i => $row)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="bid_requirements[required_attachments][{{ $i }}][key]" value="{{ $row['key'] ?? '' }}" placeholder="technical">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="bid_requirements[required_attachments][{{ $i }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="مرفقات العرض الفني">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="bid_requirements[required_attachments][{{ $i }}][category]" value="{{ $row['category'] ?? '' }}" placeholder="technical">
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="bid_requirements[required_attachments][{{ $i }}][required]" value="1" {{ !empty($row['required']) ? 'checked' : '' }}>
                                                <label class="form-check-label small">إلزامي</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="form-text mt-2">يمكنك ترك صفوف فارغة. سيتم تجاهل أي صف بدون "مفتاح".</div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save ms-2"></i>
                        حفظ الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
