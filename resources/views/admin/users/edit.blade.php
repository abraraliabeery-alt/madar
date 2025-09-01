@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تعديل المستخدم - {{ $user->name }}</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">المعلومات الأساسية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    <small class="text-muted">اتركها فارغة إذا كنت لا تريد تغييرها</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات إضافية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">الدور <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                        <option value="">اختر الدور</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                                {{ $role->getTranslatedDisplayName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="facility_id" class="form-label">المنشأة</label>
                                    <select class="form-select @error('facility_id') is-invalid @enderror" id="facility_id" name="facility_id">
                                        <option value="">اختر المنشأة</option>
                                        @foreach($facilities as $facility)
                                            <option value="{{ $facility->id }}" {{ old('facility_id', $user->facilities->first()->id ?? '') == $facility->id ? 'selected' : '' }}>
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('facility_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bank_id" class="form-label">البنك</label>
                                    <select class="form-select @error('bank_id') is-invalid @enderror" id="bank_id" name="bank_id">
                                        <option value="">اختر البنك</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ old('bank_id', $user->bank_id) == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                                    <input type="text" class="form-control @error('bank_account') is-invalid @enderror" id="bank_account" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}">
                                    @error('bank_account')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="avatar" class="form-label">الصورة الشخصية</label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                                    @if($user->avatar)
                                        <div class="mt-2">
                                            <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded" width="100" id="avatar-preview">
                                        </div>
                                    @endif
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Information -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات التواصل الاجتماعي</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="facebook" class="form-label">فيسبوك</label>
                                        <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $user->facebook) }}">
                                        @error('facebook')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="twitter" class="form-label">تويتر</label>
                                        <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}">
                                        @error('twitter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="instagram" class="form-label">انستغرام</label>
                                        <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $user->instagram) }}">
                                        @error('instagram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="linkedin" class="form-label">لينكد إن</label>
                                        <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}">
                                        @error('linkedin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="whatsapp_number" class="form-label">رقم الواتساب</label>
                                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                                        @error('whatsapp_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="telegram" class="form-label">تيليجرام</label>
                                        <input type="text" class="form-control @error('telegram') is-invalid @enderror" id="telegram" name="telegram" value="{{ old('telegram', $user->telegram) }}">
                                        @error('telegram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">معلومات الموقع</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="latitude" class="form-label">خط العرض</label>
                                        <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="longitude" class="form-label">خط الطول</label>
                                        <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="google_maps_url" class="form-label">رابط خرائط جوجل</label>
                                        <input type="url" class="form-control @error('google_maps_url') is-invalid @enderror" id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url', $user->google_maps_url) }}">
                                        @error('google_maps_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Preview avatar image before upload
    $('#avatar').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatar-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Initialize select2 for better dropdown experience
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush
