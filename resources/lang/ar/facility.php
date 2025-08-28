<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Facility Translation Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in facility management pages
    |
    */

    // Common
    'form' => [
        'name' => 'اسم المنشأة',
        'category' => 'الفئة',
        'select_category' => 'اختر الفئة',
        'description' => 'وصف المنشأة',
        'address' => 'العنوان',
        'phone' => 'رقم الهاتف',
        'email' => 'البريد الإلكتروني',
        'website' => 'الموقع الإلكتروني',
        'latitude' => 'خط العرض',
        'longitude' => 'خط الطول',
        'logo' => 'شعار المنشأة',
        'current_logo' => 'الشعار الحالي',
        'cover_image' => 'صورة الغلاف',
        'current_cover' => 'صورة الغلاف الحالية',
        'whatsapp' => 'رقم الواتساب',
        'working_hours' => 'ساعات العمل',
        'working_hours_placeholder' => 'مثال: 9:00 ص - 6:00 م',
        'google_maps' => 'رابط خرائط جوجل',
        'image_help' => 'يُسمح بملفات الصور: JPG, PNG, GIF. الحد الأقصى: 2MB',
        'cancel' => 'إلغاء',
        'create' => 'إنشاء المنشأة',
        'save_changes' => 'حفظ التعديلات',
    ],

    // Create Page
    'create' => [
        'title' => 'إنشاء منشأة جديدة',
    ],

    // Edit Page
    'edit' => [
        'title' => 'تعديل المنشأة',
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'لوحة تحكم المنشأة',
        'stats_title' => 'إحصائيات سريعة',
        'total_products' => 'إجمالي المنتجات',
        'total_bookings' => 'إجمالي الحجوزات',
        'pending_bookings' => 'الحجوزات المعلقة',
        'total_tasks' => 'إجمالي المهام',
        'recent_activity' => 'الحجوزات الحديثة',
        'recent_bookings' => 'أحدث الحجوزات',
        'no_recent_bookings' => 'لا توجد حجوزات حديثة',
        'recent_tasks' => 'أحدث المهام',
        'no_recent_tasks' => 'لا توجد مهام حديثة',
        'assigned_to' => 'مُسند إلى',
        'unassigned' => 'غير محدد',
        'facility_info' => 'معلومات المنشأة',
        'user_avatar' => 'صورة المستخدم',
        'deleted_product' => 'منتج محذوف',
        'edit_facility' => 'تعديل المنشأة',
        'manage_products' => 'إدارة المنتجات',
    ],

    // Products Management
    'products' => [
        'title' => 'إدارة المنتجات',
        'subtitle' => 'إدارة وعرض منتجات منشأتك',
        'add_new' => 'إضافة منتج جديد',
        'search' => 'البحث',
        'search_placeholder' => 'ابحث في اسم المنتج أو الوصف أو العنوان...',
        'category' => 'الفئة',
        'all_categories' => 'جميع الفئات',
        'status' => 'الحالة',
        'all_statuses' => 'جميع الحالات',
        'search_button' => 'بحث',
        'clear_filters' => 'مسح الفلاتر',
        'showing_results' => 'عرض :first إلى :last من أصل :total منتج',
        'filtered' => 'مفلتر',
        'product' => 'المنتج',
        'price' => 'السعر',
        'created_at' => 'تاريخ الإنشاء',
        'actions' => 'الإجراءات',
        'unspecified' => 'غير محدد',
        'price_not_set' => 'السعر غير محدد',
        'view_details' => 'عرض التفاصيل',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'confirm_delete' => 'هل أنت متأكد من حذف هذا المنتج؟',
        'no_products' => 'لا توجد منتجات',
        'no_search_results' => 'لم يتم العثور على منتجات تطابق معايير البحث المحددة.',
        'no_products_yet' => 'لم تقم بإضافة أي منتجات حتى الآن.',
        'view_all' => 'عرض جميع المنتجات',
        
        // Create Product
        'create' => [
            'title' => 'إضافة منتج جديد',
            'basic_info' => 'المعلومات الأساسية',
            'name' => 'اسم المنتج',
            'category' => 'الفئة',
            'select_category' => 'اختر الفئة',
            'description' => 'وصف المنتج',
            'price' => 'السعر',
            'status' => 'الحالة',
            'select_status' => 'اختر الحالة',
            'location_info' => 'معلومات الموقع',
            'address' => 'العنوان',
            'latitude' => 'خط العرض',
            'longitude' => 'خط الطول',
            'google_maps' => 'رابط خرائط جوجل',
            'property_details' => 'تفاصيل العقار',
            'bedrooms' => 'غرف النوم',
            'bathrooms' => 'دورات المياه',
            'area' => 'المساحة',
            'parking' => 'مواقف السيارات',
            'floor_number' => 'رقم الطابق',
            'total_floors' => 'إجمالي الطوابق',
            'owner' => 'المالك',
            'select_owner' => 'اختر المالك',
            'me' => 'أنا',
            'media' => 'الوسائط',
            'main_image' => 'الصورة الرئيسية',
            'features_options' => 'المميزات والخيارات',
            'furnished' => 'مفروش',
            'for_rent' => 'متاح للإيجار',
            'for_sale' => 'متاح للبيع',
            'featured' => 'مميز',
            'features' => 'المميزات',
            'create_product' => 'إضافة المنتج',
        ],
    ],

];
