<?php

return [
    'offers' => [
        'title' => 'إدارة العروض',
        'create_new' => 'إنشاء عرض جديد',
        'edit_offer' => 'تعديل العرض',
        'no_offers' => 'لا توجد عروض',
        'offer_created' => 'تم إنشاء العرض بنجاح',
        'offer_updated' => 'تم تحديث العرض بنجاح',
        'offer_deleted' => 'تم حذف العرض بنجاح',
        'cannot_delete_with_contracts' => 'لا يمكن حذف العرض - يوجد عقود مرتبطة به',
        'offer_activated' => 'تم تفعيل العرض',
        'offer_deactivated' => 'تم إلغاء تفعيل العرض',
        'offer_copied' => 'تم نسخ العرض بنجاح',
        'prices_updated' => 'تم تحديث الأسعار بنجاح',
        
        'product' => 'المنتج',
        'type' => 'نوع العرض',
        'price' => 'السعر',
        'currency' => 'العملة',
        'deposit_amount' => 'مبلغ العربون',
        'commission_rate' => 'نسبة العمولة',
        'commission_amount' => 'مبلغ العمولة',
        'is_active' => 'نشط',
        'is_featured' => 'مميز',
        'valid_from' => 'صالح من',
        'valid_to' => 'صالح حتى',
        'terms_conditions' => 'الشروط والأحكام',
        
        'types' => [
            'sale' => 'للبيع',
            'rent_monthly' => 'إيجار شهري',
            'rent_yearly' => 'إيجار سنوي',
            'rent_daily' => 'إيجار يومي',
        ],
        
        'actions' => [
            'view' => 'عرض',
            'edit' => 'تعديل',
            'delete' => 'حذف',
            'toggle_status' => 'تغيير الحالة',
            'copy' => 'نسخ',
            'export' => 'تصدير',
        ],
        
        'statistics' => [
            'title' => 'إحصائيات العروض',
            'total_offers' => 'إجمالي العروض',
            'active_offers' => 'العروض النشطة',
            'expired_offers' => 'العروض المنتهية',
            'sale_offers' => 'عروض البيع',
            'rent_offers' => 'عروض الإيجار',
            'total_value' => 'القيمة الإجمالية',
        ],
        
        'bulk_actions' => [
            'title' => 'إجراءات متعددة',
            'update_prices' => 'تحديث الأسعار',
            'percentage' => 'النسبة المئوية',
            'operation' => 'العملية',
            'increase' => 'زيادة',
            'decrease' => 'تقليل',
            'apply' => 'تطبيق',
        ],
    ],

    'contracts' => [
        'title' => 'إدارة العقود',
        'pending_contracts' => 'العقود المعلقة',
        'active_contracts' => 'العقود النشطة',
        'completed_contracts' => 'العقود المكتملة',
        'cancelled_contracts' => 'العقود الملغية',
        
        'contract_number' => 'رقم العقد',
        'customer' => 'العميل',
        'owner' => 'المالك',
        'property' => 'المشروع',
        'offer' => 'العرض',
        'total_amount' => 'المبلغ الإجمالي',
        'deposit_amount' => 'مبلغ العربون',
        'commission_amount' => 'مبلغ العمولة',
        'start_date' => 'تاريخ البداية',
        'end_date' => 'تاريخ النهاية',
        'status' => 'الحالة',
        
        'statuses' => [
            'draft' => 'مسودة',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ],
        
        'actions' => [
            'approve' => 'موافقة',
            'reject' => 'رفض',
            'view_details' => 'عرض التفاصيل',
            'download' => 'تحميل',
        ],
        
        'messages' => [
            'approved' => 'تم الموافقة على العقد بنجاح',
            'rejected' => 'تم رفض العقد',
            'approval_required' => 'العقد في انتظار الموافقة',
        ],
    ],

    'payments' => [
        'title' => 'إدارة المدفوعات',
        'pending_payments' => 'المدفوعات المعلقة',
        'confirmed_payments' => 'المدفوعات المؤكدة',
        'failed_payments' => 'المدفوعات الفاشلة',
        
        'payment_details' => 'تفاصيل الدفعة',
        'amount' => 'المبلغ',
        'payment_date' => 'تاريخ الدفع',
        'payment_method' => 'طريقة الدفع',
        'reference_number' => 'رقم المرجع',
        'bank_name' => 'اسم البنك',
        'check_number' => 'رقم الشيك',
        'notes' => 'ملاحظات',
        'status' => 'الحالة',
        'contract' => 'العقد',
        'invoice' => 'الفاتورة',
        
        'actions' => [
            'confirm' => 'تأكيد',
            'reject' => 'رفض',
            'view_details' => 'عرض التفاصيل',
        ],
        
        'messages' => [
            'confirmed' => 'تم تأكيد الدفعة بنجاح',
            'rejected' => 'تم رفض الدفعة',
            'confirmation_required' => 'الدفعة في انتظار التأكيد',
        ],
    ],

    'financial' => [
        'title' => 'التقارير المالية',
        'dashboard' => 'لوحة المعلومات المالية',
        
        'summary' => [
            'title' => 'الملخص المالي',
            'total_revenue' => 'إجمالي الإيرادات',
            'total_payments' => 'إجمالي المدفوعات',
            'total_commissions' => 'إجمالي العمولات',
            'net_income' => 'صافي الدخل',
            'collection_rate' => 'معدل التحصيل',
        ],
        
        'period' => [
            'select_period' => 'اختر الفترة',
            'start_date' => 'تاريخ البداية',
            'end_date' => 'تاريخ النهاية',
            'this_month' => 'هذا الشهر',
            'last_month' => 'الشهر الماضي',
            'this_year' => 'هذا العام',
            'last_year' => 'العام الماضي',
            'custom' => 'فترة مخصصة',
        ],
        
        'reports' => [
            'revenue_report' => 'تقرير الإيرادات',
            'payments_report' => 'تقرير المدفوعات',
            'commissions_report' => 'تقرير العمولات',
            'contracts_report' => 'تقرير العقود',
            'invoices_report' => 'تقرير الفواتير',
        ],
        
        'export' => [
            'title' => 'تصدير التقارير',
            'excel' => 'تصدير إلى Excel',
            'pdf' => 'تصدير إلى PDF',
            'csv' => 'تصدير إلى CSV',
        ],
    ],

    'dashboard' => [
        'title' => 'لوحة معلومات المؤسسة',
        'welcome' => 'مرحباً بك',
        'overview' => 'نظرة عامة',
        
        'stats' => [
            'total_offers' => 'إجمالي العروض',
            'active_offers' => 'العروض النشطة',
            'total_contracts' => 'إجمالي العقود',
            'pending_contracts' => 'العقود المعلقة',
            'total_revenue' => 'إجمالي الإيرادات',
            'this_month_revenue' => 'إيرادات هذا الشهر',
            'pending_payments' => 'المدفوعات المعلقة',
            'overdue_invoices' => 'الفواتير المتأخرة',
        ],
        
        'quick_actions' => [
            'title' => 'إجراءات سريعة',
            'create_offer' => 'إنشاء عرض جديد',
            'view_contracts' => 'عرض العقود',
            'check_payments' => 'مراجعة المدفوعات',
            'financial_report' => 'التقرير المالي',
        ],
        
        'recent_activities' => [
            'title' => 'الأنشطة الحديثة',
            'new_contract' => 'عقد جديد',
            'payment_received' => 'دفعة مستلمة',
            'offer_created' => 'عرض جديد',
            'contract_approved' => 'موافقة على عقد',
        ],
    ],

    'common' => [
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'delete' => 'حذف',
        'edit' => 'تعديل',
        'view' => 'عرض',
        'search' => 'بحث',
        'filter' => 'تصفية',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'print' => 'طباعة',
        'download' => 'تحميل',
        'upload' => 'رفع',
        'submit' => 'إرسال',
        'confirm' => 'تأكيد',
        'approve' => 'موافقة',
        'reject' => 'رفض',
        'activate' => 'تفعيل',
        'deactivate' => 'إلغاء تفعيل',
        'copy' => 'نسخ',
        'loading' => 'جاري التحميل...',
        'no_data' => 'لا توجد بيانات',
        'select_all' => 'تحديد الكل',
        'clear_selection' => 'مسح التحديد',
        'actions' => 'الإجراءات',
        'status' => 'الحالة',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
    ],
];
