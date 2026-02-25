# سكربت رفع التعديلات عبر Git
# Upload Changes via Git

$ErrorActionPreference = "Stop"

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   رفع التعديلات عبر Git" -ForegroundColor Yellow
Write-Host "   Upload via Git (Recommended)" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

$LOCAL_BASE = "c:\xampp82\htdocs\laravel_ecommerce_starte"
Set-Location $LOCAL_BASE

# التحقق من Git
$gitPath = Get-Command git -ErrorAction SilentlyContinue
if (-not $gitPath) {
    Write-Host "❌ Git غير مثبت" -ForegroundColor Red
    Write-Host "الرجاء تثبيت Git من: https://git-scm.com/" -ForegroundColor Yellow
    exit 1
}

Write-Host "📋 الملفات المعدلة:" -ForegroundColor Yellow
Write-Host ""
git status --short
Write-Host ""

$confirm = Read-Host "هل تريد رفع هذه التعديلات؟ (Y/N)"
if ($confirm -ne "Y" -and $confirm -ne "y") {
    Write-Host "❌ تم الإلغاء" -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "[1/4] 📝 إضافة الملفات..." -ForegroundColor Yellow
git add app/Http/Controllers/Api/*.php
git add app/Http/Resources/*.php
git add routes/*.php
git add config/*.php

Write-Host "[2/4] 💾 حفظ التغييرات..." -ForegroundColor Yellow
$commitMessage = Read-Host "رسالة الـ commit (اتركه فارغاً للرسالة الافتراضية)"
if ([string]::IsNullOrWhiteSpace($commitMessage)) {
    $commitMessage = "Update API controllers and resources - $(Get-Date -Format 'yyyy-MM-dd HH:mm')"
}
git commit -m $commitMessage

Write-Host "[3/4] 📤 رفع إلى GitHub..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ فشل الرفع إلى GitHub" -ForegroundColor Red
    Write-Host "تحقق من اتصال الإنترنت وصلاحيات Git" -ForegroundColor Yellow
    exit 1
}

Write-Host "✅ تم الرفع إلى GitHub بنجاح" -ForegroundColor Green
Write-Host ""

Write-Host "[4/4] 🔄 تحديث السيرفر..." -ForegroundColor Yellow
Write-Host ""
Write-Host "الآن شغل الأمر التالي على السيرفر:" -ForegroundColor Cyan
Write-Host ""
Write-Host @"
ssh smStore@13.37.138.216
cd /var/www/html/store
git pull origin main
php artisan cache:clear
php artisan config:clear
php artisan route:clear
exit
"@ -ForegroundColor Green

Write-Host ""
Write-Host "أو استخدم السكربت التلقائي:" -ForegroundColor Cyan
Write-Host ".\connect_server.bat" -ForegroundColor Green
Write-Host ""

$autoUpdate = Read-Host "هل تريد تحديث السيرفر الآن تلقائياً؟ (Y/N)"
if ($autoUpdate -eq "Y" -or $autoUpdate -eq "y") {
    Write-Host ""
    Write-Host "جاري تحديث السيرفر..." -ForegroundColor Yellow
    
    $plinkPath = Get-Command plink -ErrorAction SilentlyContinue
    if ($plinkPath) {
        $commands = @"
cd /var/www/html/store
git pull origin main
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
"@
        
        plink -batch -pw "aDm1n4StoRuSr2" smStore@13.37.138.216 $commands
        
        Write-Host ""
        Write-Host "✅ تم تحديث السيرفر بنجاح!" -ForegroundColor Green
        Write-Host "🌐 الموقع: https://store.update-aden.com" -ForegroundColor Magenta
        
    } else {
        Write-Host "❌ plink غير متوفر. شغل connect_server.bat يدوياً" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "🎉 اكتملت العملية!" -ForegroundColor Green
Write-Host ""
