@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إعدادات النظام</h5>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <!-- Site Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">معلومات الموقع</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">اسم الموقع <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', \App\Models\Setting::getValue('site_name', config('app.name'))) }}" required>
                                    @error('site_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_url" class="form-label">رابط الموقع <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('site_url') is-invalid @enderror" id="site_url" name="site_url" value="{{ old('site_url', \App\Models\Setting::getValue('site_url', config('app.url'))) }}" required>
                                    @error('site_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="site_description" class="form-label">وصف الموقع</label>
                                    <textarea class="form-control summernote @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="4">{{ old('site_description', \App\Models\Setting::getValue('site_description')) }}</textarea>
                                    @error('site_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">معلومات الاتصال</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', \App\Models\Setting::getValue('contact_email')) }}" required>
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">رقم الهاتف</label>
                                    <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', \App\Models\Setting::getValue('contact_phone')) }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_address" class="form-label">العنوان</label>
                                    <textarea class="form-control @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" rows="3">{{ old('contact_address', \App\Models\Setting::getValue('contact_address')) }}</textarea>
                                    @error('contact_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="working_hours" class="form-label">ساعات العمل</label>
                                    <textarea class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" rows="3">{{ old('working_hours', \App\Models\Setting::getValue('working_hours')) }}</textarea>
                                    @error('working_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">وسائل التواصل الاجتماعي</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook_url" class="form-label">فيسبوك</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                        <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', \App\Models\Setting::getValue('facebook_url')) }}">
                                    </div>
                                    @error('facebook_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="twitter_url" class="form-label">تويتر</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', \App\Models\Setting::getValue('twitter_url')) }}">
                                    </div>
                                    @error('twitter_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram_url" class="form-label">انستغرام</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', \App\Models\Setting::getValue('instagram_url')) }}">
                                    </div>
                                    @error('instagram_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="linkedin_url" class="form-label">لينكد إن</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                        <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', \App\Models\Setting::getValue('linkedin_url')) }}">
                                    </div>
                                    @error('linkedin_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp_number" class="form-label">رقم الواتساب</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', \App\Models\Setting::getValue('whatsapp_number')) }}" placeholder="+966501234567">
                                    </div>
                                    <small class="form-text text-muted">أدخل رقم الواتساب مع رمز الدولة (مثال: +966501234567)</small>
                                    @error('whatsapp_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">إعدادات النظام</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', \App\Models\Setting::getValue('maintenance_mode')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="maintenance_mode">وضع الصيانة</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="allow_registration" name="allow_registration" value="1" {{ old('allow_registration', \App\Models\Setting::getValue('allow_registration', '1')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_registration">السماح بالتسجيل</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="email_verification" name="email_verification" value="1" {{ old('email_verification', \App\Models\Setting::getValue('email_verification', '1')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verification">تفعيل التحقق من البريد الإلكتروني</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="auto_approve_facilities" name="auto_approve_facilities" value="1" {{ old('auto_approve_facilities', \App\Models\Setting::getValue('auto_approve_facilities')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_approve_facilities">الموافقة التلقائية على المنشآت</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
});
</script>
@endpush
