#!/bin/bash

# Laravel Deployment Script
# استخدم هذا السكربت لرفع الموقع تلقائياً

echo "🚀 بدء عملية النشر..."

# تنظيف الكاش
echo "🧹 تنظيف الكاش..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# تحسين Autoloader
echo "📦 تحسين Autoloader..."
composer install --no-dev --optimize-autoloader

# توليد المفتاح (إذا لم يكن موجوداً)
if [ -z "$APP_KEY" ]; then
    echo "🔑 توليد مفتاح التطبيق..."
    php artisan key:generate --force
fi

# ربط Storage
echo "🔗 ربط Storage..."
php artisan storage:link || true

# تشغيل المايجريشن
echo "🗄️ تشغيل المايجريشن..."
php artisan migrate --force

# تحسين الأداء
echo "⚡ تحسين الأداء..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# إعداد الصلاحيات
echo "🔐 إعداد الصلاحيات..."
chmod -R 755 storage bootstrap/cache
chmod -R 755 public

echo "✅ تم النشر بنجاح!"
echo "📍 تأكد من إعدادات .env وقاعدة البيانات"




