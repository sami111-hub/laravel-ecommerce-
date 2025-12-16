# Deploy to Server - Manual Interactive Script
# سكريبت النشر التفاعلي

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "    تحديث السيرفر - Deploy to Server" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

$server = "smStore@13.37.138.216"
Write-Host "سيتم الاتصال بـ: $server" -ForegroundColor Green
Write-Host "Password: aDm1n4StoRuSr2" -ForegroundColor Yellow
Write-Host ""
Write-Host "بعد إدخال كلمة المرور، سيتم تنفيذ الأوامر التالية:" -ForegroundColor Cyan
Write-Host ""

$commands = @"
cd laravel_ecommerce_starte && git remote set-url origin https://github.com/YassenAlmaqtary/laravel_ecommerce_starte.git && git fetch origin && git reset --hard origin/main && php artisan migrate --force && php artisan cache:clear && php artisan view:clear && php artisan config:clear && php artisan optimize && chmod -R 775 storage bootstrap/cache
"@

Write-Host $commands -ForegroundColor White
Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "اضغط Enter للاتصال بالسيرفر..." -ForegroundColor Yellow
Read-Host

# فتح SSH مع الأوامر
$commands | ssh $server

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "✅ تم تنفيذ الأوامر!" -ForegroundColor Green
Write-Host "تحقق من الموقع: https://store.update-aden.com/" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
