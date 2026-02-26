#!/usr/bin/env pwsh
# ============================================================
#  رفع التحديثات الجديدة إلى السيرفر - Feb 26, 2026
#  يرفع فقط الملفات المعدّلة/الجديدة يدوياً
# ============================================================

$ErrorActionPreference = "Continue"

$SERVER = "smStore@13.37.138.216"
$REMOTE = "/home/smStore/laravel_ecommerce_starte"
$LOCAL  = "C:\xampp82\htdocs\laravel_ecommerce_starte"
$SITE   = "https://store.update-aden.com"

Clear-Host
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "     Deploying Latest Updates to Server" -ForegroundColor White
Write-Host "     store.update-aden.com" -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  Time: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Gray
Write-Host "==========================================================" -ForegroundColor Cyan

$stopwatch = [System.Diagnostics.Stopwatch]::StartNew()

# ===== قائمة الملفات المطلوب رفعها =====
$filesToUpload = @(
    # --- Controllers ---
    @{ Local = "app\Http\Controllers\Admin\ProductController.php";    Remote = "$REMOTE/app/Http/Controllers/Admin/" },
    @{ Local = "app\Http\Controllers\OrderController.php";            Remote = "$REMOTE/app/Http/Controllers/" },
    @{ Local = "app\Http\Controllers\ProductController.php";          Remote = "$REMOTE/app/Http/Controllers/" },

    # --- Models ---
    @{ Local = "app\Models\Product.php";                              Remote = "$REMOTE/app/Models/" },
    @{ Local = "app\Models\ProductImage.php";                         Remote = "$REMOTE/app/Models/" },
    @{ Local = "app\Models\ProductVariant.php";                       Remote = "$REMOTE/app/Models/" },

    # --- Admin Views ---
    @{ Local = "resources\views\admin\layout.blade.php";              Remote = "$REMOTE/resources/views/admin/" },
    @{ Local = "resources\views\admin\products\create.blade.php";     Remote = "$REMOTE/resources/views/admin/products/" },
    @{ Local = "resources\views\admin\products\edit.blade.php";       Remote = "$REMOTE/resources/views/admin/products/" },

    # --- Frontend Views ---
    @{ Local = "resources\views\layout.blade.php";                    Remote = "$REMOTE/resources/views/" },
    @{ Local = "resources\views\products\category.blade.php";         Remote = "$REMOTE/resources/views/products/" },
    @{ Local = "resources\views\products\index.blade.php";            Remote = "$REMOTE/resources/views/products/" },
    @{ Local = "resources\views\products\show.blade.php";             Remote = "$REMOTE/resources/views/products/" },

    # --- New Migration ---
    @{ Local = "database\migrations\2026_02_26_004022_add_variant_details_to_product_variants_table.php"; Remote = "$REMOTE/database/migrations/" },

    # --- New Assets ---
    @{ Local = "public\css\product-carousel.css";                     Remote = "$REMOTE/public/css/" },
    @{ Local = "public\images\no-image.svg";                          Remote = "$REMOTE/public/images/" }
)

# =========================================================
# الخطوة 1: التحقق من وجود الملفات محلياً
# =========================================================
Write-Host "`n[1/5] Checking local files..." -ForegroundColor Cyan

$validFiles = @()
$missing = 0

foreach ($f in $filesToUpload) {
    $fullPath = Join-Path $LOCAL $f.Local
    if (Test-Path $fullPath) {
        $size = [math]::Round((Get-Item $fullPath).Length / 1KB, 1)
        Write-Host "  [OK] $($f.Local) ($size KB)" -ForegroundColor Green
        $validFiles += $f
    } else {
        Write-Host "  [!!] $($f.Local) - NOT FOUND" -ForegroundColor Red
        $missing++
    }
}

Write-Host "`n  Total: $($validFiles.Count) files ready, $missing missing" -ForegroundColor White

if ($validFiles.Count -eq 0) {
    Write-Host "  No files to upload. Exiting." -ForegroundColor Red
    exit 1
}

# =========================================================
# الخطوة 2: إنشاء المجلدات المطلوبة على السيرفر
# =========================================================
Write-Host "`n[2/5] Ensuring remote directories exist..." -ForegroundColor Cyan

$remoteDirs = $validFiles | ForEach-Object { $_.Remote } | Sort-Object -Unique

$mkdirCmd = ($remoteDirs | ForEach-Object { "mkdir -p `"$_`"" }) -join " && "

Write-Host "  Creating $($remoteDirs.Count) directories on server..." -ForegroundColor Gray
ssh -o ConnectTimeout=20 $SERVER "$mkdirCmd"

if ($LASTEXITCODE -eq 0) {
    Write-Host "  [OK] Directories ready" -ForegroundColor Green
} else {
    Write-Host "  [!] Warning: Could not verify all directories" -ForegroundColor Yellow
}

# =========================================================
# الخطوة 3: رفع الملفات عبر SCP
# =========================================================
Write-Host "`n[3/5] Uploading files to server..." -ForegroundColor Cyan

$success = 0
$failed = 0

foreach ($f in $validFiles) {
    $fullPath = Join-Path $LOCAL $f.Local
    $remoteDest = "$($SERVER):$($f.Remote)"
    
    Write-Host "  >> $($f.Local)" -ForegroundColor Gray -NoNewline
    
    scp -o ConnectTimeout=15 "$fullPath" "$remoteDest" 2>$null
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host " [OK]" -ForegroundColor Green
        $success++
    } else {
        Write-Host " [FAILED]" -ForegroundColor Red
        $failed++
    }
}

Write-Host "`n  Uploaded: $success OK, $failed failed" -ForegroundColor $(if ($failed -eq 0) { "Green" } else { "Yellow" })

if ($failed -eq $validFiles.Count) {
    Write-Host "  All uploads failed! Check connection/password." -ForegroundColor Red
    exit 1
}

# =========================================================
# الخطوة 4: تنفيذ أوامر التحديث على السيرفر
# =========================================================
Write-Host "`n[4/5] Running server update commands..." -ForegroundColor Cyan

$serverCommands = @"
cd $REMOTE

echo '--- [1] Running migrations ---'
php artisan migrate --force 2>&1

echo '--- [2] Clearing caches ---'
php artisan cache:clear 2>&1
php artisan config:clear 2>&1
php artisan route:clear 2>&1
php artisan view:clear 2>&1

echo '--- [3] Optimizing for production ---'
php artisan config:cache 2>&1
php artisan route:cache 2>&1
php artisan view:cache 2>&1
php artisan optimize 2>&1

echo '--- [4] Fixing permissions ---'
chmod -R 755 $REMOTE
chmod -R 775 $REMOTE/storage $REMOTE/bootstrap/cache

echo '--- [5] Restarting PHP-FPM ---'
sudo systemctl restart php8.2-fpm 2>/dev/null && echo 'PHP-FPM restarted' || echo 'Could not restart PHP-FPM'

echo '--- [6] Quick check ---'
php artisan tinker --execute="echo 'Products: ' . App\Models\Product::count() . ' | Categories: ' . App\Models\Category::count();" 2>&1

echo ''
echo '=== SERVER UPDATE COMPLETE ==='
"@

ssh -o ConnectTimeout=30 $SERVER $serverCommands

if ($LASTEXITCODE -eq 0) {
    Write-Host "  [OK] Server commands executed" -ForegroundColor Green
} else {
    Write-Host "  [!] Some commands may have had issues" -ForegroundColor Yellow
}

# =========================================================
# الخطوة 5: اختبار الموقع
# =========================================================
Write-Host "`n[5/5] Testing website..." -ForegroundColor Cyan

$tests = @(
    @{ Name = "Homepage";  Url = $SITE },
    @{ Name = "Products";  Url = "$SITE/products" },
    @{ Name = "Admin";     Url = "$SITE/admin" }
)

foreach ($test in $tests) {
    try {
        $r = Invoke-WebRequest -Uri $test.Url -UseBasicParsing -TimeoutSec 15 -MaximumRedirection 0
        Write-Host "  [OK] $($test.Name): $($r.StatusCode)" -ForegroundColor Green
    } catch {
        $code = $_.Exception.Response.StatusCode.value__
        if ($code -eq 302 -or $code -eq 301) {
            Write-Host "  [OK] $($test.Name): Redirect ($code)" -ForegroundColor Green
        } elseif ($code) {
            Write-Host "  [!!] $($test.Name): $code" -ForegroundColor Yellow
        } else {
            Write-Host "  [!!] $($test.Name): $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

# =========================================================
# النتيجة النهائية
# =========================================================
$stopwatch.Stop()
$elapsed = $stopwatch.Elapsed

Write-Host "`n==========================================================" -ForegroundColor Green
Write-Host "  DEPLOYMENT COMPLETE" -ForegroundColor White
Write-Host "==========================================================" -ForegroundColor Green
Write-Host "  Files uploaded:  $success / $($validFiles.Count)" -ForegroundColor Gray
Write-Host "  Time:            $($elapsed.Minutes)m $($elapsed.Seconds)s" -ForegroundColor Gray
Write-Host "  Website:         $SITE" -ForegroundColor Cyan
Write-Host "  Admin:           $SITE/admin" -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Green
