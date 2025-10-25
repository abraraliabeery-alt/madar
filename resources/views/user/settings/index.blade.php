@extends('layouts.app')

@section('title', 'إعدادات المستخدم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">إعدادات المستخدم</li>
                    </ol>
                </div>
                <h4 class="page-title">إعدادات المستخدم</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Settings -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">الملف الشخصي</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : asset('assets/images/default-avatar.png') }}" 
                             alt="صورة الملف الشخصي" 
                             class="rounded-circle" 
                             width="100" 
                             height="100">
                    </div>
                    
                    <form action="{{ route('user.settings.profile-picture') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">تغيير صورة الملف الشخصي</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">تحديث الصورة</button>
                    </form>
                    
                    <form action="{{ route('user.settings.delete-profile-picture') }}" method="POST" class="mb-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف صورة الملف الشخصي؟')">حذف الصورة</button>
                    </form>
                    
                    <div class="text-center">
                        <h6>{{ $user->name }}</h6>
                        <p class="text-muted">{{ $user->email }}</p>
                        <small class="text-muted">عضو منذ {{ $user->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tabs -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                                الإشعارات
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab">
                                الخصوصية
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                                التفضيلات
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                الأمان
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                                النشاط
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- Notifications Tab -->
                        <div class="tab-pane fade show active" id="notifications" role="tabpanel">
                            <form action="{{ route('user.settings.notifications') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>إعدادات الإشعارات</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                                   {{ ($notificationSettings['email_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_notifications">الإشعارات عبر البريد الإلكتروني</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" 
                                                   {{ ($notificationSettings['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sms_notifications">الإشعارات عبر الرسائل النصية</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="push_notifications" name="push_notifications" 
                                                   {{ ($notificationSettings['push_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="push_notifications">الإشعارات الفورية</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="marketing_emails" name="marketing_emails" 
                                                   {{ ($notificationSettings['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="marketing_emails">رسائل التسويق</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>أنواع الإشعارات</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="property_updates" name="property_updates" 
                                                   {{ ($notificationSettings['property_updates'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="property_updates">تحديثات العقارات</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="booking_updates" name="booking_updates" 
                                                   {{ ($notificationSettings['booking_updates'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="booking_updates">تحديثات الحجوزات</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="payment_updates" name="payment_updates" 
                                                   {{ ($notificationSettings['payment_updates'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="payment_updates">تحديثات المدفوعات</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="system_updates" name="system_updates" 
                                                   {{ ($notificationSettings['system_updates'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="system_updates">تحديثات النظام</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="notification_frequency" class="form-label">تكرار الإشعارات</label>
                                            <select class="form-select" id="notification_frequency" name="notification_frequency">
                                                <option value="immediate" {{ ($notificationSettings['notification_frequency'] ?? 'immediate') == 'immediate' ? 'selected' : '' }}>فوري</option>
                                                <option value="daily" {{ ($notificationSettings['notification_frequency'] ?? 'immediate') == 'daily' ? 'selected' : '' }}>يومي</option>
                                                <option value="weekly" {{ ($notificationSettings['notification_frequency'] ?? 'immediate') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                                <option value="monthly" {{ ($notificationSettings['notification_frequency'] ?? 'immediate') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quiet_hours_start" class="form-label">ساعات الهدوء - من</label>
                                            <input type="time" class="form-control" id="quiet_hours_start" name="quiet_hours_start" 
                                                   value="{{ $notificationSettings['quiet_hours_start'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">حفظ إعدادات الإشعارات</button>
                            </form>
                        </div>

                        <!-- Privacy Tab -->
                        <div class="tab-pane fade" id="privacy" role="tabpanel">
                            <form action="{{ route('user.settings.privacy') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>إعدادات الخصوصية</h6>
                                        <div class="mb-3">
                                            <label for="profile_visibility" class="form-label">رؤية الملف الشخصي</label>
                                            <select class="form-select" id="profile_visibility" name="profile_visibility">
                                                <option value="public" {{ ($privacySettings['profile_visibility'] ?? 'public') == 'public' ? 'selected' : '' }}>عام</option>
                                                <option value="private" {{ ($privacySettings['profile_visibility'] ?? 'public') == 'private' ? 'selected' : '' }}>خاص</option>
                                                <option value="friends_only" {{ ($privacySettings['profile_visibility'] ?? 'public') == 'friends_only' ? 'selected' : '' }}>الأصدقاء فقط</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="show_email" name="show_email" 
                                                   {{ ($privacySettings['show_email'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_email">إظهار البريد الإلكتروني</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="show_phone" name="show_phone" 
                                                   {{ ($privacySettings['show_phone'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_phone">إظهار رقم الهاتف</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="show_location" name="show_location" 
                                                   {{ ($privacySettings['show_location'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_location">إظهار الموقع</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>إعدادات إضافية</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="show_activity" name="show_activity" 
                                                   {{ ($privacySettings['show_activity'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_activity">إظهار النشاط</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="allow_messages" name="allow_messages" 
                                                   {{ ($privacySettings['allow_messages'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_messages">السماح بالرسائل</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="allow_friend_requests" name="allow_friend_requests" 
                                                   {{ ($privacySettings['allow_friend_requests'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_friend_requests">السماح بطلبات الصداقة</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="search_visibility" name="search_visibility" 
                                                   {{ ($privacySettings['search_visibility'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="search_visibility">إظهار في البحث</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="data_sharing" name="data_sharing" 
                                                   {{ ($privacySettings['data_sharing'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="data_sharing">مشاركة البيانات</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="analytics_tracking" name="analytics_tracking" 
                                                   {{ ($privacySettings['analytics_tracking'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="analytics_tracking">تتبع التحليلات</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">حفظ إعدادات الخصوصية</button>
                            </form>
                        </div>

                        <!-- Preferences Tab -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <form action="{{ route('user.settings.preferences') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>الإعدادات العامة</h6>
                                        <div class="mb-3">
                                            <label for="language" class="form-label">اللغة</label>
                                            <select class="form-select" id="language" name="language">
                                                <option value="ar" {{ ($preferences['language'] ?? 'ar') == 'ar' ? 'selected' : '' }}>العربية</option>
                                                <option value="en" {{ ($preferences['language'] ?? 'ar') == 'en' ? 'selected' : '' }}>English</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">المنطقة الزمنية</label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <option value="Asia/Riyadh" {{ ($preferences['timezone'] ?? 'Asia/Riyadh') == 'Asia/Riyadh' ? 'selected' : '' }}>الرياض</option>
                                                <option value="Asia/Dubai" {{ ($preferences['timezone'] ?? 'Asia/Riyadh') == 'Asia/Dubai' ? 'selected' : '' }}>دبي</option>
                                                <option value="Asia/Kuwait" {{ ($preferences['timezone'] ?? 'Asia/Riyadh') == 'Asia/Kuwait' ? 'selected' : '' }}>الكويت</option>
                                                <option value="Asia/Bahrain" {{ ($preferences['timezone'] ?? 'Asia/Riyadh') == 'Asia/Bahrain' ? 'selected' : '' }}>البحرين</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="date_format" class="form-label">تنسيق التاريخ</label>
                                            <select class="form-select" id="date_format" name="date_format">
                                                <option value="Y-m-d" {{ ($preferences['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>2024-01-01</option>
                                                <option value="d-m-Y" {{ ($preferences['date_format'] ?? 'Y-m-d') == 'd-m-Y' ? 'selected' : '' }}>01-01-2024</option>
                                                <option value="m/d/Y" {{ ($preferences['date_format'] ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>01/01/2024</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="time_format" class="form-label">تنسيق الوقت</label>
                                            <select class="form-select" id="time_format" name="time_format">
                                                <option value="12" {{ ($preferences['time_format'] ?? '12') == '12' ? 'selected' : '' }}>12 ساعة</option>
                                                <option value="24" {{ ($preferences['time_format'] ?? '12') == '24' ? 'selected' : '' }}>24 ساعة</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>إعدادات العرض</h6>
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">العملة</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="SAR" {{ ($preferences['currency'] ?? 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                                <option value="AED" {{ ($preferences['currency'] ?? 'SAR') == 'AED' ? 'selected' : '' }}>درهم إماراتي</option>
                                                <option value="KWD" {{ ($preferences['currency'] ?? 'SAR') == 'KWD' ? 'selected' : '' }}>دينار كويتي</option>
                                                <option value="BHD" {{ ($preferences['currency'] ?? 'SAR') == 'BHD' ? 'selected' : '' }}>دينار بحريني</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="theme" class="form-label">المظهر</label>
                                            <select class="form-select" id="theme" name="theme">
                                                <option value="light" {{ ($preferences['theme'] ?? 'light') == 'light' ? 'selected' : '' }}>فاتح</option>
                                                <option value="dark" {{ ($preferences['theme'] ?? 'light') == 'dark' ? 'selected' : '' }}>داكن</option>
                                                <option value="auto" {{ ($preferences['theme'] ?? 'light') == 'auto' ? 'selected' : '' }}>تلقائي</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="dashboard_layout" class="form-label">تخطيط لوحة التحكم</label>
                                            <select class="form-select" id="dashboard_layout" name="dashboard_layout">
                                                <option value="grid" {{ ($preferences['dashboard_layout'] ?? 'grid') == 'grid' ? 'selected' : '' }}>شبكة</option>
                                                <option value="list" {{ ($preferences['dashboard_layout'] ?? 'grid') == 'list' ? 'selected' : '' }}>قائمة</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="items_per_page" class="form-label">عدد العناصر في الصفحة</label>
                                            <select class="form-select" id="items_per_page" name="items_per_page">
                                                <option value="10" {{ ($preferences['items_per_page'] ?? '10') == '10' ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ ($preferences['items_per_page'] ?? '10') == '25' ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ ($preferences['items_per_page'] ?? '10') == '50' ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ ($preferences['items_per_page'] ?? '10') == '100' ? 'selected' : '' }}>100</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="default_view" class="form-label">العرض الافتراضي</label>
                                            <select class="form-select" id="default_view" name="default_view">
                                                <option value="list" {{ ($preferences['default_view'] ?? 'list') == 'list' ? 'selected' : '' }}>قائمة</option>
                                                <option value="grid" {{ ($preferences['default_view'] ?? 'list') == 'grid' ? 'selected' : '' }}>شبكة</option>
                                                <option value="map" {{ ($preferences['default_view'] ?? 'list') == 'map' ? 'selected' : '' }}>خريطة</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sort_preference" class="form-label">ترتيب التفضيل</label>
                                            <select class="form-select" id="sort_preference" name="sort_preference">
                                                <option value="newest" {{ ($preferences['sort_preference'] ?? 'newest') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                                <option value="oldest" {{ ($preferences['sort_preference'] ?? 'newest') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                                                <option value="price_asc" {{ ($preferences['sort_preference'] ?? 'newest') == 'price_asc' ? 'selected' : '' }}>السعر من الأقل للأعلى</option>
                                                <option value="price_desc" {{ ($preferences['sort_preference'] ?? 'newest') == 'price_desc' ? 'selected' : '' }}>السعر من الأعلى للأقل</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">حفظ التفضيلات</button>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>إعدادات الأمان</h6>
                                    <form action="{{ route('user.settings.security') }}" method="POST">
                                        @csrf
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="two_factor_enabled" name="two_factor_enabled" 
                                                   {{ ($securitySettings['two_factor_enabled'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="two_factor_enabled">المصادقة الثنائية</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="login_notifications" name="login_notifications" 
                                                   {{ ($securitySettings['login_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="login_notifications">إشعارات تسجيل الدخول</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="password_change_notifications" name="password_change_notifications" 
                                                   {{ ($securitySettings['password_change_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="password_change_notifications">إشعارات تغيير كلمة المرور</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="suspicious_activity_alerts" name="suspicious_activity_alerts" 
                                                   {{ ($securitySettings['suspicious_activity_alerts'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="suspicious_activity_alerts">تنبيهات النشاط المشبوه</label>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="session_timeout" class="form-label">انتهاء الجلسة (دقيقة)</label>
                                            <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                                   value="{{ $securitySettings['session_timeout'] ?? 120 }}" min="15" max="480">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="max_login_attempts" class="form-label">أقصى محاولات تسجيل الدخول</label>
                                            <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                                                   value="{{ $securitySettings['max_login_attempts'] ?? 5 }}" min="3" max="10">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="lockout_duration" class="form-label">مدة الحظر (دقيقة)</label>
                                            <input type="number" class="form-control" id="lockout_duration" name="lockout_duration" 
                                                   value="{{ $securitySettings['lockout_duration'] ?? 15 }}" min="5" max="60">
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">حفظ إعدادات الأمان</button>
                                    </form>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>تغيير كلمة المرور</h6>
                                    <form action="{{ route('user.settings.change-password') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                                        </div>
                                        <button type="submit" class="btn btn-warning">تغيير كلمة المرور</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>سجل النشاط</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>النشاط</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($activityLogs as $log)
                                                <tr>
                                                    <td>{{ $log->description }}</td>
                                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center">لا توجد أنشطة</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <form action="{{ route('user.settings.clear-activity-logs') }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من مسح سجل النشاط؟')">مسح سجل النشاط</button>
                                    </form>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>سجل تسجيل الدخول</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>عنوان IP</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($loginHistory as $login)
                                                <tr>
                                                    <td>{{ $login->ip_address }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($login->created_at)->diffForHumans() }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center">لا توجد سجلات</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>إدارة البيانات</h6>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('user.settings.export-data') }}" class="btn btn-info">تصدير البيانات</a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">حذف الحساب</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">حذف الحساب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>تحذير!</strong> هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع بياناتك نهائياً.
                </div>
                <form action="{{ route('user.settings.delete-account') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmation" class="form-label">اكتب "DELETE" للتأكيد</label>
                        <input type="text" class="form-control" id="confirmation" name="confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-danger">حذف الحساب نهائياً</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save form data
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('change', function() {
                // Auto-save logic can be implemented here
            });
        });
    });
</script>
@endpush
