<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in the admin panel for various
    | messages, labels, and interface elements.
    |
    */

    'dashboard' => [
        'title' => 'لوحة التحكم',
        'welcome' => 'مرحباً بك في لوحة الإدارة',
        'total_users' => 'إجمالي المستخدمين',
        'total_products' => 'إجمالي المنتجات',
        'total_bookings' => 'إجمالي الحجوزات',
        'total_revenue' => 'إجمالي الإيرادات',
        'recent_activities' => 'الأنشطة الأخيرة',
        'quick_stats' => 'إحصائيات سريعة',
    ],

    'navigation' => [
        'dashboard' => 'لوحة التحكم',
        'users' => 'المستخدمين',
        'products' => 'المنتجات',
        'categories' => 'التصنيفات',
        'facilities' => 'المنشآت',
        'bookings' => 'الحجوزات',
        'contracts' => 'العقود',
        'reports' => 'التقارير',
        'settings' => 'الإعدادات',
        'profile' => 'الملف الشخصي',
        'logout' => 'تسجيل الخروج',
    ],

    'actions' => [
        'create' => 'إنشاء',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'view' => 'عرض',
        'save' => 'حفظ',
        'update' => 'تحديث',
        'cancel' => 'إلغاء',
        'back' => 'رجوع',
        'search' => 'بحث',
        'filter' => 'تصفية',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'approve' => 'موافقة',
        'reject' => 'رفض',
        'activate' => 'تفعيل',
        'deactivate' => 'إلغاء التفعيل',
    ],

    'messages' => [
        'created' => 'تم إنشاء :item بنجاح!',
        'updated' => 'تم تحديث :item بنجاح!',
        'deleted' => 'تم حذف :item بنجاح!',
        'error' => 'حدث خطأ. يرجى المحاولة مرة أخرى.',
        'confirm_delete' => 'هل أنت متأكد من حذف هذا :item؟',
        'no_records' => 'لا توجد سجلات.',
        'loading' => 'جاري التحميل...',
        'saving' => 'جاري الحفظ...',
        'deleting' => 'جاري الحذف...',
    ],

    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'في الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'draft' => 'مسودة',
        'published' => 'منشور',
    ],

    'users' => [
        'title' => 'إدارة المستخدمين',
        'create' => 'إنشاء مستخدم',
        'edit' => 'تعديل مستخدم',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'role' => 'الدور',
        'status' => 'الحالة',
        'created_at' => 'تاريخ الإنشاء',
        'actions' => 'الإجراءات',
        'profile' => 'الملف الشخصي للمستخدم',
        'permissions' => 'الصلاحيات',
        'change_password' => 'تغيير كلمة المرور',
    ],

    'products' => [
        'title' => 'إدارة المنتجات',
        'create' => 'إنشاء منتج',
        'edit' => 'تعديل منتج',
        'name' => 'اسم المنتج',
        'description' => 'الوصف',
        'price' => 'السعر',
        'category' => 'الفئة',
        'facility' => 'المنشأة',
        'status' => 'الحالة',
        'images' => 'الصور',
        'features' => 'المميزات',
        'attributes' => 'الخصائص',
    ],

    'categories' => [
        'title' => 'إدارة التصنيفات',
        'create' => 'إنشاء تصنيف',
        'edit' => 'تعديل تصنيف',
        'name' => 'اسم التصنيف',
        'description' => 'الوصف',
        'icon' => 'الأيقونة',
        'image' => 'الصورة',
        'parent' => 'التصنيف الأب',
        'status' => 'الحالة',
    ],

    'facilities' => [
        'title' => 'إدارة المنشآت',
        'create' => 'إنشاء منشأة',
        'edit' => 'تعديل منشأة',
        'name' => 'اسم المنشأة',
        'description' => 'الوصف',
        'address' => 'العنوان',
        'phone' => 'الهاتف',
        'email' => 'البريد الإلكتروني',
        'website' => 'الموقع الإلكتروني',
        'logo' => 'الشعار',
        'banner' => 'اللافتة',
        'status' => 'الحالة',
    ],

    'bookings' => [
        'title' => 'إدارة الحجوزات',
        'create' => 'إنشاء حجز',
        'edit' => 'تعديل حجز',
        'user' => 'المستخدم',
        'product' => 'المنتج',
        'facility' => 'المنشأة',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'status' => 'الحالة',
        'amount' => 'المبلغ',
        'notes' => 'الملاحظات',
    ],

    'reports' => [
        'title' => 'التقارير',
        'sales' => 'تقرير المبيعات',
        'users' => 'تقرير المستخدمين',
        'products' => 'تقرير المنتجات',
        'bookings' => 'تقرير الحجوزات',
        'date_range' => 'نطاق التاريخ',
        'generate' => 'إنشاء تقرير',
        'export' => 'تصدير التقرير',
        'no_data' => 'لا توجد بيانات متاحة للفترة المحددة.',
    ],

    'settings' => [
        'title' => 'الإعدادات',
        'general' => 'الإعدادات العامة',
        'email' => 'إعدادات البريد الإلكتروني',
        'payment' => 'إعدادات الدفع',
        'notification' => 'إعدادات الإشعارات',
        'save' => 'حفظ الإعدادات',
        'saved' => 'تم حفظ الإعدادات بنجاح!',
    ],

];
