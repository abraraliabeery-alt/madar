@props([
    'nameIcon' => 'icon_name',
    'nameImage' => 'icon',
    'valueIconName' => null,
    'labelIcon' => 'أيقونة Font Awesome (اختيارية)',
    'labelImage' => 'أيقونة كصورة (اختياري)',
    'currentImagePath' => null,
    'showCurrentImageLabel' => 'الأيقونة الحالية (صورة)',
    'imageHelpText' => 'يمكنك إما استخدام اسم أيقونة Font Awesome بالأعلى أو رفع صورة SVG/PNG صغيرة',
    'pickerButtonText' => 'استعراض الأيقونات', // لن يُستخدم حالياً بعد إزالة المودال
    'pickerTitle' => 'اختيار الأيقونة', // لن يُستخدم حالياً بعد إزالة المودال
    'searchPlaceholder' => 'ابحث باسم الأيقونة', // لن يُستخدم حالياً بعد إزالة المودال
    'modalId' => 'iconPickerModal-' . uniqid(), // لن يُستخدم حالياً بعد إزالة المودال
    'iconInputId' => null,
    'imageInputId' => null,
])

@php
    $iconInputId = $iconInputId ?: $nameIcon . '-' . uniqid();
    $imageInputId = $imageInputId ?: $nameImage . '-' . uniqid();
    $previewIconWrapperId = $iconInputId . '-preview-wrapper';
    $previewIconId = $iconInputId . '-preview-icon';
    $previewImageWrapperId = $imageInputId . '-preview-wrapper';
    $previewImageId = $imageInputId . '-preview-image';
    $searchInputId = $modalId . '-search';
    $resultsContainerId = $modalId . '-results';
@endphp

<div class="mb-3">
    <label class="form-label">{{ $labelIcon }}</label>
    <input
        type="hidden"
        id="{{ $iconInputId }}"
        name="{{ $nameIcon }}"
        value="{{ old($nameIcon, $valueIconName) }}"
    >
    <div id="{{ $previewIconWrapperId }}" class="mt-2" style="display: none;">
        <span class="text-muted d-block mb-1">معاينة الأيقونة المختارة:</span>
        <span id="{{ $previewIconId }}" class="fa-2x"></span>
    </div>
    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="window.aqarOpenIconPickerOverlay('{{ $modalId }}')">
        اختيار الأيقونة من المكتبة
    </button>
    @error($nameIcon)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<hr>

@if($currentImagePath)
    <div class="mb-3">
        <label class="form-label">{{ $showCurrentImageLabel }}</label>
        <div class="text-center">
            <img src="{{ asset($currentImagePath) }}" alt="Current Icon" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
        </div>
    </div>
@endif

<div class="mb-3">
    <label for="{{ $imageInputId }}" class="form-label">{{ $labelImage }}</label>
    <input
        type="file"
        class="form-control @error($nameImage) is-invalid @enderror"
        id="{{ $imageInputId }}"
        name="{{ $nameImage }}"
        accept="image/*"
    >
    <small class="form-text text-muted">{{ $imageHelpText }}</small>
    @error($nameImage)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div id="{{ $previewImageWrapperId }}" class="text-center" style="display: none;">
    <img id="{{ $previewImageId }}" src="" alt="Preview" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
</div>

<!-- Icon picker overlay (custom, not Bootstrap modal) -->
<div id="{{ $modalId }}" class="aqar-icon-overlay" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.45); z-index:1055;">
    <div class="aqar-icon-overlay-backdrop" style="width:100%; height:100%;" onclick="window.aqarCloseIconPickerOverlay(event, '{{ $modalId }}')">
        <div class="aqar-icon-overlay-panel bg-white rounded-3 shadow-lg" style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); max-width:860px; width:90%; max-height:90vh; display:flex; flex-direction:column; overflow:hidden;" onclick="event.stopPropagation()">
            <div class="border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold">اختيار الأيقونة</h5>
                <button type="button" class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:32px; height:32px;" onclick="window.aqarHideIconPickerOverlay('{{ $modalId }}')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="px-4 pt-3 pb-2">
                <div class="position-relative">
                    <span class="position-absolute top-50 translate-middle-y" style="left:0.75rem; color:#9ca3af;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control ps-5" id="{{ $searchInputId }}" placeholder="ابحث عن اسم الأيقونة...">
                </div>
            </div>

            <div class="px-4 pb-3" style="flex:1; overflow:hidden;">
                <div id="{{ $resultsContainerId }}" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap:12px; max-height:100%; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@once
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script>
    window.initAqarIconPickerInstance = function(config) {
        const iconInput = document.getElementById(config.iconInputId);
        const iconPreviewWrapper = document.getElementById(config.previewIconWrapperId);
        const iconPreviewIcon = document.getElementById(config.previewIconId);
        const imageInput = document.getElementById(config.imageInputId);
        const imagePreviewWrapper = document.getElementById(config.previewImageWrapperId);
        const imagePreview = document.getElementById(config.previewImageId);
        const searchInput = document.getElementById(config.searchInputId);
        const resultsContainer = document.getElementById(config.resultsContainerId);
        const modalElement = document.getElementById(config.modalId);
        if (!iconInput || !iconPreviewWrapper || !iconPreviewIcon || !imageInput || !imagePreviewWrapper || !imagePreview || !searchInput || !resultsContainer) {
            return;
        }

        // Ensure the modal is attached directly to <body> so it always appears as a full-page centered popup
        if (modalElement && modalElement.parentElement !== document.body) {
            document.body.appendChild(modalElement);
        }

        const isArabic = function(text) {
            return /[؀-ۿ]/.test(text);
        };

        const translateIfArabic = async function(text) {
            if (!isArabic(text)) {
                return text;
            }
            return text;
        };

        const updateIconPreview = function() {
            const value = (iconInput.value || '').trim();
            if (!value) {
                iconPreviewWrapper.style.display = 'none';
                iconPreviewIcon.removeAttribute('data-icon');
                iconPreviewIcon.className = '';
                return;
            }
            if (value.includes('fa-')) {
                iconPreviewIcon.removeAttribute('data-icon');
                iconPreviewIcon.className = value + ' fa-2x';
            } else {
                iconPreviewIcon.className = '';
                iconPreviewIcon.setAttribute('data-icon', value);
                iconPreviewIcon.style.fontSize = '28px';
            }
            iconPreviewWrapper.style.display = 'block';
            if (window.Iconify && typeof window.Iconify.scan === 'function') {
                window.Iconify.scan(iconPreviewWrapper);
            }
        };

        const renderIcons = function(list) {
            resultsContainer.innerHTML = '';
            list.forEach(function(icon) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = '<button type="button" class="w-100 h-100 border rounded-3 bg-white text-center px-2 py-2" \
                    style="cursor:pointer; transition:all 0.15s ease; font-size:11px;">\
                    <span class="iconify d-block mb-1" data-icon="' + icon + '" style="font-size:24px;"></span>\
                    <span class="d-block text-truncate" title="' + icon + '">' + icon + '</span>\
                </button>';
                const box = wrapper.firstElementChild;
                box.addEventListener('mouseenter', function() {
                    box.style.boxShadow = '0 0 0 1px rgba(37,99,235,0.35)';
                });
                box.addEventListener('mouseleave', function() {
                    box.style.boxShadow = 'none';
                });
                box.addEventListener('click', function() {
                    iconInput.value = icon;
                    updateIconPreview();
                });
                resultsContainer.appendChild(wrapper);
            });
            if (window.Iconify && typeof window.Iconify.scan === 'function') {
                window.Iconify.scan(resultsContainer);
            }
        };

        const searchIcons = async function(query) {
            if (!query || query.length < 2) {
                resultsContainer.innerHTML = '';
                return;
            }
            const translated = await translateIfArabic(query.trim());
            try {
                const res = await fetch('https://api.iconify.design/search?query=' + encodeURIComponent(translated));
                if (!res.ok) {
                    return;
                }
                const data = await res.json();
                const icons = Array.isArray(data.icons) ? data.icons.slice(0, 60) : [];
                renderIcons(icons);
            } catch (e) {
            }
        };

        iconInput.addEventListener('input', updateIconPreview);
        updateIconPreview();

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    imagePreview.src = ev.target.result;
                    imagePreviewWrapper.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreviewWrapper.style.display = 'none';
            }
        });

        let searchTimeout = null;
        searchInput.addEventListener('input', function() {
            const value = searchInput.value || '';
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            searchTimeout = setTimeout(function() {
                searchIcons(value);
            }, 400);
        });
    };

    window.aqarOpenIconPickerOverlay = function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = 'block';
    };

    window.aqarHideIconPickerOverlay = function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = 'none';
    };

    window.aqarCloseIconPickerOverlay = function(event, id) {
        if (event && event.target && event.target.classList.contains('aqar-icon-overlay-backdrop')) {
            window.aqarHideIconPickerOverlay(id);
        }
    };
</script>
@endonce
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.initAqarIconPickerInstance({
            modalId: @json($modalId),
            iconInputId: @json($iconInputId),
            imageInputId: @json($imageInputId),
            previewIconWrapperId: @json($previewIconWrapperId),
            previewIconId: @json($previewIconId),
            previewImageWrapperId: @json($previewImageWrapperId),
            previewImageId: @json($previewImageId),
            searchInputId: @json($searchInputId),
            resultsContainerId: @json($resultsContainerId),
        });
    });
</script>
@endpush
