# سكربت رفع المشروع إلى السيرفر
# Server: store.update-aden.com

$serverUser = "smStore"
$serverIP = "13.37.138.216"
$serverPath = "/var/www/html/store"
$localPath = "C:\xampp82\htdocs\laravel_ecommerce_starte"

Write-Host "========== رفع المشروع إلى السيرفر ==========" -ForegroundColor Cyan
Write-Host ""

# الخطوة 1: ضغط الملفات
Write-Host "📦 الخطوة 1: ضغط الملفات..." -ForegroundColor Yellow
$excludeDirs = @('vendor', 'node_modules', '.git', 'storage/logs/*', 'storage/framework/cache/*', 'storage/framework/sessions/*', 'storage/framework/views/*')

# إنشاء ملف مؤقت للضغط
$zipFile = "$env:TEMP\laravel_store_deploy.zip"
if (Test-Path $zipFile) {
    Remove-Item $zipFile -Force
}

# ضغط الملفات
Write-Host "جاري الضغط..." -ForegroundColor White
Compress-Archive -Path "$localPath\*" -DestinationPath $zipFile -Force -CompressionLevel Optimal

Write-Host "✅ تم الضغط بنجاح: $zipFile" -ForegroundColor Green
Write-Host "حجم الملف: $([math]::Round((Get-Item $zipFile).Length / 1MB, 2)) MB" -ForegroundColor Cyan

# الخطوة 2: تعليمات الرفع
Write-Host "`n📤 الخطوة 2: رفع الملف إلى السيرفر" -ForegroundColor Yellow
Write-Host ""
Write-Host "استخدم أحد الطرق التالية:" -ForegroundColor White
Write-Host ""
Write-Host "🔹 الطريقة 1: SCP (الأسرع)" -ForegroundColor Green
Write-Host "scp `"$zipFile`" ${serverUser}@${serverIP}:~/" -ForegroundColor Cyan
Write-Host ""
Write-Host "🔹 الطريقة 2: FileZilla/WinSCP" -ForegroundColor Green
Write-Host "   - Host: $serverIP" -ForegroundColor Cyan
Write-Host "   - Username: $serverUser" -ForegroundColor Cyan
Write-Host "   - Port: 22" -ForegroundColor Cyan
Write-Host "   - ارفع الملف: $zipFile" -ForegroundColor Cyan
Write-Host ""

# الخطوة 3: الأوامر على السيرفر
Write-Host "🔧 الخطوة 3: الأوامر على السيرفر (SSH)" -ForegroundColor Yellow
Write-Host ""
Write-Host "ssh ${serverUser}@${serverIP}" -ForegroundColor Cyan
Write-Host ""
Write-Host "ثم نفذ:" -ForegroundColor White
Write-Host @"
# 1. فك الضغط
cd /var/www/html
unzip ~/laravel_store_deploy.zip -d store
cd store

# 2. إنشاء ملف .env
cp .env.production.example .env
nano .env  # عدل معلومات قاعدة البيانات

# 3. تثبيت المكتبات
composer install --no-dev --optimize-autoloader

# 4. الإعدادات
php artisan key:generate --force
php artisan storage:link

# 5. قاعدة البيانات
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS smStore_db;"
mysql -u root -p smStore_db < database/dumps/jkhsfi.sql

# 6. التحسينات
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. الصلاحيات
chmod -R 755 storage bootstrap/cache
chmod 644 .env
chown -R www-data:www-data storage bootstrap/cache

# 8. اختبار
php artisan serve --host=0.0.0.0 --port=8000
"@ -ForegroundColor Cyan

Write-Host ""
Write-Host "✅ الملف جاهز للرفع!" -ForegroundColor Green
Write-Host "📍 الموقع: https://store.update-aden.com" -ForegroundColor Magenta
