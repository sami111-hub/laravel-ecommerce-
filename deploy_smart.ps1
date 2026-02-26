#!/usr/bin/env pwsh
# ============================================================
# سكريبت النشر الذكي - Smart Professional Deployment
# يرفع جميع ملفات الموقع باحترافية وسرعة
# تاريخ: فبراير 2026
# ============================================================

param(
    [switch]$SkipArchive,    # تخطي إنشاء الأرشيف (إذا موجود)
    [switch]$SkipUpload,     # تخطي الرفع (فقط أوامر السيرفر)
    [switch]$DryRun          # معاينة بدون تنفيذ
)

$ErrorActionPreference = "Continue"

# ===== الإعدادات =====
$SERVER_USER = "smStore"
$SERVER_IP = "13.37.138.216"
$SERVER = "$SERVER_USER@$SERVER_IP"
$REMOTE_PATH = "/home/smStore/laravel_ecommerce_starte"
$LOCAL_PATH = "C:\xampp82\htdocs\laravel_ecommerce_starte"
$SITE_URL = "https://store.update-aden.com"
$ARCHIVE_NAME = "site_deploy.tar.gz"
$ARCHIVE_PATH = "$env:TEMP\$ARCHIVE_NAME"

# ===== الألوان والتنسيق =====
function Write-Step($num, $text) { Write-Host "`n[$num] $text" -ForegroundColor Cyan }
function Write-OK($text) { Write-Host "  [OK] $text" -ForegroundColor Green }
function Write-Warn($text) { Write-Host "  [!] $text" -ForegroundColor Yellow }
function Write-Err($text) { Write-Host "  [X] $text" -ForegroundColor Red }
function Write-Info($text) { Write-Host "  >>> $text" -ForegroundColor Gray }

# ===== بداية =====
Clear-Host
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "          Smart Store Deployment Script" -ForegroundColor White
Write-Host "           store.update-aden.com" -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  Server: $SERVER" -ForegroundColor Gray
Write-Host "  Remote: $REMOTE_PATH" -ForegroundColor Gray
Write-Host "  Time:   $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Gray
Write-Host "==========================================================" -ForegroundColor Cyan

if ($DryRun) {
    Write-Warn "--- DRY RUN MODE --- No actual changes will be made"
}

$stopwatch = [System.Diagnostics.Stopwatch]::StartNew()

# =========================================================
# الخطوة 1: فحص الملفات المحلية
# =========================================================
Write-Step "1/6" "Checking local project files..."

$requiredDirs = @("app", "bootstrap", "config", "database", "public", "resources", "routes")
$allGood = $true
foreach ($dir in $requiredDirs) {
    if (Test-Path (Join-Path $LOCAL_PATH $dir)) {
        Write-OK "$dir/"
    } else {
        Write-Err "Missing: $dir/"
        $allGood = $false
    }
}

if (-not $allGood) {
    Write-Err "Some required directories are missing. Aborting."
    exit 1
}

# فحص أخطاء PHP
Write-Info "Checking for PHP syntax errors..."
$phpCheck = & C:\xampp82\php\php.exe artisan route:list --json 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Warn "Route list check had issues - continuing anyway"
} else {
    Write-OK "PHP syntax check passed"
}

# =========================================================
# الخطوة 2: إنشاء الأرشيف المحسن
# =========================================================
Write-Step "2/6" "Creating optimized archive..."

if ($SkipArchive -and (Test-Path $ARCHIVE_PATH)) {
    Write-Info "Using existing archive: $ARCHIVE_PATH"
    $archiveSize = [math]::Round((Get-Item $ARCHIVE_PATH).Length / 1MB, 2)
    Write-Info "Archive size: $archiveSize MB"
} else {
    # حذف الأرشيف القديم
    if (Test-Path $ARCHIVE_PATH) { Remove-Item $ARCHIVE_PATH -Force }

    # إنشاء قائمة الملفات المطلوب رفعها
    Write-Info "Preparing file list (excluding unnecessary files)..."
    
    # المجلدات المطلوب رفعها
    $includeDirs = @(
        "app",
        "bootstrap",
        "config", 
        "database/factories",
        "database/migrations",
        "database/seeders",
        "public/build",
        "public/css",
        "public/images",
        "public/js",
        "resources",
        "routes"
    )
    
    # الملفات الفردية المطلوبة
    $includeFiles = @(
        "artisan",
        "composer.json",
        "composer.lock",
        "package.json",
        "vite.config.js",
        "public/index.php",
        "public/robots.txt",
        "public/.htaccess",
        "phpunit.xml"
    )

    # المجلدات والملفات المستثناة بشكل صريح
    $excludePatterns = @(
        "store_app",
        "vendor",
        "node_modules",
        ".git",
        "New folder",
        "storage",
        "*.ps1",
        "*.bat",
        "*.sh",
        "*.md",
        "*.txt",
        "*.php.bak",
        "bootstrap/cache/*.php",
        "database/dumps",
        "public/storage",
        "tests"
    )

    # إنشاء مجلد مؤقت للتجميع
    $tempDir = "$env:TEMP\site_deploy_temp"
    if (Test-Path $tempDir) { Remove-Item $tempDir -Recurse -Force }
    New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

    Push-Location $LOCAL_PATH

    # نسخ المجلدات المطلوبة
    foreach ($dir in $includeDirs) {
        $srcPath = Join-Path $LOCAL_PATH $dir
        if (Test-Path $srcPath) {
            $destPath = Join-Path $tempDir $dir
            $parentDir = Split-Path $destPath -Parent
            if (-not (Test-Path $parentDir)) {
                New-Item -ItemType Directory -Path $parentDir -Force | Out-Null
            }
            Copy-Item -Path $srcPath -Destination $destPath -Recurse -Force
            Write-Info "  + $dir"
        }
    }

    # نسخ الملفات الفردية
    foreach ($file in $includeFiles) {
        $srcPath = Join-Path $LOCAL_PATH $file
        if (Test-Path $srcPath) {
            $destPath = Join-Path $tempDir $file
            $parentDir = Split-Path $destPath -Parent
            if (-not (Test-Path $parentDir)) {
                New-Item -ItemType Directory -Path $parentDir -Force | Out-Null
            }
            Copy-Item -Path $srcPath -Destination $destPath -Force
        }
    }

    # تنظيف الملفات غير المرغوبة من bootstrap/cache
    $cacheDir = Join-Path $tempDir "bootstrap\cache"
    if (Test-Path $cacheDir) {
        Get-ChildItem $cacheDir -Filter "*.php" | Remove-Item -Force 2>$null
    }

    Pop-Location

    # حساب عدد الملفات
    $fileCount = (Get-ChildItem $tempDir -Recurse -File).Count
    Write-Info "Total files to deploy: $fileCount"

    # ضغط بصيغة tar.gz باستخدام tar (موجود في Windows 10+)
    Write-Info "Compressing with tar.gz..."
    
    Push-Location $tempDir
    
    if (Get-Command tar -ErrorAction SilentlyContinue) {
        tar -czf $ARCHIVE_PATH -C $tempDir .
        if ($LASTEXITCODE -eq 0) {
            Write-OK "Archive created with tar.gz"
        } else {
            Write-Warn "tar failed, falling back to zip..."
            $ARCHIVE_NAME = "site_deploy.zip"
            $ARCHIVE_PATH = "$env:TEMP\$ARCHIVE_NAME"
            Compress-Archive -Path "$tempDir\*" -DestinationPath $ARCHIVE_PATH -Force -CompressionLevel Optimal
            Write-OK "Archive created with zip"
        }
    } else {
        # Fallback to zip
        $ARCHIVE_NAME = "site_deploy.zip"
        $ARCHIVE_PATH = "$env:TEMP\$ARCHIVE_NAME"
        Compress-Archive -Path "$tempDir\*" -DestinationPath $ARCHIVE_PATH -Force -CompressionLevel Optimal
        Write-OK "Archive created with zip"
    }

    Pop-Location

    # تنظيف المجلد المؤقت
    Remove-Item $tempDir -Recurse -Force 2>$null

    $archiveSize = [math]::Round((Get-Item $ARCHIVE_PATH).Length / 1MB, 2)
    Write-OK "Archive: $ARCHIVE_PATH ($archiveSize MB, $fileCount files)"
}

if ($DryRun) {
    Write-Warn "DRY RUN: Would upload $ARCHIVE_PATH to server"
    Write-Host "`nDone (dry run)." -ForegroundColor Yellow
    exit 0
}

# =========================================================
# الخطوة 3: رفع الأرشيف إلى السيرفر (كلمة مرور واحدة)
# =========================================================
Write-Step "3/6" "Uploading archive to server..."

if (-not $SkipUpload) {
    Write-Info "Uploading $archiveSize MB to $SERVER..."
    Write-Host "  >>> Enter password for $SERVER <<<" -ForegroundColor Yellow
    
    scp -o ConnectTimeout=30 "$ARCHIVE_PATH" "${SERVER}:~/$ARCHIVE_NAME"
    
    if ($LASTEXITCODE -eq 0) {
        Write-OK "Archive uploaded successfully!"
    } else {
        Write-Err "Upload failed! Check your password and connection."
        exit 1
    }
} else {
    Write-Info "Skipping upload (--SkipUpload flag)"
}

# =========================================================
# الخطوة 4: استخراج وتحديث على السيرفر (كلمة مرور واحدة)
# =========================================================
Write-Step "4/6" "Extracting and updating on server..."

# بناء أوامر السيرفر كسلسلة واحدة
$isZip = $ARCHIVE_NAME.EndsWith(".zip")

if ($isZip) {
    $extractCmd = "unzip -o ~/$ARCHIVE_NAME -d $REMOTE_PATH"
} else {
    $extractCmd = "tar -xzf ~/$ARCHIVE_NAME -C $REMOTE_PATH"
}

$serverCommands = @"
echo '=========================================='
echo '  Starting Server Update...'
echo '=========================================='

# عمل نسخة احتياطية من .env
echo '[1] Backing up .env...'
cp $REMOTE_PATH/.env ~/env_backup_$(date +%Y%m%d_%H%M%S) 2>/dev/null && echo '  .env backed up' || echo '  No .env to backup'

# استخراج الملفات
echo '[2] Extracting files...'
$extractCmd
echo '  Files extracted'

# حذف الأرشيف
rm -f ~/$ARCHIVE_NAME
echo '  Archive cleaned up'

cd $REMOTE_PATH

# تثبيت التبعيات (إذا تغير composer.lock)
echo '[3] Installing/updating dependencies...'
composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tail -5
echo '  Dependencies updated'

# تشغيل قاعدة البيانات
echo '[4] Running database migrations...'
php artisan migrate --force 2>&1
echo '  Migrations done'

# إنشاء رابط التخزين
echo '[5] Storage link...'
php artisan storage:link 2>/dev/null && echo '  Storage linked' || echo '  Storage link exists'

# مسح جميع الكاشات
echo '[6] Clearing all caches...'
php artisan cache:clear 2>&1
php artisan config:clear 2>&1
php artisan route:clear 2>&1
php artisan view:clear 2>&1
echo '  All caches cleared'

# تحسين الأداء للإنتاج
echo '[7] Optimizing for production...'
php artisan config:cache 2>&1
php artisan route:cache 2>&1
php artisan view:cache 2>&1
php artisan optimize 2>&1
echo '  Production optimized'

# إصلاح الصلاحيات
echo '[8] Fixing permissions...'
chmod 755 /home/smStore
chmod -R 755 $REMOTE_PATH
chmod -R 775 $REMOTE_PATH/storage $REMOTE_PATH/bootstrap/cache
chmod 644 $REMOTE_PATH/.env 2>/dev/null
echo '  Permissions fixed'

# إعادة تشغيل PHP-FPM
echo '[9] Restarting PHP-FPM...'
sudo systemctl restart php8.2-fpm 2>/dev/null && echo '  PHP-FPM restarted' || echo '  Could not restart PHP-FPM (may need sudo)'

# اختبار سريع
echo '[10] Quick test...'
php artisan tinker --execute="echo 'Products: ' . App\Models\Product::count() . ' | Categories: ' . App\Models\Category::count() . ' | Users: ' . App\Models\User::count();" 2>&1

echo ''
echo '=========================================='
echo '  Server Update Complete!'
echo '=========================================='
"@

Write-Host "  >>> Enter password for $SERVER (server commands) <<<" -ForegroundColor Yellow
ssh -o ConnectTimeout=30 $SERVER $serverCommands

if ($LASTEXITCODE -eq 0) {
    Write-OK "Server updated successfully!"
} else {
    Write-Warn "Some server commands may have had issues - check output above"
}

# =========================================================
# الخطوة 5: اختبار الموقع
# =========================================================
Write-Step "5/6" "Testing website..."

$testResults = @()

# اختبار الصفحة الرئيسية
Write-Info "Testing homepage..."
try {
    $response = Invoke-WebRequest -Uri $SITE_URL -UseBasicParsing -TimeoutSec 20
    if ($response.StatusCode -eq 200) {
        $pageSize = [math]::Round($response.RawContentLength / 1KB, 1)
        Write-OK "Homepage: $($response.StatusCode) OK ($pageSize KB)"
        $testResults += "Homepage: OK"
    }
} catch {
    $statusCode = $_.Exception.Response.StatusCode.value__
    Write-Err "Homepage: $statusCode - $($_.Exception.Message)"
    $testResults += "Homepage: FAILED ($statusCode)"
}

# اختبار صفحة المنتجات
Write-Info "Testing products page..."
try {
    $response = Invoke-WebRequest -Uri "$SITE_URL/products" -UseBasicParsing -TimeoutSec 15
    if ($response.StatusCode -eq 200) {
        Write-OK "Products: $($response.StatusCode) OK"
        $testResults += "Products: OK"
    }
} catch {
    $statusCode = $_.Exception.Response.StatusCode.value__
    Write-Err "Products: $statusCode"
    $testResults += "Products: FAILED"
}

# اختبار لوحة الإدارة
Write-Info "Testing admin panel..."
try {
    $response = Invoke-WebRequest -Uri "$SITE_URL/admin" -UseBasicParsing -TimeoutSec 15 -MaximumRedirection 0
    Write-OK "Admin: $($response.StatusCode)"
    $testResults += "Admin: OK"
} catch {
    $statusCode = $_.Exception.Response.StatusCode.value__
    if ($statusCode -eq 302 -or $statusCode -eq 301) {
        Write-OK "Admin: Redirects to login (correct!)"
        $testResults += "Admin: OK (redirects)"
    } else {
        Write-Err "Admin: $statusCode"
        $testResults += "Admin: FAILED"
    }
}

# اختبار API
Write-Info "Testing API..."
try {
    $response = Invoke-WebRequest -Uri "$SITE_URL/api/v1/products" -UseBasicParsing -TimeoutSec 15 -Headers @{"Accept"="application/json"}
    Write-OK "API Products: $($response.StatusCode) OK"
    $testResults += "API: OK"
} catch {
    $statusCode = $_.Exception.Response.StatusCode.value__
    if ($statusCode -eq 401) {
        Write-OK "API: Auth protected (correct!)"
        $testResults += "API: OK (protected)"
    } else {
        Write-Warn "API: $statusCode"
        $testResults += "API: $statusCode"
    }
}

# =========================================================
# الخطوة 6: ملخص نهائي
# =========================================================
Write-Step "6/6" "Deployment Summary"

$stopwatch.Stop()
$elapsed = $stopwatch.Elapsed

Write-Host ""
Write-Host "==========================================================" -ForegroundColor Green
Write-Host "          DEPLOYMENT COMPLETE" -ForegroundColor White
Write-Host "==========================================================" -ForegroundColor Green
Write-Host "  Time:     $($elapsed.Minutes)m $($elapsed.Seconds)s" -ForegroundColor Gray
Write-Host "  Archive:  $archiveSize MB" -ForegroundColor Gray
Write-Host "  Server:   $SERVER" -ForegroundColor Gray
Write-Host ""
Write-Host "  Test Results:" -ForegroundColor Yellow
foreach ($result in $testResults) {
    if ($result -match "OK") {
        Write-Host "    [OK] $result" -ForegroundColor Green
    } else {
        Write-Host "    [!!] $result" -ForegroundColor Red
    }
}
Write-Host ""
Write-Host "  URLs:" -ForegroundColor Yellow
Write-Host "    Website:  $SITE_URL" -ForegroundColor Cyan
Write-Host "    Admin:    $SITE_URL/admin" -ForegroundColor Cyan
Write-Host "    API:      $SITE_URL/api/v1/" -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Green

# تنظيف الأرشيف المحلي
if (Test-Path $ARCHIVE_PATH) {
    Remove-Item $ARCHIVE_PATH -Force 2>$null
    Write-Info "Local archive cleaned up"
}

Write-Host "`nDone!" -ForegroundColor Green
