# تحديث الصفحة الرئيسية - مشابه لتصميم sa.aqar.fm

## التحديثات المنجزة

### 1. القائمة العلوية (Navbar)
- ✅ إضافة قائمة منسدلة للفئات العقارية مع عدد الإعلانات
- ✅ إضافة قائمة منسدلة للمدن مع عدد الإعلانات
- ✅ روابط ديناميكية تُقرأ من قاعدة البيانات
- ✅ تصميم متجاوب يعمل على جميع الأجهزة

### 2. واجهة المدن
- ✅ إنشاء جدول `cities` مع العلاقات المطلوبة
- ✅ إضافة قسم المدن المميزة في الصفحة الرئيسية
- ✅ عرض عدد الإعلانات المتاحة لكل مدينة
- ✅ تصميم جذاب مع صور وأيقونات

### 3. قسم الإعلانات البارزة
- ✅ تحسين عرض العقارات مع تفاصيل إضافية
- ✅ إضافة معلومات المساحة والغرف والحمامات
- ✅ عرض المدينة لكل عقار
- ✅ تصميم متجاوب مع تأثيرات hover

### 4. الفوتر
- ✅ روابط ديناميكية تُقرأ من جدول `pages`
- ✅ أيقونات شبكات التواصل الاجتماعي من جدول `settings`
- ✅ روابط قابلة للتعديل من لوحة التحكم

### 5. التحسينات الإضافية
- ✅ قسم إحصائيات محسن مع تصميم جذاب
- ✅ قسم Call to Action محسن مع تأثيرات بصرية
- ✅ CSS محسن مع animations وتأثيرات hover
- ✅ تصميم متجاوب بالكامل

## الجداول الجديدة

### جدول `cities`
```sql
- id (primary key)
- name (اسم المدينة بالعربية)
- name_en (اسم المدينة بالإنجليزية)
- slug (رابط المدينة)
- description (وصف المدينة)
- image (صورة المدينة)
- is_active (نشط/غير نشط)
- is_featured (مميز/غير مميز)
- sort_order (ترتيب العرض)
```

### جدول `pages`
```sql
- id (primary key)
- title (العنوان بالعربية)
- title_en (العنوان بالإنجليزية)
- slug (رابط الصفحة)
- content (المحتوى)
- content_en (المحتوى بالإنجليزية)
- type (نوع الصفحة: page, link, footer)
- url (رابط خارجي)
- is_active (نشط/غير نشط)
- sort_order (ترتيب العرض)
```

## العلاقات الجديدة

### Product Model
- `belongsTo(City::class)` - علاقة مع المدينة

### Facility Model
- `belongsTo(City::class)` - علاقة مع المدينة

### City Model
- `hasMany(Product::class)` - علاقة مع المنتجات
- `hasMany(Facility::class)` - علاقة مع المنشآت

## كيفية الاستخدام

### 1. إضافة مدينة جديدة
```php
City::create([
    'name' => 'اسم المدينة',
    'name_en' => 'City Name',
    'slug' => 'city-slug',
    'description' => 'وصف المدينة',
    'is_featured' => true,
    'sort_order' => 1
]);
```

### 2. إضافة صفحة/رابط جديد
```php
Page::create([
    'title' => 'عنوان الصفحة',
    'title_en' => 'Page Title',
    'slug' => 'page-slug',
    'type' => 'footer', // أو 'link' أو 'page'
    'url' => '/page-url', // للروابط الخارجية
    'sort_order' => 1
]);
```

### 3. إضافة إعدادات التواصل الاجتماعي
```php
Setting::setValue('social_facebook', 'https://facebook.com/username');
Setting::setValue('social_twitter', 'https://twitter.com/username');
Setting::setValue('social_instagram', 'https://instagram.com/username');
```

## الملفات المعدلة

1. `database/migrations/2025_01_01_000000_create_cities_table.php`
2. `database/migrations/2025_01_01_000001_create_pages_table.php`
3. `database/migrations/2025_01_01_000002_add_city_id_to_products_table.php`
4. `database/migrations/2025_01_01_000003_add_city_id_to_facilities_table.php`
5. `app/Models/City.php`
6. `app/Models/Page.php`
7. `app/Models/Product.php`
8. `app/Models/Facility.php`
9. `app/Http/Controllers/Public/HomeController.php`
10. `resources/views/layouts/app.blade.php`
11. `resources/views/public/home.blade.php`
12. `database/seeders/CitySeeder.php`
13. `database/seeders/PageSeeder.php`

## المميزات

- 🎨 تصميم عصري وجذاب
- 📱 متجاوب بالكامل مع جميع الأجهزة
- 🚀 أداء عالي مع lazy loading
- 🔧 قابل للتخصيص من لوحة التحكم
- 🌐 دعم متعدد اللغات
- 📊 إحصائيات حية من قاعدة البيانات
- 🎯 SEO محسن مع URLs صديقة لمحركات البحث

## الخطوات التالية

1. إضافة صور للمدن
2. إنشاء صفحات المدن الفردية
3. إضافة فلترة الإعلانات حسب المدينة
4. إنشاء خريطة تفاعلية للمدن
5. إضافة نظام تقييمات للمدن
6. إنشاء صفحات إدارية لإدارة المدن والصفحات
