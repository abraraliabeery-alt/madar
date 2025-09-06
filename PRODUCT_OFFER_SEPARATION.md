# فصل المنتج عن العرض - النظام المحاسبي الجديد

## نظرة عامة

تم تطوير نظام جديد لفصل المنتجات (العقارات) عن العروض (الأسعار وطرق التسويق) مع إضافة نظام محاسبي متكامل يدعم المحاسبة المزدوجة وتتبع المدفوعات والذمم.

## التغييرات الرئيسية

### 1. فصل المنتج عن العرض

#### المنتج (Product)
- **المنتج = الأصل نفسه** (الوحدة أو الأرض)
- يحتوي على المعلومات الأساسية: العنوان، الوصف، الموقع، الصور، إلخ
- **تم إزالة السعر** من جدول المنتجات
- يمكن أن يكون له **عدة عروض** مختلفة

#### العرض (Offer)
- **العرض = طريقة التسويق أو البيع** (سعر، مدة، شروط)
- يحتوي على: السعر، نوع العرض، العمولة، الشروط، إلخ
- **أنواع العروض**:
  - `sale` - للبيع
  - `rent_monthly` - إيجار شهري
  - `rent_yearly` - إيجار سنوي
  - `rent_daily` - إيجار يومي

### 2. نظام العقود المحدث

#### العقد (Contract)
- يربط بين المنتج والعرض والعميل والمالك
- يحتوي على: المبلغ الإجمالي، العربون، العمولة، الشروط
- **أنواع العقود**:
  - `sale` - عقد بيع
  - `rent` - عقد إيجار
- **حالات العقد**:
  - `draft` - مسودة
  - `active` - نشط
  - `completed` - مكتمل
  - `cancelled` - ملغي

### 3. نظام الفواتير

#### الفاتورة (Invoice)
- تُنشأ تلقائياً عند إنشاء العقد
- **أنواع الفواتير**:
  - `rent` - فاتورة إيجار (شهرية)
  - `sale` - فاتورة بيع
  - `deposit` - فاتورة العربون
  - `commission` - فاتورة العمولة
  - `refund` - فاتورة استرداد
- **حالات الفاتورة**:
  - `draft` - مسودة
  - `sent` - مرسل
  - `paid` - مدفوع
  - `overdue` - متأخر
  - `cancelled` - ملغي

### 4. نظام المدفوعات

#### الدفعة (Payment)
- تسجيل المدفوعات للفواتير
- **طرق الدفع**:
  - `cash` - نقداً
  - `bank_transfer` - تحويل بنكي
  - `credit_card` - بطاقة ائتمان
  - `check` - شيك
  - `online` - عبر الإنترنت
- **حالات الدفعة**:
  - `pending` - معلق
  - `confirmed` - مؤكد
  - `failed` - فشل
  - `refunded` - مسترد

### 5. نظام المحاسبة المزدوج

#### القيود المحاسبية (AccountingEntry)
- **أنواع القيود**:
  - `debit` - مدين
  - `credit` - دائن
- **أنواع الحسابات**:
  - `revenue` - الإيرادات
  - `receivable` - الذمم المدينة
  - `commission` - العمولات
  - `liability` - الالتزامات
  - `expense` - المصروفات

## الميزات الجديدة

### 1. مرونة في التسعير
- نفس العقار يمكن أن يكون له عدة عروض (للبيع وللإيجار)
- إمكانية تغيير الأسعار دون التأثير على معلومات العقار
- تتبع تاريخ الأسعار

### 2. نظام محاسبي متكامل
- **تسجيل تلقائي** للقيود المحاسبية عند:
  - إنشاء العقد
  - تسجيل الدفعة
  - إلغاء العقد
- **تتبع دقيق** للذمم والمستحقات
- **تقارير مالية** شاملة

### 3. إدارة المدفوعات
- تتبع المدفوعات الجزئية
- حساب المبالغ المتبقية
- إشعارات الفواتير المتأخرة
- تقارير التحصيل

### 4. التقارير المالية
- تقرير الإيرادات
- تقرير الذمم المدينة
- تقرير العمولات
- تقرير المدفوعات
- تقرير الفواتير
- تقرير العقود
- تقارير العملاء والملاك
- تقارير شهرية وسنوية

## الملفات الجديدة

### الموديلات
- `app/Models/Offer.php` - موديل العروض
- `app/Models/OfferTranslation.php` - ترجمة العروض
- `app/Models/Invoice.php` - موديل الفواتير
- `app/Models/InvoiceTranslation.php` - ترجمة الفواتير
- `app/Models/Payment.php` - موديل المدفوعات
- `app/Models/AccountingEntry.php` - موديل القيود المحاسبية

### السيرفيسز
- `app/Services/OfferService.php` - خدمة إدارة العروض
- `app/Services/ContractService.php` - خدمة إدارة العقود والفواتير
- `app/Services/FinancialReportService.php` - خدمة التقارير المالية

### الكونترولرز
- `app/Http/Controllers/Api/ApiOfferController.php` - API العروض
- `app/Http/Controllers/Api/ApiContractController.php` - API العقود المحدث
- `app/Http/Controllers/Api/ApiFinancialReportController.php` - API التقارير المالية

### المايجريشنز
- `database/migrations/2025_01_27_000001_create_offers_table.php`
- `database/migrations/2025_01_27_000002_create_offer_translations_table.php`
- `database/migrations/2025_01_27_000003_update_contracts_table.php`
- `database/migrations/2025_01_27_000004_create_invoices_table.php`
- `database/migrations/2025_01_27_000005_create_invoice_translations_table.php`
- `database/migrations/2025_01_27_000006_create_payments_table.php`
- `database/migrations/2025_01_27_000007_create_accounting_entries_table.php`
- `database/migrations/2025_01_27_000008_remove_price_from_products_table.php`

### ملفات الترجمة
- `resources/lang/ar/offers.php` - ترجمة العروض
- `resources/lang/ar/contracts.php` - ترجمة العقود
- `resources/lang/ar/financial.php` - ترجمة النظام المالي

## كيفية الاستخدام

### 1. إنشاء عرض جديد
```php
$offer = Offer::create([
    'product_id' => 1,
    'offer_type' => 'sale',
    'price' => 500000,
    'currency' => 'SAR',
    'deposit_amount' => 50000,
    'commission_rate' => 0.05,
    'is_active' => true,
    'facility_id' => 1,
    'created_by' => auth()->id(),
]);
```

### 2. إنشاء عقد جديد
```php
$contract = Contract::create([
    'product_id' => 1,
    'offer_id' => 1,
    'user_id' => 2,
    'owner_id' => 3,
    'contract_type' => 'sale',
    'total_amount' => 500000,
    'currency' => 'SAR',
    'deposit_amount' => 50000,
    'commission_rate' => 0.05,
    'facility_id' => 1,
    'created_by' => auth()->id(),
]);
```

### 3. تسجيل دفعة
```php
$payment = Payment::create([
    'invoice_id' => 1,
    'contract_id' => 1,
    'payment_method' => 'bank_transfer',
    'amount' => 100000,
    'currency' => 'SAR',
    'payment_date' => now(),
    'reference_number' => 'TXN123456',
    'facility_id' => 1,
    'created_by' => auth()->id(),
]);
```

### 4. الحصول على التقارير
```php
$reportService = new FinancialReportService();

// تقرير الإيرادات
$revenueReport = $reportService->getRevenueReport($facilityId, $startDate, $endDate);

// تقرير شامل للمنشأة
$summary = $reportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);
```

## الفوائد

### 1. للعملاء
- **وضوح في الفواتير**: كل عقد له فواتير واضحة
- **تتبع المدفوعات**: معرفة المبالغ المدفوعة والمتبقية
- **مرونة في الدفع**: إمكانية الدفع على أقساط

### 2. للملاك
- **تتبع الأرباح**: معرفة صافي المستحقات
- **تقارير مفصلة**: تقارير عن كل عقد ومستحقاته
- **شفافية العمولات**: معرفة العمولات المخصومة

### 3. للإدارة
- **تقارير دقيقة**: إيرادات، ضرائب، عمولات، تحويلات
- **تتبع شامل**: كل المعاملات المالية
- **إدارة أفضل**: نظام محاسبي متكامل

## التحديثات المطلوبة

### 1. تشغيل المايجريشنز
```bash
php artisan migrate
```

### 2. تحديث البيانات الموجودة
- نقل الأسعار من جدول المنتجات إلى جدول العروض
- إنشاء عروض للعقود الموجودة

### 3. تحديث الواجهات
- تحديث صفحات إدارة المنتجات
- إضافة صفحات إدارة العروض
- إضافة صفحات التقارير المالية

## الخلاصة

هذا النظام الجديد يوفر:
- **مرونة أكبر** في إدارة العقارات والأسعار
- **نظام محاسبي متكامل** مع المحاسبة المزدوجة
- **تتبع دقيق** للمدفوعات والذمم
- **تقارير شاملة** لجميع الأطراف
- **شفافية كاملة** في المعاملات المالية

النظام جاهز للاستخدام ويمكن البدء في تطبيقه تدريجياً مع الحفاظ على البيانات الموجودة.
