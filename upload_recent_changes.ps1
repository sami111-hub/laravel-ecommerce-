# سكربت رفع الملفات المعدلة خلال آخر 15 ساعة
# Upload Recently Modified Files to Server

$ErrorActionPreference = "Continue"

$SERVER_USER = "smStore"
$SERVER_IP = "13.37.138.216"
$SERVER_PASSWORD = "aDm1n4StoRuSr2"
$SERVER_BASE_PATH = "/var/www/html/store"
$LOCAL_BASE = "c:\xampp82\htdocs\laravel_ecommerce_starte"
$HOURS_AGO = 15

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   رفع الملفات المعدلة خلال آخر $HOURS_AGO ساعة" -ForegroundColor Yellow
Write-Host "   Upload Recently Modified Files" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# الانتقال إلى مجلد المشروع
Set-Location $LOCAL_BASE

# حساب التاريخ (15 ساعة مضت)
$cutoffTime = (Get-Date).AddHours(-$HOURS_AGO)
Write-Host "📅 البحث عن ملفات معدلة بعد: $($cutoffTime.ToString('yyyy-MM-dd HH:mm:ss'))" -ForegroundColor Cyan
Write-Host ""

# المجلدات المهمة للفحص
$includePaths = @(
    "app\Http\Controllers\*.php",
    "app\Http\Controllers\Api\*.php",
    "app\Http\Controllers\Api\V1\*.php",
    "app\Http\Resources\*.php",
    "app\Models\*.php",
    "app\Services\*.php",
    "routes\*.php",
    "config\*.php",
    "database\migrations\*.php"
)

# المجلدات المستبعدة
$excludePaths = @("vendor", "node_modules", "storage", ".git", "store_app")

Write-Host "🔍 جاري البحث عن الملفات المعدلة..." -ForegroundColor Yellow
Write-Host ""

$modifiedFiles = @()

foreach ($pattern in $includePaths) {
    $files = Get-ChildItem -Path $pattern -Recurse -File -ErrorAction SilentlyContinue | 
        Where-Object { 
            $_.LastWriteTime -gt $cutoffTime
        }
    
    if ($files) {
        foreach ($file in $files) {
            $shouldExclude = $false
            foreach ($exclude in $excludePaths) {
                if ($file.FullName -like "*\$exclude\*") {
                    $shouldExclude = $true
                    break
                }
            }
            if (-not $shouldExclude -and $modifiedFiles.FullName -notcontains $file.FullName) {
                $modifiedFiles += $file
            }
        }
    }
}

if ($modifiedFiles.Count -eq 0) {
    Write-Host "❌ لم يتم العثور على ملفات معدلة خلال آخر $HOURS_AGO ساعة" -ForegroundColor Red
    Write-Host ""
    Read-Host "اضغط Enter للإغلاق"
    exit 0
}

# عرض الملفات المعدلة
Write-Host "📋 الملفات المعدلة ($($modifiedFiles.Count) ملف):" -ForegroundColor Green
Write-Host ""

$index = 1
$uploadList = @()

foreach ($file in $modifiedFiles) {
    $relativePath = $file.FullName.Substring($LOCAL_BASE.Length + 1).Replace("\", "/")
    $timeAgo = New-TimeSpan -Start $file.LastWriteTime -End (Get-Date)
    $hours = [math]::Floor($timeAgo.TotalHours)
    $minutes = $timeAgo.Minutes
    
    $uploadList += @{
        LocalPath = $file.FullName
        RelativePath = $relativePath
        RemotePath = "$SERVER_BASE_PATH/$relativePath"
        Size = $file.Length
        Modified = $file.LastWriteTime
    }
    
    Write-Host "  [$index] $relativePath" -ForegroundColor White
    Write-Host "      ⏱️  قبل ${hours}h ${minutes}m | 📊 $([math]::Round($file.Length / 1KB, 2)) KB" -ForegroundColor Gray
    $index++
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
$totalSize = 0
foreach ($item in $uploadList) { $totalSize += $item.Size }
Write-Host "📦 الإجمالي: $($uploadList.Count) ملف | الحجم: $([math]::Round($totalSize / 1KB, 2)) KB" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""

# طلب التأكيد
$confirm = Read-Host "هل تريد رفع هذه الملفات إلى السيرفر؟ (Y/N)"
if ($confirm -ne "Y" -and $confirm -ne "y" -and $confirm -ne "نعم") {
    Write-Host "❌ تم الإلغاء" -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   بدء عملية الرفع" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# التحقق من أدوات الرفع
$pscpPath = Get-Command pscp -ErrorAction SilentlyContinue
$plinkPath = Get-Command plink -ErrorAction SilentlyContinue
$scpPath = Get-Command scp -ErrorAction SilentlyContinue
$sshPath = Get-Command ssh -ErrorAction SilentlyContinue

$uploadSuccess = 0
$uploadFailed = 0

if ($pscpPath -and $plinkPath) {
    Write-Host "✅ استخدام pscp/plink من PuTTY" -ForegroundColor Green
    Write-Host ""
    
    foreach ($item in $uploadList) {
        Write-Host "📤 رفع: $($item.RelativePath)..." -ForegroundColor Yellow -NoNewline
        
        try {
            # إنشاء المجلد على السيرفر إذا لم يكن موجوداً
            $remoteDir = Split-Path $item.RemotePath -Parent
            $mkdirCmd = "mkdir -p `"$remoteDir`""
            $plinkArgs = @("-batch", "-pw", $SERVER_PASSWORD, "${SERVER_USER}@${SERVER_IP}", $mkdirCmd)
            Start-Process -FilePath "plink" -ArgumentList $plinkArgs -Wait -NoNewWindow -WindowStyle Hidden 2>$null
            
            # رفع الملف
            $pscpArgs = @(
                "-batch",
                "-pw", $SERVER_PASSWORD,
                $item.LocalPath,
                "${SERVER_USER}@${SERVER_IP}:$($item.RemotePath)"
            )
            
            $process = Start-Process -FilePath "pscp" -ArgumentList $pscpArgs -Wait -PassThru -NoNewWindow -WindowStyle Hidden
            
            if ($process.ExitCode -eq 0) {
                Write-Host " ✅" -ForegroundColor Green
                $uploadSuccess++
            } else {
                Write-Host " ❌" -ForegroundColor Red
                $uploadFailed++
            }
            
        } catch {
            Write-Host " ❌ خطأ: $_" -ForegroundColor Red
            $uploadFailed++
        }
    }
    
    Write-Host ""
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
    Write-Host "✅ نجح: $uploadSuccess | ❌ فشل: $uploadFailed" -ForegroundColor Cyan
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
    Write-Host ""
    
    if ($uploadSuccess -gt 0) {
        Write-Host "🔧 تنظيف الكاش على السيرفر..." -ForegroundColor Yellow
        
        $cacheCommands = @"
cd $SERVER_BASE_PATH
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo 'Cache cleared successfully'
"@
        
        $plinkArgs = @("-batch", "-pw", $SERVER_PASSWORD, "${SERVER_USER}@${SERVER_IP}", $cacheCommands)
        $output = & plink $plinkArgs 2>&1
        
        Write-Host "✅ تم تنظيف الكاش" -ForegroundColor Green
        Write-Host ""
        Write-Host "🎉 اكتملت عملية الرفع بنجاح!" -ForegroundColor Green
        Write-Host ""
        Write-Host "🌐 الموقع: https://store.update-aden.com" -ForegroundColor Magenta
    }
    
} elseif ($scpPath -and $sshPath) {
    Write-Host "✅ استخدام scp/ssh من OpenSSH" -ForegroundColor Green
    Write-Host "⚠️  سيتم طلب كلمة المرور لكل ملف" -ForegroundColor Yellow
    Write-Host ""
    
    foreach ($item in $uploadList) {
        Write-Host "📤 رفع: $($item.RelativePath)..." -ForegroundColor Yellow
        
        try {
            # إنشاء المجلد
            $remoteDir = Split-Path $item.RemotePath -Parent
            ssh "${SERVER_USER}@${SERVER_IP}" "mkdir -p `"$remoteDir`"" 2>$null
            
            # رفع الملف
            scp $item.LocalPath "${SERVER_USER}@${SERVER_IP}:$($item.RemotePath)"
            
            if ($LASTEXITCODE -eq 0) {
                Write-Host "   ✅ تم الرفع" -ForegroundColor Green
                $uploadSuccess++
            } else {
                Write-Host "   ❌ فشل الرفع" -ForegroundColor Red
                $uploadFailed++
            }
            
        } catch {
            Write-Host "   ❌ خطأ: $_" -ForegroundColor Red
            $uploadFailed++
        }
    }
    
    Write-Host ""
    if ($uploadSuccess -gt 0) {
        Write-Host "🔧 تنظيف الكاش..." -ForegroundColor Yellow
        ssh "${SERVER_USER}@${SERVER_IP}" "cd $SERVER_BASE_PATH && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear"
        Write-Host "✅ اكتمل!" -ForegroundColor Green
    }
    
} else {
    Write-Host ""
    Write-Host "❌ لم يتم العثور على أدوات الرفع (pscp/plink أو scp/ssh)" -ForegroundColor Red
    Write-Host ""
    Write-Host "📋 الحلول المتاحة:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "🔹 الحل 1: تثبيت PuTTY (موصى به)" -ForegroundColor Green
    Write-Host "   رابط التحميل: https://www.putty.org/" -ForegroundColor Cyan
    Write-Host "   ثم شغل هذا السكربت مرة أخرى" -ForegroundColor White
    Write-Host ""
    Write-Host "🔹 الحل 2: استخدام Git (الأسهل)" -ForegroundColor Green
    Write-Host "   git add ." -ForegroundColor Cyan
    Write-Host "   git commit -m 'Update files'" -ForegroundColor Cyan
    Write-Host "   git push origin main" -ForegroundColor Cyan
    Write-Host "   ثم على السيرفر: git pull origin main" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "🔹 الحل 3: حفظ قائمة الملفات" -ForegroundColor Green
    
    # حفظ قائمة الملفات
    $listFile = "modified_files_list.txt"
    $uploadList | ForEach-Object { $_.RelativePath } | Out-File -FilePath $listFile -Encoding UTF8
    
    Write-Host "   تم حفظ قائمة الملفات في: $listFile" -ForegroundColor Cyan
    Write-Host "   يمكنك رفعها يدوياً باستخدام FileZilla/WinSCP" -ForegroundColor White
    Write-Host ""
}

Write-Host ""
Read-Host "اضغط Enter للإغلاق"
