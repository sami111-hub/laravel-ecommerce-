#!/bin/bash

echo "======================================"
echo "🧪 اختبار شامل للموقع"
echo "======================================"

# 1. اختبار الصفحة الرئيسية
echo -e "\n1️⃣ اختبار الصفحة الرئيسية:"
curl -s -o /tmp/homepage.html -w "Status: %{http_code}\nTime: %{time_total}s\nSize: %{size_download} bytes\n" https://store.update-aden.com

# 2. فحص قاعدة البيانات
echo -e "\n2️⃣ فحص قاعدة البيانات:"
cd ~/laravel_ecommerce_starte
php artisan db:show 2>&1 | head -15

# 3. عرض الجداول
echo -e "\n3️⃣ الجداول الموجودة:"
php artisan db:table --database=mysql 2>&1 | head -30

# 4. فحص البيانات
echo -e "\n4️⃣ إحصائيات البيانات:"
php artisan tinker --execute="
echo 'Users: ' . App\\Models\\User::count() . PHP_EOL;
echo 'Products: ' . App\\Models\\Product::count() . PHP_EOL;
echo 'Categories: ' . App\\Models\\Category::count() . PHP_EOL;
echo 'Orders: ' . App\\Models\\Order::count() . PHP_EOL;
echo 'Phones: ' . App\\Models\\Phone::count() . PHP_EOL;
"

# 5. اختبار الروتات
echo -e "\n5️⃣ اختبار الروتات الأساسية:"
echo "- الصفحة الرئيسية:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" https://store.update-aden.com

echo "- صفحة المنتجات:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" https://store.update-aden.com/products

echo "- صفحة التسجيل:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" https://store.update-aden.com/register

echo "- صفحة تسجيل الدخول:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" https://store.update-aden.com/login

# 6. فحص الصلاحيات
echo -e "\n6️⃣ فحص صلاحيات الملفات:"
ls -la ~/laravel_ecommerce_starte | head -10
echo ""
ls -la ~/laravel_ecommerce_starte/storage | head -5
echo ""
ls -la ~/laravel_ecommerce_starte/bootstrap/cache | head -5

# 7. فحص آخر الأخطاء
echo -e "\n7️⃣ آخر أخطاء Laravel (إن وجدت):"
if [ -f ~/laravel_ecommerce_starte/storage/logs/laravel.log ]; then
    tail -10 ~/laravel_ecommerce_starte/storage/logs/laravel.log
else
    echo "  ✅ لا توجد أخطاء"
fi

# 8. فحص حالة الخدمات
echo -e "\n8️⃣ حالة الخدمات:"
echo "Nginx:"
systemctl is-active nginx && echo "  ✅ يعمل" || echo "  ❌ متوقف"

echo "PHP-FPM:"
systemctl is-active php8.2-fpm && echo "  ✅ يعمل" || echo "  ❌ متوقف"

echo "MySQL:"
systemctl is-active mysql && echo "  ✅ يعمل" || echo "  ❌ متوقف"

# 9. فحص استهلاك الموارد
echo -e "\n9️⃣ استهلاك الموارد:"
echo "الذاكرة:"
free -h | grep Mem

echo -e "\nالقرص:"
df -h /home/smStore | tail -1

# 10. فحص الكاش
echo -e "\n🔟 حالة الكاش:"
ls -lh ~/laravel_ecommerce_starte/bootstrap/cache/*.php 2>/dev/null | wc -l | xargs echo "  ملفات الكاش:"

echo -e "\n======================================"
echo "✅ انتهى الاختبار"
echo "======================================"
