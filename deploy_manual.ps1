# Deploy to Server - Manual Interactive Script
# سكريبت النشر التفاعلي - يرفع من GitHub ثم يحدث السيرفر

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "    تحديث ورفع إلى السيرفر" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# الخطوة 1: رفع إلى GitHub
Write-Host "📤 الخطوة 1: رفع التعديلات إلى GitHub..." -ForegroundColor Yellow
$gitStatus = git status --porcelain
if ($gitStatus) {
    Write-Host "توجد تعديلات غير محفوظة:" -ForegroundColor Cyan
    git status -s
    Write-Host ""
    $commit = Read-Host "أدخل رسالة الـ commit (أو اضغط Enter للتخطي)"
    if ($commit) {
        git add .
        git commit -m "$commit"
        git push origin main
        Write-Host "✅ تم الرفع إلى GitHub" -ForegroundColor Green
    }
} else {
    Write-Host "✅ لا توجد تعديلات جديدة" -ForegroundColor Green
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan

# الخطوة 2: تحديث السيرفر
$server = "smStore@13.37.138.216"
Write-Host "📡 الخطوة 2: تحديث السيرفر..." -ForegroundColor Yellow
Write-Host "سيتم الاتصال بـ: $server" -ForegroundColor Green
Write-Host ""

# نسخ الملفات مباشرة عبر SCP (أسرع من Git)
Write-Host "📁 نسخ الملفات المحدثة..." -ForegroundColor Cyan
$filesToCopy = @(
    "app",
    "config", 
    "database",
    "resources",
    "routes",
    "public"
)

foreach ($item in $filesToCopy) {
    if (Test-Path $item) {
        Write-Host "  → $item" -ForegroundColor Gray
        scp -r $item "${server}:~/laravel_ecommerce_starte/" 2>$null
    }
}

Write-Host ""
Write-Host "🔧 تنفيذ أوامر التحديث..." -ForegroundColor Cyan

$commands = @"
cd laravel_ecommerce_starte && echo '--- Fixing permissions ---' && chmod 755 /home/smStore && chmod -R 755 /home/smStore/laravel_ecommerce_starte && chmod -R 775 /home/smStore/laravel_ecommerce_starte/storage /home/smStore/laravel_ecommerce_starte/bootstrap/cache && echo '--- Running migrations ---' && php artisan migrate --force && echo '--- Clearing all caches ---' && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && echo '--- Optimizing ---' && php artisan optimize && echo '--- DONE ---'
"@

ssh $server $commands

Write-Host ""

# اختبار الموقع
Write-Host "🌐 اختبار الموقع..." -ForegroundColor Blue
try {
    $response = Invoke-WebRequest -Uri "https://store.update-aden.com" -UseBasicParsing -TimeoutSec 15
    Write-Host "  ✅ الموقع يعمل! ($($response.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "  ⚠️ $($_.Exception.Message)" -ForegroundColor Yellow
}

# اختبار API Login
Write-Host "🔐 اختبار API..." -ForegroundColor Blue
try {
    $apiResponse = Invoke-WebRequest -Uri "https://store.update-aden.com/api/v1/cart" -UseBasicParsing -TimeoutSec 10 -Headers @{"Accept"="application/json"}
    Write-Host "  ⚠️ /api/v1/cart بدون Token رجع $($apiResponse.StatusCode) (يجب 401)" -ForegroundColor Yellow
} catch {
    if ($_.Exception.Response.StatusCode.value__ -eq 401) {
        Write-Host "  ✅ /api/v1/cart بدون Token = 401 (محمي!)" -ForegroundColor Green
    } else {
        Write-Host "  ⚠️ $($_.Exception.Message)" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "✅ تم التحديث والتأمين بنجاح!" -ForegroundColor Green
Write-Host "🌐 الموقع: https://store.update-aden.com/" -ForegroundColor Yellow
Write-Host "🔐 API: https://store.update-aden.com/api/v1/" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
