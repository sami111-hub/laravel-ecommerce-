# سكريبت اختبار الموقع الشامل
# تشغيل: .\test_site.ps1

$server = "smStore@13.37.138.216"
$siteUrl = "https://store.update-aden.com"

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "🧪 اختبار شامل للموقع" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Cyan

# 1. اختبار الوصول للموقع
Write-Host "`n1️⃣ اختبار الوصول للصفحة الرئيسية..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri $siteUrl -UseBasicParsing -TimeoutSec 10
    Write-Host "  ✅ الحالة: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "  📊 حجم الصفحة: $($response.RawContentLength) bytes" -ForegroundColor Gray
    Write-Host "  ⏱️ نوع المحتوى: $($response.Headers['Content-Type'])" -ForegroundColor Gray
} catch {
    Write-Host "  ❌ خطأ: $($_.Exception.Message)" -ForegroundColor Red
}

# 2. اختبار صفحات أساسية
Write-Host "`n2️⃣ اختبار الصفحات الأساسية..." -ForegroundColor Yellow

$pages = @{
    "الصفحة الرئيسية" = "/"
    "المنتجات" = "/products"
    "الهواتف" = "/phones"
    "التسجيل" = "/register"
    "تسجيل الدخول" = "/login"
    "السلة" = "/cart"
    "من نحن" = "/about"
    "اتصل بنا" = "/contact"
}

foreach ($page in $pages.GetEnumerator()) {
    try {
        $url = "$siteUrl$($page.Value)"
        $resp = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 5 -MaximumRedirection 0 -ErrorAction SilentlyContinue
        $status = $resp.StatusCode
        $icon = if ($status -eq 200) { "✅" } elseif ($status -eq 302) { "🔄" } else { "⚠️" }
        Write-Host "  $icon $($page.Key): $status" -ForegroundColor $(if ($status -eq 200) { "Green" } elseif ($status -eq 302) { "Yellow" } else { "Red" })
    } catch {
        $statusCode = $_.Exception.Response.StatusCode.value__
        if ($statusCode -eq 302 -or $statusCode -eq 301) {
            Write-Host "  🔄 $($page.Key): $statusCode (إعادة توجيه)" -ForegroundColor Yellow
        } else {
            Write-Host "  ❌ $($page.Key): خطأ $statusCode" -ForegroundColor Red
        }
    }
}

# 3. فحص قاعدة البيانات والبيانات
Write-Host "`n3️⃣ فحص قاعدة البيانات..." -ForegroundColor Yellow
Write-Host "  📡 جاري الاتصال بالسيرفر..." -ForegroundColor Gray

$dbTest = @"
cd ~/laravel_ecommerce_starte && php artisan tinker --execute=`"
echo '  Users: ' . App\\Models\\User::count();
echo '  Products: ' . App\\Models\\Product::count();
echo '  Categories: ' . App\\Models\\Category::count();
echo '  Phones: ' . App\\Models\\Phone::count();
echo '  Orders: ' . App\\Models\\Order::count();
echo '  Brands: ' . App\\Models\\Brand::count();
`"
"@

Write-Host $dbTest -ForegroundColor Gray

# 4. فحص الأخطاء
Write-Host "`n4️⃣ فحص سجل الأخطاء..." -ForegroundColor Yellow
$errorCheck = "tail -20 ~/laravel_ecommerce_starte/storage/logs/laravel.log 2>/dev/null || echo '  ✅ لا توجد أخطاء'"
Write-Host "  الأمر: ssh $server `"$errorCheck`"" -ForegroundColor Gray

# 5. فحص الصلاحيات
Write-Host "`n5️⃣ فحص الصلاحيات..." -ForegroundColor Yellow
$permCheck = @"
ls -la ~/laravel_ecommerce_starte/storage/logs 2>/dev/null || echo '  ⚠️ مجلد logs غير موجود';
ls -la ~/laravel_ecommerce_starte/bootstrap/cache 2>/dev/null || echo '  ⚠️ مجلد cache غير موجود'
"@
Write-Host "  الأمر: ssh $server `"$permCheck`"" -ForegroundColor Gray

# 6. فحص الخدمات
Write-Host "`n6️⃣ فحص حالة الخدمات..." -ForegroundColor Yellow
$serviceCheck = @"
echo '  Nginx:' && systemctl is-active nginx;
echo '  PHP-FPM:' && systemctl is-active php8.2-fpm;
echo '  MySQL:' && systemctl is-active mysql
"@
Write-Host "  الأمر: ssh $server `"$serviceCheck`"" -ForegroundColor Gray

# 7. اختبار الأداء
Write-Host "`n7️⃣ اختبار الأداء..." -ForegroundColor Yellow
$times = @()
for ($i = 1; $i -le 5; $i++) {
    $start = Get-Date
    try {
        Invoke-WebRequest -Uri $siteUrl -UseBasicParsing -TimeoutSec 10 | Out-Null
        $elapsed = ((Get-Date) - $start).TotalMilliseconds
        $times += $elapsed
        Write-Host "  محاولة $i : $([math]::Round($elapsed, 0)) ms" -ForegroundColor Gray
    } catch {
        Write-Host "  محاولة $i : فشلت" -ForegroundColor Red
    }
}

if ($times.Count -gt 0) {
    $avg = ($times | Measure-Object -Average).Average
    Write-Host "  ⏱️ متوسط وقت الاستجابة: $([math]::Round($avg, 0)) ms" -ForegroundColor $(if ($avg -lt 1000) { "Green" } elseif ($avg -lt 2000) { "Yellow" } else { "Red" })
}

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "📋 ملخص الاختبار" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  🌐 الموقع: $siteUrl" -ForegroundColor White
Write-Host "  📡 السيرفر: $server" -ForegroundColor White
Write-Host "`n  للاختبارات التفصيلية، اتصل بالسيرفر وشغل:" -ForegroundColor Yellow
Write-Host "  ssh $server" -ForegroundColor Cyan
Write-Host "  cd ~/laravel_ecommerce_starte" -ForegroundColor Cyan
Write-Host "  php artisan db:show" -ForegroundColor Cyan
Write-Host "  php artisan route:list" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
