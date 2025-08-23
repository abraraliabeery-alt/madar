@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إضافة منتج جديد</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">المعلومات الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                                <span class="input-group-text">ريال</span>
                                            </div>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facility_id" class="form-label">المنشأة <span class="text-danger">*</span></label>
                                            <select class="form-select @error('facility_id') is-invalid @enderror" id="facility_id" name="facility_id" required>
                                                <option value="">اختر المنشأة</option>
                                                @foreach($facilities as $facility)
                                                    <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                                        {{ $facility->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('facility_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">الفئة <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                                <option value="">اختر الفئة</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_id" class="form-label">الحالة <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                                                <option value="">اختر الحالة</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_user_id" class="form-label">المالك <span class="text-danger">*</span></label>
                                            <select class="form-select @error('owner_user_id') is-invalid @enderror" id="owner_user_id" name="owner_user_id" required>
                                                <option value="">اختر المالك</option>
                                                @foreach($facilities as $facility)
                                                    <option value="{{ $facility->owner->id }}" {{ old('owner_user_id') == $facility->owner->id ? 'selected' : '' }}>
                                                        {{ $facility->owner->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('owner_user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">الوصف</label>
                                            <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الوسائط</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="main_image" class="form-label">الصورة الرئيسية</label>
                                    <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image" accept="image/*">
                                    <small class="text-muted d-block mt-2">الأبعاد المثالية: 800x600 بكسل</small>
                                    <div class="mt-2" id="main-image-preview"></div>
                                    @error('main_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">المميزات</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($features as $feature)
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input" id="feature_{{ $feature->id }}" name="features[]" value="{{ $feature->id }}" {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                @if($feature->icon)
                                                    <img src="{{ asset($feature->icon) }}" alt="icon" width="20" class="me-1">
                                                @endif
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attributes -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الخصائص</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($attributes as $attribute)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="attribute_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>
                                            <input type="text" class="form-control" id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}][value]" value="{{ old('attributes.'.$attribute->id.'.value') }}">
                                            <input type="hidden" name="attributes[{{ $attribute->id }}][attribute_id]" value="{{ $attribute->id }}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">تفاصيل العقار</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="parking_spaces" class="form-label">عدد مواقف السيارات</label>
                                            <input type="number" class="form-control @error('parking_spaces') is-invalid @enderror" id="parking_spaces" name="parking_spaces" value="{{ old('parking_spaces') }}" min="0">
                                            @error('parking_spaces')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="furnished" name="furnished" value="1" {{ old('furnished') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="furnished">مفروش</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الموقع</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">العنوان <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">خط العرض</label>
                                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">خط الطول</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="google_maps_url" class="form-label">رابط خرائط جوجل</label>
                                    <input type="url" class="form-control @error('google_maps_url') is-invalid @enderror" id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url') }}">
                                    @error('google_maps_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2 d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">اختر الموقع على الخريطة</label>
                                    <small class="text-muted">انقر لتثبيت المؤشر أو اسحب المؤشر لتغيير الموقع</small>
                                </div>
                                <div id="mapPicker" class="w-100" style="height: 350px; border-radius: .5rem; overflow: hidden; background: #eef2ff; direction: ltr;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">الإعدادات</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">نشط</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">تم التحقق</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">مميز</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="available_for_rent" name="available_for_rent" value="1" {{ old('available_for_rent') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_for_rent">متاح للإيجار</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="available_for_sale" name="available_for_sale" value="1" {{ old('available_for_sale') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_for_sale">متاح للبيع</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
$(document).ready(function() {
    // Initialize summernote
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol']],
        ]
    });

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Preview main image
    $('#main_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#main-image-preview').html(`<img src="${e.target.result}" class="img-thumbnail" width="200">`);
            }
            reader.readAsDataURL(file);
        }
    });

    // Update owner based on facility
    $('#facility_id').change(function() {
        let facilityId = $(this).val();
        if (facilityId) {
            let option = $(this).find(`option[value="${facilityId}"]`);
            let ownerId = option.data('owner-id');
            $('#owner_user_id').val(ownerId).trigger('change');
        }
    });

    // Leaflet Map Picker
    (function initMapPicker() {
        const mapEl = document.getElementById('mapPicker');
        if (!mapEl || typeof L === 'undefined') return;

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const defaultLat = 24.7136; // Riyadh
        const defaultLng = 46.6753;

        const initialLat = latInput.value ? parseFloat(latInput.value) : defaultLat;
        const initialLng = lngInput.value ? parseFloat(lngInput.value) : defaultLng;
        const initialZoom = (latInput.value && lngInput.value) ? 14 : 11;

        const map = L.map('mapPicker', { scrollWheelZoom: true }).setView([initialLat, initialLng], initialZoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        function setInputs(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            setInputs(lat, lng);
        });

        marker.on('dragend', function(e) {
            const { lat, lng } = e.target.getLatLng();
            setInputs(lat, lng);
        });

        latInput.addEventListener('change', function() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value || defaultLng);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], map.getZoom());
            }
        });
        lngInput.addEventListener('change', function() {
            const lat = parseFloat(latInput.value || defaultLat);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], map.getZoom());
            }
        });
    })();
});
</script>
@endpush
