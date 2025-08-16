@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog ms-2"></i>إعدادات الإشعارات
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notifications.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">أنواع الإشعارات</h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_email" name="notification_email" value="1" {{ $user->notification_email ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_email">
                                        <i class="fas fa-envelope ms-2"></i>إشعارات البريد الإلكتروني
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_sms" name="notification_sms" value="1" {{ $user->notification_sms ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_sms">
                                        <i class="fas fa-sms ms-2"></i>إشعارات الرسائل النصية
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="notification_push" name="notification_push" value="1" {{ $user->notification_push ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notification_push">
                                        <i class="fas fa-bell ms-2"></i>إشعارات الموقع
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3">توقيت الإشعارات</h6>
                                
                                <div class="mb-3">
                                    <label for="notification_frequency" class="form-label">تكرار الإشعارات</label>
                                    <select class="form-select" id="notification_frequency" name="notification_frequency">
                                        <option value="immediate" {{ $user->notification_frequency === 'immediate' ? 'selected' : '' }}>فوري</option>
                                        <option value="daily" {{ $user->notification_frequency === 'daily' ? 'selected' : '' }}>يومي</option>
                                        <option value="weekly" {{ $user->notification_frequency === 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                    </select>
                                    <div class="form-text">اختر متى تريد استلام الإشعارات</div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle ms-2"></i>
                                    <strong>ملاحظة:</strong> الإشعارات الفورية ستظهر لك فور حدوثها، بينما الإشعارات اليومية والأسبوعية ستجمع في تقرير واحد.
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">أنواع الإشعارات المتاحة</h6>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                                <h6>مستخدمين جدد</h6>
                                                <small class="text-muted">إشعارات عند تسجيل مستخدمين جدد</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-success">
                                            <div class="card-body text-center">
                                                <i class="fas fa-building fa-2x text-success mb-2"></i>
                                                <h6>منشآت جديدة</h6>
                                                <small class="text-muted">إشعارات عند إضافة منشآت جديدة</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-info">
                                            <div class="card-body text-center">
                                                <i class="fas fa-box fa-2x text-info mb-2"></i>
                                                <h6>منتجات جديدة</h6>
                                                <small class="text-muted">إشعارات عند إضافة منتجات جديدة</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="card border-warning">
                                            <div class="card-body text-center">
                                                <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                                                <h6>حجوزات جديدة</h6>
                                                <small class="text-muted">إشعارات عند إنشاء حجوزات جديدة</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-danger">
                                            <div class="card-body text-center">
                                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                                <h6>تنبيهات النظام</h6>
                                                <small class="text-muted">إشعارات مهمة عن النظام</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-secondary">
                                            <div class="card-body text-center">
                                                <i class="fas fa-chart-line fa-2x text-secondary mb-2"></i>
                                                <h6>تقارير دورية</h6>
                                                <small class="text-muted">إشعارات التقارير والإحصائيات</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.notifications') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-right ms-1"></i>رجوع
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ms-1"></i>حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card.border-primary,
.card.border-success,
.card.border-info,
.card.border-warning,
.card.border-danger,
.card.border-secondary {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card.border-primary:hover,
.card.border-success:hover,
.card.border-info:hover,
.card.border-warning:hover,
.card.border-danger:hover,
.card.border-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
@endsection
