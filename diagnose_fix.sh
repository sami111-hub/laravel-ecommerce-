#!/bin/bash
echo "========================================="
echo "🔍 التشخيص الكامل والإصلاح"
echo "========================================="

echo -e "\n1️⃣ فحص ملف Nginx الحالي:"
cat /etc/nginx/sites-available/store.update-aden.com.conf | grep -v "^#" | grep -v "^$"

echo -e "\n2️⃣ السوكيتات المتاحة:"
ls -la /run/php/*.sock

echo -e "\n3️⃣ حالة PHP-FPM:"
systemctl status php8.2-fpm --no-pager -l | head -10

echo -e "\n4️⃣ آخر أخطاء Nginx:"
sudo tail -5 /var/log/nginx/error.log

echo -e "\n5️⃣ آخر أخطاء PHP-FPM:"
sudo tail -5 /var/log/php8.2-fpm.log 2>/dev/null || echo "لا يوجد ملف خطأ"

echo -e "\n========================================="
echo "🔧 الإصلاح الآن..."
echo "========================================="

# حفظ نسخة احتياطية
sudo cp /etc/nginx/sites-available/store.update-aden.com.conf /etc/nginx/sites-available/store.update-aden.com.conf.backup-$(date +%Y%m%d-%H%M%S)

# إصلاح جميع مسارات السوكيت
sudo sed -i 's|unix:/run/php/php-fpm.sock|unix:/run/php/php8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf
sudo sed -i 's|unix:/var/run/php/smStorePhp8.2-fpm.sock|unix:/run/php/php8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf
sudo sed -i 's|unix:/run/php/smStorePhp8.2-fpm.sock|unix:/run/php/php8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf

echo -e "\n6️⃣ الملف بعد التعديل:"
grep "fastcgi_pass" /etc/nginx/sites-available/store.update-aden.com.conf

echo -e "\n7️⃣ اختبار إعدادات Nginx:"
sudo nginx -t

if [ $? -eq 0 ]; then
    echo -e "\n8️⃣ إعادة تشغيل Nginx:"
    sudo systemctl restart nginx
    
    echo -e "\n9️⃣ اختبار الموقع:"
    sleep 2
    curl -I https://store.update-aden.com
    
    echo -e "\n========================================="
    echo "✅ تم الإصلاح!"
    echo "========================================="
else
    echo -e "\n❌ خطأ في إعدادات Nginx!"
fi
