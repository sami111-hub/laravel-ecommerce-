# رفع AuthController.php - الملف المعدل اليوم
$ErrorActionPreference = "Stop"

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   رفع AuthController.php" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

$SERVER = "smStore@13.37.138.216"
$PASSWORD = "aDm1n4StoRuSr2"
$FILE = "app\Http\Controllers\Api\AuthController.php"
$REMOTE = "~/laravel_ecommerce_starte/app/Http/Controllers/Api/"

Write-Host "📤 رفع الملف..." -ForegroundColor Yellow
Write-Host "الملف: $FILE" -ForegroundColor Cyan
Write-Host "السيرفر: $SERVER" -ForegroundColor Cyan
Write-Host ""
Write-Host "⚠️  سيتم طلب كلمة المرور: $PASSWORD" -ForegroundColor Yellow
Write-Host ""

# محاولة الرفع
scp $FILE "${SERVER}:${REMOTE}"

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✅ تم رفع الملف بنجاح!" -ForegroundColor Green
    Write-Host ""
    Write-Host "الآن نفذ على السيرفر:" -ForegroundColor Cyan
    Write-Host "ssh $SERVER" -ForegroundColor White
    Write-Host "cd ~/laravel_ecommerce_starte" -ForegroundColor White
    Write-Host "php artisan cache:clear && php artisan config:clear" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "❌ فشل الرفع" -ForegroundColor Red
    Write-Host ""
    Write-Host "جرب يدوياً:" -ForegroundColor Yellow
    Write-Host "scp $FILE ${SERVER}:${REMOTE}" -ForegroundColor White
    Write-Host ""
}

Read-Host "اضغط Enter للإغلاق"
