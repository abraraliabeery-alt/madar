@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تعديل الملف الشخصي</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- الصورة الشخصية -->
                            <div class="col-12 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="avatar" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                            <span class="display-4">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <label for="avatar" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle" style="width: 35px; height: 35px;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                </div>
                                @error('avatar')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp_number" class="form-label">رقم الواتساب</label>
                                    <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                                    @error('whatsapp_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- وسائل التواصل الاجتماعي -->
                            <div class="col-12">
                                <h6 class="mb-3">وسائل التواصل الاجتماعي</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook" class="form-label">فيسبوك</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                        <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $user->facebook) }}">
                                    </div>
                                    @error('facebook')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="twitter" class="form-label">تويتر</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}">
                                    </div>
                                    @error('twitter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram" class="form-label">انستغرام</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $user->instagram) }}">
                                    </div>
                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="linkedin" class="form-label">لينكد إن</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                        <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}">
                                    </div>
                                    @error('linkedin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="youtube" class="form-label">يوتيوب</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                        <input type="url" class="form-control @error('youtube') is-invalid @enderror" id="youtube" name="youtube" value="{{ old('youtube', $user->youtube) }}">
                                    </div>
                                    @error('youtube')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="snapchat" class="form-label">سناب شات</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-snapchat"></i></span>
                                        <input type="text" class="form-control @error('snapchat') is-invalid @enderror" id="snapchat" name="snapchat" value="{{ old('snapchat', $user->snapchat) }}">
                                    </div>
                                    @error('snapchat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- تغيير كلمة المرور -->
                            <div class="col-12">
                                <h6 class="mb-3">تغيير كلمة المرور</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>

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
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // معاينة الصورة الشخصية
    $('#avatar').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let img = $('<img>').attr({
                    'src': e.target.result,
                    'class': 'rounded-circle img-thumbnail',
                    'style': 'width: 150px; height: 150px; object-fit: cover;'
                });
                $('.position-relative').find('img, div.rounded-circle').replaceWith(img);
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
