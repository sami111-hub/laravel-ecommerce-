# سكربت رفع AuthController.php إلى السيرفر
# Upload AuthController to Server

$ErrorActionPreference = "Stop"

$SERVER_USER = "smStore"
$SERVER_IP = "13.37.138.216"
$SERVER_PASSWORD = "aDm1n4StoRuSr2"
$LOCAL_FILE = "app\Http\Controllers\Api\V1\AuthController.php"
$REMOTE_PATH = "/var/www/html/store/app/Http/Controllers/Api/V1/"

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   رفع AuthController.php إلى السيرفر" -ForegroundColor Yellow
Write-Host "   Upload AuthController to Server" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# التحقق من وجود الملف محلياً
if (-not (Test-Path $LOCAL_FILE)) {
    Write-Host "❌ الملف غير موجود: $LOCAL_FILE" -ForegroundColor Red
    exit 1
}

Write-Host "📁 الملف المحلي: $LOCAL_FILE" -ForegroundColor Green
Write-Host "📊 حجم الملف: $([math]::Round((Get-Item $LOCAL_FILE).Length / 1KB, 2)) KB" -ForegroundColor Cyan
Write-Host ""

# الطريقة 1: محاولة استخدام pscp (PuTTY)
Write-Host "[1/3] 📤 محاولة رفع الملف باستخدام pscp..." -ForegroundColor Yellow

$pscpPath = Get-Command pscp -ErrorAction SilentlyContinue
if ($pscpPath) {
    Write-Host "   استخدام: pscp من PuTTY" -ForegroundColor White
    
    $pscpArgs = @(
        "-batch",
        "-pw", $SERVER_PASSWORD,
        $LOCAL_FILE,
        "${SERVER_USER}@${SERVER_IP}:${REMOTE_PATH}AuthController.php"
    )
    
    try {
        $process = Start-Process -FilePath "pscp" -ArgumentList $pscpArgs -Wait -PassThru -NoNewWindow
        
        if ($process.ExitCode -eq 0) {
            Write-Host "   ✅ تم رفع الملف بنجاح!" -ForegroundColor Green
            Write-Host ""
            
            # تنظيف الكاش
            Write-Host "[2/3] 🔧 تنظيف الكاش على السيرفر..." -ForegroundColor Yellow
            
            $plinkPath = Get-Command plink -ErrorAction SilentlyContinue
            if ($plinkPath) {
                $commands = "cd /var/www/html/store && php artisan cache:clear && php artisan config:clear"
                $plinkArgs = @("-batch", "-pw", $SERVER_PASSWORD, "${SERVER_USER}@${SERVER_IP}", $commands)
                Start-Process -FilePath "plink" -ArgumentList $plinkArgs -Wait -NoNewWindow
                Write-Host "   ✅ تم تنظيف الكاش" -ForegroundColor Green
            }
            
            Write-Host ""
            Write-Host "[3/3] ✅ اكتمل التحديث بنجاح!" -ForegroundColor Green
            Write-Host ""
            Write-Host "🌐 الموقع: https://store.update-aden.com" -ForegroundColor Magenta
            Write-Host "📁 المسار على السيرفر: ${REMOTE_PATH}AuthController.php" -ForegroundColor Cyan
            Write-Host ""
            
        } else {
            throw "فشل رفع الملف (Exit Code: $($process.ExitCode))"
        }
        
    } catch {
        Write-Host "   ❌ فشل: $_" -ForegroundColor Red
        $pscpPath = $null
    }
}

# الطريقة 2: استخدام scp (OpenSSH)
if (-not $pscpPath) {
    Write-Host "   محاولة استخدام scp..." -ForegroundColor White
    
    $scpPath = Get-Command scp -ErrorAction SilentlyContinue
    if ($scpPath) {
        try {
            Write-Host "   ⚠️  سيتم طلب كلمة المرور يدوياً" -ForegroundColor Yellow
            scp $LOCAL_FILE "${SERVER_USER}@${SERVER_IP}:${REMOTE_PATH}AuthController.php"
            
            if ($LASTEXITCODE -eq 0) {
                Write-Host "   ✅ تم رفع الملف بنجاح!" -ForegroundColor Green
                Write-Host ""
                Write-Host "[2/3] 🔧 تنظيف الكاش..." -ForegroundColor Yellow
                ssh "${SERVER_USER}@${SERVER_IP}" "cd /var/www/html/store && php artisan cache:clear && php artisan config:clear"
                Write-Host ""
                Write-Host "[3/3] ✅ اكتمل التحديث!" -ForegroundColor Green
            }
        } catch {
            Write-Host "   ❌ فشل: $_" -ForegroundColor Red
            $scpPath = $null
        }
    }
}

# إذا فشلت جميع الطرق
if (-not $pscpPath -and -not $scpPath) {
    Write-Host ""
    Write-Host "❌ لم يتم العثور على أدوات الرفع (pscp أو scp)" -ForegroundColor Red
    Write-Host ""
    Write-Host "لرفع الملف يدوياً، استخدم أحد الطرق التالية:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "🔹 الطريقة 1: تثبيت PuTTY ثم تشغيل هذا السكربت" -ForegroundColor Green
    Write-Host "   رابط التحميل: https://www.putty.org/" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "🔹 الطريقة 2: استخدام FileZilla أو WinSCP" -ForegroundColor Green
    Write-Host "   Server: $SERVER_IP" -ForegroundColor Cyan
    Write-Host "   Username: $SERVER_USER" -ForegroundColor Cyan
    Write-Host "   Password: $SERVER_PASSWORD" -ForegroundColor Cyan
    Write-Host "   Port: 22 (SFTP)" -ForegroundColor Cyan
    Write-Host "   الملف المحلي: $LOCAL_FILE" -ForegroundColor Cyan
    Write-Host "   المسار على السيرفر: ${REMOTE_PATH}AuthController.php" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "🔹 الطريقة 3: استخدام Git (الأفضل)" -ForegroundColor Green
    Write-Host "   git add $LOCAL_FILE" -ForegroundColor Cyan
    Write-Host "   git commit -m 'Update AuthController'" -ForegroundColor Cyan
    Write-Host "   git push origin main" -ForegroundColor Cyan
    Write-Host "   ثم على السيرفر: git pull origin main" -ForegroundColor Cyan
    Write-Host ""
}

Write-Host ""
Read-Host "اضغط Enter للإغلاق"
