#!/bin/bash
# إصلاح فوري
sudo sed -i 's|unix:/run/php/php8.2-fpm.sock|unix:/run/php/smartPhp8.2-fpm.sock|g' /etc/nginx/sites-available/store.update-aden.com.conf
sudo nginx -t && sudo systemctl restart nginx
sleep 2
curl -I https://store.update-aden.com
echo ""
echo "إذا رأيت HTTP/2 200 فالموقع يعمل ✅"
