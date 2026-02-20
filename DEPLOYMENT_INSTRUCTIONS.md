# 📚 دليل رفع المشروع إلى السيرفر - Laravel E-Commerce

## 📋 المعلومات الأساسية

**معلومات السيرفر:**
- العنوان: `13.37.138.216`
- المستخدم: `smStore`
- كلمة المرور: `aDm1n4StoRuSr2`
- المسار على السيرفر: `/home/smStore/laravel_ecommerce_starte`
- رابط الموقع: https://store.update-aden.com

---

## ✅ المتطلبات

قبل البدء، تأكد من توفر:

1. **على جهازك (Windows):**
   - PowerShell
   - SSH Client (متوفر في Windows 10/11)
   - SCP (متوفر في Windows 10/11)

2. **الملفات المطلوبة:**
   - `deploy_manual.ps1` (سكريبت الرفع الأوتوماتيكي)

---

## 🚀 طريقة الرفع الكاملة

### الطريقة الأولى: الرفع الأوتوماتيكي (الموصى بها)

#### 1. من جهازك (Windows)

افتح PowerShell في مجلد المشروع ونفذ:

```powershell
.\deploy_manual.ps1
```

**ملاحظة:** سيطلب منك إدخال كلمة مرور SSH عدة مرات (مرة لكل مجلد يتم نسخه):
- أدخل: `aDm1n4StoRuSr2` في كل مرة

**ما يقوم به السكريبت:**
- ✅ نسخ مجلدات: `app/`, `config/`, `database/`, `resources/`, `routes/`, `public/`
- ✅ نسخ ملفات: `composer.json`, `.env`, `artisan`
- ✅ رفع كل التحديثات تلقائياً

#### 2. على السيرفر

بعد نجاح الرفع، اتصل بالسيرفر:

```bash
ssh smStore@13.37.138.216
```
كلمة المرور: `aDm1n4StoRuSr2`

ثم نفذ الأوامر التالية:

```bash
# الانتقال إلى مجلد المشروع
cd ~/laravel_ecommerce_starte

# تحديث قاعدة البيانات
php artisan migrate --force

# مسح الكاش القديم
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# تحسين الأداء
php artisan optimize
```

#### 3. التحقق من النجاح

```bash
# اختبار الموقع
curl -I https://store.update-aden.com
```

**يجب أن ترى:** `HTTP/2 200` ✅

---

### الطريقة الثانية: الرفع اليدوي

إذا واجهت مشكلة مع السكريبت، يمكنك الرفع يدوياً:

#### 1. نسخ المجلدات واحداً تلو الآخر

```powershell
# من PowerShell على جهازك
scp -r app smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp -r config smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp -r database smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp -r resources smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp -r routes smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp -r public smStore@13.37.138.216:~/laravel_ecommerce_starte/
```

#### 2. نسخ الملفات المهمة

```powershell
scp composer.json smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp .env smStore@13.37.138.216:~/laravel_ecommerce_starte/
scp artisan smStore@13.37.138.216:~/laravel_ecommerce_starte/
```

#### 3. تحديث السيرفر

نفس خطوات "على السيرفر" من الطريقة الأولى.

---

## 🔧 حل المشاكل الشائعة

### مشكلة 1: خطأ 502 Bad Gateway

**السبب:** Nginx لا يجد سوكيت PHP-FPM

**الحل:**
```bash
# على السيرفر
sudo sed -i 's|unix:/run/php/php8.2-fpm.sock|unix:/run/php/smartPhp8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf
sudo systemctl restart nginx
```

### مشكلة 2: خطأ 403 Forbidden

**السبب:** صلاحيات المجلدات خاطئة

**الحل:**
```bash
# على السيرفر
chmod 755 /home/smStore
chmod 755 /home/smStore/laravel_ecommerce_starte
chmod -R 755 /home/smStore/laravel_ecommerce_starte/public
```

### مشكلة 3: أخطاء في قاعدة البيانات

**الحل:**
```bash
# على السيرفر
cd ~/laravel_ecommerce_starte
php artisan migrate:fresh --force --seed
```

⚠️ **تحذير:** هذا الأمر سيحذف كل البيانات! استخدمه فقط في بيئة التطوير.

### مشكلة 4: الموقع يعرض أخطاء Laravel

**الحل:**
```bash
# على السيرفر
cd ~/laravel_ecommerce_starte
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📊 التحقق من حالة السيرفر

### فحص خدمات النظام

```bash
# حالة Nginx
sudo systemctl status nginx

# حالة PHP-FPM
sudo systemctl status php8.2-fpm

# حالة MySQL
sudo systemctl status mysql
```

### فحص السوكيتات المتاحة

```bash
ls -la /run/php/*.sock
```

**يجب أن ترى:**
- `/run/php/smartPhp8.2-fpm.sock` ✅

### فحص أخطاء Nginx

```bash
sudo tail -20 /var/log/nginx/error.log
```

### فحص أخطاء Laravel

```bash
tail -20 ~/laravel_ecommerce_starte/storage/logs/laravel.log
```

---

## 🔄 سير العمل المعتاد للتحديثات

### 1. بعد تعديل الكود على جهازك:

```powershell
# رفع التحديثات
.\deploy_manual.ps1
```

### 2. على السيرفر:

```bash
# اتصل بالسيرفر
ssh smStore@13.37.138.216

# تحديث المشروع
cd ~/laravel_ecommerce_starte
php artisan migrate --force
php artisan optimize

# اختبار
curl -I https://store.update-aden.com
```

### 3. للتحديثات البسيطة (CSS/JS فقط):

لا تحتاج لتنفيذ أوامر Laravel، فقط:
```bash
# على السيرفر
cd ~/laravel_ecommerce_starte
php artisan view:clear
```

---

## 📝 ملاحظات مهمة

### ملفات لا يجب رفعها:

❌ **لا ترفع:**
- `vendor/` - يتم تحديثه عبر composer على السيرفر
- `node_modules/` - يتم تحديثه عبر npm على السيرفر
- `storage/` - يحتوي على بيانات خاصة بالسيرفر
- `.git/` - غير ضروري على السيرفر المباشر

✅ **يجب رفع:**
- `app/`
- `config/`
- `database/`
- `resources/`
- `routes/`
- `public/`
- `composer.json`
- `.env`

### إعدادات ملف .env على السيرفر:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://store.update-aden.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smStore_db
DB_USERNAME=smStore
DB_PASSWORD=[كلمة مرور قاعدة البيانات]
```

---

## 🆘 الحصول على مساعدة

### فحص شامل للمشاكل:

```bash
# على السيرفر
cd ~/laravel_ecommerce_starte

# التحقق من صلاحيات الملفات
ls -la

# التحقق من .env
cat .env | grep APP_

# التحقق من الاتصال بقاعدة البيانات
php artisan db:show

# عرض جميع الأوامر المتاحة
php artisan list
```

---

## ✨ نصائح للأداء الأفضل

### 1. تفعيل الكاش (للإنتاج):

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 2. إلغاء الكاش (للتطوير):

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. تحسين Composer:

```bash
composer install --optimize-autoloader --no-dev
```

---

## 📞 معلومات الاتصال

**في حالة وجود مشاكل:**

1. تحقق من سجلات الأخطاء أولاً
2. جرب مسح الكاش
3. تأكد من صحة ملف .env
4. تحقق من صلاحيات الملفات

---

**آخر تحديث:** 1 فبراير 2026  
**الإصدار:** 1.0  
**الحالة:** ✅ يعمل بنجاح
