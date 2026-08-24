@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <style>
        .pdf-sortable{border:1px solid var(--bs-border-color);border-radius:.75rem;padding:.5rem;background:var(--bs-body-bg);} 
        .pdf-sort-item{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.6rem .75rem;margin-bottom:.5rem;border:1px solid var(--bs-border-color);border-radius:.65rem;background:color-mix(in oklab, var(--bs-body-bg), var(--bs-body-color) 3%);cursor:grab;user-select:none;}
        .pdf-sort-item:last-child{margin-bottom:0;}
        .pdf-sort-item:hover{border-color:color-mix(in oklab, var(--bs-border-color), var(--bs-body-color) 25%);} 
        .pdf-drag{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:.5rem;background:color-mix(in oklab, var(--bs-body-bg), var(--bs-body-color) 6%);color:var(--bs-secondary-color);} 
        .pdf-sortable [draggable="true"]:active{cursor:grabbing;}
        html[data-theme="dark"] .pdf-sort-item{background:color-mix(in oklab, var(--brand-bg), #ffffff 6%);} 
        html[data-theme="dark"] .pdf-drag{background:color-mix(in oklab, var(--brand-bg), #ffffff 10%);} 
        .settings-sticky-nav .nav{flex-wrap:nowrap; overflow-x:auto; overflow-y:hidden; scrollbar-width:none; -ms-overflow-style:none;}
        .settings-sticky-nav .nav::-webkit-scrollbar{display:none;}
        .settings-sticky-nav .nav-link{white-space:nowrap; padding:.35rem .65rem; color:var(--bs-secondary-color); border-radius:.5rem; font-size:.82rem;}
        .settings-sticky-nav .nav-link:hover{color:var(--bs-body-color); background:color-mix(in oklab, var(--bs-body-bg), var(--bs-body-color) 6%);}
        .settings-sticky-nav .nav-link.active{background:var(--bs-primary); color:#fff;}
        html{scroll-behavior:smooth;}
        .attr-ref-item{cursor:pointer; transition:background .15s, padding .15s; border-radius:.35rem; padding:.25rem .5rem;}
        .attr-ref-item:hover{background:color-mix(in oklab, var(--bs-primary), transparent 92%);}
        .attr-ref-item:hover .attr-add-hint{display:inline-block !important;}
        .group-row-active{border:1px solid var(--bs-primary) !important; background:color-mix(in oklab, var(--bs-primary), transparent 95%) !important;}
        .section-toggle{cursor:pointer;}
        .section-toggle:hover{background:color-mix(in oklab, var(--bs-card-cap-bg), var(--bs-body-color) 4%);}
        .section-toggle.collapsed h6{opacity:.7;}
    </style>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إعدادات PDF (بروفايل العقار)</h5>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pdf.settings.update') }}" method="POST" id="pdf-settings-form">
                @csrf

                <div id="save-status" class="alert mb-3" role="alert" style="display:none;"></div>

                <div class="settings-sticky-nav d-flex justify-content-between align-items-center flex-wrap gap-2 p-2 rounded mb-4" style="background:var(--bs-body-bg); border:1px solid var(--bs-border-color);">
                    <div class="fw-bold">إعدادات PDF</div>
                    <nav class="nav nav-pills" id="settings-nav" style="font-size:.88rem;">
                        <a class="nav-link active" href="#section-fonts">الخطوط</a>
                        <a class="nav-link" href="#section-style">التنسيق</a>
                        <a class="nav-link" href="#section-slides">الشرائح</a>
                        <a class="nav-link" href="#section-attrs">الترتيب العام</a>
                        <a class="nav-link" href="#section-groups">شرائح الخصائص</a>
                    </nav>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary" id="save-state">محفوظ</span>
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-save me-1"></i> حفظ</button>
                    </div>
                </div>


                <div class="row g-4" style="align-items:flex-start;">
                    <div class="col-lg-8 order-1 order-lg-0">
                <div class="card mb-4" id="section-fonts">
                    <div class="card-header">
                        <h6 class="mb-0">الخطوط</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">حجم الخط الأساسي (px)</label>
                                    <input type="number" class="form-control" name="font_base_px" value="{{ old('font_base_px', $settings['font_base_px']) }}" min="10" max="40" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">حجم العناوين (px)</label>
                                    <input type="number" class="form-control" name="font_title_px" value="{{ old('font_title_px', $settings['font_title_px']) }}" min="12" max="60" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">حجم القيم (px)</label>
                                    <input type="number" class="form-control" name="font_value_px" value="{{ old('font_value_px', $settings['font_value_px']) }}" min="10" max="60" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4" id="section-style">
                    <div class="card-header">
                        <h6 class="mb-0">التنسيق</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">الثيم الافتراضي للـ PDF</label>
                                <select class="form-select" name="theme_default">
                                    <option value="light" {{ ($pdfThemeDefault ?? 'light') === 'light' ? 'selected' : '' }}>لايت</option>
                                    <option value="dark" {{ ($pdfThemeDefault ?? 'light') === 'dark' ? 'selected' : '' }}>دارك</option>
                                </select>
                                <div class="form-text">يمكنك عمل Override من الرابط: <code>?theme=dark</code> أو <code>?theme=light</code></div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-lg-7">
                                <div class="border rounded p-3" style="background: linear-gradient(135deg, rgba(18,107,97,0.12), rgba(124,58,237,0.10));">
                                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                        <div>
                                            <div class="fw-bold" style="font-size: 1.1rem;">معاينة الهوية</div>
                                            <div class="text-muted" style="font-size: .9rem;">تحديث مباشر للألوان والقياسات</div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge" id="brandBadge" style="background: {{ $brandColor }}; color: #fff; padding: .5rem .75rem;">Brand</span>
                                            <span class="badge" id="accentBadge" style="background: {{ $accentColor }}; color: #fff; padding: .5rem .75rem;">Accent</span>
                                        </div>
                                    </div>
                                    <div class="mt-3" id="stylePreview" style="border-radius: 14px; background: {{ data_get($style,'bg_color','#ffffff') }}; color: {{ data_get($style,'text_color','#162222') }}; border: 1px solid rgba(0,0,0,.08); overflow:hidden;">
                                        <div id="stylePreviewHeader" style="background: {{ $brandColor }}; color:{{ data_get($style,'title_color','#fff') }}; padding: 14px 16px; font-weight: 700;">PDF Header</div>
                                        <div id="stylePreviewBody" style="padding: 16px; background: {{ data_get($style,'bg_color','#ffffff') }}; color: {{ data_get($style,'text_color','#162222') }};">
                                            <div class="d-flex gap-2 flex-wrap" id="stylePreviewGrid">
                                                <div id="stylePreviewCard" style="flex:1; min-width: 240px; border-radius: 14px; padding: 14px; border: 1px solid rgba(0,0,0,.08);">
                                                    <div class="fw-bold mb-2">Card</div>
                                                    <div class="text-muted" style="font-size:.9rem;">عنوان + قيمة داخل بطاقة</div>
                                                    <div class="mt-3 d-flex align-items-center gap-2">
                                                        <span class="badge" id="stylePreviewBasePill" style="background: rgba(0,0,0,.06); color:#111;">Base</span>
                                                        <span id="stylePreviewPill" class="badge" style="background: {{ $accentColor }}; color:#fff;">Accent</span>
                                                    </div>
                                                </div>
                                                <div style="flex:1; min-width: 240px; border-radius: 14px; padding: 14px; border: 1px dashed rgba(0,0,0,.18);">
                                                    <div class="fw-bold mb-2">Grid Spacing</div>
                                                    <div class="text-muted" style="font-size:.9rem;">تباعد العناصر داخل الـ PDF</div>
                                                    <div class="mt-3" id="stylePreviewGridDots" style="display:flex; gap: 10px;">
                                                        <div style="height: 10px; width: 10px; border-radius: 50%; background: {{ $brandColor }};"></div>
                                                        <div style="height: 10px; width: 10px; border-radius: 50%; background: {{ $accentColor }};"></div>
                                                        <div style="height: 10px; width: 10px; border-radius: 50%; background: rgba(0,0,0,.15);"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-2">قوالب الألوان الجاهزة</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary color-preset" data-preset='{"brand_color":"#126B61","accent_color":"#7C3AED","bg_color":"#ffffff","title_color":"#1f2937","text_color":"#4b5563","dark_bg":"#0f172a","dark_title":"#f8fafc","dark_text":"#e5e7eb"}'>كلاسيك</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary color-preset" data-preset='{"brand_color":"#0d3b66","accent_color":"#f4d35e","bg_color":"#ffffff","title_color":"#0d3b66","text_color":"#1b263b","dark_bg":"#051024","dark_title":"#ffffff","dark_text":"#d1d5db"}'>أزرق ذهبي</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary color-preset" data-preset='{"brand_color":"#8B0000","accent_color":"#D4AF37","bg_color":"#fff9f9","title_color":"#2c0a0a","text_color":"#4a1a1a","dark_bg":"#2a0505","dark_title":"#fff0f0","dark_text":"#e8d5d5"}'>أحمر فاخر</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary color-preset" data-preset='{"brand_color":"#1f2937","accent_color":"#10b981","bg_color":"#ffffff","title_color":"#111827","text_color":"#374151","dark_bg":"#111827","dark_title":"#f9fafb","dark_text":"#d1d5db"}'>داكن نيون</button>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">لون الهوية (Brand)</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="padding: 0; width: 44px; justify-content:center;">
                                                <input type="color" id="brandColorPicker" value="{{ $brandColor }}" style="width: 34px; height: 34px; border: 0; background: transparent;">
                                            </span>
                                            <input type="text" class="form-control" id="brandColorText" name="style[brand_color]" value="{{ $brandColor }}" placeholder="#126B61">
                                        </div>
                                        <div class="form-text">يدعم HEX مثل #126B61</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">لون Accent</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="padding: 0; width: 44px; justify-content:center;">
                                                <input type="color" id="accentColorPicker" value="{{ $accentColor }}" style="width: 34px; height: 34px; border: 0; background: transparent;">
                                            </span>
                                            <input type="text" class="form-control" id="accentColorText" name="style[accent_color]" value="{{ $accentColor }}" placeholder="#7C3AED">
                                        </div>
                                        <div class="form-text">لون ثانوي للأزرار والبادجات</div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">خلفية الشرائح (Light)</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="padding:0; width:44px; justify-content:center;">
                                                <input type="color" class="color-picker" data-target="style[bg_color]" value="{{ data_get($style,'bg_color','#ffffff') }}" style="width:34px; height:34px; border:0; background:transparent;">
                                            </span>
                                            <input type="text" class="form-control color-text" name="style[bg_color]" value="{{ data_get($style,'bg_color') }}" placeholder="#ffffff">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">لون عنوان الشرائح (Light)</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="padding:0; width:44px; justify-content:center;">
                                                <input type="color" class="color-picker" data-target="style[title_color]" value="{{ data_get($style,'title_color','#162222') }}" style="width:34px; height:34px; border:0; background:transparent;">
                                            </span>
                                            <input type="text" class="form-control color-text" name="style[title_color]" value="{{ data_get($style,'title_color') }}" placeholder="#162222">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">لون النص الأساسي (Light)</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="padding:0; width:44px; justify-content:center;">
                                                <input type="color" class="color-picker" data-target="style[text_color]" value="{{ data_get($style,'text_color','#162222') }}" style="width:34px; height:34px; border:0; background:transparent;">
                                            </span>
                                            <input type="text" class="form-control color-text" name="style[text_color]" value="{{ data_get($style,'text_color') }}" placeholder="#162222">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-2">
                                        <div class="fw-bold mb-2">ألوان نسخة الدارك (اختياري)</div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Brand (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[brand_color]" value="{{ $darkBrandColor ?? '' }}" placeholder="#ffffff">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Accent (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[accent_color]" value="{{ $darkAccentColor ?? '' }}" placeholder="#ffffff">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">خلفية الصفحة (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[bg_color]" value="{{ data_get($darkStyle,'bg_color') }}" placeholder="#0B1220">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">لون البطاقة (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[card_color]" value="{{ data_get($darkStyle,'card_color') }}" placeholder="#111827">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">لون عنوان الشرائح (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[title_color]" value="{{ data_get($darkStyle,'title_color') }}" placeholder="#F8FAFC">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">لون النص (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[text_color]" value="{{ data_get($darkStyle,'text_color') }}" placeholder="#F8FAFC">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Muted (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[muted_color]" value="{{ data_get($darkStyle,'muted_color') }}" placeholder="#A1A1AA">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Stroke (دارك)</label>
                                                <input type="text" class="form-control" name="dark_style[stroke_color]" value="{{ data_get($darkStyle,'stroke_color') }}" placeholder="rgba(255,255,255,0.14)">
                                                <div class="form-text">يدعم HEX أو rgba(...) للحدود</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Card Radius (mm)</label>
                                        <input type="number" step="0.5" class="form-control" id="cardRadiusInput" name="style[card_radius_mm]" value="{{ data_get($style,'card_radius_mm') }}" min="0" max="20">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Card Padding (mm)</label>
                                        <input type="number" step="0.5" class="form-control" id="cardPaddingInput" name="style[card_padding_mm]" value="{{ data_get($style,'card_padding_mm') }}" min="0" max="30">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Grid Spacing (mm)</label>
                                        <input type="number" step="0.5" class="form-control" id="gridSpacingInput" name="style[grid_spacing_mm]" value="{{ data_get($style,'grid_spacing_mm') }}" min="0" max="20">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 text-muted" style="font-size:.9rem;">الخيارات أعلاه تطبق مباشرة داخل تصميم الـ PDF.</div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4" id="section-slides">
                    <div class="card-header">
                        <h6 class="mb-0">السلايدات</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="slides[cover]" value="1" id="slide_cover" {{ !empty($slides['cover']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="slide_cover"><i class="fas fa-image ms-2 text-muted"></i>الغلاف</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="slides[details]" value="1" id="slide_details" {{ !empty($slides['details']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="slide_details"><i class="fas fa-list ms-2 text-muted"></i>التفاصيل</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="slides[features]" value="1" id="slide_features" {{ !empty($slides['features']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="slide_features"><i class="fas fa-star ms-2 text-muted"></i>المميزات</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="slides[offers]" value="1" id="slide_offers" {{ !empty($slides['offers']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="slide_offers"><i class="fas fa-tags ms-2 text-muted"></i>العروض</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="slides[location]" value="1" id="slide_location" {{ !empty($slides['location']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="slide_location"><i class="fas fa-map-marker-alt ms-2 text-muted"></i>الموقع</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="slides[cta]" value="1" id="slide_cta" {{ !empty($slides['cta']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="slide_cta"><i class="fas fa-phone ms-2 text-muted"></i>التواصل</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="fw-bold mb-2">عناوين الشرائح (حسب اللغة)</div>
                            @php
                                $labelKeys = ['details','location','features','offers','cta'];
                                $labelTranslations = [
                                    'details'  => 'pdf.slides.summary.title',
                                    'location' => 'pdf.slides.location.title',
                                    'features' => 'pdf.slides.features.title',
                                    'offers'   => 'pdf.slides.offers.title',
                                    'cta'      => 'pdf.slides.cta.title',
                                ];
                            @endphp
                            @foreach($languages as $locale => $langData)
                                @php
                                    $isActive = $locale === $adminLocale;
                                @endphp
                                <div class="card mb-3 border" style="border-color: {{ $isActive ? 'var(--bs-primary)' : 'var(--bs-border-color)' }} !important;">
                                    <div class="card-header bg-body-tertiary py-2 d-flex align-items-center gap-2">
                                        <span class="fs-5">{{ $langData['flag'] ?? '' }}</span>
                                        <span class="fw-bold small">{{ $langData['native'] ?? strtoupper($locale) }}</span>
                                        <span class="badge bg-secondary ms-auto">{{ $locale }}</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            @foreach($labelKeys as $k)
                                                @php
                                                    $defaultLabel = trans($labelTranslations[$k] ?? 'pdf.slides.'.$k.'.title', [], $locale);
                                                    $stored = trim((string) ($slideLabels[$locale][$k] ?? ''));
                                                    $inputValue = $stored !== '' ? $stored : $defaultLabel;
                                                @endphp
                                                <div class="col-md-6">
                                                    <label class="form-label" for="slide_label_{{ $locale }}_{{ $k }}">{{ $defaultLabel }}</label>
                                                    <input type="text" class="form-control" id="slide_label_{{ $locale }}_{{ $k }}" name="slide_labels[{{ $locale }}][{{ $k }}]" value="{{ $inputValue }}" placeholder="{{ $defaultLabel }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                <div>
                                    <div class="fw-bold">ترتيب الشرائح</div>
                                    <div class="text-muted" style="font-size:.9rem;">اسحب وافلت لتغيير ترتيب الشرائح في ملف PDF</div>
                                </div>
                                <div class="text-muted" style="font-size:.9rem;">"التواصل" تظهر في النهاية افتراضيًا</div>
                            </div>

                            @php
                                $slideIcons = [
                                    'cover' => 'fa-image',
                                    'details' => 'fa-list',
                                    'location' => 'fa-map-marker-alt',
                                    'features' => 'fa-star',
                                    'offers' => 'fa-tags',
                                    'cta' => 'fa-phone',
                                ];
                            @endphp

                            <div class="pdf-sortable mt-2" id="slides_sortable">
                                @foreach($slidesOrder as $k)
                                    @php
                                        $displayLabel = app(\App\Services\PdfSettingsService::class)->slideLabel($k, ['slide_labels' => $slideLabels], $adminLocale);
                                    @endphp
                                    @if(array_key_exists($k, $slideLabels[$adminLocale] ?? []))
                                        <div class="pdf-sort-item" draggable="true" data-slide-key="{{ $k }}">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="pdf-drag" aria-hidden="true"><i class="fas fa-grip-vertical"></i></span>
                                                <i class="fas {{ $slideIcons[$k] ?? 'fa-layer-group' }} text-muted"></i>
                                                <strong>{{ $displayLabel }}</strong>
                                                <span class="text-muted small">({{ $k }})</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div id="slides_hidden_fields"></div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4" id="section-groups">
                    <div class="card-header">
                        <h6 class="mb-0">شرائح الخصائص حسب الفئة</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">الفئة الرئيسية</label>
                                <select class="form-select" id="main_category_select">
                                    <option value="">-- اختر الفئة الرئيسية --</option>
                                    @foreach($categories as $main)
                                        <option value="{{ $main->id }}">{{ $main->name }}</option>
                                    @endforeach
                                </select>

                                <div class="mt-3" id="sub_category_wrap" style="display:none;">
                                    <label class="form-label">الفئة الفرعية</label>
                                    <select class="form-select" id="sub_category_select">
                                        <option value="">-- اختر الفئة الفرعية --</option>
                                    </select>
                                </div>

                                <div class="mt-3" id="category_attr_panels">
                                    @foreach($categories as $main)
                                        @foreach($main->children as $category)
                                            @php
                                                $categoryAttributes = $category->attributes ?? collect();
                                                $categoryOrder = is_array(($attributeOrderByCategory[$category->id] ?? null)) ? $attributeOrderByCategory[$category->id] : [];
                                                $categoryOrder = $categoryOrder ?: [];
                                            @endphp
                                            <div class="category-attr-panel" data-cat-id="{{ $category->id }}" data-main-id="{{ $main->id }}" data-cat-name="{{ $category->name }}" style="display:none;">
                                                @php
                                                    $categoryGroups = $attributeGroups[$category->id] ?? [];
                                                    $groupCount = count($categoryGroups);
                                                    $attrInGroups = collect($categoryGroups)->sum(fn ($g) => count($g['attributes'] ?? []));
                                                @endphp
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">
                                                        شرائح الخصائص: {{ $category->name }}
                                                        <span class="badge bg-secondary ms-2 category-groups-count">{{ $groupCount }} شريحة · {{ $attrInGroups }} خاصية</span>
                                                    </label>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary add-attr-group" data-cat-id="{{ $category->id }}">
                                                            <i class="fas fa-plus me-1"></i> شريحة جديدة
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="attr-groups-list mt-2" data-cat-id="{{ $category->id }}">
                                                    @forelse($categoryGroups as $gIndex => $group)
                                                        @php
                                                            $groupAttrIds = is_array(($group['attributes'] ?? null)) ? $group['attributes'] : [];
                                                        @endphp
                                                        <div class="attr-group-row border rounded p-2 mb-2" style="background:var(--bs-body-bg);">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control-sm group-name-input"
                                                                    name="attribute_groups[{{ $category->id }}][{{ $gIndex }}][name]"
                                                                    value="{{ $group['name'] ?? '' }}"
                                                                    placeholder="عنوان الشريحة (مثال: مواصفات داخلية)"
                                                                >
                                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-attr-group" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                            <input
                                                                type="hidden"
                                                                class="group-attr-ids"
                                                                name="attribute_groups[{{ $category->id }}][{{ $gIndex }}][attributes]"
                                                                value="{{ implode(',', $groupAttrIds) }}"
                                                            >
                                                            <div class="group-attr-sortable pdf-sortable min-h-60" style="min-height:60px;" data-cat-id="{{ $category->id }}">
                                                                @foreach($groupAttrIds as $attrId)
                                                                    @php
                                                                        $attr = $categoryAttributes->firstWhere('id', $attrId);
                                                                        $attrName = $attr ? ($attr->translations->first()->name ?? $attr->name ?? $attr->key ?? 'خاصية') : 'خاصية غير موجودة';
                                                                        $attrKey = $attr ? ($attr->key ?? '-') : '';
                                                                        $attrDisplayId = $attr ? $attr->id : $attrId;
                                                                        $attrClass = $attr ? '' : 'border-danger bg-danger bg-opacity-10';
                                                                        $nameClass = $attr ? 'fw-bold' : 'fw-bold text-danger';
                                                                    @endphp
                                                                    <div class="group-attr-sort-item pdf-sort-item {{ $attrClass }}" draggable="true" data-attr-id="{{ $attrDisplayId }}">
                                                                        <div class="d-flex align-items-center gap-2 w-100">
                                                                            <span class="pdf-drag" aria-hidden="true"><i class="fas fa-grip-vertical"></i></span>
                                                                            <div class="flex-grow-1">
                                                                                <div class="{{ $nameClass }}">{{ $attrName }}</div>
                                                                                <div class="text-muted small">{{ $attrKey ? $attrKey . ' · ' : '' }}ID: {{ $attrDisplayId }}</div>
                                                                            </div>
                                                                            <button type="button" class="btn btn-link text-danger p-0 remove-group-attr" title="إزالة" style="font-size:.9rem;">
                                                                                <i class="fas fa-times"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="form-text active-group-hint" style="display:none; font-size:.75rem; color:var(--bs-primary);">
                                                                <i class="fas fa-check me-1"></i>الشريحة النشطة — اضغط على خاصية من القائمة لإضافتها
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="text-muted small no-groups-msg">لا توجد شرائح. اضغط "شريحة جديدة".</div>
                                                    @endforelse
                                                </div>

                                                <div class="form-text mt-3">خصائص الفئة (اختر شريحة ثم اضغط على الخاصية لإضافتها):</div>
                                                <input type="text" class="form-control form-control-sm attr-ref-search mt-1" placeholder="ابحث في الخصائص...">
                                                <div class="border rounded p-2 mt-1 attr-ref-list" style="max-height: 180px; overflow:auto; background:var(--bs-body-bg);">
                                                    @forelse($categoryAttributes as $attr)
                                                        <div class="d-flex justify-content-between align-items-center py-1 attr-ref-item" data-attr-id="{{ $attr->id }}" role="button">
                                                            <div>
                                                                <strong>#{{ $attr->id }}</strong>
                                                                <span class="ms-2">{{ $attr->translations->first()->name ?? $attr->name ?? $attr->key ?? 'Attribute' }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="badge bg-light text-dark me-1">{{ $attr->key ?? '-' }}</span>
                                                                <span class="badge bg-primary text-white attr-add-hint" style="display:none;"><i class="fas fa-plus"></i></span>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="text-muted small">لا توجد خصائص مرتبطة بهذه الفئة.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="alert alert-info" style="font-size:.9rem;">
                                    <strong>كيف يعمل؟</strong><br>
                                    اختر الفئة الفرعية، ثم أنشئ الشرائح وحدد عنوان كل شريحة.<br>
                                    اختر شريحة لإضافة الخصائص إليها، ثم اسحب الخصائص لترتيبها أو نقلها بين الشرائح. عند عدم وجود شرائح يُستخدم الترتيب العام.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <template id="attr_group_template">
                    <div class="attr-group-row border rounded p-2 mb-2" style="background:var(--bs-body-bg);">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <input
                                type="text"
                                class="form-control form-control-sm group-name-input"
                                name="attribute_groups[__CAT__][__KEY__][name]"
                                placeholder="عنوان الشريحة (مثال: مواصفات داخلية)"
                            >
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-attr-group" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <input
                            type="hidden"
                            class="group-attr-ids"
                            name="attribute_groups[__CAT__][__KEY__][attributes]"
                            value=""
                        >
                        <div class="group-attr-sortable pdf-sortable min-h-60" style="min-height:60px;">
                        </div>
                        <div class="form-text active-group-hint" style="display:none; font-size:.75rem; color:var(--bs-primary);">
                            <i class="fas fa-check me-1"></i>الشريحة النشطة — اضغط على خاصية من القائمة لإضافتها
                        </div>
                    </div>
                </template>

                    </div>
                    <div class="col-lg-4 order-0 order-lg-1">
                        <div style="top:1rem;">
                <div class="card mb-4" id="section-preview">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">معاينة سريعة</h6>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none toggle-preview" aria-expanded="true" title="إخفاء/إظهار">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse show" id="previewCardBody">
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small">رقم العقار (ID)</label>
                                <input type="number" class="form-control form-control-sm" id="pdf_preview_product_id" placeholder="مثال: 37" min="1">
                            </div>
                            <div class="col-6">
                                <label class="form-label small">الشريحة</label>
                                <select class="form-select form-select-sm" id="pdf_preview_slide">
                                    <option value="cover">الغلاف</option>
                                    <option value="details" selected>التفاصيل</option>
                                    <option value="location">الموقع</option>
                                    <option value="features">المميزات</option>
                                    <option value="offers">العروض</option>
                                    <option value="cta">التواصل</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">التنسيق</label>
                                <select class="form-select form-select-sm" id="pdf_preview_format">
                                    <option value="presentation" selected>عرض تقديمي</option>
                                    <option value="mobile">هاتف</option>
                                </select>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill" id="pdf_preview_open">
                                    <i class="fas fa-eye me-1"></i>فتح PDF
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary flex-fill" id="pdf_preview_inline">
                                    <i class="fas fa-layer-group me-1"></i>معاينة
                                </button>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-outline-info w-100" id="pdf_ai_audit">
                                    <i class="fas fa-clipboard-check me-1"></i>فحص جاهزية العقار وPDF
                                </button>
                            </div>
                        </div>

                        <div id="pdf_ai_audit_result" class="mt-2" style="display:none;"></div>
                        <div class="mt-2 text-muted" style="font-size:.9rem;">يمكنك فتح PDF أو معاينة شريحة داخل الصفحة.</div>

                        <div class="mt-3">
                            <div class="ratio" style="--bs-aspect-ratio: 56.25%;">
                                <iframe id="pdf_preview_frame" title="PDF Slide Preview" style="border:1px solid var(--bs-border-color); border-radius:12px; background:#fff; width:100%; height:100%;" loading="lazy"></iframe>
                            </div>
                            <div class="form-text">المعاينة هنا تعرض شريحة واحدة داخل الصفحة لتعديلها بسرعة.</div>
                        </div>
                    </div>
                </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-save me-2"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    var input = document.querySelector('input[name="attribute_order_csv"]');
    var sortable = document.getElementById('attr_sortable');
    var hiddenWrap = document.getElementById('attr_hidden_fields');
    var previewId = document.getElementById('pdf_preview_product_id');
    var previewSlide = document.getElementById('pdf_preview_slide');
    var previewBtn = document.getElementById('pdf_preview_open');
    var previewInlineBtn = document.getElementById('pdf_preview_inline');
    var previewFrame = document.getElementById('pdf_preview_frame');
    var pdfAiSuggestUrl = @json(route('admin.pdf.settings.ai.suggest-slides'));
    var pdfAiAuditUrl = @json(route('admin.pdf.settings.ai.audit-product'));
    var pdfAiPaletteUrl = @json(route('admin.pdf.settings.ai.suggest-palette'));

    if(previewId){
        var savedPreviewId = localStorage.getItem('pdf_preview_product_id');
        if(savedPreviewId && !previewId.value){
            previewId.value = savedPreviewId;
        }
    }

    var previewFormat = document.getElementById('pdf_preview_format');
    var brandPicker = document.getElementById('brandColorPicker');
    var brandText = document.getElementById('brandColorText');
    var accentPicker = document.getElementById('accentColorPicker');
    var accentText = document.getElementById('accentColorText');
    var brandBadge = document.getElementById('brandBadge');
    var accentBadge = document.getElementById('accentBadge');
    var previewHeader = document.getElementById('stylePreviewHeader');
    var previewPill = document.getElementById('stylePreviewPill');

    var cardRadiusInput = document.getElementById('cardRadiusInput');
    var cardPaddingInput = document.getElementById('cardPaddingInput');
    var gridSpacingInput = document.getElementById('gridSpacingInput');
    var previewCard = document.getElementById('stylePreviewCard');
    var previewBox = document.getElementById('stylePreview');
    var previewBody = document.getElementById('stylePreviewBody');
    var previewGrid = document.getElementById('stylePreviewGrid');
    var previewGridDots = document.getElementById('stylePreviewGridDots');
    var basePill = document.getElementById('stylePreviewBasePill');
    var styleBgInput = document.querySelector('[name="style[bg_color]"]');
    var styleTitleInput = document.querySelector('[name="style[title_color]"]');
    var styleTextInput = document.querySelector('[name="style[text_color]"]');

    var slidesSortable = document.getElementById('slides_sortable');
    var slidesHiddenWrap = document.getElementById('slides_hidden_fields');

    function getPreviewId(){
        var id = previewId ? parseInt(previewId.value || '', 10) : NaN;
        if(!id || isNaN(id) || id < 1) return null;
        return id;
    }

    function getPreviewSlide(){
        return previewSlide ? String(previewSlide.value || 'details') : 'details';
    }

    function getPreviewFormat(){
        return previewFormat ? String(previewFormat.value || 'presentation') : 'presentation';
    }

    if(previewBtn){
        previewBtn.addEventListener('click', function(){
            var id = getPreviewId();
            if(!id) return;
            var url = '/products/' + id + '/pdf?format=' + encodeURIComponent(getPreviewFormat()) + '&theme=' + encodeURIComponent(document.querySelector('[name="theme_default"]')?.value || 'light');
            window.open(url, '_blank');
        });
    }

    function loadInlinePreview(){
        var id = getPreviewId();
        if(!id || !previewFrame) return;
        localStorage.setItem('pdf_preview_product_id', String(id));
        var slide = getPreviewSlide();
        var format = getPreviewFormat();
        previewFrame.src = '/products/' + id + '/pdf/html?slide=' + encodeURIComponent(slide) + '&format=' + encodeURIComponent(format) + '&theme=' + encodeURIComponent(document.querySelector('[name="theme_default"]')?.value || 'light') + '&_=' + Date.now();
    }

    if(previewInlineBtn){
        previewInlineBtn.addEventListener('click', loadInlinePreview);
    }

    if(previewSlide){
        previewSlide.addEventListener('change', loadInlinePreview);
    }

    if(previewId){
        previewId.addEventListener('change', loadInlinePreview);
    }

    if(previewFormat){
        previewFormat.addEventListener('change', loadInlinePreview);
    }

    var themeDefault = document.querySelector('[name="theme_default"]');
    if(themeDefault){
        themeDefault.addEventListener('change', function(){
            applyStylePreview();
            loadInlinePreview();
        });
    }

    function isValidHex(v){
        if(!v) return false;
        v = String(v).trim();
        return /^#[0-9a-fA-F]{6}$/.test(v) || /^#[0-9a-fA-F]{3}$/.test(v);
    }

    function applyStylePreview(){
        var brand = brandText ? brandText.value : (brandPicker ? brandPicker.value : '');
        var accent = accentText ? accentText.value : (accentPicker ? accentPicker.value : '');
        var bg = styleBgInput ? styleBgInput.value : '';
        var title = styleTitleInput ? styleTitleInput.value : '';
        var text = styleTextInput ? styleTextInput.value : '';

        if(isValidHex(brand)){
            if(brandBadge) brandBadge.style.background = brand;
            if(previewHeader) previewHeader.style.background = brand;
        }
        if(isValidHex(accent)){
            if(accentBadge) accentBadge.style.background = accent;
            if(previewPill) previewPill.style.background = accent;
        }
        if(previewHeader) previewHeader.style.color = isValidHex(title) ? title : '#fff';
        if(previewBox){
            previewBox.style.backgroundColor = isValidHex(bg) ? bg : '#ffffff';
            previewBox.style.color = isValidHex(text) ? text : '#162222';
        }
        if(previewBody){
            previewBody.style.backgroundColor = isValidHex(bg) ? bg : '#ffffff';
            previewBody.style.color = isValidHex(text) ? text : '#162222';
        }

        if(previewCard){
            var r = parseFloat(cardRadiusInput ? cardRadiusInput.value : '');
            var p = parseFloat(cardPaddingInput ? cardPaddingInput.value : '');
            previewCard.style.borderRadius = (isNaN(r) ? 14 : Math.max(0, r * 3)) + 'px';
            previewCard.style.padding = (isNaN(p) ? 14 : Math.max(8, p * 2)) + 'px';
            previewCard.style.borderColor = isValidHex(text) ? (text + '20') : 'rgba(0,0,0,.08)';
        }

        if(basePill){
            basePill.style.backgroundColor = isValidHex(text) ? (text + '15') : 'rgba(0,0,0,.06)';
            basePill.style.color = isValidHex(text) ? text : '#111';
        }

        if(gridSpacingInput){
            var g = parseFloat(gridSpacingInput.value);
            var gap = isNaN(g) ? 10 : Math.max(4, g * 3);
            if(previewGrid) previewGrid.style.gap = gap + 'px';
            if(previewGridDots) previewGridDots.style.gap = gap + 'px';
        }
    }

    function bindColorPair(picker, text){
        if(picker && text){
            picker.addEventListener('input', function(){
                text.value = picker.value;
                applyStylePreview();
            });
            text.addEventListener('input', function(){
                if(isValidHex(text.value)) picker.value = text.value;
                applyStylePreview();
            });
        } else if(text){
            text.addEventListener('input', applyStylePreview);
        }
    }

    bindColorPair(brandPicker, brandText);
    bindColorPair(accentPicker, accentText);

    function setColorInput(name, value){
        var text = document.querySelector('[name="' + name + '"]');
        if(text) text.value = value;
        if(name === 'style[brand_color]' && brandPicker && brandText){
            brandText.value = value;
            if(isValidHex(value)) brandPicker.value = value;
        }
        if(name === 'style[accent_color]' && accentPicker && accentText){
            accentText.value = value;
            if(isValidHex(value)) accentPicker.value = value;
        }
        var picker = document.querySelector('.color-picker[data-target="' + name + '"]');
        if(picker && isValidHex(value)) picker.value = value;
    }

    function decorateColorPresets(){
        document.querySelectorAll('.color-preset').forEach(function(btn){
            var data = {};
            try { data = JSON.parse(btn.getAttribute('data-preset') || '{}'); } catch(e) {}
            if(!data.brand_color && !data.accent_color) return;
            var brand = data.brand_color || 'transparent';
            var accent = data.accent_color || 'transparent';
            var wrap = document.createElement('span');
            wrap.className = 'me-1';
            wrap.innerHTML = '<span class="d-inline-block rounded-circle border" style="width:10px;height:10px;background:' + brand + ';"></span>' +
                '<span class="d-inline-block rounded-circle border" style="width:10px;height:10px;background:' + accent + '; margin-right:-4px;"></span>';
            btn.insertBefore(wrap, btn.firstChild);
        });
    }
    decorateColorPresets();

    document.querySelectorAll('.color-preset').forEach(function(btn){
        btn.addEventListener('click', function(){
            var data = {};
            try { data = JSON.parse(this.getAttribute('data-preset') || '{}'); } catch(e) {}
            Object.keys(data).forEach(function(key){
                if(key.indexOf('dark_') === 0){
                    var map = {
                        'dark_brand_color': 'dark_style[brand_color]',
                        'dark_accent_color': 'dark_style[accent_color]',
                        'dark_bg': 'dark_style[bg_color]',
                        'dark_card': 'dark_style[card_color]',
                        'dark_title': 'dark_style[title_color]',
                        'dark_text': 'dark_style[text_color]'
                    };
                    var name = map[key] || 'dark_style[' + key.replace('dark_', '') + ']';
                    setColorInput(name, data[key]);
                } else if(['brand_color','accent_color','bg_color','title_color','text_color'].indexOf(key) !== -1){
                    setColorInput('style[' + key + ']', data[key]);
                } else {
                    setColorInput(key, data[key]);
                }
            });
            applyStylePreview();
            markSettingsDirty();
        });
    });

    var aiPaletteBtn = document.getElementById('ai-suggest-palette');
    var aiPaletteNote = document.getElementById('ai-palette-note');
    if(aiPaletteBtn){
        aiPaletteBtn.addEventListener('click', function(){
            var direction = window.prompt('صف طابع الهوية المطلوب:', 'عقاري فاخر وهادئ');
            if(direction === null) return;
            var original = aiPaletteBtn.innerHTML;
            aiPaletteBtn.disabled = true;
            aiPaletteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جارٍ الاقتراح';
            fetch(pdfAiPaletteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({direction: direction})
            }).then(function(response){
                return response.json().then(function(data){ return {ok: response.ok, data: data}; });
            }).then(function(result){
                if(!result.ok || !result.data.success) throw new Error(result.data.message || 'تعذر اقتراح الألوان');
                var palette = result.data.palette || {};
                ['brand_color','accent_color','bg_color','title_color','text_color'].forEach(function(key){
                    if(palette[key]) setColorInput('style[' + key + ']', palette[key]);
                });
                var darkMap = {dark_bg:'bg_color',dark_card:'card_color',dark_title:'title_color',dark_text:'text_color'};
                Object.keys(darkMap).forEach(function(key){
                    if(palette[key]) setColorInput('dark_style[' + darkMap[key] + ']', palette[key]);
                });
                applyStylePreview();
                markSettingsDirty();
                if(aiPaletteNote){
                    aiPaletteNote.textContent = palette.rationale || 'تم تطبيق الاقتراح داخل النموذج. اضغط حفظ لاعتماده.';
                    aiPaletteNote.style.display = 'block';
                }
            }).catch(function(error){
                window.alert(error.message || 'تعذر الاتصال بخدمة الذكاء الاصطناعي.');
            }).finally(function(){
                aiPaletteBtn.disabled = false;
                aiPaletteBtn.innerHTML = original;
            });
        });
    }

    document.querySelectorAll('.color-picker').forEach(function(picker){
        picker.addEventListener('input', function(){
            var text = document.querySelector('[name="' + (this.getAttribute('data-target') || '') + '"]');
            if(text) text.value = this.value;
            applyStylePreview();
        });
    });
    document.querySelectorAll('.color-text').forEach(function(text){
        text.addEventListener('input', function(){
            var picker = document.querySelector('.color-picker[data-target="' + this.getAttribute('name') + '"]');
            if(picker && isValidHex(this.value)) picker.value = this.value;
            applyStylePreview();
        });
    });

    if(cardRadiusInput) cardRadiusInput.addEventListener('input', applyStylePreview);
    if(cardPaddingInput) cardPaddingInput.addEventListener('input', applyStylePreview);
    if(gridSpacingInput) gridSpacingInput.addEventListener('input', applyStylePreview);
    if(styleBgInput) styleBgInput.addEventListener('input', applyStylePreview);
    if(styleTitleInput) styleTitleInput.addEventListener('input', applyStylePreview);
    if(styleTextInput) styleTextInput.addEventListener('input', applyStylePreview);
    applyStylePreview();

    function setHiddenSlidesOrder(keys){
        if(!slidesHiddenWrap) return;
        slidesHiddenWrap.innerHTML = '';
        keys.forEach(function(k){
            var h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'slides_order[]';
            h.value = String(k);
            slidesHiddenWrap.appendChild(h);
        });
    }

    function slideKeysFromList(){
        if(!slidesSortable) return [];
        return Array.prototype.slice.call(slidesSortable.querySelectorAll('[data-slide-key]'))
            .map(function(el){ return String(el.getAttribute('data-slide-key') || '').trim(); })
            .filter(function(v){ return !!v; });
    }

    function syncSlidesHiddenFromList(){
        setHiddenSlidesOrder(slideKeysFromList());
    }

    if(slidesSortable){
        var dragSlide = null;

        slidesSortable.addEventListener('dragstart', function(e){
            var item = e.target.closest('[draggable="true"]');
            if(!item) return;
            dragSlide = item;
            item.style.opacity = '0.6';
            e.dataTransfer.effectAllowed = 'move';
        });

        slidesSortable.addEventListener('dragend', function(e){
            var item = e.target.closest('[draggable="true"]');
            if(item) item.style.opacity = '1';
            dragSlide = null;
            syncSlidesHiddenFromList();
            markSettingsDirty();
        });

        slidesSortable.addEventListener('dragover', function(e){
            e.preventDefault();
            var over = e.target.closest('[draggable="true"]');
            if(!dragSlide || !over || over === dragSlide) return;

            var rect = over.getBoundingClientRect();
            var next = (e.clientY - rect.top) > rect.height / 2;
            slidesSortable.insertBefore(dragSlide, next ? over.nextSibling : over);
        });

        syncSlidesHiddenFromList();
    }

    function setHiddenOrder(ids){
        if(!hiddenWrap) return;
        hiddenWrap.innerHTML = '';
        ids.forEach(function(id){
            var h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'attribute_order[]';
            h.value = String(id);
            hiddenWrap.appendChild(h);
        });
    }

    function idsFromList(){
        if(!sortable) return [];
        return Array.prototype.slice.call(sortable.querySelectorAll('[data-attr-id]'))
            .map(function(el){ return parseInt(el.getAttribute('data-attr-id'), 10); })
            .filter(function(n){ return !isNaN(n); });
    }

    function syncHiddenFromList(){
        setHiddenOrder(idsFromList());
        if(input){
            input.value = idsFromList().join(',');
        }
    }

    if(sortable){
        var dragEl = null;

        sortable.addEventListener('dragstart', function(e){
            var item = e.target.closest('[draggable="true"]');
            if(!item) return;
            dragEl = item;
            item.style.opacity = '0.6';
            e.dataTransfer.effectAllowed = 'move';
        });

        sortable.addEventListener('dragend', function(e){
            var item = e.target.closest('[draggable="true"]');
            if(item) item.style.opacity = '1';
            dragEl = null;
            syncHiddenFromList();
            markSettingsDirty();
        });

        sortable.addEventListener('dragover', function(e){
            e.preventDefault();
            var over = e.target.closest('[draggable="true"]');
            if(!dragEl || !over || over === dragEl) return;

            var rect = over.getBoundingClientRect();
            var next = (e.clientY - rect.top) > rect.height / 2;
            sortable.insertBefore(dragEl, next ? over.nextSibling : over);
        });

        syncHiddenFromList();
    }

    if(!input) return;

    function applyCsvToList(){
        var raw = (input.value || '').trim();
        if(!raw || !sortable) {
            if(!sortable){
                setHiddenOrder(raw.split(',').map(function(s){return parseInt(s.trim(),10);}).filter(function(n){return !isNaN(n);}));
            }
            return;
        }

        var wanted = raw.split(',').map(function(s){ return parseInt(s.trim(), 10); }).filter(function(n){ return !isNaN(n); });
        if(!wanted.length) return;

        var map = {};
        Array.prototype.slice.call(sortable.querySelectorAll('[data-attr-id]')).forEach(function(el){
            map[el.getAttribute('data-attr-id')] = el;
        });

        wanted.forEach(function(id){
            var el = map[String(id)];
            if(el){
                sortable.insertBefore(el, sortable.firstChild);
            }
        });

        syncHiddenFromList();
    }

    input.addEventListener('change', applyCsvToList);

    var mainCatSelect = document.getElementById('main_category_select');
    var subCatSelect = document.getElementById('sub_category_select');
    var subCatWrap = document.getElementById('sub_category_wrap');
    var catPanels = document.getElementById('category_attr_panels');

    function buildSubOptions(mainId){
        if(!subCatSelect || !catPanels) return;
        subCatSelect.innerHTML = '<option value="">-- اختر الفئة الفرعية --</option>';
        Array.prototype.slice.call(catPanels.querySelectorAll('.category-attr-panel')).forEach(function(panel){
            if(String(panel.getAttribute('data-main-id') || '') !== String(mainId)) return;
            var opt = document.createElement('option');
            opt.value = String(panel.getAttribute('data-cat-id') || '');
            opt.textContent = String(panel.getAttribute('data-cat-name') || '');
            subCatSelect.appendChild(opt);
        });
    }

    function showCategoryPanel(){
        if(!catPanels) return;
        var selected = subCatSelect ? String(subCatSelect.value || '') : '';
        Array.prototype.slice.call(catPanels.querySelectorAll('.category-attr-panel')).forEach(function(panel){
            panel.style.display = (String(panel.getAttribute('data-cat-id') || '') === selected) ? 'block' : 'none';
        });
    }

    if(mainCatSelect && subCatSelect && subCatWrap){
        mainCatSelect.addEventListener('change', function(){
            makeActiveGroup(null);
            var mainId = String(mainCatSelect.value || '');
            if(!mainId){
                subCatWrap.style.display = 'none';
                subCatSelect.value = '';
                showCategoryPanel();
                return;
            }
            subCatWrap.style.display = 'block';
            buildSubOptions(mainId);
            showCategoryPanel();
        });

        subCatSelect.addEventListener('change', function(){
            makeActiveGroup(null);
            showCategoryPanel();
        });
    }

    var groupTemplate = document.getElementById('attr_group_template');
    var groupKeyCounter = 1000;

    function updateNoGroupsMessage(list){
        if(!list) return;
        var msg = list.querySelector('.no-groups-msg');
        var hasRows = list.querySelectorAll('.attr-group-row').length > 0;
        if(msg) msg.style.display = hasRows ? 'none' : 'block';
    }

    function addAttrGroup(catId, list){
        if(!groupTemplate || !list) return;
        var key = ++groupKeyCounter;
        var html = groupTemplate.innerHTML
            .split('__CAT__').join(catId)
            .split('__KEY__').join(String(key));
        var wrap = document.createElement('div');
        wrap.innerHTML = html;
        var row = wrap.firstElementChild;
        if(!row) return;
        list.appendChild(row);
        updateNoGroupsMessage(list);
        var sortable = row.querySelector('.group-attr-sortable');
        if(sortable) bindGroupSortable(sortable);
        makeActiveGroup(row);
        updateCategorySummary(row.closest('.category-attr-panel'));
        markSettingsDirty();
    }

    function applyAiGroups(panel, suggestion){
        var list = panel.querySelector('.attr-groups-list');
        var catId = String(panel.getAttribute('data-cat-id') || '');
        if(!list || !catId) return;
        Array.prototype.slice.call(list.querySelectorAll('.attr-group-row')).forEach(function(row){ row.remove(); });

        (suggestion.groups || []).forEach(function(group){
            addAttrGroup(catId, list);
            var row = list.querySelector('.attr-group-row:last-of-type');
            if(!row) return;
            var nameInput = row.querySelector('.group-name-input');
            if(nameInput) nameInput.value = group.name || '';
            (group.attributes || []).forEach(function(id){
                var ref = panel.querySelector('.attr-ref-item[data-attr-id="' + String(id) + '"]');
                if(ref) addAttrToGroup(row, ref);
            });
        });

        updateNoGroupsMessage(list);
        updateCategorySummary(panel);
        var oldNote = panel.querySelector('.ai-groups-rationale');
        if(oldNote) oldNote.remove();
        if(suggestion.rationale){
            var note = document.createElement('div');
            note.className = 'alert alert-info py-2 px-3 mt-2 mb-0 small ai-groups-rationale';
            note.textContent = suggestion.rationale;
            list.parentNode.insertBefore(note, list);
        }
        markSettingsDirty();
    }

    function requestAiGroups(button){
        var panel = button.closest('.category-attr-panel');
        if(!panel) return;
        var list = panel.querySelector('.attr-groups-list');
        if(list && list.querySelector('.attr-group-row') && !window.confirm('سيستبدل الاقتراح الشرائح الحالية داخل النموذج. لن تُحفظ النتيجة حتى تضغط حفظ. هل تريد المتابعة؟')) return;

        var original = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جارٍ التحليل';
        fetch(pdfAiSuggestUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({category_id: panel.getAttribute('data-cat-id')})
        }).then(function(response){
            return response.json().then(function(data){ return {ok: response.ok, data: data}; });
        }).then(function(result){
            if(!result.ok || !result.data.success) throw new Error(result.data.message || 'تعذر توليد الاقتراح');
            applyAiGroups(panel, result.data.suggestion || {});
        }).catch(function(error){
            window.alert(error.message || 'تعذر الاتصال بخدمة الذكاء الاصطناعي.');
        }).finally(function(){
            button.disabled = false;
            button.innerHTML = original;
        });
    }

    var settingsNav = document.getElementById('settings-nav');
    if(settingsNav){
        var sectionIds = ['section-fonts','section-style','section-slides','section-attrs','section-groups'];
        function updateActiveNav(){
            var scrollY = window.scrollY || window.pageYOffset;
            var activeId = sectionIds[0];
            sectionIds.forEach(function(id){
                var el = document.getElementById(id);
                if(el && el.getBoundingClientRect().top <= 120){
                    activeId = id;
                }
            });
            Array.prototype.slice.call(settingsNav.querySelectorAll('a')).forEach(function(link){
                var isActive = (link.getAttribute('href') || '') === '#' + activeId;
                link.classList.toggle('active', isActive);
            });
        }
        window.addEventListener('scroll', updateActiveNav, {passive:true});
        updateActiveNav();
    }

    // Group / slide editor
    var activeGroupRow = null;

    function updateCategorySummary(panel){
        if(!panel) return;
        var badge = panel.querySelector('.category-groups-count');
        if(!badge) return;
        var slides = panel.querySelectorAll('.attr-group-row').length;
        var attributes = panel.querySelectorAll('.group-attr-sort-item').length;
        badge.textContent = slides + ' شريحة · ' + attributes + ' خاصية';
    }

    function syncGroupIds(groupRow){
        var sortable = groupRow.querySelector('.group-attr-sortable');
        var idsInput = groupRow.querySelector('.group-attr-ids');
        if(!sortable || !idsInput) return;
        var ids = Array.prototype.slice.call(sortable.querySelectorAll('.group-attr-sort-item')).map(function(el){
            return String(el.getAttribute('data-attr-id') || '');
        }).filter(Boolean);
        idsInput.value = ids.join(',');
        updateCategorySummary(groupRow.closest('.category-attr-panel'));
        markSettingsDirty();
    }

    function createAttrSortItem(id, name, key){
        var div = document.createElement('div');
        div.className = 'group-attr-sort-item pdf-sort-item';
        div.setAttribute('draggable', 'true');
        div.setAttribute('data-attr-id', id);
        div.innerHTML = '<div class="d-flex align-items-center gap-2 w-100">' +
            '<span class="pdf-drag" aria-hidden="true"><i class="fas fa-grip-vertical"></i></span>' +
            '<div class="flex-grow-1">' +
                '<div class="fw-bold">' + (name || 'خاصية') + '</div>' +
                '<div class="text-muted small">' + (key || '-') + ' · ID: ' + id + '</div>' +
            '</div>' +
            '<button type="button" class="btn btn-link text-danger p-0 remove-group-attr" title="إزالة" style="font-size:.9rem;">' +
                '<i class="fas fa-times"></i>' +
            '</button>' +
        '</div>';
        return div;
    }

    function addAttrToGroup(groupRow, refRow){
        var id = String(refRow.getAttribute('data-attr-id') || '');
        if(!id || !groupRow) return;
        var sortable = groupRow.querySelector('.group-attr-sortable');
        if(!sortable) return;
        var existing = sortable.querySelector('.group-attr-sort-item[data-attr-id="' + id + '"]');
        if(existing) return;

        var nameEl = refRow.querySelector('span.ms-2');
        var name = nameEl ? nameEl.textContent.trim() : 'خاصية';
        var keyEl = refRow.querySelector('.badge.bg-light');
        var key = keyEl ? keyEl.textContent.trim() : '-';

        var item = createAttrSortItem(id, name, key);
        sortable.appendChild(item);
        syncGroupIds(groupRow);
    }

    function makeActiveGroup(groupRow){
        activeGroupRow = groupRow;
        document.querySelectorAll('.attr-group-row').forEach(function(el){ el.classList.remove('group-row-active'); });
        document.querySelectorAll('.active-group-hint').forEach(function(h){ h.style.display = 'none'; });
        if(!groupRow) return;
        groupRow.classList.add('group-row-active');
        var hint = groupRow.querySelector('.active-group-hint');
        if(hint) hint.style.display = 'block';
    }

    var groupDragEl = null;
    var groupDragSourceRow = null;

    function bindGroupSortable(sortable){
        if(!sortable || sortable.getAttribute('data-sort-bound')) return;
        sortable.setAttribute('data-sort-bound', '1');

        sortable.addEventListener('dragstart', function(e){
            var item = e.target.closest('.group-attr-sort-item');
            if(!item) return;
            groupDragEl = item;
            groupDragSourceRow = sortable.closest('.attr-group-row');
            item.style.opacity = '0.6';
            e.dataTransfer.effectAllowed = 'move';
        });

        sortable.addEventListener('dragend', function(e){
            var item = e.target.closest('.group-attr-sort-item');
            if(item) item.style.opacity = '1';
            if(groupDragSourceRow) syncGroupIds(groupDragSourceRow);
            var targetRow = groupDragEl ? groupDragEl.closest('.attr-group-row') : null;
            if(targetRow && targetRow !== groupDragSourceRow) syncGroupIds(targetRow);
            groupDragEl = null;
            groupDragSourceRow = null;
        });

        sortable.addEventListener('dragover', function(e){
            e.preventDefault();
            if(!groupDragEl) return;
            var over = e.target.closest('.group-attr-sort-item');
            if(over && over !== groupDragEl){
                var rect = over.getBoundingClientRect();
                var next = (e.clientY - rect.top) > rect.height / 2;
                sortable.insertBefore(groupDragEl, next ? over.nextSibling : over);
            } else if(sortable === e.target || e.target === sortable){
                // drop on empty sortable
                sortable.appendChild(groupDragEl);
            }
        });
    }

    document.querySelectorAll('.group-attr-sortable').forEach(bindGroupSortable);

    if(catPanels){
        catPanels.addEventListener('click', function(e){
            var groupRow = e.target.closest('.attr-group-row');
            if(groupRow && !e.target.closest('.remove-attr-group') && !e.target.closest('.remove-group-attr')){
                makeActiveGroup(groupRow);
            }

            var aiBtn = e.target.closest('.ai-suggest-groups');
            if(aiBtn){
                requestAiGroups(aiBtn);
                return;
            }

            var addBtn = e.target.closest('.add-attr-group');
            if(addBtn){
                var catId = String(addBtn.getAttribute('data-cat-id') || '');
                var list = catId ? catPanels.querySelector('.attr-groups-list[data-cat-id="' + catId + '"]') : null;
                addAttrGroup(catId, list);
                return;
            }

            var removeBtn = e.target.closest('.remove-attr-group');
            if(removeBtn){
                var row = removeBtn.closest('.attr-group-row');
                var panel = row ? row.closest('.category-attr-panel') : null;
                var list = row ? row.closest('.attr-groups-list') : null;
                if(row){
                    if(row === activeGroupRow) activeGroupRow = null;
                    row.remove();
                }
                updateNoGroupsMessage(list);
                updateCategorySummary(panel);
                markSettingsDirty();
                return;
            }

            var removeItemBtn = e.target.closest('.remove-group-attr');
            if(removeItemBtn){
                var item = removeItemBtn.closest('.group-attr-sort-item');
                var gRow = item ? item.closest('.attr-group-row') : null;
                if(item) item.remove();
                if(gRow) syncGroupIds(gRow);
                return;
            }

            var refRow = e.target.closest('.attr-ref-item');
            if(refRow){
                var sourcePanel = refRow.closest('.category-attr-panel');
                var activePanel = activeGroupRow ? activeGroupRow.closest('.category-attr-panel') : null;
                if(!activeGroupRow || !sourcePanel || sourcePanel !== activePanel || sourcePanel.style.display === 'none'){
                    makeActiveGroup(null);
                    alert('اختر شريحة من الفئة الحالية أولاً.');
                    return;
                }
                addAttrToGroup(activeGroupRow, refRow);
            }
        });
    }
    // Attribute reference search
    document.querySelectorAll('.category-attr-panel').forEach(function(panel){
        var search = panel.querySelector('.attr-ref-search');
        var list = panel.querySelector('.attr-ref-list');
        if(!search || !list) return;
        search.addEventListener('input', function(){
            var q = (this.value || '').toLowerCase();
            Array.prototype.slice.call(list.querySelectorAll('.attr-ref-item')).forEach(function(row){
                row.style.display = (row.textContent || '').toLowerCase().indexOf(q) !== -1 ? 'flex' : 'none';
            });
        });
    });

    // Auto-load preview on first visit
    if(previewId && previewId.value){
        loadInlinePreview();
    }

    var pdfAiAuditBtn = document.getElementById('pdf_ai_audit');
    var pdfAiAuditResult = document.getElementById('pdf_ai_audit_result');
    if(pdfAiAuditBtn){
        pdfAiAuditBtn.addEventListener('click', function(){
            var productId = getPreviewId();
            if(!productId){
                window.alert('أدخل رقم العقار أولاً.');
                return;
            }
            var original = pdfAiAuditBtn.innerHTML;
            pdfAiAuditBtn.disabled = true;
            pdfAiAuditBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جارٍ الفحص';
            fetch(pdfAiAuditUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({product_id: productId})
            }).then(function(response){
                return response.json().then(function(data){ return {ok: response.ok, data: data}; });
            }).then(function(result){
                if(!result.ok || !result.data.success) throw new Error(result.data.message || 'تعذر فحص العقار');
                var audit = result.data.audit || {};
                pdfAiAuditResult.innerHTML = '';
                pdfAiAuditResult.style.display = 'block';
                var box = document.createElement('div');
                box.className = 'alert ' + (audit.ready ? 'alert-success' : 'alert-warning') + ' py-2 px-3 small mb-0';
                var title = document.createElement('div');
                title.className = 'fw-bold mb-1';
                title.textContent = 'درجة الجاهزية: ' + String(audit.score || 0) + '%';
                box.appendChild(title);
                var list = document.createElement('ul');
                list.className = 'mb-0 ps-3';
                (audit.issues || []).forEach(function(issue){
                    var item = document.createElement('li');
                    item.textContent = issue.message || '';
                    if(issue.severity === 'error') item.className = 'text-danger';
                    list.appendChild(item);
                });
                if(!(audit.issues || []).length){
                    var ready = document.createElement('div');
                    ready.textContent = 'العقار جاهز للتصدير.';
                    box.appendChild(ready);
                } else {
                    box.appendChild(list);
                }
                pdfAiAuditResult.appendChild(box);
            }).catch(function(error){
                window.alert(error.message || 'تعذر فحص العقار.');
            }).finally(function(){
                pdfAiAuditBtn.disabled = false;
                pdfAiAuditBtn.innerHTML = original;
            });
        });
    }

    // AJAX save
    var settingsForm = document.getElementById('pdf-settings-form');
    var saveStatus = document.getElementById('save-status');
    var saveState = document.getElementById('save-state');
    var hasUnsavedChanges = false;

    function setSaveState(state){
        hasUnsavedChanges = state !== 'saved';
        if(!saveState) return;
        var states = {
            saved: ['محفوظ', 'bg-success'],
            dirty: ['تعديلات غير محفوظة', 'bg-warning text-dark'],
            saving: ['جارٍ الحفظ...', 'bg-primary'],
            error: ['تعذر الحفظ', 'bg-danger']
        };
        var current = states[state] || states.saved;
        saveState.className = 'badge ' + current[1];
        saveState.textContent = current[0];
    }

    function markSettingsDirty(){
        setSaveState('dirty');
    }

    function showSaveStatus(message, isError){
        if(!saveStatus) return;
        saveStatus.className = 'alert mb-3 ' + (isError ? 'alert-danger' : 'alert-success');
        saveStatus.innerHTML = message;
        saveStatus.style.display = 'block';
        if(!isError){
            setTimeout(function(){ saveStatus.style.display = 'none'; }, 4000);
        }
    }

    function submitFormAjax(){
        if(!settingsForm) return;
        var buttons = settingsForm.querySelectorAll('[type="submit"]');
        buttons.forEach(function(btn){ btn.disabled = true; });
        setSaveState('saving');
        fetch(settingsForm.action, {
            method: 'POST',
            body: new FormData(settingsForm),
            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
        }).then(function(response){
            return response.json().catch(function(){ return {message: 'حدث خطأ غير متوقع'}; }).then(function(data){
                return {response: response, data: data};
            });
        }).then(function(result){
            var response = result.response;
            var data = result.data;
            if(response.ok && data.success){
                setSaveState('saved');
                showSaveStatus(data.message || 'تم الحفظ بنجاح', false);
                loadInlinePreview();
            } else if(data.errors){
                setSaveState('error');
                var errors = data.errors;
                var list = Object.keys(errors).map(function(k){ return '<li>' + (errors[k].join ? errors[k].join(' ') : errors[k]) + '</li>'; }).join('');
                showSaveStatus('<ul class="mb-0">' + list + '</ul>', true);
            } else {
                setSaveState('error');
                showSaveStatus(data.message || 'حدث خطأ أثناء الحفظ', true);
            }
        }).catch(function(err){
            setSaveState('error');
            showSaveStatus('حدث خطأ في الاتصال', true);
        }).finally(function(){
            buttons.forEach(function(btn){ btn.disabled = false; });
        });
    }

    if(settingsForm){
        settingsForm.addEventListener('submit', function(e){
            e.preventDefault();
            submitFormAjax();
        });
        settingsForm.addEventListener('input', function(e){
            if(e.target && e.target.name) markSettingsDirty();
        });
        settingsForm.addEventListener('change', function(e){
            if(e.target && e.target.name) markSettingsDirty();
        });
    }

    // Preview panel collapse
    var previewToggleBtn = document.querySelector('.toggle-preview');
    var previewCardBody = document.getElementById('previewCardBody');
    if(previewToggleBtn && previewCardBody){
        previewToggleBtn.addEventListener('click', function(){
            previewCardBody.classList.toggle('d-none');
            var icon = previewToggleBtn.querySelector('i');
            if(icon) icon.className = previewCardBody.classList.contains('d-none') ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
            previewToggleBtn.setAttribute('aria-expanded', previewCardBody.classList.contains('d-none') ? 'false' : 'true');
        });
    }

    // Section collapse
    document.querySelectorAll('#section-fonts > .card-header, #section-style > .card-header, #section-slides > .card-header, #section-attrs > .card-header, #section-groups > .card-header').forEach(function(header){
        header.classList.add('section-toggle', 'd-flex', 'align-items-center', 'justify-content-between');
        header.setAttribute('title', 'انقر لطي/فتح');
        var indicator = document.createElement('i');
        indicator.className = 'fas fa-chevron-up text-muted small';
        header.appendChild(indicator);
        header.addEventListener('click', function(){
            var card = header.closest('.card');
            var body = card ? card.querySelector('.card-body') : null;
            if(!body) return;
            body.classList.toggle('d-none');
            var collapsed = body.classList.contains('d-none');
            header.classList.toggle('collapsed', collapsed);
            indicator.className = collapsed ? 'fas fa-chevron-down text-muted small' : 'fas fa-chevron-up text-muted small';
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e){
        if((e.ctrlKey || e.metaKey) && e.key === 's'){
            e.preventDefault();
            submitFormAjax();
        }
    });

    window.addEventListener('beforeunload', function(e){
        if(!hasUnsavedChanges) return;
        e.preventDefault();
        e.returnValue = '';
    });
})();
</script>
@endpush
@endsection
