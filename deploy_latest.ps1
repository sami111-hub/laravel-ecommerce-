#!/usr/bin/env pwsh
# سكريبت رفع آخر التحديثات إلى السيرفر
# آخر تحديث: فبراير 2026

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "🚀 رفع آخر التحديثات إلى السيرفر" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Cyan

$SERVER = "smStore@13.37.138.216"
$REMOTE_PATH = "~/laravel_ecommerce_starte"

# قائمة الملفات والمجلدات المحدثة
Write-Host "`n📋 الملفات المطلوب رفعها:" -ForegroundColor Yellow

$filesToUpload = @(
    @{
        "Source" = "app\Http\Controllers\Admin\SiteSettingsController.php"
        "Dest" = "$REMOTE_PATH/app/Http/Controllers/Admin/"
        "Description" = "إصلاح وظيفة الشريط الترويجي"
    },
    @{
        "Source" = "app\Http\Controllers\PhoneController.php"  
        "Dest" = "$REMOTE_PATH/app/Http/Controllers/"
        "Description" = "Controller جديد للهواتف"
    },
    @{
        "Source" = "resources\views\admin\settings\promo-bar.blade.php"
        "Dest" = "$REMOTE_PATH/resources/views/admin/settings/"
        "Description" = "تحسين واجهة الشريط الترويجي"
    },
    @{
        "Source" = "resources\views\phones\index.blade.php"
        "Dest" = "$REMOTE_PATH/resources/views/phones/"
        "Description" = "صفحة عرض الهواتف الجديدة"
    },
    @{
        "Source" = "routes\web.php"
        "Dest" = "$REMOTE_PATH/routes/"
        "Description" = "إضافة مسارات الهواتف"
    },
    @{
        "Source" = "smartPhp.conf"
        "Dest" = "$REMOTE_PATH/"
        "Description" = "ملف إعدادات PHP-FPM"
    },
    @{
        "Source" = "scripts\"
        "Dest" = "$REMOTE_PATH/scripts/"
        "Description" = "سكريبتات الاختبار والإصلاح"
    },
    @{
        "Source" = "*.sh"
        "Dest" = "$REMOTE_PATH/"
        "Description" = "ملفات bash للإصلاح"
    }
)

# عرض قائمة الملفات
foreach ($item in $filesToUpload) {
    if (Test-Path $item.Source) {
        Write-Host "  ✅ $($item.Source) → $($item.Description)" -ForegroundColor Green
    } else {
        Write-Host "  ⚠️ $($item.Source) → غير موجود (سيتم تخطي)" -ForegroundColor Yellow
    }
}

Write-Host "`n🔄 بدء عملية الرفع..." -ForegroundColor Yellow

$successCount = 0
$failCount = 0

foreach ($item in $filesToUpload) {
    if (Test-Path $item.Source) {
        Write-Host "`n📤 رفع: $($item.Source)" -ForegroundColor Blue
        
        try {
            if ($item.Source.EndsWith("\")) {
                # مجلد كامل
                $command = "scp -r `"$($item.Source)`" `"$SERVER`:$($item.Dest)`""
            } elseif ($item.Source.Contains("*")) {
                # ملفات متعددة
                $command = "scp $($item.Source) `"$SERVER`:$($item.Dest)`""
            } else {
                # ملف واحد
                $command = "scp `"$($item.Source)`" `"$SERVER`:$($item.Dest)`""
            }
            
            Write-Host "   الأمر: $command" -ForegroundColor Gray
            Invoke-Expression $command
            
            if ($LASTEXITCODE -eq 0) {
                Write-Host "   ✅ تم بنجاح" -ForegroundColor Green
                $successCount++
            } else {
                Write-Host "   ❌ فشل الرفع" -ForegroundColor Red
                $failCount++
            }
        } catch {
            Write-Host "   ❌ خطأ: $($_.Exception.Message)" -ForegroundColor Red
            $failCount++
        }
    }
}

Write-Host "`n📊 ملخص الرفع:" -ForegroundColor Yellow
Write-Host "   ✅ نجح: $successCount" -ForegroundColor Green
Write-Host "   ❌ فشل: $failCount" -ForegroundColor Red

if ($failCount -gt 0) {
    Write-Host "`n⚠️ بعض الملفات لم يتم رفعها. راجع الأخطاء أعلاه." -ForegroundColor Yellow
} else {
    Write-Host "`n🎉 تم رفع جميع الملفات بنجاح!" -ForegroundColor Green
}

# الآن تحديث السيرفر
Write-Host "`n🔧 تحديث السيرفر..." -ForegroundColor Yellow

$serverCommands = @"
# الانتقال لمجلد المشروع
cd $REMOTE_PATH

# إنشاء مجلد phones/views إذا لم يكن موجود
mkdir -p resources/views/phones

# تحديث صلاحيات المجلدات الجديدة  
chmod -R 755 resources/views/phones
chmod -R 755 scripts

# مسح الكاش القديم
echo '🧹 مسح الكاش...'
php artisan cache:clear
php artisan config:clear  
php artisan route:clear
php artisan view:clear

# تحديث قاعدة البيانات إذا لزم الأمر
echo '📊 تحديث قاعدة البيانات...'
php artisan migrate --force

# تحسين الأداء للإنتاج
echo '⚡ تحسين الأداء...'
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# اختبار النظام
echo '🧪 اختبار سريع...'
php artisan tinker --execute="echo 'Test: ' . App\\Models\\User::count() . ' users'"

echo '✅ تم تحديث السيرفر بنجاح!'
"@

Write-Host "`n📝 سيتم تشغيل الأوامر التالية على السيرفر:" -ForegroundColor Gray
Write-Host $serverCommands -ForegroundColor DarkGray

Write-Host "`n🔐 الاتصال بالسيرفر..." -ForegroundColor Blue

try {
    # تنفيذ الأوامر على السيرفر
    $sshCommand = "ssh $SERVER `"$serverCommands`""
    Write-Host "الأمر: $sshCommand" -ForegroundColor Gray
    
    Invoke-Expression $sshCommand
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "`n✅ تم تحديث السيرفر بنجاح!" -ForegroundColor Green
    } else {
        Write-Host "`n⚠️ قد تكون هناك مشاكل في تحديث السيرفر" -ForegroundColor Yellow
    }
} catch {
    Write-Host "`n❌ خطأ في الاتصال بالسيرفر: $($_.Exception.Message)" -ForegroundColor Red
}

# اختبار نهائي
Write-Host "`n🌐 اختبار الموقع النهائي..." -ForegroundColor Blue

try {
    $response = Invoke-WebRequest -Uri "https://store.update-aden.com" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "   ✅ الموقع يعمل بنجاح! ($($response.StatusCode))" -ForegroundColor Green
        Write-Host "   📊 حجم الصفحة: $($response.RawContentLength) bytes" -ForegroundColor Gray
    } else {
        Write-Host "   ⚠️ حالة غير متوقعة: $($response.StatusCode)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ❌ لا يمكن الوصول للموقع: $($_.Exception.Message)" -ForegroundColor Red
}

# اختبار صفحة الهواتف الجديدة
Write-Host "`n📱 اختبار صفحة الهواتف الجديدة..." -ForegroundColor Blue

try {
    $phoneResponse = Invoke-WebRequest -Uri "https://store.update-aden.com/phones" -UseBasicParsing -TimeoutSec 10
    if ($phoneResponse.StatusCode -eq 200) {
        Write-Host "   ✅ صفحة الهواتف تعمل! ($($phoneResponse.StatusCode))" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️ حالة غير متوقعة: $($phoneResponse.StatusCode)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ❌ صفحة الهواتف لا تعمل: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "🎯 ملخص العملية" -ForegroundColor Green  
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  📤 ملفات مرفوعة: $successCount" -ForegroundColor White
Write-Host "  ❌ ملفات فشلت: $failCount" -ForegroundColor White
Write-Host "  🌐 الموقع: https://store.update-aden.com" -ForegroundColor White
Write-Host "  📱 صفحة الهواتف: https://store.update-aden.com/phones" -ForegroundColor White
Write-Host "  🔐 لوحة الإدارة: https://store.update-aden.com/admin" -ForegroundColor White

Write-Host "`n🔗 روابط للاختبار:" -ForegroundColor Yellow
Write-Host "  📢 الشريط الترويجي: https://store.update-aden.com/admin/settings/promo-bar" -ForegroundColor Cyan
Write-Host "  📊 لوحة التحكم: https://store.update-aden.com/admin" -ForegroundColor Cyan

if ($failCount -eq 0) {
    Write-Host "`n🎉 تمت العملية بنجاح! الموقع محدث وجاهز." -ForegroundColor Green
} else {
    Write-Host "`n⚠️ راجع الأخطاء أعلاه وأعد المحاولة للملفات الفاشلة." -ForegroundColor Yellow
}

Write-Host "=====================================" -ForegroundColor Cyan