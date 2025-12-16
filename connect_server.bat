@echo off
chcp 65001 >nul
echo ============================================
echo    تحديث السيرفر التلقائي
echo    Auto Deploy to Server
echo ============================================
echo.

REM استخدام plink إذا كان متوفراً (من PuTTY)
where plink >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [1/9] Setting git remote...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && git remote set-url origin https://github.com/YassenAlmaqtary/laravel_ecommerce_starte.git"
    
    echo [2/9] Aborting any pending merges...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && git merge --abort || true"
    
    echo [3/9] Pulling latest code from GitHub...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && git pull origin main"
    
    echo [4/9] Running database migrations...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && php artisan migrate --force"
    
    echo [5/9] Clearing application cache...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && php artisan cache:clear"
    
    echo [6/9] Clearing view cache...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && php artisan view:clear"
    
    echo [7/9] Clearing config cache...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && php artisan config:clear"
    
    echo [8/9] Optimizing application...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && php artisan optimize"
    
    echo [9/9] Setting permissions...
    plink -batch -pw aDm1n4StoRuSr2 smStore@13.37.138.216 "cd laravel_ecommerce_starte && chmod -R 775 storage bootstrap/cache"
    
    echo.
    echo ============================================
    echo ✅ اكتمل التحديث بنجاح!
    echo ✅ Deployment completed successfully!
    echo ============================================
    echo.
    echo تحقق من الموقع: https://store.update-aden.com/
    echo.
) else (
    echo ❌ plink غير متوفر. الرجاء تثبيت PuTTY
    echo ❌ plink not found. Please install PuTTY
    echo.
    echo أو استخدم SSH يدوياً:
    echo ssh smStore@13.37.138.216
    echo Password: aDm1n4StoRuSr2
    echo.
    echo ثم نفذ الأوامر من ملف: update_server_commands.txt
)

pause