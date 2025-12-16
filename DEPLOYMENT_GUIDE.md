# دليل رفع المشروع إلى السيرفر

## 📋 قائمة التحقق السريعة

### ✅ قبل الرفع
- [ ] تنظيف الكاش (تم ✓)
- [ ] التأكد من عمل جميع الميزات محلياً
- [ ] حفظ نسخة احتياطية من قاعدة البيانات
- [ ] رفع أحدث نسخة إلى GitHub (تم ✓)

### ✅ على السيرفر

#### 1. إعداد قاعدة البيانات
```bash
# من cPanel → MySQL Databases
1. أنشئ قاعدة بيانات جديدة (مثال: username_ecommerce)
2. أنشئ مستخدم MySQL
3. أعط المستخدم جميع الصلاحيات على القاعدة
4. استورد: database/dumps/jkhsfi.sql
```

#### 2. رفع الملفات
**الملفات المطلوبة:**
- app/
- bootstrap/
- config/
- database/
- public/
- resources/
- routes/
- storage/
- artisan
- composer.json
- composer.lock

**لا ترفع:**
- .env (ستنشئه يدوياً)
- vendor/ (سينشأ بـ composer)
- node_modules/
- .git/

#### 3. إنشاء ملف .env
```bash
# انسخ محتوى .env.production.example
# عدّل المعلومات التالية:

APP_NAME="متجر UPDATE"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost  # أو 127.0.0.1
DB_PORT=3306
DB_DATABASE=username_ecommerce
DB_USERNAME=username_dbuser
DB_PASSWORD=your_secure_password
```

#### 4. تشغيل الأوامر (SSH/Terminal)
```bash
# الانتقال لمجلد المشروع
cd /home/username/public_html

# تثبيت المكتبات
composer install --no-dev --optimize-autoloader

# توليد مفتاح التطبيق
php artisan key:generate --force

# ربط مجلد Storage
php artisan storage:link

# تشغيل المايجريشن (إذا لم تستورد القاعدة)
# php artisan migrate --force

# تنظيف وتحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ضبط الصلاحيات
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

#### 5. إعدادات الخادم

**A. إعدادات Apache (.htaccess)**
تأكد من وجود ملف `.htaccess` في مجلد `public`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
</IfModule>
```

**B. توجيه الدومين**
- وجّه الدومين إلى مجلد `public`
- من cPanel → Domains → ضبط Document Root
- أو انقل محتويات `public` إلى `public_html`

#### 6. الأمان

```bash
# حماية ملف .env
chmod 600 .env

# حماية المجلدات الحساسة
chmod 755 storage
chmod 755 bootstrap/cache

# تعطيل display_errors في production
# تأكد من أن php.ini يحتوي على:
display_errors = Off
log_errors = On
```

#### 7. اختبار الموقع

- [ ] افتح الموقع في المتصفح
- [ ] اختبر تسجيل الدخول
- [ ] اختبر عرض المنتجات
- [ ] اختبر إضافة منتج للسلة
- [ ] اختبر عملية الشراء
- [ ] اختبر لوحة الإدارة

## 🔧 حل المشاكل الشائعة

### خطأ 500
```bash
# افحص السجلات
tail -f storage/logs/laravel.log

# تأكد من الصلاحيات
chmod -R 755 storage bootstrap/cache
```

### خطأ قاعدة البيانات
```bash
# تحقق من .env
php artisan config:clear
php artisan config:cache
```

### الصور لا تظهر
```bash
php artisan storage:link
chmod -R 755 storage/app/public
```

### خطأ Composer
```bash
# إذا لم يكن Composer متاحاً
# حمّل vendor من جهاز آخر وارفعه
```

## 📞 الدعم

إذا واجهت مشاكل:
1. افحص `storage/logs/laravel.log`
2. تأكد من إعدادات `.env`
3. تحقق من صلاحيات الملفات
4. تأكد من توافق إصدار PHP (8.2+)

## ✅ قائمة النجاح

بعد الرفع الناجح:
- [ ] الموقع يعمل بدون أخطاء
- [ ] جميع الصفحات تظهر بشكل صحيح
- [ ] الصور تظهر
- [ ] النظام يعمل بشكل سليم
- [ ] SSL مفعل (HTTPS)
- [ ] النسخ الاحتياطي التلقائي مفعل

---
**آخر تحديث:** 16 ديسمبر 2025
**الإصدار:** 1.0
