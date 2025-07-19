# نظام إدارة العقارات - Aqar Management System

## 🏗️ **وصف النظام**

نظام شامل لإدارة العقارات مع دعم متعدد اللغات وأدوار متعددة للمستخدمين. يتضمن إدارة المنشآت، المنتجات، الحجوزات، العقود، والمزيد.

## 🚀 **الميزات الرئيسية**

### 👥 **الأدوار المتاحة**
- **الأدمن (Admin):** إدارة شاملة للنظام
- **المنشأة (Facility):** إدارة المنشأة ومنتجاتها
- **العميل (Client):** تصفح المنتجات والحجوزات

### 🏢 **إدارة المنشآت**
- إنشاء وتعديل المنشآت
- إدارة المنتجات والعقارات
- إدارة الحجوزات والمواعيد
- التقارير والإحصائيات

### 🏠 **إدارة المنتجات**
- إضافة وتعديل العقارات
- إدارة الصور والمعارض
- تحديد المميزات والخصائص
- إدارة الأسعار والتوفر

### 📅 **نظام الحجوزات**
- حجز العقارات والمواعيد
- إدارة حالة الحجوزات
- نظام الدفع والتأكيد
- التقويم والجدولة

### 📋 **نظام العقود**
- إنشاء وإدارة العقود
- توقيع العقود إلكترونياً
- تتبع حالة العقود
- تحميل وطباعة العقود

## 🛠️ **التقنيات المستخدمة**

- **Laravel 11** - إطار العمل الرئيسي
- **Laravel UI** - واجهة المستخدم
- **Bootstrap** - تصميم الواجهة
- **MySQL** - قاعدة البيانات
- **Laravel Sanctum** - API Authentication

## 📦 **التثبيت والإعداد**

### 1. **متطلبات النظام**
```bash
PHP >= 8.2
Composer
MySQL >= 8.0
Node.js & NPM
```

### 2. **تثبيت المشروع**
```bash
# استنساخ المشروع
git clone [repository-url]
cd aqar

# تثبيت التبعيات
composer install
npm install

# نسخ ملف البيئة
cp .env.example .env

# إنشاء مفتاح التطبيق
php artisan key:generate

# تكوين قاعدة البيانات في .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aqar_db
DB_USERNAME=root
DB_PASSWORD=

# تشغيل الهجرات
php artisan migrate

# تشغيل البذور (Seeders)
php artisan db:seed

# تثبيت Laravel UI
composer require laravel/ui
php artisan ui bootstrap --auth

# تجميع الأصول
npm run dev
```

### 3. **تشغيل الخادم**
```bash
php artisan serve
```

## 🗂️ **هيكل المشروع**

### **Controllers**
```
app/Http/Controllers/
├── Admin/           # وحدات تحكم الأدمن
├── Facility/        # وحدات تحكم المنشآت
├── Client/          # وحدات تحكم العملاء
├── Api/             # وحدات تحكم API
└── Public/          # وحدات تحكم الجمهور
```

### **Routes**
```
routes/
├── web.php          # الملف الرئيسي
├── admin.php        # routes الأدمن
├── facility.php     # routes المنشآت
├── client.php       # routes العملاء
├── api.php          # routes API
└── public.php       # routes الجمهور
```

### **Models**
```
app/Models/
├── User.php         # نموذج المستخدم
├── Facility.php     # نموذج المنشأة
├── Product.php      # نموذج المنتج
├── Booking.php      # نموذج الحجز
├── Contract.php     # نموذج العقد
├── Category.php     # نموذج الفئة
├── Feature.php      # نموذج المميزة
└── ...              # باقي النماذج
```

## 🔐 **نظام الصلاحيات**

### **Middleware**
- `auth` - التحقق من تسجيل الدخول
- `role:admin` - التحقق من دور الأدمن
- `role:facility` - التحقق من دور المنشأة
- `role:client` - التحقق من دور العميل

### **التحقق من الأدوار**
```php
// في Controller
if (auth()->user()->hasRole('admin')) {
    // كود الأدمن
}

// في Blade
@if(auth()->user()->hasRole('facility'))
    <!-- محتوى المنشأة -->
@endif
```

## 🌐 **الـ API**

### **Endpoints الرئيسية**
```
GET    /api/v1/products          # قائمة المنتجات
GET    /api/v1/facilities        # قائمة المنشآت
POST   /api/v1/login             # تسجيل الدخول
POST   /api/v1/register          # التسجيل
GET    /api/v1/user              # بيانات المستخدم
```

### **Authentication**
```bash
# تسجيل الدخول
POST /api/v1/login
{
    "email": "user@example.com",
    "password": "password"
}

# استخدام Token
Authorization: Bearer {token}
```

## 📊 **قاعدة البيانات**

### **الجداول الرئيسية**
- `users` - المستخدمين
- `facilities` - المنشآت
- `products` - المنتجات
- `bookings` - الحجوزات
- `contracts` - العقود
- `categories` - الفئات
- `features` - المميزات
- `roles` - الأدوار
- `permissions` - الصلاحيات

## 🎨 **الواجهات (Views)**

### **Blade Templates**
```
resources/views/
├── layouts/         # القوالب الأساسية
├── admin/           # واجهات الأدمن
├── facility/        # واجهات المنشآت
├── client/          # واجهات العملاء
├── public/          # واجهات الجمهور
├── auth/            # واجهات المصادقة
└── components/      # المكونات
```

## 🚀 **التطوير**

### **أوامر مفيدة**
```bash
# إنشاء Controller جديد
php artisan make:controller Admin/NewController

# إنشاء Model جديد
php artisan make:model NewModel -m

# إنشاء Migration جديد
php artisan make:migration create_new_table

# إنشاء Seeder جديد
php artisan make:seeder NewSeeder

# تشغيل Tests
php artisan test

# تحسين الأداء
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📝 **المساهمة**

1. Fork المشروع
2. إنشاء branch جديد (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push إلى Branch (`git push origin feature/AmazingFeature`)
5. فتح Pull Request

## 📄 **الترخيص**

هذا المشروع مرخص تحت رخصة MIT - انظر ملف [LICENSE](LICENSE) للتفاصيل.

## 📞 **الدعم**

للدعم والمساعدة:
- 📧 البريد الإلكتروني: support@aqar.com
- 📱 الهاتف: +966-50-000-0000
- 🌐 الموقع: https://aqar.com

---

**تم تطوير هذا النظام بواسطة فريق Aqar Development Team** 🚀
