@echo off
chcp 65001 >nul
echo ============================================
echo    رفع ملف AuthController.php إلى السيرفر
echo    Upload AuthController to Server
echo ============================================
echo.

set SERVER=smStore@13.37.138.216
set PASSWORD=aDm1n4StoRuSr2
set LOCAL_FILE=app\Http\Controllers\Api\V1\AuthController.php
set REMOTE_PATH=/var/www/html/store/app/Http/Controllers/Api/V1/

echo [1/3] 📤 رفع الملف إلى السيرفر...
echo.

REM محاولة استخدام pscp (من PuTTY)
where pscp >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo استخدام pscp لرفع الملف...
    pscp -batch -pw %PASSWORD% %LOCAL_FILE% %SERVER%:%REMOTE_PATH%AuthController.php
    
    if %ERRORLEVEL% EQU 0 (
        echo ✅ تم رفع الملف بنجاح!
        echo.
        
        echo [2/3] 🔧 تنظيف الكاش...
        plink -batch -pw %PASSWORD% %SERVER% "cd /var/www/html/store && php artisan cache:clear && php artisan config:clear"
        
        echo.
        echo [3/3] ✅ اكتمل التحديث!
        echo.
        echo 🌐 الموقع: https://store.update-aden.com
        echo 📁 الملف: %REMOTE_PATH%AuthController.php
    ) else (
        echo ❌ فشل رفع الملف
    )
) else (
    echo.
    echo ❌ pscp غير متوفر
    echo.
    echo لرفع الملف يدوياً، استخدم أحد الطرق التالية:
    echo.
    echo 🔹 الطريقة 1: FileZilla
    echo    Host: 13.37.138.216
    echo    Username: smStore
    echo    Password: aDm1n4StoRuSr2
    echo    Port: 22
    echo    المسار: /var/www/html/store/app/Http/Controllers/Api/V1/
    echo.
    echo 🔹 الطريقة 2: WinSCP
    echo    نفس الإعدادات أعلاه
    echo.
    echo 🔹 الطريقة 3: تثبيت PuTTY ثم تشغيل هذا السكربت مرة أخرى
    echo    رابط التحميل: https://www.putty.org/
    echo.
)

echo.
pause
