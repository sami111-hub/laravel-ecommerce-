#!/bin/bash

echo "🔍 جاري فحص الوضع الحالي..."

# فحص السوكيتات المتاحة
echo -e "\n📋 السوكيتات المتاحة:"
ls -la /run/php/*.sock 2>/dev/null || echo "لا يوجد سوكيتات"

# فحص إعدادات Nginx الحالية
echo -e "\n⚙️ إعدادات Nginx الحالية:"
grep "fastcgi_pass" /etc/nginx/sites-available/store.update-aden.com.conf

# إصلاح Nginx
echo -e "\n🔧 جاري إصلاح Nginx..."
sudo sed -i.bak 's|unix:/run/php/php-fpm.sock|unix:/run/php/php8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf
sudo sed -i 's|unix:/var/run/php/smStorePhp8.2-fpm.sock|unix:/run/php/php8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf

# فحص التعديل
echo -e "\n✅ الإعدادات بعد التعديل:"
grep "fastcgi_pass" /etc/nginx/sites-available/store.update-aden.com.conf

# اختبار إعدادات Nginx
echo -e "\n🧪 اختبار إعدادات Nginx..."
sudo nginx -t

# إعادة تشغيل Nginx
echo -e "\n♻️ إعادة تشغيل Nginx..."
sudo systemctl restart nginx

# فحص حالة Nginx
echo -e "\n📊 حالة Nginx:"
sudo systemctl status nginx --no-pager | head -5

# اختبار الموقع
echo -e "\n🌐 اختبار الموقع:"
curl -I https://store.update-aden.com 2>&1 | head -10

echo -e "\n✨ تم!"
