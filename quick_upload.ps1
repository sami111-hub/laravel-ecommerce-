# 🚀 رفع سريع للملفات المعدلة فقط
# Quick Upload Modified Files Only

$SERVER = "smStore@13.37.138.216"
$PASS = "aDm1n4StoRuSr2"
$REMOTE_PATH = "~/laravel_ecommerce_starte"

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "       🚀 رفع الملفات المعدلة للسيرفر" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""

# الحصول على الملفات المعدلة من Git
$modifiedFiles = git status --porcelain | Where-Object {
    $_ -match '^\s*M' -and 
    $_ -match '\.php$' -and 
    $_ -notmatch 'vendor|node_modules|store_app'
} | ForEach-Object { 
    $_.Substring(3).Trim() 
}

if (-not $modifiedFiles) {
    Write-Host "✅ لا توجد ملفات PHP معدلة للرفع" -ForegroundColor Green
    Write-Host ""
    Read-Host "اضغط Enter للإغلاق"
    exit 0
}

Write-Host "📋 الملفات المعدلة ($($modifiedFiles.Count) ملف):" -ForegroundColor Cyan
Write-Host ""
$index = 1
foreach ($file in $modifiedFiles) {
    $size = (Get-Item $file -ErrorAction SilentlyContinue).Length
    $sizeKB = if ($size) { [math]::Round($size / 1KB, 1) } else { 0 }
    Write-Host "  [$index] " -NoNewline -ForegroundColor Gray
    Write-Host "$file " -NoNewline -ForegroundColor White
    Write-Host "($sizeKB KB)" -ForegroundColor DarkGray
    $index++
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""
$confirm = Read-Host "رفع هذه الملفات؟ (Y/Enter = نعم، N = لا)"

if ($confirm -eq 'N' -or $confirm -eq 'n') {
    Write-Host "❌ تم الإلغاء" -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "📤 جاري الرفع..." -ForegroundColor Yellow
Write-Host "⚠️  كلمة المرور: $PASS" -ForegroundColor DarkGray
Write-Host ""

$success = 0
$failed = 0

foreach ($file in $modifiedFiles) {
    if (-not (Test-Path $file)) {
        Write-Host "  ⚠️  تخطي: $file (غير موجود)" -ForegroundColor Yellow
        continue
    }
    
    # تحويل المسار للصيغة Unix
    $remotePath = "$REMOTE_PATH/" + ($file -replace '\\', '/')
    $remoteDir = Split-Path $remotePath -Parent
    
    Write-Host "  → " -NoNewline -ForegroundColor Cyan
    Write-Host "$file" -NoNewline -ForegroundColor White
    
    try {
        # إنشاء المجلد على السيرفر
        $null = scp $file "${SERVER}:${remotePath}" 2>&1
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host " ✅" -ForegroundColor Green
            $success++
        } else {
            Write-Host " ❌" -ForegroundColor Red
            $failed++
        }
    } catch {
        Write-Host " ❌ خطأ" -ForegroundColor Red
        $failed++
    }
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "📊 النتيجة:" -ForegroundColor Yellow
Write-Host "   ✅ نجح: $success ملف" -ForegroundColor Green
if ($failed -gt 0) {
    Write-Host "   ❌ فشل: $failed ملف" -ForegroundColor Red
}
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""

if ($success -gt 0) {
    Write-Host "🔧 مسح الكاش على السيرفر..." -ForegroundColor Yellow
    
    $cacheCmd = "cd $REMOTE_PATH && php artisan cache:clear && php artisan config:clear && php artisan route:clear"
    
    try {
        ssh $SERVER $cacheCmd
        Write-Host "✅ تم مسح الكاش" -ForegroundColor Green
    } catch {
        Write-Host "⚠️  لم يتم مسح الكاش (نفذه يدوياً)" -ForegroundColor Yellow
    }
    
    Write-Host ""
    Write-Host "🎉 اكتمل الرفع بنجاح!" -ForegroundColor Green
    Write-Host "🌐 الموقع: https://store.update-aden.com" -ForegroundColor Magenta
}

Write-Host ""
Read-Host "اضغط Enter للإغلاق"
